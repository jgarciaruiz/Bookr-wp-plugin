<?php # -*- coding: utf-8 -*-
/**
 * Plugin Name: Bookr
 * Description: Plugin for easy management of books. Each book is assigned a number key. Users use book keys for unlocking different books. Also, each unlocked book will be accessible for the user for the following 15 months.
 * Plugin URI:  http://jgarciaruiz.es
 * Version:     1.0.0
 * Author:      Javier García Ruiz
 * Author URI:  http://jgarciaruiz.es
 * License:     MIT
 * License URI: http://www.opensource.org/licenses/mit-license.php
 */

// prevent direct access of plugin files by a user
if(!defined('ABSPATH'))
    die('No tienes permisos de acceso directo a este archivo.'); 


// function to create the DB / Options / Defaults					
function install_bookr() {
   	global $wpdb;
	$dbtable = $wpdb->prefix . 'bookr_admin';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE " . $dbtable . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_libro mediumtext NOT NULL,
			titulo mediumtext NOT NULL,
			url varchar(256) NOT NULL,
			thumbnail varchar(128) NOT NULL,
			codigos varchar(512) NOT NULL,
			disponible tinytext NOT NULL,
			UNIQUE KEY id (id)
		)$charset_collate;";

 		//update db structure if new cols added
		require_once(ABSPATH . 'wp-admin/includes/upgrade.php');
		dbDelta($sql);
 
}
register_activation_hook(__FILE__,'install_bookr');
//install_bookr();
   	

//uninstall delete plugin table from db
function uninstall_bookr() {
	global $wpdb;
	$dbtable = $wpdb->prefix . 'bookr_admin';
	$wpdb->query("DROP TABLE IF EXISTS $dbtable");
}
register_uninstall_hook( __FILE__, 'uninstall_bookr' );


//menu items
add_action('admin_menu','bookr_modificar_menu');
function bookr_modificar_menu() {
	
	//this is the main item for the menu
	add_menu_page(
		'Bookr items', //page title
		'Bookr items', //menu title
		'manage_options', //capabilities
		'bookr_list_items', //menu slug
		bookr_list_items, //function
		null,//icon
		8//position in menu
	);
	
	//this is a submenu
	add_submenu_page(
		'bookr_list_items', //parent slug
		'Add new item', //page title
		'Add new', //menu title
		'manage_options', //capability
		'bookr_create', //menu slug
		'bookr_create' //function
	);
	
	//this submenu is HIDDEN, however, we need to add it anyways
	add_submenu_page(
		null, //parent slug
		'Update item', //page title
		'Update', //menu title
		'manage_options', //capability
		'bookr_update', //menu slug
		'bookr_update' //function
	); 
}

add_action( 'admin_head', 'bookr_menu_icon' );
function bookr_menu_icon() {
?>
    <style>
    #toplevel_page_bookr_list_items > a > div.wp-menu-image.dashicons-before.dashicons-admin-generic:before{
    	content: "\f330";
    }
    </style>
<?php
}

define('ROOTDIR', plugin_dir_path(__FILE__));
require_once(ROOTDIR . 'books-list.php');
require_once(ROOTDIR . 'books-create.php');
require_once(ROOTDIR . 'books-update.php');
require_once(ROOTDIR . 'user-profile.php');







//script admin dash para llamar al media uploader de WP
add_action('admin_enqueue_scripts', 'my_admin_scripts');
function my_admin_scripts() {
	//añado el script de subida de archivo solo a las páginas del plugin crear y Update
    if (isset($_GET['page']) && $_GET['page'] == 'bookr_create' || isset($_GET['page']) && $_GET['page'] == 'bookr_update') {
        wp_enqueue_media();
        wp_register_script('media-uploader-js', WP_PLUGIN_URL.'/bookr/js/media-uploader.js', array('jquery'));
        wp_enqueue_script('media-uploader-js');
    }
}


//
