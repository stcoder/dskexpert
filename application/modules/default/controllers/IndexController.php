<?php
class IndexController extends Kaipack_Controller_Action
{

    function indexAction()
    {
        $model = new page_model_Table();
        $page = $model->findByUrlKey('index');

        if (!$page) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }

        $this->view->headTitle($page->title, 'PREPEND');
        $this->view->headMeta()->setName('description', $page->description);
        $this->view->headMeta()->setName('keywords', $page->keywords);
        $this->view->body = $page->body;

        $advice = new Advice();
        $this->view->advices = $advice->getRandom();

        $photo = new Photo();
        $this->view->photo = $photo->getRandomPhoto();
	}

	function serviceAction()
	{
		$this->setPage('service');
	}

	function adviceAction()
	{
		$this->setPage('advice');
	}

	function articleAction()
	{
		$this->setPage('article');
	}

	function faqAction()
	{
		$this->setPage('faq');
	}

	function ratingContractorsAction()
	{
		$this->setPage('rating-contractors');
	}

    function aboutAction()
    {
        $this->setPage('about');
    }

	function tenderAction()
	{
		$this->setPage('tender');
	}

	function costOfServicesAction()
	{
		$this->setPage('cost-of-services');
	}

	function contactsAction()
	{
        $model = new page_model_Table();
        $page = $model->findByUrlKey('contacts');

        if (!$page) {
            throw new Zend_Controller_Action_Exception('Page not found', 404);
        }

        $this->view->headTitle($page->title, 'PREPEND');
        $this->view->headMeta()->setName('description', $page->description);
        $this->view->headMeta()->setName('keywords', $page->keywords);
        $this->view->body = $page->body;

        $form = new Form_AddQuestion();
        $form->setAction('/contacts');
        $form->getElement('question')->setLabel('Сообщение');
        $form->getElement('accept')->setLabel('Написать');

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {

                $config = Config::getInstance();

                $mail = new Zend_Mail('utf-8');
                $mail->setFrom($config->getOption('email-notification'), $config->getOption('title_site'));
                $mail->addTo($config->getOption('email-notification'));
                $mail->setSubject('Новое сообщение');

                $html = '<p>Новое сообщение от: ' . $form->getValue('name') . ' <em>(' . $form->getValue('email') . ')</em></p>';
                $html .= '<p>Сообщение: ' . $form->getValue('question') . '</p>';

                $mail->setBodyHtml($html);
                $mail->send();

                $this->view->message = true;
                $this->view->email = $form->getValue('email');
            }
        }
        $this->view->form = $form;
	}
}