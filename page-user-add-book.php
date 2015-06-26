<?php

    global $wpdb;

	$user_ID = get_current_user_id();
	$user_info = get_userdata($user_ID);

	$id_user =  $user_info->ID;
	$user_login =  $user_info->user_login;
	$user_email =  $user_info->user_email;


	if ( isset($_POST['add']) && !empty($_POST['codigo_libro']) ) {
		$codigolibro = $_POST['codigo_libro'];
		$dbtable = $wpdb->prefix . 'bookr_register_form';
		
		//ontengo de la bbdd cuántas veces aparece el código enviado por el usuario (codigo_enviado es un alias para poder leer el array de resultados var_dump para debuggearlo)
		$rows = $wpdb->get_results( "SELECT COUNT(*) as codigo_enviado FROM $dbtable WHERE codigo_libro = '$codigolibro' AND user_id = '$id_user'");
		foreach($rows as $row) {
			$total_rows = $row->codigo_enviado;
		}

		//si el código aparece 0 veces grabo el nuevo código
		if($total_rows == 0) {
	        $id_user =  $_POST["id_user"];
	        $user_login =  $_POST["user_login"];
	        $user_email =  $_POST["user_email"];

	        $codigo_libro = $_POST["codigo_libro"];

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
		else{
	        $warning.="El código introducido ya se está usando.";			
		}

	}

?>
<?php get_header(); ?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-user-libros.css" rel="stylesheet" />

	<div id="primary" class="content-area">
		<main id="main" class="site-main" role="main">
			<article id="post-1" class="post type-post format-standard hentry">
				<header class="entry-header">
					<h1 class="entry-title">Tus libros</h1>
				</header>

				<div class="entry-content lista-libros">
					<div class="libros-profesor">
						<h2>Profesor</h2>
						<?php
							$dbtable = $wpdb->prefix . 'bookr_admin';
							$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'profesor'");
							foreach( $rows as $row ) {
								echo "<div id='user-$row->user_id'>";
									echo "<div>$row->codigo_libro</div>";	
									echo "<div>$row->codigo</div>";	
									echo "<div>$row->fecha_activacion</div>";	
									echo "<div>$row->fecha_expiracion</div>";																	
									echo "<div>$row->id_libro</div>";									
									echo "<div>$row->titulo</div>";									
									echo "<div>$row->thumbnail</div>";	
									echo "<div>$row->url</div>";	
									echo "<div>$row->categoria</div>";	
								echo "</div>";
							}

						?>
					</div>

					<div class="libros-alumno">					
						<h2>Alumno</h2>						
							<?php
							$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'alumno'");

							foreach( $rows as $row ) {
								echo "<div id='user-$row->user_id'>";
									echo "<div>$row->codigo_libro</div>";	
									echo "<div>$row->codigo</div>";	
									echo "<div>$row->fecha_activacion</div>";	
									echo "<div>$row->fecha_expiracion</div>";																	
									echo "<div>$row->id_libro</div>";									
									echo "<div>$row->titulo</div>";									
									echo "<div>$row->thumbnail</div>";	
									echo "<div>$row->url</div>";	
									echo "<div>$row->categoria</div>";	
								echo "</div>";
							}
						?>
					</div>

					<div class="libros-digital">					
						<h2>Digital</h2>						
							<?php
							$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'digital'");

							foreach( $rows as $row ) {
								echo "<div id='user-$row->user_id'>";
									echo "<div>$row->codigo_libro</div>";	
									echo "<div>$row->codigo</div>";	
									echo "<div>$row->fecha_activacion</div>";	
									echo "<div>$row->fecha_expiracion</div>";																	
									echo "<div>$row->id_libro</div>";									
									echo "<div>$row->titulo</div>";									
									echo "<div>$row->thumbnail</div>";	
									echo "<div>$row->url</div>";	
									echo "<div>$row->categoria</div>";	
								echo "</div>";
							}
						?>
					</div>

					<div class="libros-online">					
						<h2>Online</h2>						
							<?php
							$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'online'");

							foreach( $rows as $row ) {
								echo "<div id='user-$row->user_id'>";
									echo "<div>$row->codigo_libro</div>";	
									echo "<div>$row->codigo</div>";	
									echo "<div>$row->fecha_activacion</div>";	
									echo "<div>$row->fecha_expiracion</div>";																	
									echo "<div>$row->id_libro</div>";									
									echo "<div>$row->titulo</div>";									
									echo "<div>$row->thumbnail</div>";	
									echo "<div>$row->url</div>";	
									echo "<div>$row->categoria</div>";	
								echo "</div>";
							}
						?>
					</div>

				</div>


				<header class="entry-header">
					<h1 class="entry-title">Añade un libro</h1>
				</header>

				<div class="entry-content registrar-libro">

					<?php if (isset($message)): ?>
						<div class="book-added">
							<p><?php echo $message;?></p>
						</div>
					<?php endif;?>

					<?php if (isset($warning)): ?>
						<div class="codigo-aviso">
							<p><?php echo $warning;?></p>
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
