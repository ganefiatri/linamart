jQuery( document ).ready( function($) {

	/* === Sortable Multi-CheckBoxes === */

	/* Make it sortable. */
	$( 'ul.sortable-list' ).sortable({
		handle: '.sortable-handle',
		axis: 'y',
		update: function( e, ui ){
			$('input.sortable-item').trigger( 'change' );
		}
	});

	/* On changing the value. */
	$( "input.sortable-item" ).on( 'change', function() {

		/* Get the value, and convert to string. */
		this_checkboxes_values = $( this ).parents( 'ul.sortable-list' ).find( 'input.sortable-item' ).map( function() {
			var active = '0';
			if( $(this).prop("checked") ){
				var active = '1';
			}
			return this.name + ':' + active;
		}).get().join( ',' );

		/* Add the value to hidden input. */
		$( this ).parents( 'ul.sortable-list' ).find( 'input.sortable' ).val( this_checkboxes_values ).trigger( 'change' );

	});
});
