<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2015 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

class SocialStoryPanel extends SocialStoryPlugin
{
	public $button;
	public $content;

	public function __construct($name, $story)
	{
		parent::__construct($name, $story);

		$this->type = 'panel';

		$this->button = new stdClass();
		$this->button->classname = '';
		$this->button->html = '';

		$this->content = new stdClass();
		$this->content->classname = '';
		$this->content->html = '';
	}

	public function setHtml($button, $form)
	{
		$this->button->html = $button;
		$this->content->html = $form;
	}

	public function setScript($script)
	{
		$this->script = $script;
	}
}
