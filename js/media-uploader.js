	console.log("js ok");
	(function ($) {
		$(document).ready(function(){

			var custom_uploader;
 
		    $('#upload_image_button').click(function(e) {
		 
		        e.preventDefault();
		 
		        //If the uploader object has already been created, reopen the dialog
		        if (custom_uploader) {
		            custom_uploader.open();
		            return;
		        }
		 
		        //Extend the wp.media object
		        custom_uploader = wp.media.frames.file_frame = wp.media({
		            title: 'Bookr Item',
		            button: {
		                text: 'Selecciona una imagen'
		            },
		            multiple: false
		        });
		 
		        //When a file is selected, grab the URL and set it as the text field's value
		        custom_uploader.on('select', function() {
		            attachment = custom_uploader.state().get('selection').first().toJSON();
		            $('#upload_image').val(attachment.url);

		            //también envío el nombre del archivo subido al campo thumbnail.
					$('#libro_thumb').val(attachment.url);		            
		        });
		 
		        //Open the uploader dialog
		        custom_uploader.open();
		 
		    });


		});
	}(jQuery));