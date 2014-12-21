// Reset possibly collapsed header
$(window).resize(function(){
    if ($(window).width() > 500) {
        $('#navbar-collapse').show();
    }
});

// Scroll to top
var bttElement = '#back-to-top';
var backToTop = function() {
    var scrollTop = $(document).scrollTop();
    var visible = $(bttElement).is(':visible');

    if (scrollTop > 50 && !visible) {
        $(bttElement).fadeIn(500);
    } else if (scrollTop <= 50 && visible) {
        $(bttElement).fadeOut(500);
    }
};

backToTop();
$(window).scroll(backToTop);