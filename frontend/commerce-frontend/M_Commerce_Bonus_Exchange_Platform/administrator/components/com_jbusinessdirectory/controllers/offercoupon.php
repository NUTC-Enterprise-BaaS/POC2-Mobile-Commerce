<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.application.component.controllerform');

/**
 * The OfferCoupon Controller
 *
 */
class JBusinessDirectoryControllerOfferCoupon extends JControllerForm {

	/**
	 * Dummy method to redirect back to standard controller
	 *
	 */
	public function display($cachable = false, $urlparams = false) {
		$this->setRedirect(JRoute::_('index.php?option=com_jbusinessdirectory&view=offercoupons', false));
	}

	/**
	 * Method to cancel an edit.
	 *
	 * @param   string  $key  The name of the primary key of the URL variable.
	 *
	 * @return  boolean  True if access level checks pass, false otherwise.
	 */
	public function cancel($key = null) {
		JSession::checkToken() or jexit(JText::_('JINVALID_TOKEN'));
		$app = JFactory::getApplication();
		$context = 'com_jbusinessdirectory.edit.offercoupon';
		$result = parent::cancel();
	}

	/**
	 * Show coupon (PDF)
	 */
	public function show() {
		// Get coupon to show from the request.
		$id = JFactory::getApplication()->input->get('id');

		// Get the model.
		$model = $this->getModel("OfferCoupon");
		
		// Show the PDF file.
		$model->show($id);
		exit();
	}
}
