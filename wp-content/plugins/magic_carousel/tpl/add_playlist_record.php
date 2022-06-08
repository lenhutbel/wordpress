<script>
jQuery(document).ready(function() {

	jQuery('#upload_img_button_magic_carousel').click(function(event) {
		var file_frame;
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			//alert (attachment.url);
			jQuery('#img').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});


	jQuery('#upload_thumb_button_magic_carousel').click(function(event) {
		var file_frame;
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			//alert (attachment.url);
			jQuery('#data_bottom_thumb').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});



	jQuery('#upload_largeimg_button_magic_carousel').click(function(event) {
		var file_frame;
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			//alert (attachment.url);
			jQuery('#data_large_image').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});



	jQuery('#upload_video_button_magic_carousel').click(function(event) {
		var file_frame;
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			//alert (attachment.url);
			jQuery('#data_video_selfhosted').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});


	jQuery('#upload_audio_button_magic_carousel').click(function(event) {
		var file_frame;
		event.preventDefault();
		// If the media frame already exists, reopen it.
		if ( file_frame ) {
			file_frame.open();
			return;
		}
		// Create the media frame.
		file_frame = wp.media.frames.file_frame = wp.media({
			title: jQuery( this ).data( 'uploader_title' ),
			button: {
			text: jQuery( this ).data( 'uploader_button_text' ),
			},
			multiple: false // Set to true to allow multiple files to be selected
		});
		// When an image is selected, run a callback.
		file_frame.on( 'select', function() {
			// We set multiple to false so only get one image from the uploader
			attachment = file_frame.state().get('selection').first().toJSON();
			// Do something with attachment.id and/or attachment.url here
			//alert (attachment.url);
			jQuery('#data_audio').val(attachment.url);
		});
		// Finally, open the modal
		file_frame.open();
	});

/*jQuery('#upload_img_button_magic_carousel').click(function() {
 //formfield = jQuery('#img').attr('name');
 formfield = 'img';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

jQuery('#upload_thumb_button_magic_carousel').click(function() {
 formfield = 'data_bottom_thumb';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

jQuery('#upload_largeimg_button_magic_carousel').click(function() {
 formfield = 'data_large_image';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

jQuery('#upload_video_button_magic_carousel').click(function() {
 formfield = 'data_video_selfhosted';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});

jQuery('#upload_audio_button_magic_carousel').click(function() {
 formfield = 'data_audio';
 tb_show('', 'media-upload.php?type=image&amp;TB_iframe=true');
 return false;
});



window.send_to_editor = function(html) {

 imgurl = jQuery(html).attr('href'); // We do this to get Links like PDF's etc
 jQuery('#'+formfield).val(imgurl);
 tb_remove();


}*/

});
</script>

<div class="wrap">
	<div id="lbg_logo">
			<h2>Playlist for showcase: <span style="color:#FF0000; font-weight:bold;"><?php echo strip_tags($_SESSION['xname'])?> - ID #<?php echo strip_tags($_SESSION['xid'])?></span> - Add New</h2>
 	</div>

    <form method="POST" enctype="multipart/form-data" id="form-add-playlist-record">
	    <input name="carouselid" id="carouselid" type="hidden" value="<?php echo strip_tags($_SESSION['xid'])?>" />
		<table class="wp-list-table widefat fixed pages" cellspacing="0">
		  <tr>
		    <td align="left" valign="middle" width="25%">&nbsp;</td>
		    <td align="left" valign="middle" width="77%"><a href="?page=magic_carousel_Playlist" style="padding-left:25%;">Back to Playlist</a></td>
		  </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
	      </tr>
		  <tr>
		    <td align="right" valign="middle" class="row-title">Set It First</td>
		    <td align="left" valign="top"><input name="setitfirst" type="checkbox" id="setitfirst" value="1" checked="checked" />
		      <label for="setitfirst"></label></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image </td>
		    <td width="77%" align="left" valign="top"><input name="img" type="text" id="img" size="60" value="<?php echo (array_key_exists('img', $_POST))?strip_tags($_POST['img']):''?>" /> <input name="upload_img_button_magic_carousel" type="button" id="upload_img_button_magic_carousel" value="Upload Image" />
	        <br />
	        Enter an URL or upload an image<br /></td>
		  </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Image Title</td>
		    <td align="left" valign="top"><input name="title" type="text" size="60" id="title" value="<?php echo (array_key_exists('title', $_POST))?strip_tags($_POST['title']):''?>"/></td>
	      </tr>
		  <tr>
		    <td align="right" valign="top" class="row-title">Link For The Image</td>
		    <td align="left" valign="top"><input name="data-link" type="text" size="60" id="data-link" value="<?php echo (array_key_exists('data-link', $_POST))?strip_tags($_POST['data-link']):''?>"/></td>
	      </tr>
				<tr>
					<td align="right" valign="top" class="row-title">Link Target</td>
					<td align="left" valign="top"><select name="data-target" id="data-target">
								<option value="" <?php echo ((array_key_exists('data-target', $_POST) && $_POST['data-target']=='')?'selected="selected"':'')?>>select...</option>
						<option value="_blank" <?php echo ((array_key_exists('data-target', $_POST) && $_POST['data-target']=='_blank')?'selected="selected"':'')?>>_blank</option>
						<option value="_self" <?php echo ((array_key_exists('data-target', $_POST) && $_POST['data-target']=='_self')?'selected="selected"':'')?>>_self</option>
						</select></td>
					</tr>
          <tr>
            <td align="right" valign="top" class="row-title">Preview Thumb</td>
            <td align="left" valign="top"><input name="data_bottom_thumb" type="text" id="data_bottom_thumb" size="100" value="<?php echo (array_key_exists('data_bottom_thumb', $_POST))?strip_tags($_POST['data_bottom_thumb']):''?>" /><input name="upload_thumb_button_magic_carousel" type="button" id="upload_thumb_button_magic_carousel" value="Upload Image" /></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
            <td align="left" valign="top">Recommended size: 79px x 79px</td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
            <td align="left" valign="top">&nbsp;</td>
          </tr>
          <tr>
            <td colspan="2" align="center" valign="top"><span class="regGray">Options for LightBox</span></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Large Image</td>
            <td align="left" valign="top"><input name="data_large_image" type="text" id="data_large_image" size="100" value="<?php echo (array_key_exists('data_large_image', $_POST))?strip_tags($_POST['data_large_image']):''?>" /><input name="upload_largeimg_button_magic_carousel" type="button" id="upload_largeimg_button_magic_carousel" value="Upload Image" /><br />
Enter an URL or upload an image<br /></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">YouTube Video ID</td>
            <td align="left" valign="top"><input name="data-video-youtube" type="text" size="60" id="data-video-youtube" value="<?php echo (array_key_exists('data-video-youtube', $_POST))?strip_tags($_POST['data-video-youtube']):''?>"/></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Vimeo Video ID</td>
            <td align="left" valign="top"><input name="data-video-vimeo" type="text" size="60" id="data-video-vimeo" value="<?php echo (array_key_exists('data-video-vimeo', $_POST))?strip_tags($_POST['data-video-vimeo']):''?>"/></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Self Hosted/Third Party Hosted Video</td>
            <td align="left" valign="middle"><input name="data_video_selfhosted" type="text" id="data_video_selfhosted" size="100" value="<?php echo (array_key_exists('data_video_selfhosted', $_POST))?strip_tags($_POST['data_video_selfhosted']):''?>" />
              <input name="upload_video_button_magic_carousel" type="button" id="upload_video_button_magic_carousel" value="Upload Video" />
              <br />
              Enter an URL or upload a .mp4 file<br /></td>
          </tr>
          <tr>
            <td align="right" valign="top" class="row-title">Audio File</td>
            <td align="left" valign="middle"><input name="data_audio" type="text" id="data_audio" size="100" value="<?php echo (array_key_exists('data_audio', $_POST))?strip_tags($_POST['data_audio']):''?>" />
              <input name="upload_audio_button_magic_carousel" type="button" id="upload_audio_button_magic_carousel" value="Upload Audio" />
              <br />
              Enter an URL or upload a .mp3 file<br /></td>
          </tr>
		  <tr>
            <td align="right" valign="top" class="row-title">&nbsp;</td>
		    <td align="left" valign="top">&nbsp;</td>
	      </tr>
		  <tr>
		    <td colspan="2" align="left" valign="middle">&nbsp;</td>
		  </tr>
		  <tr>
		    <td colspan="2" align="center" valign="middle"><input name="Submit" id="Submit" type="submit" class="button-primary" value="Add Record"></td>
		  </tr>
		</table>
  </form>






</div>
