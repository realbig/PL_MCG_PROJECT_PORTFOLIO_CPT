( function( $ ) {

	$( document ).ready( function() {

		if ( $( '.post-type-archive-mcg-project' ).length < 0 ) return false;

		$( '.project-category-select' ).on( 'change', function() {

			var location = window.location.href.replace( /page.*/i, '' );

			location = location.substr( 0, location.lastIndexOf( '/' ) ) + '/';

			var category = $( this ).val();

			location = location + '?project_category=' + category;

			window.location.href = location;

		} );

	} );

	$( window ).on( 'select2loaded', function() {
		$( '.project-category-select' ).select2( {
		} );
	} );

} )( jQuery );