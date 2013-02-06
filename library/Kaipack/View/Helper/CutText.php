<?php
class Kaipack_View_Helper_CutText extends Zend_View_Helper_Abstract
{
	public function cutText($text)
	{
		$this->view->cut_text = '';
		$length = strpos($text, '<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>');

		if ($length) {
			$this->view->cut_text = substr(substr($text, 0, $length), 0, $length);
		}
		$text = strtr($text, array('<div style="page-break-after: always;"><span style="display: none;">&nbsp;</span></div>' => ''));
		$this->view->full_text = $text;

		return $this->view->render('cut-text.html');
	}
}