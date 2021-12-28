jQuery(document).ready(function ($) {
    let file_frame;

    $('.rajaongkirtype').on('change', function(){
        let val = $(this).val();

        if( val == 'starter' ){
            $('.basic').prop('checked', false);
            $('.basic').prop('disabled', true);

            $('.pro').prop('checked', false);
            $('.pro').prop('disabled', true);
        }else if( val == 'basic' ){
            $('.basic').prop('disabled', false);

            $('.pro').prop('checked', false);
            $('.pro').prop('disabled', true);
        }else{
            $('.basic').prop('disabled', false);

            $('.pro').prop('disabled', false);
        }
    })
});

var metaImageFrame;
function foodpressMediaOpen(ini){

    let selector = jQuery(ini).attr('selector'),
    preview = jQuery(ini).attr('preview');

    // Sets up the media library frame
    metaImageFrame = wp.media.frames.metaImageFrame = wp.media({
        title: jQuery(ini).attr('data-uploader-title'),
        button: {
            text:  jQuery(ini).attr('data-uploader-button-text'),
        },
    });

    // Runs when an image is selected.
    metaImageFrame.on('select', function() {

        // Grabs the attachment selection and creates a JSON representation of the model.
        var media_attachment = metaImageFrame.state().get('selection').first().toJSON();

        // Sends the attachment URL to our custom image input field.
        jQuery(selector).val(media_attachment.url);
        jQuery(preview).attr('src', media_attachment.url);

    });

    // Opens the media library frame.
    metaImageFrame.open();
}

function customerFollowUp(nomor){

    let wa = 'https://web.whatsapp.com/send';

	if (/Android|webOS|iPhone|iPad|iPod|BlackBerry|IEMobile|Opera Mini/i.test(navigator.userAgent)) {
        wa = 'whatsapp://send';
	}

    let url = wa + '?phone=' + nomor;

    let w = 960,h = 540,left = Number((screen.width / 2) - (w / 2)),top = Number((screen.height / 2) - (h / 2)),popupWindow = window.open(url, '', 'toolbar=no, location=no, directories=no, status=no, menubar=no, scrollbars=yes, resizable=1, copyhistory=no, width=' + w + ', height=' + h + ', top=' + top + ', left=' + left);

    popupWindow.focus();
    return false;
}
