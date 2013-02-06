<?php

class PhotosController extends Kaipack_Controller_Action
{
    public function indexAction()
    {
        $photos = new Photo();
        $this->view->photos = $photos->getPaginatorRows($this->_getParam('page', 1));
    }

    public function getAction()
    {
        if (!$this->_getParam('photo_id')) {
            $this->_redirect('/photos');
        }

        $photos = new Photo();

        $row = $photos->find((int) $this->_getParam('photo_id'))->current();

        if (!$row) {
            $this->_redirect('/photos');
            return;
        }

        $this->view->photo = $row;
    }
}