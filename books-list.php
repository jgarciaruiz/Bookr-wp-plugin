<?php
function bookr_list_items () {
?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/style-admin.css" rel="stylesheet" />

	<div class="wrap">
		
		<h2>Libros</h2>

		<a href="<?php echo admin_url('admin.php?page=bookr_create'); ?>">Add new</a>
		
		<?php
		   	global $wpdb;
			$dbtable = $wpdb->prefix . 'bookr_admin';

			$rows = $wpdb->get_results("SELECT id,id_libro,titulo,url,thumbnail,codigos,disponible from ".$dbtable."");
			
			echo "<table class='wp-list-table widefat fixed'>";
			echo "<tr><!--<th>ID</th>--><th>ID Item</th><th>Título</th><th>URL</th><th>Miniatura</th><th>Códigos</th><th>Disponible</th><th>&nbsp;</th></tr>";
			foreach ($rows as $row ){
				echo "<tr>";
				echo "<!-- <td>$row->id</td> -->";
				echo "<td>$row->id_libro</td>";	
				echo "<td>$row->titulo</td>";	
				echo "<td><a href='$row->url' target='_blank'>$row->url</a></td>";	
				echo "<td><img src='$row->thumbnail' width='70' alt='Portada libro'></td>";	
				echo "<td><textarea rows='4' cols='50' style='resize: none; overflow-x: scroll; height: 60px;' readonly>$row->codigos</textarea></td>";	
				echo "<td>$row->disponible</td>";					
				echo "<td><a href='".admin_url('admin.php?page=bookr_update&id='.$row->id)."'>Editar</a></td>";
				echo "</tr>";}
			echo "</table>";
		?>

	</div>
<?php
}