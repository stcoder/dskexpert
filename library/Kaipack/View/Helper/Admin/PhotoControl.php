<?php
class Kaipack_View_Helper_Admin_PhotoControl extends Zend_View_Helper_Abstract
{
    public function Admin_PhotoControl($photo)
    {
        $auth = Zend_Auth::getInstance();
        if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
            $this->view->photo = $photo;
            return $this->view->render('admin/photo.html');
        }
    }
}