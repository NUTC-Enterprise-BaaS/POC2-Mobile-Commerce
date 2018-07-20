<?php if($this->countModules('subcontent')) { ?>
<section id="subcontent" class="grid-block">
    <?php if($this->countModules('subcontent') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subcontent') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
  	<?php if($this->countModules('subcontent') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subcontent') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subcontent') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('subcontent') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="subcontent" style="gridmodule" />
    </div>
    <?php } ?>
</section>
<?php } ?>