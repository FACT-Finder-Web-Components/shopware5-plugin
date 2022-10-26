<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Shopware\Bundle\CookieBundle\CookieCollection;
use Shopware\Bundle\CookieBundle\Structs\CookieGroupStruct;
use Shopware\Bundle\CookieBundle\Structs\CookieStruct;

class FactfinderCookie implements SubscriberInterface
{
    public static function getSubscribedEvents(): array
    {
        return [
            'CookieCollector_Collect_Cookies' => 'addCookies',
        ];
    }

    public function addCookies(): CookieCollection
    {
        $collection = new CookieCollection();
        $collection->add(new CookieStruct(
            'ff_has_just_logged_in',
            '/^ff_has_just_logged_in$/',
            'Matches with only "ff_has_just_logged_in"',
            CookieGroupStruct::TECHNICAL
        ));
        $collection->add(new CookieStruct(
            'ff_has_just_logged_out',
            '/^ff_has_just_logged_out$/',
            'Matches with only "ff_has_just_logged_out"',
            CookieGroupStruct::TECHNICAL
        ));
        $collection->add(new CookieStruct(
            'ff_user_id',
            '/^ff_user_id$/',
            'Matches with only "ff_user_id"',
            CookieGroupStruct::TECHNICAL
        ));

        return $collection;
    }
}
