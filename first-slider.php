<?php
/**
 * Plugin Name: First Slider
 * Plugin URI:
 * Description: First slider slider is created with good back-end and front-end UI.
 * Version: 1.0
 * Author: Jigar Kahar
 * Author URI:
 **/
function register_custom_menu_page()
{
    $menu = add_menu_page(
        'first slider title',
        'First Slider',
        'add_users',
        'firstslider',
        '_first_slider_home_page',
        null,
        6
    );
    add_action('admin_print_styles-' . $menu, 'wpb_adding_styles');
    add_action('admin_print_scripts-' . $menu, 'wpb_adding_scripts');
}
add_action('admin_menu', 'register_custom_menu_page');
function wpb_adding_scripts()
{
    wp_enqueue_media();
    wp_enqueue_script('script', '/wp-content/plugins/first-slider/js/slider_setting.js', array('jquery'), 1.1, true);
    wp_localize_script('script', 'myAjax', array('ajaxurl' => admin_url('admin-ajax.php')));
}
function wpb_adding_styles()
{
    wp_enqueue_style('style','/wp-content/plugins/first-slider/css/be_style.css');
}
function wpb_adding_front_scripts() {
    wp_register_script('front_script', '/wp-content/plugins/first-slider/js/fe_slider_setting.js', array('jquery'),1.1,
        true);
    wp_enqueue_script('front_script');
    wp_register_script('slick_script', 'https://cdn.jsdelivr.net/jquery.slick/1.3.15/slick.min.js', array('jquery'),
        1.1,
        true);
    wp_enqueue_script('slick_script');
}
add_action( 'wp_enqueue_scripts', 'wpb_adding_front_scripts' );
function wpb_adding_front_styles() {
    wp_enqueue_style('front_stylesheet','/wp-content/plugins/first-slider/css/fe_style.css');
    wp_enqueue_style('slick_stylesheet','https://cdn.jsdelivr.net/jquery.slick/1.3.15/slick.css');
}
add_action( 'wp_enqueue_scripts', 'wpb_adding_front_styles' );
function _first_slider_home_page()
{
    require dirname(__FILE__) . "\html\slider_setting.php";
}
require dirname(__FILE__) . "\html\get_siders.php";

//add_action('init', 'wpb_adding_styles');

add_action("wp_ajax_nopriv_create_slider", "create_slider");
add_action("wp_ajax_create_slider", "create_slider");
function create_slider()
{
    require dirname(__FILE__) . "\ajax\slider_setting_ajax.php";
}

function my_handle_attachment($file_handler, $post_id, $set_thu = false)
{
    // check to make sure its a successful upload
    if ($_FILES[$file_handler]['error'] !== UPLOAD_ERR_OK) {
        __return_false();
    }

    require_once(ABSPATH . "wp-admin" . '/includes/image.php');
    require_once(ABSPATH . "wp-admin" . '/includes/file.php');
    require_once(ABSPATH . "wp-admin" . '/includes/media.php');

    $attach_id = media_handle_upload($file_handler, $post_id);
    if (is_numeric($attach_id)) {
        update_post_meta($post_id, '_my_file_upload', $attach_id);
    }
    return $attach_id;
}

function create_plugin_database_table()
{
    global $wpdb;
    $table_name = $wpdb->prefix . "first_slider";
    $my_products_db_version = '1.0.0';
    $charset_collate = $wpdb->get_charset_collate();

    if ( $wpdb->get_var( "SHOW TABLES LIKE '{$table_name}'" ) != $table_name ) {
        $table_name = $wpdb->prefix ."first_slider";
        $sql = "CREATE TABLE $table_name (slider_id INT(20) NOT NULL AUTO_INCREMENT,
                                        slider_sequence TEXT NULL,
                                        slider_name TEXT NULL,
                                        slider_status INT(2) DEFAULT 0,
                                        PRIMARY KEY (slider_id))";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
        $table_name = $wpdb->prefix ."first_slider_imgs";
        $sql = "CREATE TABLE $table_name (slider_img_id INT(20) NOT NULL AUTO_INCREMENT,
                                            slider_id INT(20) NOT NULL,
                                            post_id INT(20) NOT NULL,
                                            PRIMARY KEY (slider_img_id))";
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($sql);
    }
}
register_activation_hook( __FILE__, 'create_plugin_database_table' );

function pu_insert_custom_table(){
    global $wpdb;
    $welcome_id = "1";
    $table_name = $wpdb->prefix ."first_slider";
    $rows_affected = $wpdb->insert( $table_name, array( 'slider_id' => $welcome_id));
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($rows_affected);
    $upload_dir =  ABSPATH."/wp-content/plugins/first-slider/demosimg";
//     echo $upload_dir;
    $dir = opendir($upload_dir);
    if(is_resource($dir))
    {
        while (false !== ($file = readdir($dir))) {
            if ($file != "." && $file != "..") {
                $images[] = $file;
            }
        }
        // closing the directory
        closedir($dir);
    }
    $slider_sequence = array();
    foreach ($images as $image) {
        $imgurl = plugins_url()."/first-slider/demosimg/".$image;
        $table_name = $wpdb->prefix ."posts";
        $rows_affected = $wpdb->insert( $table_name, array( 'guid' => $imgurl));
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($rows_affected);

        $lastid = $wpdb->insert_id;
        array_push($slider_sequence,$lastid);
        $table_name = $wpdb->prefix ."first_slider_imgs";
        $inserted_img_postid = $wpdb->insert( $table_name, array( 'slider_id' => "1", "post_id" => $lastid));
        require_once ABSPATH . 'wp-admin/includes/upgrade.php';
        dbDelta($inserted_img_postid);
    }
    $serializeslider = serialize($slider_sequence);
    $table_name = $wpdb->prefix ."first_slider";
    $result = $wpdb->update($table_name, array('slider_sequence' => $serializeslider),array('slider_id' => "1"));
    require_once ABSPATH . 'wp-admin/includes/upgrade.php';
    dbDelta($result);


    
}
register_activation_hook( __FILE__, 'pu_insert_custom_table' );


