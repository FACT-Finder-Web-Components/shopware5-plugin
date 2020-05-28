<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Components_Session_Namespace as Session;
use Enlight_Event_EventArgs as EventArgs;
use OmikronFactfinder\Components\Formatter\NumberFormatter;
use OmikronFactfinder\Components\Service\TrackingService;
use Shopware\Components\Cart\Struct\CartItemStruct;

class Tracking implements SubscriberInterface
{
    /** @var Session */
    private $session;

    /** @var NumberFormatter */
    private $numberFormatter;

    /** @var bool Since the Updated even is called twice, we prevent a double tracking with a flag */
    private $shouldTrack = false;

    /** @var TrackingService */
    private $trackingService;

    public function __construct(TrackingService $trackingService, Session $session, NumberFormatter $numberFormatter)
    {
        $this->session         = $session;
        $this->numberFormatter = $numberFormatter;
        $this->trackingService = $trackingService;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Basket_AddArticle_Start'        => 'prepareForTracking',
            'Shopware_Modules_Basket_UpdateCartItems_Updated' => 'trackCart',
            'Shopware_Modules_Order_SaveOrder_OrderCreated'   => 'trackOrder',
        ];
    }

    public function prepareForTracking(): void
    {
        $this->shouldTrack = true;
    }

    public function trackCart(EventArgs $arg): void
    {
        if ($this->shouldTrack) {
            $this->shouldTrack = false;
            $this->trackingService->track('cart', array_map([$this, 'getCartEvent'], $arg['updateableItems']));
        }
    }

    public function trackOrder(EventArgs $arg): void
    {
        $this->trackingService->track('checkout', array_map(function (array $orderItem): array {
            return array_filter([
                'id'       => $orderItem['ordernumber'],
                'masterId' => $orderItem['ordernumber'], // @todo: Track master variant
                'count'    => $orderItem['quantity'],
                'price'    => $this->numberFormatter->format((float) $orderItem['price']),
                'sid'      => substr($orderItem['sessionID'], 0, 30),
                'userId'   => $orderItem['userID'],
            ]);
        }, $arg['details']));
    }

    private function getCartEvent(CartItemStruct $cartItem): array
    {
        ['price' => $price, 'tax' => $tax] = $cartItem->getUpdatedPrice();
        return array_filter([
            'id'       => $cartItem->getAdditionalInfo()['ordernumber'],
            'masterId' => $cartItem->getAdditionalInfo()['ordernumber'], // @todo: Track master variant
            'count'    => $cartItem->getQuantity(),
            'price'    => $this->numberFormatter->format($price * (1 + $tax / 100)),
            'sid'      => substr($this->session->get('sessionId'), 0, 30),
            'userId'   => (int) $this->session->get('sUserId', 0),
        ]);
    }
}
