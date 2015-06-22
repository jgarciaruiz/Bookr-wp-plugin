<?php
function bookr_list_items () {
?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />

	<div class="wrap">
		
		<h2>Libros</h2>

		<a href="<?php echo admin_url('admin.php?page=bookr_create'); ?>" class="button button-primary button-large" id="list-addnew">Add new</a>
		
		<?php
	   		global $wpdb;
			$dbtable = $wpdb->prefix . 'bookr_admin';

			$pagenum = isset( $_GET['pagina'] ) ? absint( $_GET['pagina'] ) : 1;
			$limit = 20;
			$offset = ( $pagenum - 1 ) * $limit;
			//traer todos los libros
			$rows = $wpdb->get_results( "SELECT * FROM $dbtable LIMIT $offset, $limit");

			//solo traer libros visibles/disponibles (1). Si pongo 0 en el formulario ese libro desaparecerá de la vista edición y usuarios
			//$rows = $wpdb->get_results( "SELECT * FROM $dbtable WHERE disponible=1 LIMIT $offset, $limit");

			?>
			<table class="wp-list-table widefat fixed striped libros">
				<thead>
					<tr>
						<th scope="col" class="manage-column column-name" id="sort-idlibro">ID Libro</th>
						<th scope="col" class="manage-column column-name" id="sort-titulo">Titulo</th>
						<th scope="col" class="manage-column column-name">URL</th>
						<th scope="col" class="manage-column column-name thumb-col">Miniatura</th>
						<th scope="col" class="manage-column column-name codes-col">Códigos</th>
						<!-- <th scope="col" class="manage-column column-name">Activo</th> -->
						<th>&nbsp;</th>
					</tr>
				</thead>

				<tfoot>
					<tr>
						<th scope="col" class="manage-column column-name">ID Libro</th>
						<th scope="col" class="manage-column column-name">Titulo</th>
						<th scope="col" class="manage-column column-name">URL</th>
						<th scope="col" class="manage-column column-name">Miniatura</th>
						<th scope="col" class="manage-column column-name">Códigos</th>
						<!-- <th scope="col" class="manage-column column-name">Activo</th> -->
						<th>&nbsp;</th>
					</tr>
				</tfoot>

				<tbody>
					<?php
						//buscar códigos que hayan usado los usuarios (FNhYR4mk) para ver cuántos libros hay activos

					?>

					<?php if( $rows ) { ?>

						<?php
						$count = 1;
						$class = '';
						foreach( $rows as $row ) {
							$class = ( $count % 2 == 0 ) ? ' class="alternbg"' : '';

							$disponible = ( $row->disponible == '1' ) ? '1' : '0';

							echo "<tr class='$class'>";
								echo "<td>$row->id_libro</td>";	
								echo "<td>$row->titulo</td>";	
								echo "<td><a href='$row->url' target='_blank'>$row->url</a></td>";	
								echo "<td><img src='$row->thumbnail' width='70' alt='Portada libro'></td>";	
								echo "<td><textarea class='libroscode' readonly>$row->codigos</textarea></td>";	
								//echo "<td>$row->titulo</td>";									
								//echo "<td>$disponible</td>";					
								echo "<td><a href='".admin_url('admin.php?page=bookr_update&id='.$row->id)."'>Editar</a></td>";
							echo "</tr>";
						?>

						<?php
							$count++;
						}
						?>

					<?php } else { ?>
					<tr>
						<td colspan="5">No hay ningún registro</td>
					</tr>
					<?php } ?>
				</tbody>
			</table>

			<?php

			$total = $wpdb->get_var( "SELECT COUNT('id') FROM $dbtable" );
			$num_of_pages = ceil( $total / $limit );
			$page_links = paginate_links( array(
				'base' => add_query_arg( 'pagina', '%#%' ),
				'format' => '',
				'prev_text' => __( '&laquo;', 'aag' ),
				'next_text' => __( '&raquo;', 'aag' ),
				'total' => $num_of_pages,
				'current' => $pagenum
			) );

			if ( $page_links ) {
				echo '<div class="tablenav bottom">
						<span class="displaying-num">'. $total .' elementos</span>
						<div class="tablenav-pages" style="margin: 1em 0">' . $page_links . '</div>
					</div>';
			}


			?>


	</div>
<?php
}