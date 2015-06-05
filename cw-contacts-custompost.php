<?php

// Registers a new custom post showing contacts

	add_action( 'init', 'contacts_post_type' );
	function contacts_post_type() {
	  register_post_type( 'contacts',
	    array(
	      'labels' => array(
	        'name' => __( 'Contacts', 'c-framework' ),
	        'singular_name' => __( 'Contact', 'c-framework' )
	      ),
	      'public' => true,
	    )
	  );
	}


 
// Add the Events Meta Boxes
    
function add_contact_metaboxes($post) {
      add_meta_box('cw_contact_details', 'Details', 'cw_contact_details', 'contacts', 'normal', 'default');
    }

add_action('add_meta_boxes','add_contact_metaboxes');

function cw_contact_details($post){

    $contactemail = get_post_meta($post->ID, 'contactemail', true);
    $contactrole = get_post_meta($post->ID, 'contactrole', true);
    $contactphone = get_post_meta($post->ID, 'contactphone', true);
?>
    <table width="100%" border="0" cellspacing="4" cellpadding="0">
         <tr>
            <td width="16%">
                <strong>Role:</strong>
            </td>
            <td width="50%">
                <input type="text" name="contactrole" id="contactrole" size="72%" value="<?php echo $contactrole ?>" />
            </td>
        </tr>
        <tr>
            <td width="16%">
                <strong>Email:</strong>
            </td>
            <td width="50%">
                <input type="text" name="contactemail" id="contactemail" size="72%" value="<?php echo $contactemail ?>" />
            </td>
        </tr>
        <tr>
            <td width="16%">
                <strong>Phonenumber:</strong>
            </td>
            <td width="50%">
                <input type="text" name="contactphone" id="contactphone" size="72%" value="<?php echo $contactphone ?>" />
            </td>
        </tr>
       
    </table>
<?php
}


add_action('save_post','save_contact_details');
function save_contact_details(){
    global $post;

    $contactemail = $_POST['contactemail'];
    update_post_meta( $post->ID, 'contactemail', $contactemail);

    $contactrole = $_POST['contactrole'];
    update_post_meta( $post->ID, 'contactrole', $contactrole);

    $contactphone = $_POST['contactphone'];
    update_post_meta( $post->ID, 'contactphone', $contactphone);
}



//Changes the default "Enter title here" placeholder to "Enter name" for our contact custom posts
	function change_contacts_title( $title ){
	     $screen = get_current_screen();
	 
	     if  ( 'contacts' == $screen->post_type ) {
	          $title = 'Enter name';
	     }
	 
	     return $title;
	}
	 
	add_filter( 'enter_title_here', 'change_contacts_title' );





//Add template files to theme

class PageTemplater {

		/**
         * A Unique Identifier
         */
		 protected $plugin_slug;

        /**
         * A reference to an instance of this class.
         */
        private static $instance;

        /**
         * The array of templates that this plugin tracks.
         */
        protected $templates;


        /**
         * Returns an instance of this class. 
         */
        public static function get_instance() {

                if( null == self::$instance ) {
                        self::$instance = new PageTemplater();
                } 

                return self::$instance;

        } 

        /**
         * Initializes the plugin by setting filters and administration functions.
         */
        private function __construct() {

                $this->templates = array();


                // Add a filter to the attributes metabox to inject template into the cache.
                add_filter(
					'page_attributes_dropdown_pages_args',
					 array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the save post to inject out template into the page cache
                add_filter(
					'wp_insert_post_data', 
					array( $this, 'register_project_templates' ) 
				);


                // Add a filter to the template include to determine if the page has our 
				// template assigned and return it's path
                add_filter(
					'template_include', 
					array( $this, 'view_project_template') 
				);


                // Add your templates to this array.
                $this->templates = array(
                        'page-contacts.php'     => 'Contact'
                );
				
        } 


        /**
         * Adds our template to the pages cache in order to trick WordPress
         * into thinking the template file exists where it doens't really exist.
         *
         */

        public function register_project_templates( $atts ) {

                // Create the key used for the themes cache
                $cache_key = 'page_templates-' . md5( get_theme_root() . '/' . get_stylesheet() );

                // Retrieve the cache list. 
				// If it doesn't exist, or it's empty prepare an array
                $templates = wp_get_theme()->get_page_templates();
                if ( empty( $templates ) ) {
                        $templates = array();
                } 

                // New cache, therefore remove the old one
                wp_cache_delete( $cache_key , 'themes');

                // Now add our template to the list of templates by merging our templates
                // with the existing templates array from the cache.
                $templates = array_merge( $templates, $this->templates );

                // Add the modified cache to allow WordPress to pick it up for listing
                // available templates
                wp_cache_add( $cache_key, $templates, 'themes', 1800 );

                return $atts;

        } 

        /**
         * Checks if the template is assigned to the page
         */
        public function view_project_template( $template ) {

                global $post;

                if (!isset($this->templates[get_post_meta( 
					$post->ID, '_wp_page_template', true 
				)] ) ) {
					
                        return $template;
						
                } 

                $file = plugin_dir_path(__FILE__). get_post_meta( 
					$post->ID, '_wp_page_template', true 
				);
				
                // Just to be safe, we check if the file exist first
                if( file_exists( $file ) ) {
                        return $file;
                } 
				else { echo $file; }

                return $template;

        } 


} 

add_action( 'plugins_loaded', array( 'PageTemplater', 'get_instance' ) );

//Display template for single contact post
add_filter( 'single_template', 'get_contact_single_template' );
function locate_plugin_template($template_names, $load = false, $require_once = true )
{
    if ( !is_array($template_names) )
        return '';
    
    $located = '';
    
    $this_plugin_dir = WP_PLUGIN_DIR.'/'.str_replace( basename( __FILE__), "", plugin_basename(__FILE__) );
    
    foreach ( $template_names as $template_name ) {
        if ( !$template_name )
            continue;
        if ( file_exists(STYLESHEETPATH . '/' . $template_name)) {
            $located = STYLESHEETPATH . '/' . $template_name;
            break;
        } else if ( file_exists(TEMPLATEPATH . '/' . $template_name) ) {
            $located = TEMPLATEPATH . '/' . $template_name;
            break;
        } else if ( file_exists( $this_plugin_dir .  $template_name) ) {
            $located =  $this_plugin_dir . $template_name;
            break;
        }
    }
    
    if ( $load && '' != $located )
        load_template( $located, $require_once );
    
    return $located;
}

function get_contact_single_template($template)
{
    global $wp_query;
    $object = $wp_query->get_queried_object();
    
    if ( 'contacts' == $object->post_type ) {
        $templates = array('single-' . $object->post_type . '.php', 'single.php');
        $template = locate_plugin_template($templates);
    }

    return $template;
}