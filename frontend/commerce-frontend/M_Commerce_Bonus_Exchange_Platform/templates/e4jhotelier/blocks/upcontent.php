<?php if($this->countModules('upcontent')) { ?>
<section id="upcontent" class="grid-block">
<div class="module-fullwidth-cont">
    <?php if($this->countModules('upcontent') == 6) { ?>
    <div class="grid-block width16">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('upcontent') == 5) { ?>
    <div class="grid-block width20">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
  	<?php if($this->countModules('upcontent') == 4) { ?>
    <div class="grid-block width25">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('upcontent') == 3) { ?>
    <div class="grid-block width33">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('upcontent') == 2) { ?>
    <div class="grid-block width50">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
    <?php if($this->countModules('upcontent') == 1) { ?>
    <div class="grid-block width100">
      <jdoc:include type="modules" name="upcontent" style="gridmodule" />
    </div>
    <?php } ?>
</div>
</section>
<?php } ?>