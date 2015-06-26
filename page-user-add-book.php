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
        $message.="Libro añadido";
	}

?>
<?php get_header(); ?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-user-libros.css" rel="stylesheet" />

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-1" class="post type-post format-standard hentry">

				<header class="entry-header">
					<h1 class="entry-title">Añade un libro nuevo</h1>
				</header>
				<?php
					$user_ID = get_current_user_id();
					$user_info = get_userdata($user_ID);

					$id_user =  $user_info->ID;
					$user_login =  $user_info->user_login;
					$user_email =  $user_info->user_email;

				?>
				<div class="entry-content">
				
					<?php if (isset($message)): ?>
						<div class="book-added">
							<p><?php echo $message;?></p>
						</div>
					<?php endif;?>

					<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="front-add-book" method="POST">
					    <fieldset>
					        <label for="codigo-libr"><?php _e('Codigo libro:') ?></label>
					        <input type="text" name="codigo_libro" id="codigo-libro" class="required" />
					    </fieldset>
					    <input type="hidden" name="id_user" value="<?php echo $user_ID ?>">
					    <input type="hidden" name="user_login" value="<?php echo $user_login ?>">
					    <input type="hidden" name="user_email" value="<?php echo $user_email ?>">
					    <fieldset>					 
					        <button type="submit" name="add" style="margin-top:20px;"><?php _e('Enviar') ?></button>
					    </fieldset>
					</form>
				</div>

			</article>
		</main>
	</div>

<?php get_footer(); ?>
