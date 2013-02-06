<?php
class Form_Mail extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/faq/addQuestion');
		$this->setAttrib('class', 'form-horizontal');
		$this->setAttrib('id', 'user-data');

		$this->addDecorator('formElements')
			->addDecorator('form');

		$user = new Zend_Form_Element_Text('name');
		$user->setOptions(array(
			'validators' => array(
				array('regex', false, '/[а-я\s]+/i')
			),
			'autofocus' => true,
			'required' => true,
			'label' => 'Ваше имя',
			'disableLoadDefaultDecorators' => true
		));
		$user->addDecorator('viewHelper')
			->addDecorator('errors')
			->addDecorator(array('control' => 'htmlTag'), array('tag' => 'div', 'class' => 'controls'))
			->addDecorator('label', array('class' => 'control-label'))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group'));

		$email = new Zend_Form_Element_Text('email');
		$email->setOptions(array(
			'validators' => array(
				'emailAddress'
			),
			'required' => true,
			'label' => 'Ваш e-mail',
			'disableLoadDefaultDecorators' => true
		));
		$email->addDecorator('viewHelper')
			->addDecorator('errors')
			->addDecorator(array('control' => 'htmlTag'), array('tag' => 'div', 'class' => 'controls'))
			->addDecorator('label', array('class' => 'control-label'))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group'));

		$description = new Zend_Form_Element_Textarea('description');
		$description->setOptions(array(
			'style' => 'height: 200px; width: 500px;',
			'label' => 'Дополнительные сведения',
			'disableLoadDefaultDecorators' => true
		));
		$description->addDecorator('viewHelper')
			->addDecorator('errors')
			->addDecorator(array('control' => 'htmlTag'), array('tag' => 'div', 'class' => 'controls'))
			->addDecorator('label', array('class' => 'control-label'))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group'));

		$hash = new Zend_Form_Element_Hash('form-hash');
		$hash->setOptions(array(
			'salt' => 'kaihost::mail_',
			'disableLoadDefaultDecorators' => true
		));
		$hash->addDecorator('viewHelper')
			->addDecorator('label', array('tag' => ''))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group', 'style' => 'display: none;'));

		$submit = new Zend_Form_Element_Submit('accept');
		$submit->setOptions(array(
			'label' => 'Подтвердить',
			'class' => 'btn btn-primary',
			'disableLoadDefaultDecorators' => true,
			'decorators' => array(
				'viewHelper',
				'FormElements',
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-actions')),
			)
		));
		$submit->setAttrib('data-loading-text', 'Обработка');

		/*$this->addElement('hidden', 'message_body', array(
			'disableLoadDefaultDecorators' => true,
			'decorators' => array(
				'viewHelper',
				array('label', array('tag' => '')),
				array('htmlTag', array('tag' => 'div', 'class' => 'control-group', 'style' => 'display: none;')),
			)
		));*/
		$this->addElements(array($user, $email, $description, $hash, $submit));
	}
}