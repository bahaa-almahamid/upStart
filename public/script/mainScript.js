$(window).scroll(function() {
    if ($(window).scrollTop() + $(window).height() == $(document).height()) {
        $('footer').slideDown(300);
    } else {
        $('footer').slideUp(300);
    }
});