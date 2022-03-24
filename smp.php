<?php

/**
 * The plugin bootstrap file
 *
 * This file is read by WordPress to generate the plugin information in the plugin
 * admin area. This file also includes all of the dependencies used by the plugin,
 * registers the activation and deactivation functions, and defines a function
 * that starts the plugin.
 *
 * @link              https://portfoliosazzad.web.app
 * @since             1.0.0
 * @package           Smp
 *
 * @wordpress-plugin
 * Plugin Name:       Simple Membership Plugin
 * Plugin URI:        https://portfoliosazzad.web.app
 * Description:       This is a short description of what the plugin does. It's displayed in the WordPress admin area.
 * Version:           1.0.0
 * Author:            Sazzad Mahmud
 * Author URI:        https://portfoliosazzad.web.app
 * License:           GPL-2.0+
 * License URI:       http://www.gnu.org/licenses/gpl-2.0.txt
 * Text Domain:       simple-membership
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if ( ! defined( 'WPINC' ) ) {
	die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define( 'SMP_VERSION', '1.0.0' );

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smp-activator.php
 */
function activate_smp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smp-activator.php';
	Smp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smp-deactivator.php
 */
function deactivate_smp() {
	require_once plugin_dir_path( __FILE__ ) . 'includes/class-smp-deactivator.php';
	Smp_Deactivator::deactivate();
}

register_activation_hook( __FILE__, 'activate_smp' );
register_deactivation_hook( __FILE__, 'deactivate_smp' );

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path( __FILE__ ) . 'includes/class-smp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smp() {

	$plugin = new Smp();
	$plugin->run();

}
run_smp();

class SimpleMembership{

    public function __construct(){
        add_action('plugins_loaded', array($this, 'smp_load_textdomain'));
        add_action('cmb2_init', array($this, 'smp_add_metabox'));

        add_filter('the_content', array($this, 'smp_premium_posts_content'));
    }

    function is_user_have_permition($user_id, $post_id){
        return $post_id = get_post($post_id);
        return $user_id = get_user_by('id', $user_id);
    }

    // function get_user_by_id( $user_id ) {
    //     return get_user_by( 'id', $user_id );
    // }

    function smp_premium_posts_content($content){
        global $post;
        $little_desc = implode(' ', array_slice(str_word_count($content, 1), 1, 20));
        $premium_post = get_post_meta(get_the_ID(), 'premium_post', true);
        if ($premium_post) {
            
            $little_desc = implode(' ', array_slice(str_word_count($content, 1), 1, 20));
            $post_link = '';

            if ( is_user_logged_in() ) {
                

                // $ss_content = implode(' ', array_slice(str_word_count($content, 1), 1, 20));
                // $premium_post_excerpt = $ss_content;
                // $post_link = get_permalink();
                // $read_more_btn = "<a href='{$post_link}' class='smp-read-more-btn'>Read More</a>";
                // return "<p>{$premium_post_excerpt}</p>". "<p>{$read_more_btn}</p>";
                
                // $post_id = get_post(get_the_ID());

                // $user = wp_get_current_user();  
	            // $user_roles = $user->roles[0];

                // $premium_users_field = get_post_meta(get_the_ID(), 'premium_users', true);
                // $premium_users = explode(',',  $premium_users_field );
                // var_dump($premium_users);


                /* foreach($premium_users as $premium_user){
                    $user = wp_get_current_user();
                    $user_id = $user->ID;
                    var_dump($premium_user);
                    var_dump($user_id);
                    if($premium_user == $user_id){
                        // $post_link = get_permalink();
                        return $content;
                        // return "Hello from Premium User";
                    }else{
                        return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                        // return "Hello from Normal User";
                    }
                } */

                // solve the problrm in "in_array" method 
                
                // $user = wp_get_current_user();
                // $user_id = strval($user->ID);
                // if(in_array($user_id, $premium_users)){
                //     return $content;
                // }else{
                //     return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                //     // return "Hello from Normal User";
                // } 
               
                
                   /*
                                // $user = wp_get_current_user();
                                $logged_in_user[] = strval(get_current_user_id());
                            foreach($logged_in_user as $current_user){
                                var_dump($logged_in_user);
                                foreach($premium_users as $premium_user){
                                    var_dump($premium_users);
                                    var_dump($premium_user);
                                    if($current_user == $premium_user){
                                        $post_link = get_permalink();
                                        // return "Hello from Premium User";
                                    }else{
                                        return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                                        // return "Hello from Normal User";
                                    }
                                }                    
                            } 
                    */

                // database is saving the ID number not the name
                $premium_users_name = get_post_meta(get_the_ID(), 'premium_users_name', true);
                $current_user = wp_get_current_user();
                $current_user_name = strval($current_user->display_name);
            
                if(in_array($current_user_name, $premium_users_name)){
                    return $content;
                }else{
                    return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                    // return "Hello from Normal User";
                }

            }
            else {
                $post_link = wp_login_url(get_permalink());
                $read_more_btn = "<a href='{$post_link}' class='read-more-btn'>Login</a>";
                return "<p>{$little_desc}</p>"."<p>If you want to read full content please - {$read_more_btn}</p>";
                // $post_link = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
            }
        } else {

        }
        return $content;
    }

    function smp_activation(){
        add_role('premiumm', 'premiumm');
    }

    function smp_deactivation(){
        remove_role('premiumm');
    }

    function smp_add_metabox(){

        $cmb = new_cmb2_box(array(
            'id'           => 'simple_membership',
            'title'        => __('Membership Category', 'simple-membership'),
            'object_types' => array('post'),
            'context'      => 'normal',
            'priority'     => 'default',
        ));

        $cmb->add_field(array(
            'name' => __('Premium', 'simple-membership'),
            'id' => 'premium_post',
            'type' => 'checkbox',
            'default' => false,
        ));

        $cmb->add_field(array(
            'name' => __('For All', 'simple-membership'),
            'id' => 'for_all',
            'type' => 'checkbox',
            'default' => false,
        ));

        $cmb->add_field(array(
            'name' => __('Premium Users', 'simple-membership'),
            'id' => 'premium_users',
            'type' => 'input',
            'default' => false,
        ));

        // $user = wp_get_current_user();
        //         $user_id = strval($user->ID);
        //         if(in_array($user_id, $premium_users)){
        //             return $content;
        //         }else{
        //             return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
        //             // return "Hello from Normal User";
        //         } 

        $all_users = get_users();
        $all_user = [];
        foreach($all_users as $user){
            $all_user[] = $user->display_name;
        }
        $username = array_combine($all_user, $all_user);
        
        $cmb->add_field(array(
            'name'    => 'Premium Users Name',
            'id'      => 'premium_users_name',
            'desc'    => 'Select Users. Drag to reorder.',
            'type'    => 'pw_multiselect',
            'options' => $username
        ));
        
    }

    function smp_load_textdomain(){
        load_plugin_textdomain('simple-membership', false, dirname(__FILE__) . "/languages");
    }
}

new SimpleMembership();