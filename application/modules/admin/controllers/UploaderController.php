<?php

class Admin_UploaderController extends Kaipack_Controller_AdminAction
{
	public function init()
	{
		parent::init();

		$this->layoutOff();
		$this->viewOff();
	}

	public function imageAction()
	{
		$dir = ROOT_DIR . '/public/uploads/images/';

		if (isset($_FILES['upload'])) {	

			$file = $_FILES['upload'];

			$file['type'] = strtolower($file['type']);
			$uploaddir = ROOT_DIR . '/public/uploads/images/';
			$uploadfile = $uploaddir . basename($_FILES['upload']['name']);

			var_dump($_FILES['upload']['tmp_name'], $uploadfile);

			if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
			    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(" . $this->_getParam('CKEditorFuncNum') . ", '/uploads/images/" . $_FILES['upload']['name'] . "', '');</script>";
			} else {
			    echo "Возможная атака с помощью файловой загрузки!\n";
			}
			
		} else {
			echo "Возможная атака с помощью файловой загрузки!\n";
		}
	}

	public function fileAction()
	{
		/*copy($_FILES['file']['tmp_name'], ROOT_DIR . '/public/uploads/files/' . $_FILES['file']['name']);

		$array = array(
			'filelink' => '/uploads/files/'.$_FILES['file']['name'],
			'filename' => $_FILES['file']['name']
		);

		echo stripslashes('<a href="' . $array['filelink'] . '">' . $array['filename'] . '</a>');*/
		if (isset($_FILES['upload'])) {	
			$uploaddir = ROOT_DIR . '/public/uploads/files/';
			$uploadfile = $uploaddir . basename($_FILES['upload']['name']);

			if (move_uploaded_file($_FILES['upload']['tmp_name'], $uploadfile)) {
			    echo "<script type='text/javascript'>window.parent.CKEDITOR.tools.callFunction(" . $this->_getParam('CKEditorFuncNum') . ", '/uploads/files/" . $_FILES['upload']['name'] . "', '');</script>";
			} else {
			    echo "Возможная атака с помощью файловой загрузки!\n";
			}
		} else {
			echo "Возможная атака с помощью файловой загрузки!\n";
		}
	}
}