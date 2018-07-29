<?php
/**
 * @package Freestyle Joomla
 * @author Freestyle Joomla
 * @copyright (C) 2013 Freestyle Joomla
 * @license GNU/GPLv3 http://www.gnu.org/licenses/gpl-3.0.html
**/
defined('_JEXEC') or die;

class FsssControllerProd extends FsssController
{

	function __construct()
	{
		parent::__construct();

		// Register Extra tasks
		$this->registerTask( 'add'  , 	'edit' );
		$this->registerTask( 'unpublish', 'unpublish' );
		$this->registerTask( 'publish', 'publish' );
		$this->registerTask( 'orderup', 'orderup' );
		$this->registerTask( 'orderdown', 'orderdown' );
		$this->registerTask( 'saveorder', 'saveorder' );
		$this->registerTask( 'import', 'import' );
		$this->registerTask( 'apply', 'save' );
		$this->registerTask( 'save2new', 'save' );
	}


	function cancellist()
	{
		$link = 'index.php?option=com_fss&view=fsss';
		$this->setRedirect($link, $msg);
	}

	function edit()
	{
		JRequest::setVar( 'view', 'prod' );
		JRequest::setVar( 'layout', 'form'  );
		JRequest::setVar('hidemainmenu', 1);

		parent::display();
	}

	function save()
	{
		$model = $this->getModel('prod');

        $post = JRequest::get('post');
        $post['description'] = JRequest::getVar('description', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['extratext'] = JRequest::getVar('extratext', '', 'post', 'string', JREQUEST_ALLOWRAW);
        $post['translation'] = JRequest::getVar('translation', '', 'post', 'string', JREQUEST_ALLOWRAW);
 
		if ($model->store($post)) {
			$msg = JText::_("PRODUCT_SAVED");
		} else {
			$msg = JText::_("ERROR_SAVING_PRODUCT");
		}


		if ($this->task == "apply")
		{
			$link = "index.php?option=com_fss&controller=prod&task=edit&cid[]=" . $model->_id;
		} else if ($this->task == "save2new")
		{
			$link = 'index.php?option=com_fss&controller=prod&task=edit';
		} else {
			$link = 'index.php?option=com_fss&view=prods';
		}
		$this->setRedirect($link, $msg);
	}


	function remove()
	{
		$model = $this->getModel('prod');
		if(!$model->delete()) {
			$msg = JText::_("ERROR_ONE_OR_MORE_PRODUCTS_COULD_NOT_BE_DELETED");
		} else {
			$msg = JText::_("PRODUCTS_S_DELETED" );
		}

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}


	function cancel()
	{
		$msg = JText::_("OPERATION_CANCELLED");
		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function import_vm()
	{
		$log = $this->ImportVMart();
		echo "<h4>VirtueMart Import</h4>";
		echo "<pre>";
		echo $log;
		echo "</pre>";
		
		parent::display();
	}

	function import_hs()
	{
		//$log = $this->ImportVMart();
		$log = $this->ImportHikaShop();
		echo "<h4>Hika Shop Import</h4>";
		echo "<pre>";
		echo $log;
		echo "</pre>";
		
		parent::display();
	}

	function unpublish()
	{
		$model = $this->getModel('prod');
		if (!$model->unpublish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_UNPUBLISHING_A_PRODUCT");

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function publish()
	{
		$model = $this->getModel('prod');
		if (!$model->publish())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_PUBLISHING_A_PRODUCT");

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function orderup()
	{
		$model = $this->getModel('prod');
		if (!$model->changeorder(-1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function orderdown()
	{
		$model = $this->getModel('prod');
		if (!$model->changeorder(1))
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function saveorder()
	{
		$model = $this->getModel('prod');
		if (!$model->saveorder())
			$msg = JText::_("ERROR_THERE_HAS_BEEN_AN_ERROR_CHANGING_THE_ORDER");

		$this->setRedirect( 'index.php?option=com_fss&view=prods', $msg );
	}

	function ImportVMart()
	{
		$log = "";
		
		// check that the vm products table exists and has products in it
		$tablename = "#__virtuemart_products_en_gb";
		$pid = "virtuemart_product_id";
		$ver = 2;
		
		if (!FSS_Helper::TableExists($tablename))
		{
			$tablename = "#__vm_product";
			$pid = "product_id";
			$ver = 1;
			if (!FSS_Helper::TableExists($tablename))
			{
				$log = "No VirtueMart installation found\n";
				return $log;			
			}		
		}
		
		$db = JFactory::getDBO();
		
		$qry = "SELECT count(*) FROM $tablename";
		$db->setQuery($qry);
		
		$result = $db->loadResult();
		if ($result == 0)
		{
			$log = "No VirtueMart products found, aborting\n";
			return $log;			
		}
		
		$qry = "SELECT MAX(ordering)+1 as neworder FROM #__fss_prod";
		$db->setQuery($qry);
		$order = $db->loadResult();
		
		$qry = "SELECT * FROM $tablename WHERE product_parent_id = 0 ORDER BY product_name";
		if ($ver == 2)
		{
			$qry = "SELECT p.*, m.file_url FROM #__virtuemart_products_en_gb as p 

				LEFT JOIN #__virtuemart_product_medias as i 
				ON p.virtuemart_product_id = i.virtuemart_product_id

				LEFT JOIN  #__virtuemart_medias as m 
				ON i.virtuemart_media_id = m.virtuemart_media_id

				LEFT JOIN  #__virtuemart_products as x
				ON p.virtuemart_product_id = x.virtuemart_product_id
				
				WHERE product_parent_id = 0 ORDER BY product_name  ";
		}
		$db->setQuery($qry);
		$products = $db->loadObjectList();
		$log .= "Synchroizing " . count($products) . " VirtueMart products\n";
		
		$pids = array();
		// check for any removed products
		$qry = "SELECT * FROM #__fss_prod WHERE import_id > 0";
		$db->setQuery($qry);
		$existingproducts = $db->loadObjectList('import_id');
			
		if ($ver == 1)
		{
			$sourcepath = JPATH_SITE.DS.'components'.DS.'com_virtuemart'.DS.'shop_image'.DS.'product';
		} else if ($ver == 2)
		{
			$sourcepath = JPATH_SITE;
		}
		$destbase = JPATH_SITE.DS.'images'.DS.'fss'.DS.'products';
		
		// get existing file list
		$existingfiles = array();
			
		if (is_dir($destbase)) {
			if ($dh = opendir($destbase)) {
				while (($file = readdir($dh)) !== false) {
					if ($file == "." || $file == "..") continue;
					$existingfiles[$file] = $file;
				}
				closedir($dh);
			}
		}
		
		// products:
		/**
		 * product_name - title
		 * product_s_desc - description
		 * product_full_image - image file
		 **/
		
		foreach($products as $product)
		{
			// check for existing product
			$existing = null;
			if (array_key_exists($product->$pid, $existingproducts))
				$existing = $existingproducts[$product->$pid];
			
			// import image
			$pids[$product->$pid] = $product->$pid;

			// check if the image exists or not
			if ($ver == 1)
			{
				$imagesource = $product->product_full_image;
				$destfile = $imagesource;
			} else if ($ver == 2)
			{
				$imagesource = $product->file_url;
				$fin = pathinfo($imagesource);
				$destfile = $fin['basename'];
			}
			
			$order = 1;
	
			if ($existing)
			{
				if ($product->product_name != $existing->title || $product->product_s_desc != $existing->description || $destfile != $existing->image)
				{
					$log .= "Product '{$product->product_name}' already exists, updating\n";
					$qry = "UPDATE #__fss_prod SET title = '".FSSJ3Helper::getEscaped($db, $product->product_name)."', description = '".FSSJ3Helper::getEscaped($db, $product->product_s_desc)."', image = '".FSSJ3Helper::getEscaped($db, $destfile)."' WHERE id = '{$existing->id}'";
					$db->setQuery($qry);
					$db->query();
				}
			} else {
				$log .= "Adding product '{$product->product_name}'\n";
				$qry = "INSERT INTO #__fss_prod (import_id, title, description, image, published, ordering, inkb, insupport, intest) VALUES (";
				$qry .= $product->$pid . ", ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $product->product_name) . "', ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $product->product_s_desc) . "', ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $destfile) . "', ";
				$qry .= "1, $order, 1, 1, 1)";
				$order++;
			}
			
			$db->setQuery($qry);
			$db->Query();
			
			// not existing, so make a new resized image for this product
			if ($imagesource && !array_key_exists($destfile,$existingfiles))
			{
				$log .= "Copying and resizing image $imagesource for product '{$product->product_name}'\n";
				if (!$this->image_resize($sourcepath.DS.$imagesource,$destbase.DS.$destfile,64,64,0))
				{
					copy($sourcepath.DS.$imagesource,$destbase.DS.$destfile);
				}
			}
						
		}
		
		foreach($existingproducts as $product)
		{
			if (!array_key_exists($product->import_id, $pids))
			{
				$log .= "Removing product {$product->title}\n";
				$qry = "DELETE FROM #__fss_prod WHERE id = ".FSSJ3Helper::getEscaped($db, $product->id);
				$db->setQuery($qry);
				$db->Query();	
			}	
		}
		$log .= "Done\n";
		
		return $log;
	}

	function ImportHikaShop()
	{
		$log = "";
		
		// check that the vm products table exists and has products in it
		$tablename = "#__hikashop_product";
		$pid = "product_id";
		$ver = 2;
		
		if (!FSS_Helper::TableExists($tablename))
		{
			$log = "No HikaShop installation found\n";
			return $log;			
		}
		
		$db = JFactory::getDBO();
		
		$qry = "SELECT count(*) FROM $tablename";
		$db->setQuery($qry);
		
		$result = $db->loadResult();
		if ($result == 0)
		{
			$log = "No HikaShop products found, aborting\n";
			return $log;			
		}
		
		$qry = "SELECT MAX(ordering)+1 as neworder FROM #__fss_prod";
		$db->setQuery($qry);
		$order = $db->loadResult();
		
		$qry = "SELECT * FROM $tablename WHERE product_parent_id = 0 ORDER BY product_name";
		if ($ver == 2)
		{
			$qry = "SELECT p.*, m.file_path FROM $tablename as p 

				LEFT JOIN #__hikashop_file as m 
				ON p.product_id = m.file_ref_id

				WHERE product_parent_id = 0 
				
				GROUP BY p.product_id
				
				ORDER BY product_name  

				";
		}
		
		$db->setQuery($qry);
		$products = $db->loadObjectList();
	
		$log .= "Synchroizing " . count($products) . " Hika Shop products\n";
		
		$pids = array();
		// check for any removed products
		$qry = "SELECT * FROM #__fss_prod WHERE import_id > 0";
		$db->setQuery($qry);
		$existingproducts = $db->loadObjectList('import_id');
			
		$sourcepath = JPATH_SITE.DS.'media'.DS.'com_hikashop'.DS.'upload';
		$destbase = JPATH_SITE.DS.'images'.DS.'fss'.DS.'products';
		
		// get existing file list
		$existingfiles = array();
			
		if (is_dir($destbase)) {
			if ($dh = opendir($destbase)) {
				while (($file = readdir($dh)) !== false) {
					if ($file == "." || $file == "..") continue;
					$existingfiles[$file] = $file;
				}
				closedir($dh);
			}
		}
		
		// products:
		/**
		 * product_name - title
		 * product_s_desc - description
		 * product_full_image - image file
		 **/
		
		foreach($products as $product)
		{
			// check for existing product
			$existing = null;
			if (array_key_exists($product->$pid, $existingproducts))
				$existing = $existingproducts[$product->$pid];
			
			// import image
			$pids[$product->$pid] = $product->$pid;

			// check if the image exists or not
			$imagesource = $product->file_path;
			$fin = pathinfo($imagesource);
			$destfile = $fin['basename'];
			
			$order = 1;
			
			if ($existing)
			{
				if ($product->product_name != $existing->title || $product->product_description != $existing->description || $destfile != $existing->image)
				{
					$log .= "Product '{$product->product_name}' already exists, updating\n";
					$qry = "UPDATE #__fss_prod SET title = '".FSSJ3Helper::getEscaped($db, $product->product_name)."', description = '".FSSJ3Helper::getEscaped($db, $product->product_description)."', image = '".FSSJ3Helper::getEscaped($db, $destfile)."' WHERE id = '{$existing->id}'";
					$db->setQuery($qry);
					$db->query();
				}
			} else {
				$log .= "Adding product '{$product->product_name}'\n";
				$qry = "INSERT INTO #__fss_prod (import_id, title, description, image, published, ordering, inkb, insupport, intest, access) VALUES (";
				$qry .= $product->$pid . ", ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $product->product_name) . "', ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $product->product_description) . "', ";
				$qry .= "'" . FSSJ3Helper::getEscaped($db, $destfile) . "', ";
				$qry .= "1, $order, 1, 1, 1, 1)";
				$order++;
			}
			
			$db->setQuery($qry);
			$db->Query();
			
			// not existing, so make a new resized image for this product
			if ($imagesource && !array_key_exists($destfile,$existingfiles))
			{
				$log .= "Copying and resizing image $imagesource for product '{$product->product_name}'\n";
				if (!$this->image_resize($sourcepath.DS.$imagesource,$destbase.DS.$destfile,64,64,0))
				{
					copy($sourcepath.DS.$imagesource,$destbase.DS.$destfile);
				}
			}
						
		}
		
		foreach($existingproducts as $product)
		{
			if (!array_key_exists($product->import_id, $pids))
			{
				$log .= "Removing product {$product->title}\n";
				$qry = "DELETE FROM #__fss_prod WHERE id = ".FSSJ3Helper::getEscaped($db, $product->id);
				$db->setQuery($qry);
				$db->Query();	
			}	
		}
		$log .= "Done\n";
		
		return $log;
	}

	function image_resize($src, $dst, $width, $height, $crop=0)
	{
		echo "Src : $src<br>";
		echo "Dst : $dst<br>";
		if(!list($w, $h) = getimagesize($src)) 
			return false;

		$type = strtolower(substr(strrchr($src,"."),1));
		if($type == 'jpeg') $type = 'jpg';
		switch($type){
			case 'bmp': $img = imagecreatefromwbmp($src); break;
			case 'gif': $img = imagecreatefromgif($src); break;
			case 'jpg': $img = imagecreatefromjpeg($src); break;
			case 'png': $img = imagecreatefrompng($src); break;
			default : return false;
		}

		// resize
		if($crop){
			if($w < $width or $h < $height) 
				return false;
			$ratio = max($width/$w, $height/$h);
			$h = $height / $ratio;
			$x = ($w - $width / $ratio) / 2;
			$w = $width / $ratio;
		} else{
			if($w < $width and $h < $height) 
				return false;
			$ratio = min($width/$w, $height/$h);
			$width = $w * $ratio;
			$height = $h * $ratio;
			$x = 0;
		}

		$new = imagecreatetruecolor($width, $height);

		// preserve transparency
		if($type == "gif" or $type == "png"){
			imagecolortransparent($new, imagecolorallocatealpha($new, 0, 0, 0, 127));
			imagealphablending($new, false);
			imagesavealpha($new, true);
		}

		imagecopyresampled($new, $img, 0, 0, $x, 0, $width, $height, $w, $h);

		switch($type){
			case 'bmp': imagewbmp($new, $dst); break;
			case 'gif': imagegif($new, $dst); break;
			case 'jpg': imagejpeg($new, $dst); break;
			case 'png': imagepng($new, $dst); break;
		}
		return true;
	}
}



