<?php

defined('_JEXEC') or die('Restricted access');
jimport('joomla.filesystem.file');
jimport('joomla.application.component.modeladmin');
require_once(JPATH_COMPONENT_SITE.DS.'libraries'.DS.'phpqrcode'.DS.'qrlib.php');
require_once(JPATH_COMPONENT_SITE.DS.'libraries'.DS.'tfpdf'.DS.'tfpdf.php');

/**
 * OfferCoupon Model.
 *
 */
class JBusinessDirectoryModelOfferCoupon extends JModelAdmin {

	/**
	 * @var		string	The prefix to use with controller messages.
	 * @since   1.6
	 */
	protected $text_prefix = 'COM_JBUSINESSDIRECTORY_OFFER_COUPON';

	/**
	 * Model context string.
	 *
	 * @var		string
	 */
	protected $_context	= 'com_jbusinessdirectory.offercoupon';

	/**
	 * Method to test whether a record can be deleted.
	 *
	 * @param   object	A record object.
	 *
	 * @return  boolean  True if allowed to delete the record. Defaults to the permission set in the component.
	 */
	protected function canDelete($record) {
		return true;
	}

	/**
	 * Returns a Table object, always creating it
	 *
	 * @param   type	The table type to instantiate
	 * @param   string	A prefix for the table class name. Optional.
	 * @param   array  Configuration array for model. Optional.
	 * @return  JTable	A database object
	*/
	public function getTable($type = 'OfferCoupon', $prefix = 'JTable', $config = array()) {
		return JTable::getInstance($type, $prefix, $config);
	}

	/**
	 * Method to get a menu item.
	 *
	 * @param   integer	The id of the menu item to get.
	 *
	 * @return  mixed  Menu item data object on success, false on failure.
	 */
	public function &getItem($itemId = null) {
		$itemId = (!empty($itemId)) ? $itemId : (int) $this->getState('offer.id');
		$false	= false;

		// Get a menu item row instance.
		$table = $this->getTable();

		// Attempt to load the row.
		$return = $table->load($itemId);

		// Check for a table object error.
		if ($return === false && $table->getError()) {
			$this->setError($table->getError());
			return $false;
		}

		$properties = $table->getProperties(1);
		$value = JArrayHelper::toObject($properties, 'JObject');

		$value->generated_time = JBusinessUtil::convertToFormat($value->generated_time);
		$value->expiration_time = JBusinessUtil::convertToFormat($value->expiration_time);
		
		return $value;
	}
	
	/**
	 * Method to get the menu item form.
	 *
	 * @param   array  $data		Data for the form.
	 * @param   boolean	$loadData	True if the form is to load its own data (default case), false if not.
	 * @return  JForm	A JForm object on success, false on failure
	 * @since   1.6
	 */
	public function getForm($data = array(), $loadData = true) {
		// The folder and element vars are passed when saving the form.
		if (empty($data)) {
			$item = $this->getItem();
			// The type should already be set.
		}
		// Get the form.
		$form = $this->loadForm('com_jbusinessdirectory.offercoupon', 'item', array('control' => 'jform', 'load_data' => $loadData), true);
		if (empty($form)) {
			return false;
		}
		
		return $form;
	}

	/**
	 * Method to get the data that should be injected in the form.
	 *
	 * @return  mixed  The data for the form.
	 * @since   1.6
	 */
	protected function loadFormData() {
		// Check the session for previously entered form data.
		$data = JFactory::getApplication()->getUserState('com_jbusinessdirectory.edit.offercoupon.data', array());

		if (empty($data)) {
			$data = $this->getItem();
		}

		return $data;
	}

	/**
	*
	* Show PDF by coupon ID
	*/
	public function show($id) {
		$offercouponsTable = $this->getTable("OfferCoupon");
		$this->coupon = $offercouponsTable->getCoupon($id);

		//set it to writable location, a place for temp generated PNG files
		$PNG_TEMP_DIR = JPATH_ROOT.DS.PICTURES_PATH.DS.'coupons'.DS.$this->coupon->offer_id.DS;

		//html PNG location prefix
		$PNG_WEB_DIR = JURI::root().PICTURES_PATH.DS.'coupons'.DS.$this->coupon->offer_id.DS;

		//we need rights to create temp dir
		if (!file_exists($PNG_TEMP_DIR))
			mkdir($PNG_TEMP_DIR);

		$filename = $PNG_TEMP_DIR.'test.png';

		//options
		$errorCorrectionLevel = 'L';
		$matrixPointSize = 5;

		if ($this->coupon->code) {
			//it's very important!
			if (trim($this->coupon->code) == '')
				die('data cannot be empty!');

			$filename = $PNG_TEMP_DIR.md5($this->coupon->code).'.png';

			QRcode::png($this->coupon->code, $filename, $errorCorrectionLevel, $matrixPointSize, 2);    
		} else {
			//default data
			echo 'You can provide data in GET parameter';
			QRcode::png('PHP QR Code :)', $filename, $errorCorrectionLevel, $matrixPointSize, 2);
		}

		header('Content-type: application/pdf');
		header('Content-Disposition: inline; filename="coupon.pdf"');
		header('Content-Transfer-Encoding: binary');
		header('Accept-Ranges: bytes');

		$pdf = new tFPDF();

		$pdf->AddPage();
		$pdf->AddFont('DejaVu','','DejaVuSans.ttf',true);
		$pdf->AddFont('DejaVu', 'B', 'DejaVuSans-Bold.ttf', true);
		$pdf->SetFont("DejaVu","B","14");

		$pdf->Cell(0,10,JText::_('LNG_COUPON').": ".strtoupper($this->coupon->code),0,1,"L");
	
		try{
			$pdf->Image($PNG_WEB_DIR.basename($filename));
		}catch( Exception $ex ){
			$this->setError($ex);
		}

		$pdf->Cell(0,10,"",0,1,"L");

		$pdf->SetFont("","","15");
		$pdf->Cell(0,10,($this->coupon->offer),0,1,"C");
		$pdf->Cell(0,10,"",0,1,"L");
		
		$pdf->SetFont("","","11");
		$pdf->Cell(0,10,JText::_('LNG_COMPANY').": ".($this->coupon->company),0,1,"L");
		$pdf->Cell(0,10,JText::_('LNG_LOCATION').": ".($this->coupon->offer_address).', '.utf8_decode($this->coupon->offer_city),0,1,"L");
		$pdf->Cell(0,10,JText::_('LNG_PHONE').": ".($this->coupon->phone),0,1,"L");
		$pdf->Cell(0,10,JText::_('LNG_GENERATED_TIME').": ". JBusinessUtil::getDateGeneralFormatWithTime($this->coupon->generated_time),0,1,"L");
		$pdf->Cell(0,10,JText::_('LNG_EXPIRATION_TIME').": ".JBusinessUtil::getDateGeneralFormatWithTime($this->coupon->expiration_time),0,1,"L");
		
		$pdf->Cell(0,10,"",0,1,"L");

		$pdf->SetFont("","","10");

		$appSettings = JBusinessUtil::getInstance()->getApplicationSettings();
		$translations = JBusinessDirectoryTranslations::getAllTranslations(OFFER_DESCRIPTION_TRANSLATION, $this->coupon->offer_id);
		$lng = JFactory::getLanguage()->getTag();

		if($appSettings->enable_multilingual) {
			$description = isset($translations[$lng])?$translations[$lng]:"";
			if(empty($description))
				$description = $this->coupon->offer_description;
		} else {
			//This can be improved with the addon html2pdf for fpdf library.
			$description = $this->coupon->offer_description;
		}

		$description = strip_tags(($description));
		$pdf->Write(5, $description);
		
		$pdf->Cell(0,10,"",0,1,"L");
		
		$pdf->SetFont("","B","10");
		$warning = strip_tags((JText::_('LNG_QRCODE_MESSAGE')));
		$pdf->Write(5, $warning);

		$pdf->Output('coupon.pdf', 'I');
		exit;
	}
	
	/**
	 * Method to delete groups.
	 *
	 * @param   array  An array of item ids.
	 * @return  boolean  Returns true on success, false on failure.
	 */
	public function delete(&$itemIds) {
		// Sanitize the ids.
		$itemIds = (array) $itemIds;
		JArrayHelper::toInteger($itemIds);
	
		// Get a group row instance.
		$table = $this->getTable();
	
		// Iterate the items to delete each one.
		foreach ($itemIds as $itemId) {
			// Detele coupon image
			if (!$this->deleteFiles($itemId)) {
				$this->setError("Could not delete files");
				return false;
			}
			// Delete coupon
			if (!$table->delete($itemId)) {
				$this->setError($table->getError());
				return false;
			}
		}
		// Clean the cache
		$this->cleanCache();
	
		return true;
	}
	
	/**
	 * Delete coupon files
	 * @param $itemId
	 * @return boolean
	 */
	function deleteFiles($itemId) {
		$offercouponsTable = $this->getTable("OfferCoupon");
		$coupon = $offercouponsTable->getCoupon($itemId);
		$file = JPATH_ROOT.DS.PICTURES_PATH.DS.'coupons'.DS.$coupon->offer_id.DS.md5($coupon->code).'.png';
		JFile::delete($file);
		return true;
	}
}