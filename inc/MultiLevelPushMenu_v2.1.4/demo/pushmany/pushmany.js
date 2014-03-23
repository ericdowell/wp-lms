$(document).ready(function(){
	// HTML markup implementation, overlap mode, push 3 DOM elements
	$( '#menu' ).multilevelpushmenu({
		containersToPush: [$( '#pushobj' ), $( '#pushthisobjalso' ), $( '#pushthisobjtoo' )]
	});
	$('body').click(function() { $('#menu').multilevelpushmenu('collapse'); });
});