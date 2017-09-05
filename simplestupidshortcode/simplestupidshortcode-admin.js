jQuery(function($) {
    var picker_frame = null;

    function sss_insert_image_button_click(event) {
        event.preventDefault();

        if(picker_frame == null) {
            picker_frame = wp.media({
                title: ssst.title_insert,
                library: {type: 'image'},
                multiple: false,
                button: {
                    text: ssst.label_insert
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
