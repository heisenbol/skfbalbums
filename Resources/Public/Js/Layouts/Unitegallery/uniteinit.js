if (typeof jQuery == 'undefined') {

	function nonjqueryReady(callback){
	    // in case the document is already rendered
	    if (document.readyState!='loading') callback();
	    // modern browsers
	    else if (document.addEventListener) document.addEventListener('DOMContentLoaded', callback);
	    // IE <= 8
	    else document.attachEvent('onreadystatechange', function(){
	        if (document.readyState=='complete') callback();
	    });
	}

	nonjqueryReady(function(){
		if (typeof skfbalbumsUnite !== 'undefined') {
			var skfbalbumCounter;
			for (skfbalbumCounter=0; skfbalbumCounter<skfbalbumsUnite.length; skfbalbumCounter++) {
				var containerId = skfbalbumsUnite[skfbalbumCounter].replace('#','');
				document.getElementById(containerId).style.display = 'block';
				document.getElementById(containerId).innerHTML = '<div style="color:red">jquery is not available on page. Unite Gallery needs jquery to work</div>'+document.getElementById(containerId).innerHTML;
			}
		}
		else {

		}
	});

}
else {
	console.log('aaassssa');
	jQuery(document).ready(function(){ 
		if (typeof skfbalbumsUnite !== 'undefined') {
			var skfbalbumCounter;
			for (skfbalbumCounter=0; skfbalbumCounter<skfbalbumsUnite.length; skfbalbumCounter++) {
				jQuery(skfbalbumsUnite[skfbalbumCounter]).unitegallery(
					skfbalbumsUniteOptions[skfbalbumCounter]
					); 
			}
		}
	}); 
}