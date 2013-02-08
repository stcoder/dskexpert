<?php
class Form_AddRating extends Zend_Form
{
	public function init()
	{
		$this->setMethod('post');
		$this->setAction('/contractors/add');
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

		$email = new Zend_Form_Element_Text('email');
		$email->setOptions(array(
			'validators' => array(
				'emailAddress'
			),
			'required' => true,
			'label' => 'Ваш e-mail',
			'disableLoadDefaultDecorators' => true
		));

		$company_name = new Zend_Form_Element_Text('company_name');
		$company_name->setOptions(array(
			/*'filters' => array(
				'stripTags'
			),*/
			'required' => true,
			'label' => 'Название компании',
			'disableLoadDefaultDecorators' => true
		));

		/*$company_description = new Zend_Form_Element_Textarea('company_description');
		$company_description->setOptions(array(
			'style' => 'height: 200px; width: 500px;',
			'label' => 'Описание компании',
			'required' => true,
			'disableLoadDefaultDecorators' => true
		));*/

		$comment = new Zend_Form_Element_Textarea('comment');
		$comment->setOptions(array(
			'style' => 'height: 200px; width: 500px;',
			'label' => 'Отзыв',
			'required' => true,
			'disableLoadDefaultDecorators' => true
		));

		$rating = new Zend_Form_Element_Select('rating', array(
			'label' => 'Оценка'
		));
		$rating->addMultiOptions(array(
			'1' => '1',
			'2' => '2',
			'3' => '3',
			'4' => '4',
			'5' => '5',
		));

		$hash = new Zend_Form_Element_Hash('form-hash');
		$hash->setOptions(array(
			'salt' => 'kaihost::comment_',
			'required' => true,
			'disableLoadDefaultDecorators' => true
		));
		$hash->addDecorator('viewHelper')
			->addDecorator('label', array('tag' => ''))
			->addDecorator('htmlTag', array('tag' => 'div', 'class' => 'control-group', 'style' => 'display: none;'));

		$submit = new Zend_Form_Element_Submit('accept');
		$submit->setOptions(array(
			'label' => 'Оставить отзыв',
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

		$this->addElements(array($user, $email, $company_name, /*$company_description, */$rating, $comment, $captcha, $hash, $submit));
	}
}