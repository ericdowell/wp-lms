$(document).ready(function () {
    $('#menu').multilevelpushmenu({
        containersToPush: [$('.wrap')],
        menuWidth: '300px',
        menuHeight: '100%',
        collapsed: true,
        onCollapseMenuEnd: function() {
            $( '.wrap' ).removeAttr('style');
        }
    });
    $('#menu').multilevelpushmenu('option', 'menuHeight', $(document).height());
    $('#menu').multilevelpushmenu('redraw');
    $('.wrap').click(function() { $('#menu').multilevelpushmenu('collapse'); });
});

$(window).resize(function () {
    $('#menu').multilevelpushmenu('option', 'menuHeight', $(document).height());
    $('#menu').multilevelpushmenu('redraw');
});

