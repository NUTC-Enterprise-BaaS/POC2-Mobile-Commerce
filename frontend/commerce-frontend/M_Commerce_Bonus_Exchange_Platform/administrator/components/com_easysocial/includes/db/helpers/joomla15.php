<?php
/**
* @package      EasySocial
* @copyright    Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license      GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined('_JEXEC') or die('Unauthorized Access');

FD::import('admin:/includes/db/helpers/base');

/**
 * DB layer for EasySocial.
 *
 * @since   1.0
 * @author  Mark Lee <mark@stackideas.com>
 */
class SocialDBHelperJoomla15 extends SocialDBHelper
{
    public function loadColumn()
    {
        return $this->db->loadResultArray();
    }
}
