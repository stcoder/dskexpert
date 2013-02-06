<?php
class Form_AdminLogin extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/admin');
		$this->setAttrib('class', 'form-horizontal');
		$this->setAttrib('id', 'user-data');

		$this->addDecorator('formElements')
			->addDecorator('form');

		$user = new Zend_Form_Element_Text('login');
		$user->setOptions(array(
			'validators' => array(
				'alnum'
			),
			'autofocus' => true,
			'required' => true,
			'label' => 'Логин',
			'disableLoadDefaultDecorators' => true
		));

		$password = new Zend_Form_Element_Password('password');
		$password->setOptions(array(
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

		$this->addElements(array($user, $password, $captcha, $hash, $submit));
	}
}