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