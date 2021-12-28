/**
 * Script run inside a Customizer control sidebar
 */
(function($) {
    wp.customize.bind('ready', function() {

        let customize = this; // Customize object alias.

		//get the toggle controls
		let toggleControls = $('.customize-toogle-label').parent();

		let toggleControlIds = [];

		//Segment in the id of the control that is added by wordpress, but not needed for our purpose
		let idSegment = "customize-control-";

		//fill the id array
		for (let control of toggleControls){
			//remove the segment from the control id
			let controlId = control.id.substring(idSegment.length, control.id.length);
			toggleControlIds.push(controlId);
		}

		$.each( toggleControlIds, function( index, control_name ) {
			customize( control_name, function( value ) {
				let controlTitle = customize.control( control_name ).container.find( '.customize-control-title' ); // Get control  title.
				// 1. On loading.
				controlTitle.toggleClass('disabled-control-title', !value.get() );
				// 2. Binding to value change.
				value.bind( function( to ) {
					controlTitle.toggleClass( 'disabled-control-title', !value.get() );
				} );
			} );
		} );

    });

})(jQuery);
