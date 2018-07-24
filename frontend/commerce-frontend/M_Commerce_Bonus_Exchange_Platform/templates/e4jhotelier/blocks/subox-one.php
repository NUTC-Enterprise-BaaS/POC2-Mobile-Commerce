<?php if($this->countModules('module-box1')) { ?>
<section id="module-box1" class="grid-block">
    <?php if($this->countModules('module-box1') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box1') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
  	<?php if($this->countModules('module-box1') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box1') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box1') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('module-box1') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="module-box1" style="gridmodule" />
    </div>
    <?php } ?>
</section>
<?php } ?>