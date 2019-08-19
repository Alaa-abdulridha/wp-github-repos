<?php
/**
Plugin Name:  Github Repos
Plugin URI: https://alaa.blog
Description: Showing the latest Github Repos in the Sidebar
Author: Alaa Abdulridha
Version: 0.1
Author URI: https://alaa.blog
Text Domain: wp-github-repos
Domain Path: languages/

*/


class WP_Github_Repos {


  
    const CACHE = 'github-repos-';

    function __construct() {

 
        load_plugin_textdomain( 'wp-github-repos', false, dirname(plugin_basename(__FILE__)) .  '/languages' );
		        // Register style sheet.
		add_action( 'wp_enqueue_scripts', array( $this, 'register_plugin_styles' ) );

    }

    /**
	 * Register and enqueue style sheet.
	 */
	public function register_plugin_styles() {
		wp_register_style( 'github_api_style', plugins_url(). '/wp-github-repos/include/color.css'  );
		wp_enqueue_style( 'github_api_style' );
		wp_register_script('color',plugin_dir_url( __FILE__ ) . 'include/color.js',false,'1.0',true);
		wp_enqueue_script('color');
	}
    public function get_github_repos($user, $count = 5, $forked = false) {


        $data = '';
        $rowsno = 0;




        $key = self::CACHE . "$user";

        if (false === ( $repos = get_transient( $key ) ) ) {
            if(!class_exists('Github_API')){
                require dirname(__FILE__) . '/include/github-api.php';
            }

            $github_api = new Github_API();
            $repos = $github_api->get_repos($user,'');


			set_transient($key, $repos, 5 * HOUR_IN_SECONDS); 
			/* For Testing , To delete the cache
			delete_transient($key);
			delete_transient($data);
			delete_transient($latest);
			*/
        }

        $data = '<ul class = "github-repos">';

	if(is_array($repos)){
        foreach($repos as $latest) {
            $rowsno ++;
		

			if ($latest->fork == json_decode($forked))
			{
            $repo_name = wp_trim_words( $latest->name, 20, '...' );
            $data .= '<li class = "github-repos">';
            $data .= "<a href = '{$latest->html_url}'> " . $repo_name . '</a> ';
            $data .= __(' ', 'wp-github-repos') . ' <pp class = "github-color"> ' . $latest->language . '</a> </pp>';
            $data .= __('on', 'wp-github-repos') . ' ' . date("M d, Y ", strtotime($latest->updated_at));
            $data .= '</li>';
			
			}
             if ($count == $rowsno)
				 {
                break;
            }
        }
	}
        $data .= '</ul>';
		
	
  
        return $data;
		
    }
}
function myplugin_register_widgets() {
	register_widget( 'WP_Github_Repos_Widget' );
}
// Register the widget
add_action( 'init', 'WP_Github_Repos' ); function WP_Github_Repos() { global $wp_github_repos; $wp_github_repos = new WP_Github_Repos(); }
add_action( 'widgets_init', 'myplugin_register_widgets' );


class WP_Github_Repos_Widget extends WP_Widget {
    
    function __construct() {
	
		$widget_ops = array( 'classname' => 'WP_Github_Repos_Widget', 'description' => __('Github Repos for a user', 'wp-github-repos'));

		$control_ops = array('id_base' => 'wp-github-repos' );

		parent::__construct( 'wp-github-repos', __('WP Github Repos', 'wp-github-repos'), $widget_ops, $control_ops );
    }


    function widget($args, $instance) {
        extract( $args );


		$defaults = array( 'title' => __('RECENT GITHUB REPOS', 'wp-github-repos'), 'user' => '', 'forked' => 'false');
		$instance = wp_parse_args( (array) $instance, $defaults );

        $title = $instance['title'];
        $user = $instance['user'];
		$forked = $instance['forked'];
        $count = absint($instance['count']);
		

 
        $widget_content = get_github_repos($user, $count, $forked);

        if ($widget_content != '') {
            echo $before_widget;
            echo $before_title;
            echo $title;
            echo $after_title;
            echo $widget_content;
            echo $after_widget;
        }
    }


    function update($new_instance, $old_instance) {
		$instance = $old_instance;

        
        $instance['title'] = strip_tags($new_instance['title']);
        $instance['user'] = strip_tags($new_instance['user']);
		$instance['forked'] = strip_tags($new_instance['forked']);
        $instance['count'] = absint($new_instance['count']);

        return $instance;
    }

   
    function form($instance) {

		$defaults = array( 'title' => __('RECENT GITHUB REPOS', 'wp-github-repos'), 'user' => '', 'count' => 5, 'forked' =>'false');
		$instance = wp_parse_args( (array) $instance, $defaults );

        $title = esc_attr($instance['title']);
		$user = $instance['user'];
		$forked = $instance['forked'];
        $count = absint($instance['count']);
?>

        <p>
            <label for="<?php echo $this->get_field_id('title'); ?>"><?php _e('Title:', 'wp-github-repos'); ?>
            <input class="widefat" id="<?php echo $this->get_field_id('title'); ?>" name="<?php echo $this->get_field_name('title'); ?>" type="text" value="<?php echo $title; ?>" /></label>
        </p>

        <p>
            <label for="<?php echo $this->get_field_id('user'); ?>"><?php _e('User:', 'wp-github-repos'); ?>
            <input class="widefat" id="<?php echo $this->get_field_id('user'); ?>" name="<?php echo $this->get_field_name('user'); ?>" type="text" value="<?php echo $user; ?>" /></label>
        </p>

     

        <p>
            <label for="<?php echo $this->get_field_id('count'); ?>"><?php _e('No of Repos to show:', 'wp-github-repos'); ?>
            <input class="widefat" id="<?php echo $this->get_field_id('count'); ?>" name="<?php echo $this->get_field_name('count'); ?>" type="text" value="<?php echo $count; ?>" /></label>
        </p>
		   <p>
            <label for="<?php echo $this->get_field_id('forked'); ?>"><?php _e('Show forked repos ?(true or false):', 'wp-github-repos'); ?>
            <input class="widefat" id="<?php echo $this->get_field_id('forked'); ?>" name="<?php echo $this->get_field_name('forked'); ?>" type="text" value="<?php echo $forked; ?>" /></label>
        </p>

<?php
    }
} 

function get_github_repos($user, $count = 5, $forked) {
    global $wp_github_repos;
    return $wp_github_repos->get_github_repos($user,  $count, $forked);
}
?>
