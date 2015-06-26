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
	$dbtable2 = $wpdb->prefix . 'bookr_register_form';
	$dbtable3 = $wpdb->prefix . 'bookr_book_keys';

		$charset_collate = $wpdb->get_charset_collate();

		$sql = "CREATE TABLE " . $dbtable . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_libro mediumtext NOT NULL,
			titulo mediumtext NOT NULL,
			url varchar(256) NOT NULL,
			thumbnail varchar(128) NOT NULL,
			categoria varchar(56) NOT NULL,			
			codigos varchar(512) NOT NULL,
			disponible tinytext NOT NULL,
			UNIQUE KEY id (id)
		);
		CREATE TABLE " . $dbtable2 . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			user_id mediumint(9) NOT NULL,
			user_login mediumtext NOT NULL,
			user_email mediumtext NOT NULL,
			codigo_libro mediumtext NOT NULL,
			fecha_activacion varchar(56) NOT NULL,
			fecha_expiracion varchar(56) NOT NULL,
			UNIQUE KEY id (id)
		);
		CREATE TABLE " . $dbtable3 . " (
			id mediumint(9) NOT NULL AUTO_INCREMENT,
			id_libro mediumtext NOT NULL,
			titulo mediumtext NOT NULL,
			url varchar(256) NOT NULL,
			thumbnail varchar(128) NOT NULL,																											
			categoria varchar(28) NOT NULL,																											
			codigo varchar(56) NOT NULL,																											
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
	$dbtable2 = $wpdb->prefix . 'bookr_register_form';
	$dbtable3 = $wpdb->prefix . 'bookr_book_keys';

	$wpdb->query("DROP TABLE IF EXISTS $dbtable");
	$wpdb->query("DROP TABLE IF EXISTS $dbtable2");
	$wpdb->query("DROP TABLE IF EXISTS $dbtable3");	

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


	add_users_page(
         'Tus Libros' //page_title
        ,'Tus Libros' //menu_title
        ,'read' //capability
        ,$current_user->user_login //menu_slug
        ,'bookr_user_profile' //function
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

function my_myme_types($mime_types){
	//http://www.freeformatter.com/mime-types-list.html
    $mime_types['svg'] = 'image/svg+xml';
    $mime_types['csv'] = 'text/csv';
    return $mime_types;
}
add_filter('upload_mimes', 'my_myme_types', 1, 1);



define('ROOTDIR', plugin_dir_path(__FILE__));

//formulario registro
require_once(ROOTDIR . 'user-register.php');

//templates para el front
require_once(ROOTDIR . 'load-tpl.php');

//CRUD libros
require_once(ROOTDIR . 'books-list.php');
require_once(ROOTDIR . 'books-create.php');
require_once(ROOTDIR . 'books-update.php');

//usuarios y códigos de libro
require_once(ROOTDIR . 'user-profile.php'); //para que el admin pueda editar los codigos de los usuarios si es necesario
require_once(ROOTDIR . 'user-profile-books.php'); //panel para que usuarios añadan sus libros


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


//redirect to login page if not logged in (tiene sentido usar esta funcion si toda la web será de acceso privado)
/*
function redirect_user() {
  if ( ! is_user_logged_in() && ! is_page( 'login' ) ) {
    $return_url = esc_url( home_url( '/wp-admin/' ) );
    wp_redirect( $return_url );
    exit;
  }
}
add_action( 'template_redirect', 'redirect_user' );
*/


//obligar estar logado en determinadas páginas (perfil usuario, lista libros, subir libros...)
function private_page_redirect_user(){
	if( ! is_user_logged_in() && is_page("listado-libros")){
	    wp_redirect( site_url( '/wp-admin' ) ); 
	    exit();
	}
	}
add_action( 'template_redirect', 'private_page_redirect_user');


