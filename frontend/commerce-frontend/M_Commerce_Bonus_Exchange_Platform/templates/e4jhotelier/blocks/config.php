<?php 
//**** Grid Modules ***/
$document = JFactory::getDocument();
$tot_content = 100;
$sidebar_1cl = 27;
$sidebar_2cl = 20;
$css_string = "";
$sidebar = 0;
$numb_sidebar = 0;
if($this->countModules('sidebar-left') xor $this->countModules('sidebar-right')) {	
	$sidebar = $sidebar_1cl;
	$numb_sidebar = 1;	
	if($this->countModules('sidebar-left')) {
		$css_string .="#main {left:".$sidebar."%;}";
	}
} elseif(($this->countModules('sidebar-left'))  && ($this->countModules('sidebar-right'))) {
	$sidebar = $sidebar_2cl;
	$numb_sidebar = 2;
	$css_string .="#sidebar-right, #main {left:".$sidebar."%;}";
}
$mainbody = $tot_content - ($sidebar * $numb_sidebar);
$sidebar_left =  $tot_content - $sidebar;
$css_string .="#main {width:".$mainbody."%;} 
.sidebar {width:".$sidebar."%} 
#sidebar-left {left:-".$sidebar_left."%}";

//**** XML -> TEXT HEADING SIZE ***/
$h_textsize = $this->params->get('headertxt');
$css_string .=".moduletable > h3 {font-size:".$h_textsize.";}";
$document->addStyleDeclaration($css_string);

?>