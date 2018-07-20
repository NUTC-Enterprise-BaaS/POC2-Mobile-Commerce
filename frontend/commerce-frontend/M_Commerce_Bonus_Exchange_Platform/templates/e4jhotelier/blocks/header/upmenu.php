<?php if(($this->countModules('upmenu-left') != 0)  or ($this->countModules('upmenu-right') != 0)) { ?>
	<div id="tbar-upmenu">
		<div class="upmenu-content">
			<div id="tbar-left">
				<jdoc:include type="modules" name="upmenu-left" style="e4jstyle" />
			</div>
			<div id="tbar-right">
				<div class="l-inline">
					<jdoc:include type="modules" name="upmenu-right" style="e4jstyle" />
					<jdoc:include type="modules" name="upmenu-right-alt" style="e4jmainm" />
				</div>
			</div>
		</div>
	</div>
<?php } ?>