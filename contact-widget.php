<?php

// Block direct requests
if ( !defined('ABSPATH') )
	die('-1');


add_action( 'widgets_init', function(){
	register_widget( 'CW_Contacts_Widget' );
});	

/**
 * Adds My_Widget widget.
 */
class CW_Contacts_Widget extends WP_Widget {

	/**
	 * Register widget with WordPress.
	 */
	function __construct() {
		parent::__construct(
			'CW_Contacts_Widget', // Base ID
			__('Contact Widget', 'cw_widget_plugin'), // Name
			array('description' => __( 'Display a contact', 'cw_widget_plugin' ),) // Args
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
		
		// get the excerpt of the required story
		if ( $instance['contact_id'] == 0 ) {

			$gp_args = array(
				'posts_per_page' => 1,
				'post_type' => 'contacts',
				'orderby' => 'post_date',
				'order' => 'desc',
				'post_status' => 'publish'
				);

			$posts = get_posts( $gp_args );
			
			if ( $posts ) {
				$post = $post[0];
			} else {
				$post = null;
			}

		} else {

			$post = get_post( $instance['contact_id'] );
			
		}

		if ( array_key_exists('before_widget', $args) ) echo $args['before_widget'];
		
		if ( $post ) {
		
			
			echo '<h3 class="contact_name">' . $post->post_title . '</h3>';

			echo get_the_post_thumbnail( $post->ID, array(250,500), array('class'=>'contact_widget_img') );

			$contactrole = get_post_meta($post->ID, "contactrole", true);
			echo '<div>' . $contactrole . '</div>';

			$emailadress = get_post_meta($post->ID, "contactemail", true);
			echo '<div class="contacts-single-email"><a href="mailto:' . $emailadress . '">' . $emailadress . '</a></div>';

			$contactphone = get_post_meta($post->ID, "contactphone", true);
			echo '<div>' . $contactphone . '</div>';

			$contact_twitterlink = get_post_meta($post->ID, "contacttwitter", true);
			if(!empty($contact_twitterlink)) {  
				echo '<div class="contact-twitter"><a href="';
				echo $contact_twitterlink; 
				echo'">Twitter</a></div>';
			}


			$contact_linkedinlink = get_post_meta($post->ID, "contactlinkedin", true);
			if(!empty($contact_linkedinlink)) { 
				echo '<div class="contact-linkedin"><a href="';
				echo $contact_linkedinlink; 
				echo '">Linkedin</a></div>';
			} else {
				echo '';
			}

			$contact_facebooklink = get_post_meta($post->ID, "contactfacebook", true);

			if(!empty($contact_facebooklink)) {
				echo '<div class="contact-facebook"><a href="';
				echo $contact_facebooklink;
				echo '">Facebook</a>';
			} else {
				echo '';
			}


			echo '<p class="contact_widget_readmore"><a href="' . get_permalink( $post->ID ) . '" title="More info' . $post->post_title . '">More info</a></p>';
			
		} else {

			echo __( 'No recent contact found.', 'cw_widget_plugin' );
		}

		if ( array_key_exists('after_widget', $args) ) echo $args['after_widget'];
	}

	/**
	 * Back-end widget form.
	 *
	 * @see WP_Widget::form()
	 *
	 * @param array $instance Previously saved values from database.
	 */
	public function form( $instance ) {

		// Check values	
		if ( isset( $instance[ 'contact_id' ] ) ) {
			$contact_id = $instance[ 'contact_id' ];
		}
		else {
			$contact_id = 0;
		}
		?>
		
			
			<label for="<?php echo $this->get_field_id( 'contact_id' ); ?>"><?php _e( 'Contact:' ); ?></label> 		
			<select id="<?php echo $this->get_field_id( 'contact_id' ); ?>" name="<?php echo $this->get_field_name( 'contact_id' ); ?>">
				
				<?php 
		// get the exceprt of the most recent story
				$gp_args = array(
					'posts_per_page' => -1,
					'post_type' => 'contacts',
					'orderby' => 'name',
					'order' => 'asc',
					'post_status' => 'publish'
					);

				$posts = get_posts( $gp_args );

				foreach( $posts as $post ) {

					$selected = ( $post->ID == $contact_id ) ? 'selected' : ''; 

					if ( strlen($post->post_title) > 30 ) {
						$title = substr($post->post_title, 0, 27) . '...';
					} else {
						$title = $post->post_title;
					}

					echo '<option value="' . $post->ID . '" ' . $selected . '>' . $title . '</option>';

				}

				?>
			</select>
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
		$instance['contact_id'] = ( ! empty( $new_instance['contact_id'] ) ) ? strip_tags( $new_instance['contact_id'] ) : '';
		return $instance;
	}

} // class My_Widget