<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use OmikronFactfinder\Components\Configuration;
use Symfony\Component\DependencyInjection\ContainerInterface;
use Symfony\Component\HttpFoundation\Response;

class LoginState implements SubscriberInterface
{
    const HAS_JUST_LOGGED_IN = 'ff_has_just_logged_in';
    const HAS_JUST_LOGGED_OUT = 'ff_has_just_logged_out';
    const USER_ID = 'ff_user_id';

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
        $this->container = $container;
        $this->configuration = $configuration;
        $this->session = $this->container->get('session');
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend' => [
                ['hasJustLoggedIn'],
                ['hasJustLoggedOut'],
                ['setIsTriggered'],
            ],
        ];
    }

    public function hasJustLoggedIn(\Enlight_Controller_ActionEventArgs $args): void
    {
        $session = $this->container->get('session');
        $response = $args->getResponse();
        $request = $args->getRequest();

        if (
            $this->isTriggered
            || $request->isXmlHttpRequest()
            || $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES
        ) {
            return;
        }

        if ($this->getUserId() === null) {
            $this->clearCookie(self::USER_ID);
            $this->clearCookie(self::HAS_JUST_LOGGED_OUT);
        }

        if ($this->getCookie(self::HAS_JUST_LOGGED_IN) !== '') {
            $this->clearCookie(self::HAS_JUST_LOGGED_IN);

            return;
        }

        if ($session->get(self::HAS_JUST_LOGGED_IN, false) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_IN, '1');
            $this->setCookie(self::USER_ID, $this->getUserId());
            $session->set(self::HAS_JUST_LOGGED_IN, false);
        }
    }

    public function hasJustLoggedOut(\Enlight_Controller_ActionEventArgs $args): void
    {
        $session = $this->container->get('session');
        $response = $args->getResponse();
        $request = $args->getRequest();

        if (
            $this->isTriggered
            || $request->isXmlHttpRequest()
            || $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES
        ) {
            return;
        }

        if ($session->get(self::HAS_JUST_LOGGED_OUT, false) === true) {
            $this->setCookie(self::HAS_JUST_LOGGED_OUT, '1');
            $this->clearCookie(self::USER_ID);
            $session->set(self::HAS_JUST_LOGGED_OUT, false);
        }
    }

    public function setIsTriggered(\Enlight_Controller_ActionEventArgs $args): void
    {
        $request = $args->getRequest();
        $response = $args->getResponse();

        if (
            $request->isXmlHttpRequest()
            || $response->getStatusCode() >= Response::HTTP_MULTIPLE_CHOICES
        ) {
            return;
        }

        $this->isTriggered = true;
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

    private function getCookie(string $name): string
    {
        return $_COOKIE[$name] ?? '';
    }

    private function getUserId(): string
    {
        $session = $this->container->get('session');
        $userId = (string) $session->get('sUserId');

        return $this->configuration->isFeatureEnabled('ffAnonymizeUserId') ? md5($userId) : $userId;
    }
}
