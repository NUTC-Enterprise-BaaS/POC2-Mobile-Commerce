<div class="main-box grid-block">
	<section id="main" class="grid-box">
		<div class="main-body">
			<jdoc:include type="modules" name="breadcrumb" style="e4jstyle" />
			<div class="errore"><jdoc:include type="message" /></div>
			<jdoc:include type="component" />
		</div>
	</section>
	<?php if($this->countModules('sidebar-right')) { ?>  
		<aside id="sidebar-right" class="sidebar width100 grid-box">
	    	<jdoc:include type="modules" name="sidebar-right" style="gridmodule" />
		</aside>
	<?php } ?>
	<?php if($this->countModules('sidebar-left')) { ?>  
		<aside id="sidebar-left" class="sidebar width100 grid-box">
	    	<jdoc:include type="modules" name="sidebar-left" style="gridmodule" />
		</aside>
	<?php } ?>	
</div>