<?php

/**
 * @copyright		(C)2013 DM Digital S.r.l
 * @author 			DM Digital <support@dmjoomlaextensions.com>
 * @link				http://www.dmjoomlaextensions.com
 * @license 			GNU/LGPL http://www.gnu.org/copyleft/gpl.html
 **/

defined('_JEXEC') or die('Restricted access');

class pkg_dmpinboardliteInstallerScript {

    public function install($parent) {

        $parent->getParent()->setRedirectURL('index.php?option=com_dmpinboardlite');
    }

    public function uninstall($parent) {

    }

    public function update($parent) {

        $parent->getParent()->setRedirectURL('index.php?option=com_dmpinboardlite');
    }

    public function preflight($type, $parent) {
    }

    public function postflight($type, $parent) {
    }

}

?>
