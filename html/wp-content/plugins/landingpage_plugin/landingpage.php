<?php
/*
 Plugin Name: LandingPage 
 Plugin URI: https://github.com/suspect22/docker_compose_wordpress_plugin
 Description: build a customized landing page
 Version: 1.0
 Author: suspect22
 Author URI: http://github.com/suspect22
 Update Server: http://localhost
 License: GPLv2 
 */

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
				'cust.php'     => 'Custom Template',
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

function register_plugin_javascript(){	
	wp_register_script('landingpageJS',plugins_url('landingpage.js',__FILE__));
	//wp_enqueue_script('landingpageJS', array('jquery'));
}
add_action('init','register_plugin_javascript');

function register_plugin_css(){
	wp_register_style('navigationStyle',plugins_url('style.css',__FILE__));
	wp_enqueue_style('navigationStyle');
}

add_action('init', 'register_plugin_css');


//register_deactivation_hook( __FILE__, 'pluginprefix_function_to_run' );


// Custom Blocks
//add_action( 'widgets_init', 'theme_slug_widgets_init' );
/*function theme_slug_widgets_init() {
    register_sidebar( array(
    'name' 			=> __( 'Nav Menu Block 1', 'theme-slug' ),
    'id' 			=> 'sidebar-99',
    'description' 	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
    'before_widget' => '<li id="%1$s" class="widget %2$s">',
	'after_widget'  => '</li>',
	'before_title'  => '<h2 class="widgettitle">',
	'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
    		'name' 			=> __( 'Nav Menu Block 2', 'theme-slug' ),
    		'id' 			=> 'sidebar-2',
    		'description' 	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
    		'before_widget' => '<li id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</li>',
    		'before_title'  => '<h2 class="widgettitle">',
    		'after_title'   => '</h2>',
    ) );
    
    register_sidebar( array(
    		'name' 			=> __( 'Nav Menu Block 3', 'theme-slug' ),
    		'id' 			=> 'sidebar-3',
    		'description' 	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
    		'before_widget' => '<li id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</li>',
    		'before_title'  => '<h2 class="widgettitle">',
    		'after_title'   => '</h2>',
    ) );

    register_sidebar( array(
    		'name' 			=> __( 'Nav Menu Block 4', 'theme-slug' ),
    		'id' 			=> 'sidebar-4',
    		'description' 	=> __( 'Widgets in this area will be shown on all posts and pages.', 'theme-slug' ),
    		'before_widget' => '<li id="%1$s" class="widget %2$s">',
    		'after_widget'  => '</li>',
    		'before_title'  => '<h2 class="widgettitle">',
    		'after_title'   => '</h2>',
    ) );
    
}*/


// adds Configureable Menus in Worpress 
function register_frontpage_customnavigation(){
	register_nav_menus(
			array(
					'block-menu0' => __( 'Landing Page Navigation Menu 1 Block' ),
					'block-menu1' => __( 'Landing Page Navigation Menu 2 Block' ),
					'block-menu2' => __( 'Landing Page Navigation Menu 3 Block' ),
					'block-menu3' => __( 'Landing Page Navigation Menu 4 Block' ),
			)
			);
	
}
add_action('init','register_frontpage_customnavigation');

// adds a custom template via Plugin
function register_custom_template(){
	if ( is_page( 'my-custom-page-slug' ) ) {
		$page_template = dirname( __FILE__ ) . '/cust.php';
	}
	return $page_template;
}
add_filter('page_template','register_custom_template');


add_action( 'admin_menu', 'admin_menu_re_navigation' );
function admin_menu_re_navigation(){
	//add_option_page('<Website Title>',<NavigationMenuItem>,<Wordpress ACLs>,<PageID>,<function for form display>);
	add_options_page('Front Navigation Configuration','RE Navigationseinstellungen','manage_options','renavigation','admin_menu_re_navigation_show_options');
}

function admin_menu_re_navigation_show_options(){
	// added for checking if Form has been send back to the Server
	// defining Names for Field Names in Form & Database
	$postedBackFieldName='postback';
	
	$colorFieldNameArray=array();
	$blockNameFieldNameArray=array();
	$colorValueArray=array();
	$blockNameValueArray=array();
	
	
	for($i=0; $i<4; $i++){
		$colorFieldNameArray[$i]='block' .  $i .'Color';
		$blockNameFieldNameArray[$i]='block'.$i.'Name';
	}
	
	// read current Options From Database which are defined
	for($i=0; $i<4; $i++){
		
		$colorValueArray[$i]=get_option($colorFieldNameArray[$i]);
		$blockNameValueArray[$i]=get_option($blockNameFieldNameArray[$i]);	
	}
	
	
	// Authorisation
	if (!current_user_can('manage_options')){
		wp_die(__('You do not have permissions to access This Page'));
	}

	
	if(isset($_POST[$postedBackFieldName]) && $_POST[$postedBackFieldName]=='Y'){
		// Save & Update the Options

		// print_r($_POST);
		for($i=0; $i<4; $i++){
			echo $i;
			$colorValueArray[$i] = $_POST[$colorFieldNameArray[$i]];
			$blockNameValueArray[$i] = $_POST[$blockNameFieldNameArray[$i]];
			echo $i;
			update_option($colorFieldNameArray[$i],$colorValueArray[$i]);
			update_option($blockNameFieldNameArray[$i],$blockNameValueArray[$i]);
		}
		
	}


	/* Form in Admin Menu */
	?>
	<div class="wrap">$FieldValues = array();
	<h1>Manage Options for Navigation</h1>
	<form name="myColorSettings" method="post">
	<input type="hidden" name="<?php echo $postedBackFieldName;  ?>" value="Y" />
	
	<table>
	<?php 
	for($i=0; $i<4; $i++){
	?>
		<tr>
			<td>
				<label for="">Navigation Name Block <?php echo $i; ?></label>
			</td>
			<td>
				<input type="text" name="<?php echo $blockNameFieldNameArray[$i]?>" value="<?php echo $blockNameValueArray[$i]?>" />
			</td>
		</tr>
		<tr>
			<td>
				<label for="">Navigation Color <?php echo $i; ?></label>
			</td>
			<td>
				<textarea name="<?php echo $colorFieldNameArray[$i]?>"><?php echo $colorValueArray[$i]?></textarea>
			</td>
		</tr>
	<?php 
	}
	?>	
	<tr><td colspan="2"><input class='button-primary' value="<?php esc_attr_e('Save Changes'); ?>" type='submit' /></td></tr>
	</table>
	</form>
	</div>
	<?php 
	
}
?>
