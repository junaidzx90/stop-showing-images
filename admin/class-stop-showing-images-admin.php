<?php

/**
 * The admin-specific functionality of the plugin.
 *
 * @link       https://www.fiverr.com/junaidzx90
 * @since      1.0.0
 *
 * @package    Stop_Showing_Images
 * @subpackage Stop_Showing_Images/admin
 */

/**
 * The admin-specific functionality of the plugin.
 *
 * Defines the plugin name, version, and two examples hooks for how to
 * enqueue the admin-specific stylesheet and JavaScript.
 *
 * @package    Stop_Showing_Images
 * @subpackage Stop_Showing_Images/admin
 * @author     Md Junayed <admin@easeare.com>
 */
class Stop_Showing_Images_Admin {

	/**
	 * The ID of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $plugin_name    The ID of this plugin.
	 */
	private $plugin_name;

	/**
	 * The version of this plugin.
	 *
	 * @since    1.0.0
	 * @access   private
	 * @var      string    $version    The current version of this plugin.
	 */
	private $version;

	/**
	 * Initialize the class and set its properties.
	 *
	 * @since    1.0.0
	 * @param      string    $plugin_name       The name of this plugin.
	 * @param      string    $version    The version of this plugin.
	 */
	public function __construct( $plugin_name, $version ) {

		$this->plugin_name = $plugin_name;
		$this->version = $version;
		add_shortcode( 'stop_showing_images', [$this,"junu_stop_showing_images_shortcode"] );
		
	}

	function junu_filters(){
		if(get_user_meta( get_current_user_id(  ), 'junu_myimages_hideshow', true ) === 'checked'){
			add_action('wp_get_attachment_image_src', [$this,'junu_wooor_attached_src'], 10, 4);
			add_action('the_content', [$this,'junu_image_contents']);
		}
	}
	/**
	 * Register the stylesheets for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_styles() {
		if(isset($_GET['page']) && $_GET['page'] === "stop-showing-img-opt"){
			wp_enqueue_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/stop-showing-images-admin.css', array(), $this->version, 'all' );
		}
		wp_register_style( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'css/stop-showing-public.css', array(), $this->version, 'all' );
	}

	/**
	 * Register the JavaScript for the admin area.
	 *
	 * @since    1.0.0
	 */
	public function enqueue_scripts() {
		if(isset($_GET['page']) && $_GET['page'] === "stop-showing-img-opt"){
			wp_enqueue_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/stop-showing-images-admin.js', array( 'jquery' ), $this->version, true );
		}
		wp_register_script( $this->plugin_name, plugin_dir_url( __FILE__ ) . 'js/stop-showing-public.js', array( 'jquery' ), microtime(), false );
		wp_localize_script($this->plugin_name, "junu_show_hide_ajax_calling", array(
			'ajaxurl' => admin_url('admin-ajax.php'),
			'nonce' => wp_create_nonce('ajax-nonce'),
		));
	}


	/**
	 * Register menupage
	 */
	function junu_stop_showing_img_menu(){
		add_menu_page( "Stop Showing Images", "Stop Showing Images", "manage_options", "stop-showing-img-opt", [$this,"stop_showing_menu_callback"], "dashicons-hidden", 45 );
	}

	function junu_image_contents($the_content){
		$content = str_replace('#\<img(.+?)src="*">#s', '#', $the_content);
		$content = str_replace('srcset="', 'load-srcset="', $the_content);
		$rep = '#';
		return preg_replace("~<img (.*?)>~","<img style='width:100%' src='".STOP_SHOWING_IMAGES_URL."image/blank.png'>",$the_content);
	}
	
	function junu_wooor_attached_src($image, $attachment_id, $size, $icon){        
		$image[0] = STOP_SHOWING_IMAGES_URL.'image/blank.png';
		return $image;
	}

	// Menupage callback
	function stop_showing_menu_callback(){
		if(isset($_POST['save_settings'])){
			$description = sanitize_text_field( stripslashes($_POST['stopshowing_desc']) );
			$title = sanitize_text_field( stripslashes($_POST['junu_stop_showing_title']) );
			$button_text = sanitize_text_field( stripslashes($_POST['buttontxtinput']) );

			if(get_option('junu_stop_showing_title') || empty(get_option('junu_stop_showing_title'))){
				update_option('junu_stop_showing_title', $title);
			}else{
				add_option('junu_stop_showing_title', $title);
			}

			if(get_option('junu_stopshowing_desc') || empty(get_option('junu_stopshowing_desc'))){
				update_option('junu_stopshowing_desc', $description);
			}else{
				add_option('junu_stopshowing_desc', $description);
			}

			if(get_option('junu_buttontxtinput') || empty(get_option('junu_buttontxtinput'))){
				update_option('junu_buttontxtinput', $button_text);
			}else{
				add_option('junu_buttontxtinput', $button_text);
			}
		}
		?>
		<h2>Stop Showing Images <input type="text" readonly value="[stop_showing_images]"></h2>
		<hr>
		<div id="stopshowing_settings">
			<form action="" method="post">
				<table>
				<tbody>
				<tr>
				<th><label for="junu_stop_showing_title">Title</label></th>
				<td><input type="text" name="junu_stop_showing_title" placeholder="Title" id="junu_stop_showing_title" value="<?php echo get_option('junu_stop_showing_title'); ?>"></td>
				</tr>

				<tr>
				<th><label for="stopshowing_desc">Descriptiption</label></th>
				<td><textarea name="stopshowing_desc" placeholder="Desciption" id="stopshowing_desc"><?php echo get_option('junu_stopshowing_desc'); ?></textarea></td>
				</tr>

				<tr>
				<th><label for="buttontxtinput">Button text</label></th>
				<td><input type="text" name="buttontxtinput" placeholder="Text" id="buttontxtinput" value="<?php echo get_option('junu_buttontxtinput'); ?>"></td>
				</tr>
				</tbody>
				</table>
				<br>
				<button name="save_settings" class="button">Save</button>
			</form>
		</div>
		<?php
	}

	
	/**
	 * This is a shortcode callback
	 */
	function junu_stop_showing_images_shortcode($atts){
		ob_start();
		wp_enqueue_style($this->plugin_name);
		wp_enqueue_script($this->plugin_name);
		?>
		<div id="stopmyimages">
			<div class="stopshowingcontents">
				<h4 class="stop_showing_ttl"><?php echo __(get_option('junu_stop_showing_title'),$this->plugin_name); ?></h4>
				<p><?php echo __(get_option('junu_stopshowing_desc'),$this->plugin_name); ?></p>

				<div class="buttn_wrapper">
					<div class="enabled_switch">
						<input type="checkbox" name="stop_shown_enabled_switch" id="stop_shown_enabled_switch_opt" <?php echo get_user_meta( get_current_user_id(  ), 'junu_myimages_hideshow', true ); ?> value="<?php echo (get_user_meta( get_current_user_id(  ), 'junu_myimages_hideshow', true )?get_user_meta( get_current_user_id(  ), 'junu_myimages_hideshow', true ):'unchecked'); ?>">
						<label for="stop_shown_enabled_switch_opt"><?php echo __(get_option('junu_buttontxtinput','Stop showing images')?get_option('junu_buttontxtinput','Stop showing images'):'Stop showing images',$this->plugin_name); ?></label>
					</div>
				</div>
				
			</div>
		</div>
		<?php

		$output = ob_get_contents();
		ob_end_clean();
		return $output;
		exit;
	}


	function junu_images_hide_show(){
		if ( ! wp_verify_nonce( $_POST['nonce'], 'ajax-nonce' ) ) {
			die ( 'Hey! What are you doing?');
		}
		
		if(isset($_POST['data'])){
			$request = $_POST['data'];
			if($request == 'checked'){
				$user_id = get_current_user_id(  );
				update_user_meta( $user_id, 'junu_myimages_hideshow', 'checked' );
				die;
			}

			if($request == 'unchecked'){
				$user_id = get_current_user_id(  );
				update_user_meta( $user_id, 'junu_myimages_hideshow', 'unchecked' );
				die;
			}
		}
		die;
	}
}
