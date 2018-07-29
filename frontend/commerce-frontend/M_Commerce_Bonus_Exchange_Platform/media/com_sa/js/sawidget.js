if((typeof Ad_widget != "undefined" ) && (typeof Ad_widget_sitebase != "undefined" ) ){
var scripts = document.getElementsByTagName('script');
var myScript = scripts[ scripts.length - 1 ];
var queryString = myScript.src.replace(/^[^\?]+\??/,'');
var params = sa_parseQuery(queryString);


if(!params.ifwidth){
	params.ifwidth='100%';
}
if(!params.ifheight){
	params.ifheight='100%';
}
if(!params.ifseamless){
	params.ifseamless='seamless';
}
if(typeof Ad_targeting == "undefined" ){
	Ad_targeting ={};
}
Ad_targeting.ads_params = Ad_widget;
Ad_targeting.context_params= {};
Ad_targeting.context_params['keys'] = sa_client_document_keywords();
var paramsObj_json = JSON.stringify(Ad_targeting);

var res = encodeURIComponent(paramsObj_json);
var iframeobj  = '';
if(document.getElementById(Ad_widget.ad_unit)){
	/*iframeobj += '<iframe id="idIframe_'+Ad_widget.ad_unit+'" src="'+Ad_widget_sitebase+'index.php?option=com_socialads&view=ads&template=system&adData='+res+'&tmpl=component"';*/
	iframeobj += '<iframe id="idIframe_'+Ad_widget.ad_unit+'" src="'+Ad_widget_sitebase+'index.php?option=com_sa&view=remotecontrol&template=system&adData='+res+'&tmpl=component"';
	iframeobj += ' name="SA_widget" frameborder="0"';
	iframeobj += ' width="'+params.ifwidth+'" height="'+params.ifheight+'" ';
	if(!params.ifseamless){
		iframeobj += ' seamless="seamless" ';
	}

	iframeobj += ' ></iframe>';
	document.getElementById(Ad_widget.ad_unit).innerHTML = iframeobj;
}

}


function sa_parseQuery ( query ) {
	var Params = new Object ();
	if ( ! query ) return Params;
	var Pairs = query.split(/[;&]/);
	for ( var i = 0; i < Pairs.length; i++ ) {
		var KeyVal = Pairs[i].split('=');
		if ( ! KeyVal || KeyVal.length != 2 ) continue;
		var key = unescape( KeyVal[0] );
		var val = unescape( KeyVal[1] );
		val = val.replace(/\+/g, ' ');
		Params[key] = val;
	}
	return Params;
}
function sa_client_document_keywords(){
	var keywords = '';
	var metas = document.getElementsByTagName('meta');
	if (metas) {
		for (var x=0,y=metas.length; x<y; x++) {
			if (metas[x].name.toLowerCase() == "keywords") {
				keywords += metas[x].content;
			}
		}
	}
	return keywords != '' ? keywords : false;
}
