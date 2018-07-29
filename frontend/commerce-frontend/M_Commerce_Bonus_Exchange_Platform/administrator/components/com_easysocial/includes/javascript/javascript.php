<?php
/**
* @package		EasySocial
* @copyright	Copyright (C) 2010 - 2014 Stack Ideas Sdn Bhd. All rights reserved.
* @license		GNU/GPL, see LICENSE.php
* EasySocial is free software. This version may have been modified pursuant
* to the GNU General Public License, and as distributed it includes or
* is derivative of works licensed under the GNU General Public License or
* other free or open source software licenses.
* See COPYRIGHT.php for copyright notices and details.
*/
defined( '_JEXEC' ) or die( 'Unauthorized Access' );

class SocialJavascript
{
	public function createChain();
	{
		require_once( dirname( __FILE__ ) . '/chain.php' );

		$chain = new SocialJavascriptChain();

		return $chain;
	}

	public function getChain($chain, $stringify=false)
	{
		if ($chain instanceof SocialJavascriptChain) {

			if ($stringify) {
				return FD::('JSON')->encode($chain->__chain);
			}

			return $chain->__chain;
		}

		return false;
	}
}

