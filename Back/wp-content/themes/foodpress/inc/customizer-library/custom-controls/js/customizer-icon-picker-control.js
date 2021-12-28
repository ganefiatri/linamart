/**
 * Script run inside a Customizer control sidebar
 */
(function($) {
    wp.customize.bind('ready', function() {
        iconPicker();
    });

    var iconPicker = function() {
		console.log('psuhshshs');
        $('.customizer-select-2').select2({
			templateResult: function(state){
				let $state = $('<i class="lni '+state.text+'"></i>&nbsp;&nbsp;<span>'+state.text+'</span>');
				return $state;
			}
		});
    };

})(jQuery);
