<?php
/*------------------------------------------------------------------------
# JBusinessDirectory
# author CMSJunkie
# copyright Copyright (C) 2012 cmsjunkie.com. All Rights Reserved.
# @license - http://www.gnu.org/licenses/gpl-2.0.html GNU/GPL
# Websites: http://www.cmsjunkie.com
# Technical Support:  Forum - http://www.cmsjunkie.com/forum/j-businessdirectory/?p=1
-------------------------------------------------------------------------*/

defined( '_JEXEC' ) or die( 'Restricted access' );

require_once JPATH_COMPONENT_SITE.'/views/jbdview.php';

class JBusinessDirectoryViewManageCompanyMessages extends JBusinessDirectoryFrontEndView
{
    function __construct()
    {
        parent::__construct();
    }

    function display($tpl = null)
    {
        $this->companyId 	= $this->get('CompanyId');
        $this->items		= $this->get('CompanyMessages');
        $this->pagination	= $this->get('Pagination');
        $this->state		= $this->get('State');

        parent::display($tpl);
    }
}
?>