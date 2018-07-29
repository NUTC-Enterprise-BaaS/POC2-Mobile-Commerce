<div id="subfooter">
  <div id="subfoot-cont">
  	<?php if($this->countModules('subfooter') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subfooter') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subfooter') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subfooter') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subfooter') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subfooter') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="subfooter" style="gridmodule" />
    </div>
    <?php } ?>
</div>