<?php

    global $wpdb;

	$user_ID = get_current_user_id();
	$user_info = get_userdata($user_ID);

	$id_user =  $user_info->ID;
	$user_login =  $user_info->user_login;
	$user_email =  $user_info->user_email;


	if ( isset($_POST['add']) && !empty($_POST['codigo_libro']) ) {
		$codigolibro = $_POST['codigo_libro'];

		//compruebo que el código que introduce el usuario existe en la BBDD
		$dbtable = $wpdb->prefix . 'bookr_book_keys';
		$rows = $wpdb->get_results( "SELECT COUNT(*) as codigo_enviado FROM $dbtable WHERE codigo = '$codigolibro'");
		foreach($rows as $row) {
			$total_rows = $row->codigo_enviado;
		}

		//el código no existe
		if($total_rows == 0) {
			$warning.="El código introducido no es correcto";
		}

		//si el código valida grabo datos si no han sido grabados previamente por alguien
		if($total_rows > 0) {
			
			//obtengo de la bbdd cuántas veces aparece el código enviado por el usuario (codigo_enviado es un alias para poder leer el array de resultados var_dump para debuggearlo)
			$dbtable = $wpdb->prefix . 'bookr_register_form';
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





	}

?>

	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-user-libros.css" rel="stylesheet" />


<div class="listalibros-usuario">

	<div class="ficha-usuariowr">
		<h2 class="ficha-titulo"><span class="small">Bienvenido a </span>Bookr</h2>
		<div class="userinfo right">
			<div class="left perfilpic">
				<?php echo get_avatar($id_user, 50) ?>
			</div>
			<div class="right perfildata">
				<span class="name"><?php echo $user_login ?></span>
				<span class="mail"><?php echo $user_email ?></span>
			</div>
			<div class="clearfix"></div>
		</div>
		<div class="clearfix"></div>
		
		<form action="<?php echo $_SERVER['REQUEST_URI']; ?>" id="front-add-book" method="POST">				
			<div class="add-librowr">
				<p class="icon-pencil left">Añade un libro</p>
				<div class="submitwr right">
					<button type="submit" name="add" class="right btn-add-libro">Añadir libro</button>
				    <input type="hidden" name="id_user" value="<?php echo $user_ID ?>">
				    <input type="hidden" name="user_login" value="<?php echo $user_login ?>">
				    <input type="hidden" name="user_email" value="<?php echo $user_email ?>">								
					<div class="clearfix"></div>
				</div>
				<div class="clibrowr right">
					<input type="text" name="codigo_libro" id="codigo-libro" class="required">
				</div>						
				<div class="clearfix"></div>
			</div>
		</form>

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

	</div><!-- /ficha-usuariowr -->
	
	<div class="lista-libros">
		<div class="libros-profesor">
			<h2 class="ficha-titulo"><span class="small">Tus libros </span>Categoria Alumno</h2>
			<?php
				$dbtable = $wpdb->prefix . 'bookr_admin';
				$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'alumno'");
				foreach( $rows as $row ) {
					$bookcode = $row->codigo_libro;
					$thecode = $row->codigo;
					$activation = $row->fecha_activacion;
					$expiration = $row->fecha_expiracion;
					$bookid = $row->id_libro;
					$booktitle = $row->titulo;
					$bookcover = $row->thumbnail;
					$bookurl = $row->url;
					$bookcategory = $row->categoria;


					//si la fecha actual es menor a la de caducidad del libro, muestro el acceso al libro	
					// convierto a segundos la fecha de hoy ( time()-(60*60*24) ) y luego lo comparo con la fecha de caducidad convertida en segundos strtotime($expiration)			
					if((time()-(60*60*24)) < strtotime($expiration)){
						echo '
						<div class="libro" data-bookcode="'.$thecode.'">
							<div class="portada left">
								<img src="'.$bookcover.'" class="portadapic" alt="libro">
							</div>
							<div class="datos left">
								<h4 class="titulo">'.$booktitle.'</h4>
								<div class="expiracion">Válido hasta <span>'.date('d-m-Y', strtotime($expiration)).'</span></div>
								<a href="'.$bookurl.'" class="btn red-rounded">Acceder al contenido</a>
							</div>
							<div class="clearfix"></div>
						</div><!-- /libro -->
						';

					}
				}
			?>
			<div class="clearfix"></div>	
		</div>	<!-- /libros-alumno -->

		<div class="libros-profesor">
			<h2 class="ficha-titulo"><span class="small">Tus libros </span>Categoría Profesor</h2>
			<?php
				$dbtable = $wpdb->prefix . 'bookr_admin';
				$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'profesor'");
				foreach( $rows as $row ) {
					$bookcode = $row->codigo_libro;
					$thecode = $row->codigo;
					$activation = $row->fecha_activacion;
					$expiration = $row->fecha_expiracion;
					$bookid = $row->id_libro;
					$booktitle = $row->titulo;
					$bookcover = $row->thumbnail;
					$bookurl = $row->url;
					$bookcategory = $row->categoria;


					//si la fecha actual es menor a la de caducidad del libro, muestro el acceso al libro	
					// convierto a segundos la fecha de hoy ( time()-(60*60*24) ) y luego lo comparo con la fecha de caducidad convertida en segundos strtotime($expiration)			
					if((time()-(60*60*24)) < strtotime($expiration)){
						echo '
						<div class="libro" data-bookcode="'.$thecode.'">
							<div class="portada left">
								<img src="'.$bookcover.'" class="portadapic" alt="libro">
							</div>
							<div class="datos left">
								<h4 class="titulo">'.$booktitle.'</h4>
								<div class="expiracion">Válido hasta <span>'.date('d-m-Y', strtotime($expiration)).'</span></div>
								<a href="'.$bookurl.'" class="btn red-rounded">Acceder al contenido</a>
							</div>
							<div class="clearfix"></div>
						</div><!-- /libro -->
						';

					}
				}
			?>
			<div class="clearfix"></div>	
		</div>	<!-- /libros-profesor -->

		<div class="libros-digitales">
			<h2 class="ficha-titulo"><span class="small">Tus libros </span>Categoría Digitales</h2>
			<?php
				$dbtable = $wpdb->prefix . 'bookr_admin';
				$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'digital'");
				foreach( $rows as $row ) {

					$bookcode = $row->codigo_libro;
					$thecode = $row->codigo;
					$activation = $row->fecha_activacion;
					$expiration = $row->fecha_expiracion;
					$bookid = $row->id_libro;
					$booktitle = $row->titulo;
					$bookcover = $row->thumbnail;
					$bookurl = $row->url;
					$bookcategory = $row->categoria;


					//si la fecha actual es menor a la de caducidad del libro, muestro el acceso al libro	
					// convierto a segundos la fecha de hoy ( time()-(60*60*24) ) y luego lo comparo con la fecha de caducidad convertida en segundos strtotime($expiration)			
					if((time()-(60*60*24)) < strtotime($expiration)){
						echo '
						<div class="libro" data-bookcode="'.$thecode.'">
							<div class="portada left">
								<img src="'.$bookcover.'" class="portadapic" alt="libro">
							</div>
							<div class="datos left">
								<h4 class="titulo">'.$booktitle.'</h4>
								<div class="expiracion">Válido hasta <span>'.date('d-m-Y', strtotime($expiration)).'</span></div>
								<a href="'.$bookurl.'" class="btn red-rounded">Acceder al contenido</a>
							</div>
							<div class="clearfix"></div>
						</div><!-- /libro -->
						';

					}
				}
			?>
			<div class="clearfix"></div>	
		</div>	<!-- /libros-digitales -->

		<div class="libros-online">
			<h2 class="ficha-titulo"><span class="small">Tus libros </span>Cateogría Moodle</h2>
			<?php
				$dbtable = $wpdb->prefix . 'bookr_admin';
				$rows = $wpdb->get_results( "SELECT wp_bookr_register_form.user_id, wp_bookr_register_form.codigo_libro,  wp_bookr_book_keys.codigo, wp_bookr_register_form.fecha_activacion, wp_bookr_register_form.fecha_expiracion, wp_bookr_book_keys.id_libro, wp_bookr_book_keys.titulo, wp_bookr_book_keys.url, wp_bookr_book_keys.thumbnail, wp_bookr_book_keys.categoria FROM wp_bookr_register_form INNER JOIN wp_bookr_book_keys ON wp_bookr_register_form.codigo_libro = wp_bookr_book_keys.codigo WHERE user_id = '$id_user' AND categoria = 'online'");
				foreach( $rows as $row ) {
					$bookcode = $row->codigo_libro;
					$thecode = $row->codigo;
					$activation = $row->fecha_activacion;
					$expiration = $row->fecha_expiracion;
					$bookid = $row->id_libro;
					$booktitle = $row->titulo;
					$bookcover = $row->thumbnail;
					$bookurl = $row->url;
					$bookcategory = $row->categoria;

					//si la fecha actual es menor a la de caducidad del libro, muestro el acceso al libro	
					// convierto a segundos la fecha de hoy ( time()-(60*60*24) ) y luego lo comparo con la fecha de caducidad convertida en segundos strtotime($expiration)			
					if((time()-(60*60*24)) < strtotime($expiration)){
						echo '
						<div class="libro" data-bookcode="'.$thecode.'">
							<div class="portada left">
								<img src="'.$bookcover.'" class="portadapic" alt="libro">
							</div>
							<div class="datos left">
								<h4 class="titulo">'.$booktitle.'</h4>
								<div class="expiracion">Válido hasta <span>'.date('d-m-Y', strtotime($expiration)).'</span></div>
								<a href="'.$bookurl.'" class="btn red-rounded">Acceder al contenido</a>
							</div>
							<div class="clearfix"></div>
						</div><!-- /libro -->
						';

					}
				}
			?>
			<div class="clearfix"></div>	
		</div>	<!-- /libros-online -->

	</div><!-- /lista-libros -->

</div><!-- /listalibros-usuario -->

