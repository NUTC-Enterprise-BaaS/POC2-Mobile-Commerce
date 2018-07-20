<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
 * @deprecated 7a268a760d08d80fc36b66c7f72b6c73
**/
defined('_JEXEC') or die;

jimport( 'joomla.application.component.view');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'models'.DS.'admin.php');
require_once (JPATH_SITE.DS.'components'.DS.'com_fss'.DS.'helper'.DS.'comments.php');


class FssViewLogin extends FSSView
{
	protected $data;

	protected $form;

	protected $params;

	protected $state;

	public $document;


    function display($tpl = null)
    {
		$file = JPATH_SITE.DS."components".DS."com_fss".DS."models".DS."forms".DS."registration.xml";
		if (file_exists($file)) unlink($file);

		$lang = JFactory::getLanguage();
		$lang->load("com_users");

		$layout = JRequest::getVar('layout');

		$this->return = base64_encode($this->getLink());

		if ($layout == "register")
			return $this->register();

		if ($layout == "doregister")
			return $this->doregister();

		$user = JFactory::getUser();
		if ($user->id > 0)
		{
			JFactory::getApplication()->redirect($this->getLink());
		}

        parent::display();
    }
		
	public function register()
	{
		$this->setLayout("register");

		// Get the view data.
		$this->data		= $this->get('Data');
		$this->form		= $this->get('Form');
		$this->state	= $this->get('State');
		$this->params	= $this->state->get('params');

		// Check for errors.
		if (count($errors = $this->get('Errors')))
		{
			JError::raiseError(500, implode('<br />', $errors));
			return false;
		}

		// Check for layout override
		$active = JFactory::getApplication()->getMenu()->getActive();
		if (isset($active->query['layout']))
		{
			$this->setLayout($active->query['layout']);
		}

		//Escape strings for HTML output
		$this->pageclass_sfx = htmlspecialchars($this->params->get('pageclass_sfx'));

		return parent::display();
	}

	function getLink()
	{

		$this->params = JFactory::getApplication()->getParams('com_fss');

		$type = $this->params->get('type');
		$link = FSSRoute::_( 'index.php?option=com_fss&view=main', false );
		// if we are a multi language site, then we need to ignore the itemid paramter, and let FSSRoute 
		// track down the correct link.
		// for manually added items, just use the link we have stored.
		switch($type)
		{
			case FSS_IT_KB:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=kb', false );
				break;		
			case FSS_IT_FAQ:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=faq', false );
				break;		
			case FSS_IT_TEST:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=test', false );
				break;		
			case FSS_IT_NEWTICKET:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=ticket&layout=open', false );
				break;		
			case FSS_IT_VIEWTICKETS:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=ticket', false );
				break;		
			case FSS_IT_ANNOUNCE:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=announce', false );
				break;		
			case FSS_IT_GLOSSARY:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=glossary', false );
				break;		
			case FSS_IT_ADMIN:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=admin', false );
				break;		
			case FSS_IT_GROUPS:
				$link = FSSRoute::_( 'index.php?option=com_fss&view=admin_groups', false );
				break;	
			case FSS_IT_MENUITEM:
				// lookup menu item link here!
				$db = JFactory::getDBO();
				$sql = "SELECT * FROM #__menu WHERE id = " . $db->escape($this->params->get('menuitemid'));
				$db->setQuery($sql);
				$menu = $db->loadObject();
				$link = JRoute::_($menu->link . "&Itemid=" . $this->params->get('menuitemid'), false);
				break;
			case FSS_IT_LINK:	
				// lookup menu item link here!
				$link = $this->params->get('link');
				break;
		}	
		
		return $link;
	}

	function get_register_url()
	{
		$return = FSS_Helper::getCurrentURLBase64();

		$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . $return, false);
		
		if (property_exists($this, "return"))
			$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . $this->return, false);
		
		if (JRequest::getVar('return'))
			$register_url = FSSRoute::_("index.php?option=com_fss&view=login&layout=register&return=" . JRequest::getVar('return'), false);
		
		if (FSS_Settings::get('support_custom_register'))
			$register_url = FSS_Settings::get('support_custom_register');

		return $register_url;
	}

	function doregister()
	{
		// Check for request forgeries.
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		JForm::addFormPath(JPATH_SITE.DS.'components'.DS.'com_users'.DS.'models'.DS.'forms');
		JForm::addFieldPath(JPATH_SITE.DS.'components'.DS.'com_users'.DS.'models'.DS.'fields');

		// If registration is disabled - Redirect to login page.
		if (JComponentHelper::getParams('com_users')->get('allowUserRegistration') == 0)
		{
			JFactory::getApplication()->redirect(JRoute::_('index.php?option=com_fss&view=login', false));
			return false;
		}

		require_once(JPATH_SITE.DS."components".DS."com_users".DS."models".DS."registration.php");

		$app	= JFactory::getApplication();
		$model = JModelLegacy::getInstance('Registration', 'UsersModel', array());
		// Get the user data.
		$requestData = JRequest::getVar('jform', array(), 'array');

		// Validate the posted data.
		$form	= $model->getForm();

		if (!$form)
		{
			JError::raiseError(500, $model->getError());
			return false;
		}

		$data	= $model->validate($form, $requestData);

		// Check for validation errors.
		if ($data === false)
		{
			// Get the validation messages.
			$errors	= $model->getErrors();

			// Push up to three validation messages out to the user.
			for ($i = 0, $n = count($errors); $i < $n && $i < 3; $i++)
			{
				if ($errors[$i] instanceof Exception)
				{
					$app->enqueueMessage($errors[$i]->getMessage(), 'warning');
				}
				else
				{
					$app->enqueueMessage($errors[$i], 'warning');
				}
			}

			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $requestData);
			
			// Redirect back to the registration screen.
			JFactory::getApplication()->redirect($this->get_register_url());

			return false;
		}

		// Attempt to save the data.
		$return	= $model->register($data);


		// Check for errors.
		if ($return === false)
		{
			// Save the data in the session.
			$app->setUserState('com_users.registration.data', $data);

			// Redirect back to the edit screen.
			JFactory::getApplication()->enqueueMessage($model->getError(), 'warning');

			JFactory::getApplication()->redirect($this->get_register_url());

			return false;
		}

		// Flush the data from the session.
		$app->setUserState('com_users.registration.data', null);

		$return = JRoute::_("index.php?option=com_fss&view=login", false);
		if (JRequest::getVar('return'))
		{
			$return = base64_decode(JRequest::getVar('return'));
		}

		// Redirect to the profile screen.
		if ($return === 'adminactivate')
		{
			JFactory::getApplication()->redirect($return, JText::_('COM_USERS_REGISTRATION_COMPLETE_VERIFY'), 'message');
		}
		elseif ($return === 'useractivate')
		{
			JFactory::getApplication()->redirect($return, JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'), 'message');
		}
		else
		{
			$this->doLogin();
		}

		return true;
	}

	function doLogin()
	{
		$app    = JFactory::getApplication();
		$input  = $app->input;
		$method = $input->getMethod();

		// Populate the data array:
		$requestData = JRequest::getVar('jform', array(), 'array');

		// Get the log in options.
		$options = array();
		$options['remember'] = false;
		$options['return']   = base64_decode($app->input->post->get('return', '', 'BASE64'));

		// Get the log in credentials.
		$credentials = array();
		$credentials['username']  = $requestData['username'];
		$credentials['password']  = $requestData['password1'];

		// Perform the log in.
		if (true === $app->login($credentials, $options))
		{
			JFactory::getApplication()->redirect($options['return'], JText::_('FSS_USER_REGISTERED'), 'message');
		}
		else
		{
			JFactory::getApplication()->redirect($options['return'], JText::_('COM_USERS_REGISTRATION_COMPLETE_ACTIVATE'), 'message');
		}
	}
}
