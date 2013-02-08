<?php
class Kaipack_View_Helper_Admin_Bar extends Zend_View_Helper_Abstract
{
	public function Admin_bar()
	{
		$auth = Zend_Auth::getInstance();

		if ($auth->hasIdentity() && $auth->getIdentity()->role === 'admin') {
			$this->view->nav_items = array();
			$controller = Zend_Controller_Front::getInstance()->getRequest()->getControllerName();
			$module = Zend_Controller_Front::getInstance()->getRequest()->getModuleName();

			if ($controller === 'index') {
				$currentPage = Zend_Controller_Front::getInstance()->getRequest()->getActionName();

				$this->view->nav_items = array(
					'edit_page' => array(
						'label' => 'Редактировать страницу',
						'href' => '/admin/page/edit?page=' . $currentPage
					)
				);
			}

			if ($controller === 'advice') {
				$this->view->nav_items = array(
					'add_advice' => array(
						'label' => 'Добавить совет',
						'href' => '/admin/advice/add'
					)
				);
			}

			if ($controller === 'article') {
				$this->view->nav_items = array(
					'add_article' => array(
						'label' => 'Добавить статью',
						'href' => '/admin/article/add'
					)
				);
			}

            if ($controller === 'photos') {
                $this->view->nav_items = array(
                    'add_photo' => array(
                        'label' => 'Добавить фотографию',
                        'href'  => '/admin/photos/add.photo'
                    )
                );
            }

			$dbFaq = new Faq();
			$this->view->new_questions = $dbFaq->counNewQuestions();

			$dbContractors = new Contractors();
			$this->view->new_contractors = $dbContractors->counNewQuestions();
			return $this->view->render('admin/bar.html');
		}
	}
}