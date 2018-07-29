<?php
/**
 * Copyright (c) Extensionsforjoomla.com - E4J - Templates for Joomla
 * 
 * You should have received a copy of the License
 * along with this program.  If not, see <http://www.extensionsforjoomla.com/>.
 * 
 * For any bug, error please contact us
 * We will try to fix it.
 * 
 * Extensionsforjoomla.com - All Rights Reserved
 * 
 */

defined( '_JEXEC' ) or die( 'Restricted access' );
?>

<div id="main-container">
  <div id="container">
    <?php if($this->countModules('infodemo')) { ?>
       <div id="tmpl-infodemo">
          <jdoc:include type="modules" name="infodemo" style="xhtml" />
       </div>
       <?php } ?>
		<?php include('./templates/'.$this->template.'/blocks/header.php'); ?>
    <?php if($this->countModules('upcontent')) { ?>    
      <?php include('./templates/'.$this->template.'/blocks/upcontent.php'); ?>    
    <?php } ?>          
    <main>
      <div id="cnt-container">
        <?php if($this->countModules('module-box1')) { ?>    
          <?php include('./templates/'.$this->template.'/blocks/subox-one.php'); ?>    
        <?php } ?> 
          <?php include('./templates/'.$this->template.'/blocks/content.php'); ?>
          <?php if($this->countModules('subcontent')) { ?>    
          <?php include('./templates/'.$this->template.'/blocks/subcontent.php'); ?>    
        <?php } ?>              
      </div>
   </main>
   <?php if($this->countModules('module-box2')) { ?>    
      <?php include('./templates/'.$this->template.'/blocks/subox-two.php'); ?>    
    <?php } ?>    
    <?php if($this->countModules('fullbox')) { ?>    
       <?php include('./templates/'.$this->template.'/blocks/fullbox.php'); ?>    
    <?php } ?>
    <?php if($this->countModules('footer')) { ?>    
       <?php include('./templates/'.$this->template.'/blocks/footer.php'); ?>    
    <?php } ?>
      <?php if($this->countModules('subfooter')) { ?>    
        <?php include('./templates/'.$this->template.'/blocks/subfooter.php'); ?>    
      <?php } ?>
  </div>
</div>
<div id="nav-menu-devices" class="nav-devices-content">
<?php include('./templates/'.$this->template.'/blocks/header/mainmenu-device.php'); ?>
</div>