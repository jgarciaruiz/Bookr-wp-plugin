<?php
	if(isset($_POST['add'])){

        $id_user =  $_POST["id_user"];
        $user_login =  $_POST["user_login"];
        $user_email =  $_POST["user_email"];

        $codigo_libro = $_POST["codigo_libro"];

        global $wpdb;
        $db_librosactivos = $wpdb->prefix . 'bookr_register_form';
        
        //fecha actual y fecha dentro de 15 meses que es el límite de uso del libro
        date_default_timezone_set("Europe/Madrid");

        $fecha_activacion = date('Y-m-j H:i:s');
        $limite_meses = 15;
        $fecha_expiracion = strtotime ( '+'.$limite_meses.' month' , strtotime ( $fecha_activacion ) ) ;
        $fecha_expiracion = date ( 'Y-m-j H:i:s' , $fecha_expiracion );

        $wpdb->insert(
            $db_librosactivos, //table
            array('user_id' => $id_user,'user_login' => $user_login,'user_email' => $user_email,'codigo_libro' => $codigo_libro,'fecha_activacion' => $fecha_activacion,'fecha_expiracion' => $fecha_expiracion), //data
            array('%s','%s','%s','%s','%s','%s') //data format   (string,...)        
        );
	}

	
?>