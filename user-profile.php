<?php

/* añadir varios inputs (equivalentes a meta_key en la tabla wp_usermeta) a la página de perfil de usuario */
add_action( 'show_user_profile', 'bookr_user_profile' );
add_action( 'edit_user_profile', 'bookr_user_profile' );

function bookr_user_profile( $user ) { ?>
	<h3><?php _e("Códigos de tus libros", "blank"); ?></h3>

	<?php 

		//traer la fecha de caducidad del libro introducido vía formulario de registro de la tabla "bookr_register_form"
	    //$user_id = get_current_user_id(); 
	    $user_id =  $_GET["user_id"];

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
	?>	

	<table class="form-table perfil-usuario">
		<tr>
			<th><label for="codigo00">Código 00</label></th>
			<td>
				<input type="text" name="codigo00" id="codigo00" value="<?php echo esc_attr( get_the_author_meta( 'codigo_libro', $user->ID ) );//código insertado durante el formulario de registro ?>" class="regular-text" /><br />
				<div class="description indicator-hint">Disponible desde: <span id="fecha-activacion"><?php echo $fecha_activacion; ?></span> hasta: <span id="fecha-expiracion"><?php echo $fecha_expiracion ?></span> (15 meses)</div>
			</td>
		</tr>
		<tr>
			<th><label for="codigo01">Código 01</label></th>
			<td>
				<input type="text" name="codigo01" id="codigo01" value="<?php echo esc_attr( get_the_author_meta( 'codigo01', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo02">Código 02</label></th>
			<td>
				<input type="text" name="codigo02" id="codigo02" value="<?php echo esc_attr( get_the_author_meta( 'codigo02', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo03">Código 03</label></th>
			<td>
				<input type="text" name="codigo03" id="codigo03" value="<?php echo esc_attr( get_the_author_meta( 'codigo03', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo04">Código 04</label></th>
			<td>
				<input type="text" name="codigo04" id="codigo04" value="<?php echo esc_attr( get_the_author_meta( 'codigo04', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 05</label></th>
			<td>
				<input type="text" name="codigo05" id="codigo05" value="<?php echo esc_attr( get_the_author_meta( 'codigo05', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 06</label></th>
			<td>
				<input type="text" name="codigo06" id="codigo06" value="<?php echo esc_attr( get_the_author_meta( 'codigo06', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 07</label></th>
			<td>
				<input type="text" name="codigo07" id="codigo07" value="<?php echo esc_attr( get_the_author_meta( 'codigo07', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 08</label></th>
			<td>
				<input type="text" name="codigo08" id="codigo08" value="<?php echo esc_attr( get_the_author_meta( 'codigo08', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 09</label></th>
			<td>
				<input type="text" name="codigo09" id="codigo09" value="<?php echo esc_attr( get_the_author_meta( 'codigo09', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
		<tr>
			<th><label for="codigo05">Código 10</label></th>
			<td>
				<input type="text" name="codigo10" id="codigo10" value="<?php echo esc_attr( get_the_author_meta( 'codigo10', $user->ID ) ); ?>" class="regular-text" /><br />
			</td>
		</tr>
	</table>
<?php }

/* guardar info de los campos añadidos al perfil de usuario */
add_action( 'personal_options_update', 'save_bookr_user_profile' );
add_action( 'edit_user_profile_update', 'save_bookr_user_profile' );
function save_bookr_user_profile( $user_id ) {

	if ( !current_user_can( 'edit_user', $user_id ) ) { return false; }

	update_user_meta( $user_id, 'codigo00', $_POST['codigo00'] );//código insertado durante el formulario de registro
	update_user_meta( $user_id, 'codigo01', $_POST['codigo01'] );
	update_user_meta( $user_id, 'codigo02', $_POST['codigo02'] );
	update_user_meta( $user_id, 'codigo03', $_POST['codigo03'] );
	update_user_meta( $user_id, 'codigo04', $_POST['codigo04'] );
	update_user_meta( $user_id, 'codigo05', $_POST['codigo05'] );
	update_user_meta( $user_id, 'codigo06', $_POST['codigo06'] );
	update_user_meta( $user_id, 'codigo07', $_POST['codigo07'] );
	update_user_meta( $user_id, 'codigo08', $_POST['codigo08'] );
	update_user_meta( $user_id, 'codigo09', $_POST['codigo09'] );
	update_user_meta( $user_id, 'codigo09', $_POST['codigo09'] );
	update_user_meta( $user_id, 'codigo10', $_POST['codigo10'] );	

}


