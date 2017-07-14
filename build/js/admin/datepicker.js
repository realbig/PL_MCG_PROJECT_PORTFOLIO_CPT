( function( $ ) {
    'use strict';

    $( function () {

        var $datepickers = $( '.mcg-field-datepicker' );

        if ( $datepickers.length ) {

            datepickers_init( $datepickers );
			
        }

        /**
         * Initializes datepickers.
         *
         * @since {{VERSION}}
         *
         * @param $datepickers
         */
        function datepickers_init( $datepickers ) {

            $datepickers.each( function () {

                var name = $( this ).find( 'input[type="hidden"]' ).attr( 'name' ),
                    option_functions = ['beforeShow', 'beforeShowDay', 'calculateWeek', 'onChangeMonthYear', 'onClose', 'onSelect'],
                    options = {};

                // Function support
                $.each( options, function (name, value ) {
                    if ( option_functions.indexOf( name ) !== -1 ) {
                        options[ name ] = window[ value ];
                    }
                } );

                options['altField'] = '[name="' + name + '"]';
                options['altFormat'] = 'yymmdd';

                $( this ).find( '.mcg-field-datepicker-preview' ).datepicker( options );
				
            } );
			
        }
		
    } );
	
} )( jQuery );