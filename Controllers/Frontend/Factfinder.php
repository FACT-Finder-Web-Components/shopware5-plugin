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
        $view->assign('searchImmediate', $this->Request()->getParam('searchImmediate', false));
        $view->assign('sid', $this->get('session')->get('sessionId'));
        $view->assign('uid', (int) $this->get('session')->get('sUserId'));
    }
}
