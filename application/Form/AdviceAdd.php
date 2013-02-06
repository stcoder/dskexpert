<?php
class Form_AdviceAdd extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/admin/advice/add');
		$this->setAttrib('class', 'form-horizontal');
		$this->setAttrib('id', 'user-data');

		$this->addDecorator('formElements')
			->addDecorator('form');

		$title = new Zend_Form_Element_Text('title');
		$title->setOptions(array(
			'autofocus' => true,
			'required' => true,
			'label' => 'Заголовок',
			'disableLoadDefaultDecorators' => true
		));

		$body = new Zend_Form_Element_Textarea('redactor_content');
		$body->setOptions(array(
			'required' => true,
			'label' => 'Пароль',
			'disableLoadDefaultDecorators' => true
		));

		$hash = new Zend_Form_Element_Hash('form-hash');
		$hash->setOptions(array(
			'salt' => 'kaihost::admin_',
			'disableLoadDefaultDecorators' => true
		));
		$hash->addDecorator('viewHelper')
			->addDecorator('label', array('tag' => ''))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group', 'style' => 'display: none;'));

		$submit = new Zend_Form_Element_Submit('accept');
		$submit->setOptions(array(
			'label' => 'Войти',
			'class' => 'btn btn-primary',
			'disableLoadDefaultDecorators' => true,
			'decorators' => array(
				'viewHelper',
				'FormElements',
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-actions')),
			)
		));
		$this->addElements(array($title, $body, $hash, $submit));
	}
}