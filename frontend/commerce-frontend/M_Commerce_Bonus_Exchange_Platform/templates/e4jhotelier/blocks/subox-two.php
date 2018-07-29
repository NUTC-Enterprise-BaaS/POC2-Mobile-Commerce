<?php if($this->countModules('module-box2')) { ?>
<section id="module-box2" class="grid-block">
    <div class="module-fullwidth-cont">
    <?php if($this->countModules('module-box2') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box2') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box2') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box2') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box2') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box2') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="module-box2" style="gridmodule" />
    </div>
   <?php } ?>
    </div> 
</section>
<?php } ?>