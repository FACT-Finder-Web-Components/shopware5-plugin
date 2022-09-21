<?php

declare(strict_types=1);

class Shopware_Controllers_Frontend_Factfinder extends \Enlight_Controller_Action
{
    public function resultAction()
    {
    }

    public function communicationAction()
    {
        $view = $this->View();
        $view->assign('addParams', $this->Request()->getParam('addParams', ''));
        $view->assign('categoryPage', $this->Request()->getParam('categoryPage', ''));
        $view->assign('searchImmediate', $this->Request()->getParam('searchImmediate', false));
        $view->assign('uid', $this->getUserId());
    }

    protected function getUserId(): string
    {
        $configuration = $this->get('OmikronFactfinder\Components\Configuration');
        $userId = (string) $this->get('session')->get('sUserId');

        return $configuration->isFeatureEnabled('ffAnonymizeUserId') ? md5($userId) : $userId;
    }
}
