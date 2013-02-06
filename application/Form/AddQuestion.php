<?php
class Form_AddQuestion extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/faq');
		$this->setAttrib('class', 'form-horizontal');
		$this->setAttrib('id', 'user-data');

		$this->addDecorator('formElements')
			->addDecorator('form');

		$user = new Zend_Form_Element_Text('name');
		$user->setOptions(array(
			'validators' => array(
				array('regex', false, '/[а-я\s]+/i')
			),
			'required' => true,
			'label' => 'Ваше имя',
			'disableLoadDefaultDecorators' => true
		));

		$email = new Zend_Form_Element_Text('email');
		$email->setOptions(array(
			'validators' => array(
				'emailAddress'
			),
			'required' => true,
			'label' => 'Ваш e-mail',
			'disableLoadDefaultDecorators' => true
		));

		$question = new Zend_Form_Element_Textarea('question');
		$question->setOptions(array(
			'style' => 'height: 200px; width: 500px;',
			'label' => 'Вопрос',
			'required' => true,
			'disableLoadDefaultDecorators' => true
		));

		$hash = new Zend_Form_Element_Hash('form-hash');
		$hash->setOptions(array(
			'salt' => 'kaihost::admin_',
			'required' => true,
			'disableLoadDefaultDecorators' => true
		));
		$hash->addDecorator('viewHelper')
			->addDecorator('label', array('tag' => ''))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group', 'style' => 'display: none;'));

		$submit = new Zend_Form_Element_Submit('accept');
		$submit->setOptions(array(
			'label' => 'Спросить',
			'class' => 'btn',
			'disableLoadDefaultDecorators' => true,
			'decorators' => array(
				'viewHelper',
				'FormElements',
				array('HtmlTag', array('tag' => 'div', 'class' => 'form-actions')),
			)
		));

		$captcha = new Zend_Form_Element_Captcha('captcha', array(
			'label' => 'Введите символы',
			'captcha' => array(
				'captcha'   => 'Image',
				'wordLen'   => 6,
				'width'     => 220,
				'timeout'   => 120,
				'expiration'=> 300,
				'font' 		=> ROOT_DIR . '/public/fonts/arial.ttf',
				'imgDir'    => ROOT_DIR . '/public/images/captcha/',
				'imgUrl'    => '/images/captcha/',
				'gcFreq'    => 5,
				'dotNoiseLevel' => 20,
				'lineNoiseLevel' => 1
			),
		));

		$this->addElements(array($user, $email, $question, $captcha, $hash, $submit));
	}
}