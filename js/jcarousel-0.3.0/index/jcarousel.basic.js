(function($) {
    $(function() {
		/* CAROUSEL SLIDESHOW INDEX, MARCAS Y BENBETESH */
        $('.jcarousel').jcarousel({
			wrap: 'last'
		});

        $('.jcarousel-control-prev')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });

        $('.jcarousel-pagination')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .jcarouselPagination();
		$('.jcarousel').jcarouselAutoscroll({
			interval: 4500,
			target: '+=1',
			autostart: true
		});
		/* CAROUSEL LOGOS INDEX */
		$('.jcarousel2').jcarousel({
			wrap: 'last'
		});
        $('.jcarousel-control-prev2')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '-=1'
            });

        $('.jcarousel-control-next2')
            .on('jcarouselcontrol:active', function() {
                $(this).removeClass('inactive');
            })
            .on('jcarouselcontrol:inactive', function() {
                $(this).addClass('inactive');
            })
            .jcarouselControl({
                target: '+=1'
            });
		$('.jcarousel2').jcarouselAutoscroll({
			interval: 4500,
			target: '+=1',
			autostart: true
		});
		/* CAROUSEL MARCAS INDEX */
		$('.jcarousel3').jcarousel({
			wrap: 'last'
		});

        $('.jcarousel-pagination3')
            .on('jcarouselpagination:active', 'a', function() {
                $(this).addClass('active');
            })
            .on('jcarouselpagination:inactive', 'a', function() {
                $(this).removeClass('active');
            })
            .jcarouselPagination();
		$('.jcarousel3').jcarouselAutoscroll({
			interval: 4500,
			target: '+=3',
			autostart: true
		});
    });
})(jQuery);
