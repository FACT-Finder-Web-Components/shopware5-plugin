<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_Request_Request;
use Enlight_Controller_Response_ResponseHttp;
use Exception;
use OmikronFactfinder\Components\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class LoginState implements SubscriberInterface
{
    const HAS_JUST_LOGGED_IN  = 'ff_has_just_logged_in';
    const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';
    const USER_ID             = 'ff_user_id';

    /** @var ContainerInterface */
    private $container;

    /** @var Configuration */
    private $configuration;

    /** @var bool */
    private $isTriggered = false;

    /** @var \Enlight_Components_Session_Namespace */
    private $session;

    public function __construct(
        ContainerInterface $container,
        Configuration $configuration
    ) {
        $this->container     = $container;
        $this->configuration = $configuration;
        $this->session       = $this->container->get('session');
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => [
                ['isCustomerLoggedOut'],
                ['hasCustomerJustLoggedIn'],
                ['hasCustomerJustLoggedOut'],
                ['setIsTriggered'],
            ],
        ];
    }

    public function isCustomerLoggedOut(\Enlight_Controller_ActionEventArgs $args): bool
    {
        try {
            $this->validateRequest($args->getRequest(), $args->getResponse());

            if ($this->getUserId() === '') {
                $this->clearCookie(self::USER_ID);
                $this->clearCookie(self::HAS_JUST_LOGGED_OUT);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function hasCustomerJustLoggedIn(\Enlight_Controller_ActionEventArgs $args): bool
    {
        $request  = $args->getRequest();

        try {
            $this->validateRequest($request, $args->getResponse());
        } catch (Exception $e) {
            return false;
        }

        if ($this->session->get(self::HAS_JUST_LOGGED_IN, false) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $this->setCookie(self::USER_ID, $this->getUserId());
            $this->session->set(self::HAS_JUST_LOGGED_IN, false);
        }

        return true;
    }

    public function hasCustomerJustLoggedOut(\Enlight_Controller_ActionEventArgs $args): bool
    {
        $request  = $args->getRequest();

        try {
            $this->validateRequest($request, $args->getResponse());

            if ($this->session->get(self::HAS_JUST_LOGGED_OUT, false) === true) {
                $this->setCookie(self::HAS_JUST_LOGGED_OUT, '1');
                $this->clearCookie(self::USER_ID);
                $this->session->set(self::HAS_JUST_LOGGED_OUT, false);
            }

            return true;
        } catch (Exception $e) {
            return false;
        }
    }

    public function setIsTriggered(\Enlight_Controller_ActionEventArgs $args): void
    {
        $request  = $args->getRequest();
        $response = $args->getResponse();

        if (
            $request->isXmlHttpRequest()
            || $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES
        ) {
            return;
        }

        $this->isTriggered = true;
    }

    protected function validateRequest(
        Enlight_Controller_Request_Request $request,
        Enlight_Controller_Response_ResponseHttp $response
    ): void {
        if (
            $this->isTriggered
            || $request->isXmlHttpRequest()
            || $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES
        ) {
            throw new Exception('Not supported request');
        }
    }

    private function setCookie(string $name, string $value): void
    {
        setcookie(
            $name,
            $value,
            (new \DateTime())->modify('+1 hour')->getTimestamp(),
            '/'
        );
    }

    private function clearCookie(string $name): void
    {
        unset($_COOKIE[$name]);
        setcookie($name, '', -1, '/');
    }

    private function getUserId(): string
    {
        $session = $this->container->get('session');
        $userId  = (string) $session->get('sUserId');

        if ($userId === '') {
            return '';
        }

        return $this->configuration->isFeatureEnabled('ffAnonymizeUserId') ? md5($userId) : $userId;
    }
}
