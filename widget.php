<?php

/**
 * Adds Dictionary_Widget widget.
 */
class Dictionary_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	public function __construct() {
		parent::__construct(
	 		'Dictionary_widget', // Base ID
			'Dictionary Widget', // Name
			array( 'description' => __( 'Dictionary Widget', 'text_domain' ), ) // Args
		);

	}

	/**
	 * Front-end display of widget.
	 *
	 * @see WP_Widget::widget()
	 *
	 * @param array $args     Widget arguments.
	 * @param array $instance Saved values from database.
	 */
	public function widget( $args, $instance ) {

		
		extract( $args );
		$title = apply_filters( 'widget_title', $instance['title'] );

		echo $before_widget;
		if ( ! empty( $title ) )
			echo $before_title . $title . $after_title;
		//Widget content
		?>
		<div class="word-definition">

		</div>
		<input type="hidden" name="word_override" class="word_override" value="<? echo $instance['word_override']; ?>" />
		<button class="refresh_words" onclick="refresh_words(jQuery(this).parent().find('.word-definition'),'<? echo $instance['words_to_show']; ?>', jQuery(this).parent().find('.word_override').val(), false)">Refresh</button>
		<?php if ($instance['show_search']) : ?>
			<div>
				<input type="text" class="word-search" placeholder="Find a word" />
			</div>
		<?php endif; ?>
			
		<script>
		//funcrion to refresh word list
			function refresh_words(element, words_to_show, word_override, initial){
				
				jQuery.ajax({
				type: "POST",
				url: "<?php echo plugins_url( 'show-random.php' , __FILE__ ) ?>",
				data: {words_to_show: words_to_show, word_override: word_override}, 
				cache: false,
				success: function(result){
					element.html(result);   
					jQuery('.word_override').val('');
				},
				error: function(error){
					element.html(error.responseText);
				}
				});

			}
		jQuery(document).ready(function() {
			
			//refresh word list on page load
		 	jQuery('.refresh_words').trigger('click');


			//refresh button search field
			jQuery('.word-search').live("keyup",function(e) 
			{	
				if(e.keyCode == 13) //if enter was pressed
				{
				 	var term = jQuery(this).val(); //get word from field
	 				var definition_area = jQuery(this).parent().parent().find('.word-definition'); //get area where we want to put the search result

	 				jQuery.ajax({
	 				type: "POST",
	 				url: "<?php echo plugins_url( 'search-word.php' , __FILE__ ) ?>",
	 				data: {term: term}, 
	 				cache: false,
	 				success: function(result){					
	 					definition_area.html(result); 

	 				},
	 				error: function(error){
	 					definition_area.html(error.responseText);
	 				}
	 				});
				}
				
			
				
			});
		});
		</script>
		<?php
		
		echo $after_widget;

		
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {
		if ( isset( $instance[ 'title' ] ) ) {
			$title = $instance[ 'title' ];
		}
		else {
			$title = __( 'Did you know...', 'text_domain' );
		}
		if ( isset( $instance[ 'words_to_show' ] ) ) {
			$words_to_show = $instance[ 'words_to_show' ];
		}
		else {
			$words_to_show = __( '1', 'text_domain' );
		}
		if ( isset( $instance[ 'word_override' ] ) ) {
			$word_override = $instance[ 'word_override' ];
		}
		else {
			$word_override = __( '', 'text_domain' );
		}
		if ( isset( $instance[ 'show_search' ] ) ) {
			$show_search = $instance[ 'show_search' ];
		}
		else {
			$show_search = __( '1', 'text_domain' );
		}
		?>
		<p>
		<label for="<?php echo $this->get_field_name( 'title' ); ?>"><?php _e( 'Title:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'title' ); ?>" name="<?php echo $this->get_field_name( 'title' ); ?>" type="text" value="<?php echo esc_attr( $title ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_name( 'words_to_show' ); ?>"><?php _e( 'Words to show:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'words_to_show' ); ?>" name="<?php echo $this->get_field_name( 'words_to_show' ); ?>" type="number" value="<?php echo esc_attr( $words_to_show ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_name( 'word_override' ); ?>"><?php _e( 'Word override:' ); ?></label> 
		<input class="widefat" id="<?php echo $this->get_field_id( 'word_override' ); ?>" name="<?php echo $this->get_field_name( 'word_override' ); ?>" type="text" value="<?php echo esc_attr( $word_override ); ?>" />
		</p>
		<p>
		<label for="<?php echo $this->get_field_name( 'show_search' ); ?>"><?php _e( 'Show Search:' ); ?></label> 
		<input type="hidden" name="<?php echo $this->get_field_name( 'show_search' ); ?>" value="0" />
		<input id="<?php echo $this->get_field_id( 'show_search' ); ?>" name="<?php echo $this->get_field_name( 'show_search' ); ?>" type="checkbox" <?php checked( '1', $show_search); ?> value="1" />
		</p>
		<?php 
	}

	/**
	 * Sanitize widget form values as they are saved.
	 *
	 * @see WP_Widget::update()
	 *
	 * @param array $new_instance Values just sent to be saved.
	 * @param array $old_instance Previously saved values from database.
	 *
	 * @return array Updated safe values to be saved.
	 */
	public function update( $new_instance, $old_instance ) {
		$instance = array();
		$instance['title'] = ( !empty( $new_instance['title'] ) ) ? strip_tags( $new_instance['title'] ) : '';
		$instance['words_to_show'] = ( !empty( $new_instance['words_to_show'] ) ) ? strip_tags( $new_instance['words_to_show'] ) : '1';
		$instance['word_override'] = ( !empty( $new_instance['word_override'] ) ) ? strip_tags( $new_instance['word_override'] ) : '';
		$instance['show_search'] = ( $new_instance['show_search'] == '1' ) ? '1' : '0';

		return $instance;
	}

} // class Dictionary_Widget



// register Dictionary_Widget widget
add_action( 'widgets_init', function() { register_widget( 'Dictionary_Widget' ); } );

function check_widget() {
    if( is_active_widget( '', '', 'dictionary_widget' ) ) { // check if Dictionary_Widget widget is used
        wp_enqueue_script('jquery');
    }
}

add_action( 'init', 'check_widget' );

