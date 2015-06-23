<?php

/* añadir varios inputs (equivalentes a meta_key en la tabla wp_usermeta) a la página de perfil de usuario */
add_action( 'show_user_profile', 'edelsa_admin_codigoslibros_perfilusuario' );
add_action( 'edit_user_profile', 'edelsa_admin_codigoslibros_perfilusuario' );

function edelsa_admin_codigoslibros_perfilusuario( $user ) { 

	//condicional para mostrar a los ADMIN listado de código activos de los usuarios en la página de Tu Perfil
    if( current_user_can('administrator') ) {

	    //$user_id = get_current_user_id(); 
	    global $wpdb;
		$dbtable = $wpdb->prefix . 'bookr_register_form';		
		$rows = $wpdb->get_results( "SELECT * FROM $dbtable"); 

		foreach( $rows as $row ) {
			$user_id =	$row->user_id;	
			$user_login = $row->user_login;
			$user_email = $row->user_email;
			$codigo_libro = $row->codigo_libro;
			$fecha_activacion = $row->fecha_activacion;
			$fecha_expiracion = $row->fecha_expiracion;
		}


	    //definir si $user_id se obtiene por $_GET o por get_current_user_id()
		if ( current_user_can( 'manage_options' ) == false ) {
		    $user_id = get_current_user_id(); 
		} else {
		     $user_id =  $_GET["user_id"];
		}


		/* LEER libros activados por el usuario */
		$dbtable2 = $wpdb->prefix . 'bookr_book_keys';

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


    ?>
    	<h3><?php _e("Códigos de tus libros", "blank"); ?></h3>

		<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />

		<table class="form-table perfil-usuario">
			<tr>
				<th><label for="codigo00">Código Alta usuario <?php echo  $user_id; ?></label></th>
				<td>
					<input type="text" name="codigo00" id="codigo00" value="<?php echo esc_attr( get_the_author_meta( 'codigo_libro', $user->ID ) );//código insertado durante el formulario de registro ?>" class="regular-text" /><br />
					<div class="description indicator-hint">Disponible desde: <span id="fecha-activacion"><?php echo $fecha_activacion; ?></span> hasta: <span id="fecha-expiracion"><?php echo $fecha_expiracion ?></span> (15 meses)</div>
				</td>
			</tr>
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

	<?php
	} //fin condicional ADMIN
	?>


<?php }



