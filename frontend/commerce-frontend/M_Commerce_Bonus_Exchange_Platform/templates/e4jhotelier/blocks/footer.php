<footer>
  <div id="foot-cont">
  	<?php if($this->countModules('footer') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('footer') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('footer') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('footer') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('footer') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('footer') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="footer" style="gridmodule" />
    </div>
    <?php } ?>
</footer>
