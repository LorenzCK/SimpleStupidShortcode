jQuery(function($) {
    var picker_frame = null;

    function sss_insert_image_button_click(event) {
        event.preventDefault();

        if(picker_frame == null) {
            picker_frame = wp.media({
                title: 'Insert an image',
                library: {type: 'image'},
                multiple: false,
                button: {
                    text: 'Insert'
                }
            });

            picker_frame.on('select', function() {
                var attachment = picker_frame.state().get('selection').first().toJSON();
                console.log(attachment);

                wp.media.editor.insert('[image id="' + attachment.id + '"]');
            });
        }

        picker_frame.open();
    }

    $(document).ready(function(){
        $('#sss-insert-image-button').click(sss_insert_image_button_click);
    });
});
