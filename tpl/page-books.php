<?php
get_header(); ?>

	<div id="page-books" class="content-area">
		<main id="main" class="site-main" role="main">
			
			<table>
				<thead>
					<tr>
						<th scope="col" class="manage-column column-name" id="sort-idlibro">ID Libro</th>
						<th scope="col" class="manage-column column-name" id="sort-titulo">Titulo</th>
						<th scope="col" class="manage-column column-name">URL</th>
						<th scope="col" class="manage-column column-name thumb-col">Miniatura</th>
					</tr>
				</thead>
				<?php

			   		global $wpdb;
					$dbtable = $wpdb->prefix . 'bookr_admin';
					$rows = $wpdb->get_results( "SELECT * FROM $dbtable");

					foreach( $rows as $row ) {

						echo "<tr>";
							echo "<td>$row->id_libro</td>";	
							echo "<td>$row->titulo</td>";	
							echo "<td><a href='$row->url' target='_blank'>$row->url</a></td>";	
							echo "<td><img src='$row->thumbnail' width='70' alt='Portada libro'></td>";	
						echo "</tr>";
					}
				?>
			</table>
	
		</main><!-- .site-main -->
	</div><!-- .content-area -->

<?php get_footer(); ?>