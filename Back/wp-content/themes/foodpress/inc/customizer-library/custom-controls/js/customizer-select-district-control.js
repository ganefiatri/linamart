/**
 * Script run inside a Customizer control sidebar
 */
(function($) {
    wp.customize.bind('ready', function() {
        select2();
    });

    var select2 = function() {
        $('.customizer-select2').select2();
    };

})(jQuery);
