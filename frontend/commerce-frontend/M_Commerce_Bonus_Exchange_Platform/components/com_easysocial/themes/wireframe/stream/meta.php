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
?>
<span class="es-stream-info-meta">
    &#8207;
    &mdash;
    <?php echo $this->loadTemplate( 'site/stream/meta.with' , array( 'stream' => $stream ) ); ?>
    <?php echo $this->loadTemplate( 'site/stream/meta.location' , array( 'stream' => $stream ) ); ?>
    <?php echo $this->loadTemplate( 'site/stream/meta.mood' , array( 'stream' => $stream ) ); ?>
</span>
