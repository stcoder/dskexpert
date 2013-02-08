<?php
class FaqController extends Kaipack_Controller_Action
{
	public function indexAction()
	{
		$faq = new Faq();
		$this->view->questions = $faq->getPaginatorRows();

        $form = new Form_AddQuestion();

        if ($this->getRequest()->isPost()) {
            if ($form->isValid($_POST)) {
                // добавить в базу вопросов
                $faq = new Faq();
                $row = $faq->createRow();
                $row->username = $form->getValue('name');
                $row->email = $form->getValue('email');
                $row->question = $form->getValue('question');
                $row->published = 'off';
                $row->isNew = 'yes';
                $row->created_at = date('Y-m-d H:i:s');
                $row->save();

                $config = Config::getInstance();

                $mail = new Zend_Mail('utf-8');
                $mail->setFrom($config->getOption('email-notification'), $config->getOption('title_site'));
                $mail->addTo($config->getOption('email-notification'));
                $mail->setSubject('Новый вопрос');

                $html = '<p>Новый вопрос от: ' . $form->getValue('name') . ' <em>(' . $form->getValue('email') . ')</em></p>';
                $html .= '<p>Вопрос: ' . $form->getValue('question') . '</p>';

                $mail->setBodyHtml($html);
                $mail->send();

                $this->view->message = true;
                $this->view->email = $form->getValue('email');
            }
        }

        $this->view->form = $form;
	}
}