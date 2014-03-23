jQuery(document).ready(function ($) {
    $('#menu-jquery').multilevelpushmenu({
        containersToPush: [$('.container')],
        menuWidth: '300px',
        menuHeight: '100%',
        collapsed: true,
        onCollapseMenuEnd: function() {
            $( '.container' ).removeAttr('style');
        },
        onItemClick: function() {
        // First argument is original event object
        var event = arguments[0],
            // Second argument is menu level object containing clicked item (<div> element)
            $menuLevelHolder = arguments[1],
            // Third argument is clicked item (<li> element)
            $item = arguments[2],
            // Fourth argument is instance settings/options object
            options = arguments[3];

        // You can do some cool stuff here before
        // redirecting to href location
        // like logging the event or even
        // adding some parameters to href, etc...

        // Anchor href
        var itemHref = $item.find( 'a:first' ).attr( 'href' );
        // Redirecting the page
        location.href = itemHref;
        }
    });
    $('#menu-jquery').multilevelpushmenu('option', 'menuHeight', $(document).height());
    $('#menu-jquery').multilevelpushmenu('redraw');
    $('.container').click(function() { $('#menu-jquery').multilevelpushmenu('collapse'); });
});

// jQuery(window).resize(function ($) {
//     $('#menu-jquery').multilevelpushmenu('option', 'menuHeight', $(document).height());
//     $('#menu-jquery').multilevelpushmenu('redraw');
// });

