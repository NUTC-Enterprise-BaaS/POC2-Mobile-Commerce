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
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

FD::import('admin:/tables/table');

class SocialTablePollsItems extends SocialTable
{
	public $id = null;
	public $poll_id = null;
	public $value = null;
	public $count = null;

	public function __construct($db)
	{
		parent::__construct('#__social_polls_items', 'id', $db);
	}

    public function delete($pk = null)
    {
        $state  = parent::delete($pk);

        if ($state) {
            // Delete all the replies
            $model  = ES::model('Polls');
            $model->deleteItemUsers($this->id);
        }

        return $state;
    }
}
