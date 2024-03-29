<?php

declare(strict_types=1);

namespace OmikronFactfinder\Subscriber;

use Enlight\Event\SubscriberInterface;
use Enlight_Controller_ActionEventArgs as EventArgs;
use OmikronFactfinder\Components\CategoryFilter;
use OmikronFactfinder\Components\Configuration;

class CategoryView implements SubscriberInterface
{
    /** @var Configuration */
    private $configuration;

    /** @var CategoryFilter */
    private $categoryPath;

    public function __construct(Configuration $configuration, CategoryFilter $categoryPath)
    {
        $this->configuration = $configuration;
        $this->categoryPath  = $categoryPath;
    }

    public static function getSubscribedEvents()
    {
        return [
            'Enlight_Controller_Action_PostDispatchSecure_Frontend_Listing' => 'onPostDispatch',
        ];
    }

    public function onPostDispatch(EventArgs $args): void
    {
        if ($this->configuration->useForCategories()) {
            $view = $args->getSubject()->View();
            $view->extendsTemplate('frontend/factfinder/category.tpl');
            ['id' => $id] = $view->getAssign('sCategoryContent');

            if ($id) {
                $view->extendsTemplate('frontend/factfinder/category.tpl');
                $categoryPath = $this->categoryPath->getValue($id);
                $view->assign('ffCategoryPath', $categoryPath);
                preg_replace_callback('/[^filter=]\w+(?=%3A)/', function (array $match) use ($view) {
                    $view->assign('ffCategoryPathFieldName', $match[0]);
                }, $categoryPath);
                return;
            }
        }
    }
}
