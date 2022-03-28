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
 * License URI:       
 * Text Domain:       simple-membership
 * Domain Path:       /languages
 */

// If this file is called directly, abort.
if (!defined('WPINC')) {
    die;
}

/**
 * Currently plugin version.
 * Start at version 1.0.0 and use SemVer - https://semver.org
 * Rename this for your plugin and update it as you release new versions.
 */
define('SMP_VERSION', '1.0.0');

/**
 * The code that runs during plugin activation.
 * This action is documented in includes/class-smp-activator.php
 */
function activate_smp()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-smp-activator.php';
    Smp_Activator::activate();
}

/**
 * The code that runs during plugin deactivation.
 * This action is documented in includes/class-smp-deactivator.php
 */
function deactivate_smp()
{
    require_once plugin_dir_path(__FILE__) . 'includes/class-smp-deactivator.php';
    Smp_Deactivator::deactivate();
}

register_activation_hook(__FILE__, 'activate_smp');
register_deactivation_hook(__FILE__, 'deactivate_smp');

/**
 * The core plugin class that is used to define internationalization,
 * admin-specific hooks, and public-facing site hooks.
 */
require plugin_dir_path(__FILE__) . 'includes/class-smp.php';

/**
 * Begins execution of the plugin.
 *
 * Since everything within the plugin is registered via hooks,
 * then kicking off the plugin from this point in the file does
 * not affect the page life cycle.
 *
 * @since    1.0.0
 */
function run_smp()
{

    $plugin = new Smp();
    $plugin->run();
}
run_smp();

class SimpleMembership
{

    public function __construct()
    {
        // Action Hooks of Wordpress
        add_action('plugins_loaded', array($this, 'smp_load_textdomain'));
        add_action('cmb2_init', array($this, 'smp_add_metabox'));
        add_action('init', array($this, 'smp_custom_taxonomy_for_users_option'));
        add_action('admin_menu', array($this, 'smp_add_members_taxonomy_admin_page'));
        add_action('cmb2_admin_init', array($this, 'smp_register_taxonomy_metabox_field_for_members'));

        // Filter Hooks of Wordpress
        add_filter('the_content', array($this, 'smp_premium_posts_content'));
    }

    function is_user_have_permition($user_id, $post_id)
    {
        return $post_id = get_post($post_id);
        return $user_id = get_user_by('id', $user_id);
    }

    // function get_user_by_id( $user_id ) {
    //     return get_user_by( 'id', $user_id );
    // }

    function smp_users_have_permission()
    {
    }

    function smp_custom_taxonomy_for_users_option()
    {
        $labels = array(
            'name'                       => _x('Members', 'Members Name', 'simple-membership'),
            'singular_name'              => _x('Member', 'Member Name', 'simple-membership'),
            'menu_name'                  => __('Members', 'simple-membership'),
            'all_items'                  => __('All Members', 'simple-membership'),
            'parent_item'                => __('Parent Member', 'simple-membership'),
            'parent_item_colon'          => __('Parent Member:', 'simple-membership'),
            'new_item_name'              => __('New Member Name', 'simple-membership'),
            'add_new_item'               => __('Add Member', 'simple-membership'),
            'edit_item'                  => __('Edit Member', 'simple-membership'),
            'update_item'                => __('Update Member', 'simple-membership'),
            'view_item'                  => __('View Member', 'simple-membership'),
            'separate_items_with_commas' => __('Separate member with commas', 'simple-membership'),
            'add_or_remove_items'        => __('Add or remove members', 'simple-membership'),
            'choose_from_most_used'      => __('Choose from the most used', 'simple-membership'),
            'popular_items'              => __('Popular Members', 'simple-membership'),
            'search_items'               => __('Search Members', 'simple-membership'),
            'not_found'                  => __('Not Found', 'simple-membership'),
            'no_terms'                   => __('No Members', 'simple-membership'),
            'items_list'                 => __('Departments list', 'simple-membership'),
            'items_list_navigation'      => __('Members list navigation', 'simple-membership'),
        );

        $args = array(
            'labels'                     => $labels,
            'hierarchical'               => true,
            'public'                     => true,
            'show_ui'                    => true,
            'show_admin_column'          => true,
            'show_in_nav_menus'          => true,
            'show_tagcloud'              => true,
        );
        register_taxonomy('members', 'user', $args);
    }

    function smp_add_members_taxonomy_admin_page()
    {
        $tax = get_taxonomy('members');
        add_users_page(
            esc_attr($tax->labels->menu_name),
            esc_attr($tax->labels->menu_name),
            $tax->cap->manage_terms,
            'edit-tags.php?taxonomy=' . $tax->name
        );
    }

    function smp_register_taxonomy_metabox_field_for_members()
    {
        $cmb = new_cmb2_box(array(
            'id'               => 'edit',
            'title'            => esc_html__('Category Metabox', 'cmb2'),
            'object_types'     => array('term'),
            'taxonomies'       => array('members'),
            // 'new_term_section' => true, // Will display in the "Add New Category" section 
        ));

        $all_user = [];
        foreach (get_users() as $user) {
            $all_user[$user->ID] = $user->display_name;
        }

        $cmb->add_field(array(
            'name'    => __('Users', 'simple-membership'),
            'id'      => 'users_name',
            'desc'    => 'Select Users.',
            'type'    => 'pw_multiselect',
            'options' => $all_user
        ));
    }

    function smp_premium_posts_content($content)
    {
        global $post;
        $little_desc = implode(' ', array_slice(str_word_count($content, 1), 1, 20));
        $premium_post = get_post_meta(get_the_ID(), 'premium_post', true);
        if ($premium_post) {

            $little_desc = implode(' ', array_slice(str_word_count($content, 1), 1, 20));
            $post_link = '';

            if (is_user_logged_in()) {


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

                // showing data by users name from multiselect field
                // showing content basaed on the user name
                /* 
                        $premium_users_name = get_post_meta(get_the_ID(), 'premium_users_name', true);
                        $current_user = wp_get_current_user();
                        $current_user_name = strval($current_user->display_name);
                    
                        if(in_array($current_user_name, $premium_users_name)){
                            return $content;
                        }else{
                            return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                            // return "Hello from Normal User";
                        } 
                */


                $current_user = wp_get_current_user();
                $current_user_id = strval($current_user->ID);
                var_dump($current_user_id);

                $member_terms = get_terms(array(
                    'taxonomy' => 'members',
                    'hide_empty' => false,
                ));
                foreach ($member_terms as $term) {
                    $term_id[] = $term->term_id;
                }

                $users_type = get_post_meta(get_the_ID(), 'member_type', true);
                
                $all_selected_users_for_post = [];
                foreach ($users_type as $user_type) {
                    $term_meta_value = get_term_meta($user_type);
                    $term_users = $term_meta_value['users_name'];
                    $term_users_unserialize = unserialize($term_users[0]);
                    $all_selected_users_for_post[] = $term_users_unserialize;
                }

                foreach ($all_selected_users_for_post as $data) {
                    foreach ($data as $value) {
                        $new_selected_users[] = $value;
                    }
                }
                var_dump($new_selected_users);

                if(in_array($current_user_id, $new_selected_users)){
                    return $content;
                }else{
                    return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                }
            
                


                // For Premium Users
                // if (in_array('5', $users_type)) {
                //     if (in_array($current_user_id, $premium_users_id)) {
                //         echo "<h4>This Content For Premium Users</h4>";
                //         return $content;
                //     } else {
                //         return "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                //     }
                // }

                // // For Platinum Users
                // else if (in_array('6', $users_type)) {
                //     if (in_array($current_user_id, $platinum_users_id)) {
                //         echo "<h4>This Content For Platinum Users</h4>";
                //         return $content;
                //     } else {
                //         return "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                //     }
                // }

                // // For Gold Users
                // else if (in_array('7', $users_type)) {
                //     if (in_array($current_user_id, $gold_users_id)) {
                //         echo "<h4>This Content For Gold Users</h4>";
                //         return $content;
                //     } else {
                //         return "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                //     }
                // }





                // if(in_array($current_user_id, $premium_users_name_id)){
                //     return $content;
                // }else{
                //     return $content = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
                //     // return "Hello from Normal User";
                // }

                //





            } else {
                $post_link = wp_login_url(get_permalink());
                $read_more_btn = "<a href='{$post_link}' class='read-more-btn'>Login</a>";
                return "<p>{$little_desc}</p>" . "<p>If you want to read full content please - {$read_more_btn}</p>";
                // $post_link = "<p>Sorry!! you are not eligible for this content. Please contact with the administrator. <br> Our Gmail: <code>admin@gmail.com</code> <br> Call US: +8801688-536148.</p>";
            }
        } else {
        }
        return $content;
    }

    function smp_activation()
    {
        add_role('premiumm', 'premiumm');
    }

    function smp_deactivation()
    {
        remove_role('premiumm');
    }

    function smp_add_metabox()
    {

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

        // $all_users = get_users();
        $all_user = [];
        foreach (get_users() as $user) {
            $all_user[$user->ID] = $user->display_name;
        }
        //$username = array_combine($all_user, $all_user);
        // var_dump($all_user);
        // var_dump(array_keys($all_user));
        // All users ,multiselect field in post option
        /*  
            $cmb->add_field(array(
                    'name'    => 'Premium Users Name',
                    'id'      => 'premium_users_name',
                    'desc'    => 'Select Users. Drag to reorder.',
                    'type'    => 'pw_multiselect',
                    'options' => $all_user
                )); 
        */

        $member_types = get_terms(array(
            'taxonomy' => 'members',
            'hide_empty' => false,
        ));
        // var_dump($member_types);
        $term_types = $member_types[0]->name;
        // var_dump($term_types); 
        $member_type = [];
        foreach ($member_types as $mtype) {
            $member_type[$mtype->term_id] = $mtype->name;
        }

        $cmb->add_field(array(
            'name'    => __('Member Type', 'simple-membership'),
            'id'      => 'member_type',
            'desc'    => 'Select Member Type.',
            'type'    => 'pw_multiselect',
            'options' => $member_type
        ));
    }

    function smp_load_textdomain()
    {
        load_plugin_textdomain('simple-membership', false, dirname(__FILE__) . "/languages");
    }
}

new SimpleMembership();
