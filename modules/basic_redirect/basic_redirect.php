<?php
namespace MaxInbound;
defined('ABSPATH') or die('No direct access permitted');

add_action('maxinbound_register_module', array(__NAMESPACE__ .'\moduleRedirect', 'init'));

class moduleRedirect extends miModule
{
	static $name = 'basic_redirect'; 
	protected $redirect_url = ''; 
	
	public static function init($modules) 
	{
		$modules->register(self::$name, get_called_class() );
	}
	
	public function __construct() 
	{
		parent::__construct(); 
	
	// Currently not in use.
		
	// 	MI()->listen('template/process-end', array($this, 'redirect') ); 
	// 	MI()->offer('editor/module-options', array($this, 'options') ); 
	//	MI()->listen('editor/save-options', array($this, 'save_options') ); 
	// 	MI()->listen('editor/metaboxes', array($this, 'box') );
	//	MI()->tell('mi-core-options', array($this, 'options') ); 
		
	}
	

	
	public function redirect() 
	{
		$options = $this->get_options();
		
		$redirect = $options["redirect"]; 
		
		switch($redirect)
		{
			case "home"; 
				$url = get_home_url(); 
			break;
			case "posts" :
				$url = get_permalink( get_option( 'page_for_posts' ) ); 
			break;
			case "custom": 
				$url = $options["custom_url"]; 
			break;	
		
		
		}
 		$this->redirect_url($url); 
		
	}
	
	public function redirect_url($url)
	{
		wp_redirect($url); 
		exit();
	}
	

	public function box() 
	{
		// ID - TITLE - CALLBACK - SCREEN - CONTEXT - PRIORITY - ARGS
		add_meta_box('mod-redirect', __("Redirect","maxinbound"), array($this, 'options'), null, 
					 'side'); 
				
	
	}
		
	public function save_options($post) 
	{
		$options = array(); 
		
		$options["redirect"] = isset($post["redirect"]) ? $post["redirect"] : 'home'; 
		$options["custom_url"] = isset($post["custom_url"]) ? $post['custom_url'] : ''; 
		
		$this->update_options($options); 	

	}
	
	public function options () 
	{
		$options = $this->get_options(); 
		$redirect = isset($options["redirect"]) ? $options["redirect"] : '';
		$custom_url = isset($options["custom_url"]) ? $options["custom_url"] : ''; 
		if ($redirect == '') 
			$redirect = 'home'; // default 
		
		echo "OPT"; 
		var_dump($options);
	?>
			<div class='option options'> 
				<p><?php _e("After form submit redirect visitor to","maxinbound"); ?> </p>
				<p><input type="radio" name='redirect' value="home" <?php checked('home', $redirect ); ?> ><?php _e('Homepage', 'maxinbound'); ?> <br /> 
				<input type="radio" name='redirect' value="posts" <?php checked('posts', $redirect); ?> ><?php _e("Posts Page","maxinbound"); ?> <br>
				<input type="radio" name='redirect' value="custom" <?php checked('custom', $redirect); ?> ><?php _e("Custom URL",'maxinbound'); ?> <br />
				<input type="text" name="custom_url" placeholder='http://' value="<?php echo $custom_url ?>"><br>
				</p>
			</div>
	<?php
	}
	

} //  class
