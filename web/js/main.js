jQuery(document).ready(function($) {

	/* ======= Scrollspy ======= */
	$('body').scrollspy({ target: '#header', offset: 400});

	/* ======= ScrollTo ======= */
	$('a.scrollto').on('click', function(e) {
		//store hash
		var target = this.hash;
		e.preventDefault();
		$('body').scrollTo(target, 800, {offset: -70, 'axis':'y', easing:'easeOutQuad'});
		//Collapse mobile menu after clicking
		if ($('.navbar-collapse').hasClass('in')){
			$('.navbar-collapse').removeClass('in').addClass('collapse');
		}
	});
});