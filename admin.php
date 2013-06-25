<?php

add_action( 'admin_menu', 'dictionary_plugin_menu' );

function dictionary_plugin_menu() {
	add_options_page( 'Dictionary Options', 'Dictionary', 'manage_options', 'dictionary_plugin', 'dictionary_plugin_options' );
}

function dictionary_plugin_options() {
	if ( !current_user_can( 'manage_options' ) )  {
		wp_die( __( 'You do not have sufficient permissions to access this page.' ) );
	}
	?>
	<div class="wrap">
	<div id="icon-options-general" class="icon32"><br></div><h2>Dictionary settings</h2>
	<h3>Add words</h3>
	<div id="notification"></div>
	<table class="form-table">
		<tbody>
			<tr valign="top">
				<th scope="row"><label for="word">Word</label></th>
				<td><input type="text" id="word" value="" name="word" class="regular-text" /></td>
			</tr>
			<tr valign="top">
				<th scope="row"><label for="definition">Definiton</label></th>
				<td><textarea rows="10" cols="100" id="definition" name="definition"></textarea>
				<p class="description">Definition will be updated if word already exists</p></td>
			</tr>
			<tr valign="top">
				<td collspan="2"><button id="add_word" class="button button-primary">Add word</button></td>
			</tr>

		</tbody>
	</table>
	<script>
		jQuery(document).ready(function() {
			//funcrion to add word to the file
			function add_word(word, definition){
				
				jQuery.ajax({
				type: "POST",
				url: "<?php echo plugins_url( 'add-word.php' , __FILE__ ) ?>",
				data: {word: word, definition: definition}, 
				cache: false,
				success: function(result){
  					jQuery('#word').val('');
  					jQuery('#definition').val('');
  					jQuery('#notification').html(result);
				},
				error: function(error){
					jQuery('#notification').html('<div id="setting-error-settings_updated" class="error settings-error"><p><strong>' + error.responseText + '</strong></p></div>');
				}
				});
			}
			
		 	//Add word button
			jQuery('#add_word').live("click",function() 
			{	
				var word = jQuery('#word').val();
				var definition = jQuery('#definition').val();
				add_word(word, definition);
			});

		
		});
	</script>
	
	</div>
	<?php
}
