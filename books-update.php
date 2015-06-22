<?php
function bookr_update () {
	global $wpdb;
	$dbtable = $wpdb->prefix . 'bookr_admin';

	$id = $_GET["id"];

	$id_libro = $_POST["id_libro"];
	$titulo = $_POST["titulo"];
	$url = $_POST["url"];
	$thumbnail = $_POST["thumbnail"];
	$codigos = $_POST["codigos"];
	$disponible = $_POST["disponible"];	

	//update
	if(isset($_POST['update'])){	
		$wpdb->update(
			$dbtable, //table
			array('id_libro' => $id_libro,'titulo' => $titulo,'url' => $url,'thumbnail' => $thumbnail,'codigos' => $codigos,'disponible' => $disponible), //data
			array( 'ID' => $id ), //where
			array('%s','%s','%s','%s','%s'), //data format	(string,...)		
			array('%s') //where format
		);	
	}
	//delete
	else if(isset($_POST['delete'])){	
		$wpdb->query($wpdb->prepare("DELETE FROM $dbtable WHERE id = %s",$id));
	}
	else{//selecting value to update	
		$books = $wpdb->get_results($wpdb->prepare("SELECT id,id_libro,titulo,url,thumbnail,codigos,disponible from $dbtable where id=%s",$id));
		foreach ($books as $book ){
			$id_libro=$book->id_libro;
			$titulo=$book->titulo;
			$url=$book->url;
			$thumbnail=$book->thumbnail;
			$codigos=$book->codigos;
			$disponible=$book->disponible;
		}
	}
?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />
	<div class="wrap">

		<h2>Libros</h2>

		<?php if($_POST['delete']){?>
		
			<div class="updated">
				<p>Libro eliminado</p>
			</div>
			<a href="<?php echo admin_url('admin.php?page=bookr_list_items')?>">&laquo; Volver al listado de libros</a>

		<?php } 

			else if($_POST['update']) {
		?>

			<div class="updated">
				<p>Libro actualizado</p>
			</div>
			<a href="<?php echo admin_url('admin.php?page=bookr_list_items')?>">&laquo; Volver al listado de libros</a>

		<?php }

			else {
		?>
			<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>">
				<table class='wp-list-table widefat crearlibro'>
					<tr><th>ID Item</th><td><input type="text" name="id_libro" value="<?php echo $id_libro;?>"/></td></tr>
					<tr><th>Título</th><td><input type="text" name="titulo" value="<?php echo $titulo;?>"/></td></tr>
					<tr><th>URL</th><td><input type="text" name="url" value="<?php echo $url;?>"/></td></tr>
					<!--
					<tr><th>Thumbnail</th><td><input type="text" name="thumbnail" value="<?php echo $thumbnail;?>"/></td></tr>
					-->
					<tr><th>Thumbnail</th>
						<td>
							<input type="text" id="libro_thumb" name="thumbnail" value="<?php echo $thumbnail;?>"/>
							<label for="upload_image">
						    	<input id="upload_image" type="hidden" name="ad_image" value="http://" /> 
						    	<input id="upload_image_button" class="button" type="button" value="Seleccionar imagen" />
						</label>
						</td>
					</tr>					
					<tr><th>Códigos</th><td><input type="text" name="codigos" value="<?php echo $codigos;?>"/></td></tr>
					<tr><th>Disponible</th><td><input type="text" name="disponible" value="<?php echo $disponible;?>"/></td></tr>
				</table>

				<a href="<?php echo admin_url('admin.php?page=bookr_list_items')?>" class="button wp-core-ui button-primary" >&laquo; Volver</a> &nbsp;&nbsp;
				<input type='submit' name="update" value='Guardar' class='button wp-core-ui button-primary'> &nbsp;&nbsp;
				<input type='submit' name="delete" value='Eliminar' class='button wp-core-ui delete' onclick="return confirm('&iquest;Est&aacute;s seguro de borrar este elemento?')"> &nbsp;&nbsp;
			</form>
		<?php }?>

	</div>
	
<?php
}