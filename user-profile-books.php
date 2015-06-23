<?php
function bookr_user_profile( $user ) { ?>
	<h3><?php _e("Códigos de tus libros", "blank"); ?></h3>
<?php $user_email = get_the_author_meta( 'user_email' ); ?>
	<?php 

		//traer la fecha de caducidad del libro introducido vía formulario de registro de la tabla "bookr_register_form"
	    $user_id = get_current_user_id(); 
	    //$user_id =  $_GET["user_id"];

	    global $wpdb;
		$dbtable = $wpdb->prefix . 'bookr_register_form';	
		$dbtable2 = $wpdb->prefix . 'bookr_book_keys';

		$user_id = get_current_user_id(); 
		$id = get_current_user_id(); 

		/* LEER DATOS REGISTRO ( fecha alta usuario + código registro primer libro ) */
		$rows = $wpdb->get_results( "SELECT * FROM $dbtable WHERE user_id = '$user_id'"); 

		foreach( $rows as $row ) {
			$user_login = $row->user_login;
			$user_email = $row->user_email;
			$codigo_libro = $row->codigo_libro;
			$fecha_activacion = $row->fecha_activacion;
			$fecha_expiracion = $row->fecha_expiracion;
		}


		/* LEER libros activados por el usuario */
		$rows2 = $wpdb->get_results( "SELECT * FROM $dbtable2 WHERE user_id = '$user_id'"); 

		foreach( $rows2 as $row2 ) {
			$codigo01 = $row2->codigo_l01;
			$codigo02 = $row2->codigo_l02;
			$codigo03 = $row2->codigo_l03;
			$codigo04 = $row2->codigo_l04;
			$codigo05 = $row2->codigo_l05;
			$codigo06 = $row2->codigo_l06;
			$codigo07 = $row2->codigo_l07;
			$codigo08 = $row2->codigo_l08;
			$codigo09 = $row2->codigo_l09;
			$codigo10 = $row2->codigo_l10;			
		}		


		/* INSERT datos alta nuevos libros */
		$codigo_l01 = $_POST["codigo_l01"];
		$codigo_l02 = $_POST["codigo_l02"];
		$codigo_l03 = $_POST["codigo_l03"];
		$codigo_l04 = $_POST["codigo_l04"];
		$codigo_l05 = $_POST["codigo_l05"];
		$codigo_l06 = $_POST["codigo_l06"];
		$codigo_l07 = $_POST["codigo_l07"];
		$codigo_l08 = $_POST["codigo_l08"];
		$codigo_l09 = $_POST["codigo_l09"];
		$codigo_l10 = $_POST["codigo_l10"];

		$fecha_activacion01 = $_POST["fecha_activacion01"];
		$fecha_activacion02 = $_POST["fecha_activacion02"];
		$fecha_activacion03 = $_POST["fecha_activacion03"];
		$fecha_activacion04 = $_POST["fecha_activacion04"];
		$fecha_activacion05 = $_POST["fecha_activacion05"];
		$fecha_activacion06 = $_POST["fecha_activacion06"];
		$fecha_activacion07 = $_POST["fecha_activacion07"];
		$fecha_activacion08 = $_POST["fecha_activacion08"];
		$fecha_activacion09 = $_POST["fecha_activacion09"];
		$fecha_activacion10 = $_POST["fecha_activacion10"];

		if(isset($_POST['insert'])){
		   	global $wpdb;
			$dbtable2 = $wpdb->prefix . 'bookr_book_keys';

			$wpdb->insert(
				$dbtable2, //table
				array('user_id' => $user_id,'user_login' => $user_login,'user_email' => $user_email,'codigo_l01' => $codigo_l01,'codigo_l02' => $codigo_l02,'codigo_l03' => $codigo_l03,'codigo_l04' => $codigo_l04,'codigo_l05' => $codigo_l05,'codigo_l06' => $codigo_l06,'codigo_l07' => $codigo_l07,'codigo_l08' => $codigo_l08,'codigo_l09' => $codigo_l09,'codigo_l10' => $codigo_l10, 'fecha_activacion01' => $fecha_activacion01, 'fecha_activacion02' => $fecha_activacion02, 'fecha_activacion03' => $fecha_activacion03, 'fecha_activacion04' => $fecha_activacion04, 'fecha_activacion05' => $fecha_activacion05, 'fecha_activacion06' => $fecha_activacion06, 'fecha_activacion07' => $fecha_activacion07, 'fecha_activacion08' => $fecha_activacion08, 'fecha_activacion09' => $fecha_activacion09, 'fecha_activacion10' => $fecha_activacion10), //data
				array('%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s','%s')//data format
			);
		}

	?>	
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />

	<?php
	if($_POST['insert']) {
			?>

			<div class="updated">
				<p>Lista de libros actualizada</p>
			</div>
			<a href="<?php echo admin_url('users.php?page')?>">&laquo; Volver al listado de tus libros</a>

	<?php }

	else {?>
	<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
		<table class="form-table perfil-usuario">
			<tr>
				<th><label for="codigo00">Código Alta usuario</label></th>
				<td>
					<input type="text" name="codigo00" id="codigo00" value="<?php echo $codigo_libro; ?>" class="regular-text" disabled="disabled" /><br />
					<div class="description indicator-hint">Disponible desde: <span id="fecha-activacion"><?php echo $fecha_activacion; ?></span> hasta: <span id="fecha-expiracion"><?php echo $fecha_expiracion ?></span> (15 meses)</div>
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 01</label></th>
				<td>
					<input type="text" name="codigo_l01" id="codigo_l01" value="<?php echo $codigo01; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l02">Código 02</label></th>
				<td>
					<input type="text" name="codigo_l02" id="codigo_l02" value="<?php echo $codigo02; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 03</label></th>
				<td>
					<input type="text" name="codigo_l03" id="codigo_l03" value="<?php echo $codigo03; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 04</label></th>
				<td>
					<input type="text" name="codigo_l04" id="codigo_l04" value="<?php echo $codigo04; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 05</label></th>
				<td>
					<input type="text" name="codigo_l05" id="codigo_l05" value="<?php echo $codigo05; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 06</label></th>
				<td>
					<input type="text" name="codigo_l06" id="codigo_l06" value="<?php echo $codigo06; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 07</label></th>
				<td>
					<input type="text" name="codigo_l07" id="codigo_l07" value="<?php echo $codigo07; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 08</label></th>
				<td>
					<input type="text" name="codigo_l08" id="codigo_l08" value="<?php echo $codigo08; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 09</label></th>
				<td>
					<input type="text" name="codigo_l09" id="codigo_l09" value="<?php echo $codigo09; ?>" class="regular-text" /><br />
				</td>
			</tr>
			<tr>
				<th><label for="codigo_l01">Código 10</label></th>
				<td>
					<input type="text" name="codigo_l10" id="codigo_l10" value="<?php echo $codigo10; ?>" class="regular-text" /><br />
				</td>
			</tr>																								
		</table>
		<input type='submit' name="insert" value='Guardar' class='button wp-core-ui button-primary'> &nbsp;&nbsp;
	</form>
	<?php }?>	
<?php }

