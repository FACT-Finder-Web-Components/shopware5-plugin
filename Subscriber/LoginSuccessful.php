<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Event_EventArgs as EventArgs;
use Symfony\Component\DependencyInjection\ContainerInterface;

class LoginSuccessful implements SubscriberInterface
{
    /**
     * @var ContainerInterface
     */
    private $container;

    public function __construct(ContainerInterface $container)
    {
        $this->container = $container;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Shopware_Modules_Admin_Login_Successful' => 'hasJustLoggedIn',
        ];
    }

    public function hasJustLoggedIn(EventArgs $args): void
    {
        $session = $this->container->get('session');
        $session->set(LoginState::HAS_JUST_LOGGED_IN, true);
    }
}
