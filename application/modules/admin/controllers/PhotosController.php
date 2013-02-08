<?php

class Admin_PhotosController extends Kaipack_Controller_AdminAction
{
    public function addPhotoAction()
    {
        if (isset($_FILES['upload'])) {

            if (!$this->_getParam('description') || $this->_getParam('description') == '') {
                $this->view->error = 'Пожалуйста укажите описание фотографии';
                return;
            }

            $file = $_FILES['upload'];

            $file['type'] = strtolower($file['type']);
            $uploaddir = ROOT_DIR . '/public/uploads/photos/';
            $uploadfile = $uploaddir . basename($_FILES['upload']['name']);

            if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
                $photo = new Photo();
                $row = $photo->createRow();

                $filter = new Zend_Filter_StripTags(array('br'));
                $row->description = $filter->filter($this->_getParam('description'));
                $row->url  = '/uploads/photos/' . $file['name'];
                $row->name = $file['name'];
                $row->full_path  = $uploadfile;
                $row->created_at = date('Y-m-d H:i:s');
                $row->save();
                $this->_redirect('/photos');
            } else {
                $this->view->error = 'Во время загрузки файла возникли ошибки.';
            }

        }
    }

    public function editAction()
    {
        if (!$this->_getParam('id')) {
            throw new Zend_Controller_Action_Exception('Не указан ID фотографии');
        }

        $db = new Photo();
        $row = $db->find((int)$this->_getParam('id'))->current();

        if (!$row) {
            throw new Zend_Controller_Action_Exception('Фотография с указанным ID не найдена');
        }

        $this->view->photo = $row;

        if ($this->getRequest()->isPost()) {
            if (!$this->_getParam('description') || $this->_getParam('description') == '') {
                $this->view->error = 'Пожалуйста укажите описание фотографии';
                return;
            }

            $filter = new Zend_Filter_StripTags(array('br'));
            $row->description = $filter->filter($this->_getParam('description'));
            $row->save();

            $this->_redirect('/photo' . $row->id);
        }
    }

    public function deleteAction()
    {
        if (!$this->_getParam('id')) {
            throw new Zend_Controller_Action_Exception('Не указан ID фотографии');
        }

        $db = new Photo();
        $row = $db->find((int)$this->_getParam('id'))->current();

        if (!$row) {
            throw new Zend_Controller_Action_Exception('Фотография с указанным ID не найдена');
        }

        unlink($row->full_path);
        $row->delete();
        $this->_redirect('/photos');
    }
}