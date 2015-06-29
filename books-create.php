<?php
function bookr_create () {
	$id_libro = $_POST["id_libro"];
	$titulo = $_POST["titulo"];
	$url = $_POST["url"];
	$thumbnail = $_POST["thumbnail"];
	$codigos = $_POST["codigos"];
	$disponible = $_POST["disponible"];			
	$categoria = $_POST["categoria"];			

	//insert
	if(isset($_POST['insert'])){
	   	global $wpdb;
		$dbtable = $wpdb->prefix . 'bookr_admin';

		//grabar datos para listar libros en la sección libros del admin
		$wpdb->insert(
			$dbtable, //table
			array('id_libro' => $id_libro,'titulo' => $titulo,'url' => $url,'thumbnail' => $thumbnail,'codigos' => $codigos,'disponible' => $disponible,'categoria' => $categoria), //data
			array('%s','%s','%s','%s','%s','%s') //data format	(string,...)		
		);

		//grabar cada código en una fila en la tabla de edelsalibros_codigos
		$lines = explode(" ", $codigos);
		$dbtable2 = $wpdb->prefix . 'bookr_book_keys';

		foreach($lines as $line) {
			$wpdb->insert(
				$dbtable2, //table
				array('id_libro' => $id_libro,'titulo' => $titulo,'url' => $url,'thumbnail' => $thumbnail,'categoria' => $categoria,'codigo' => $line), //data
				array('%s','%s','%s','%s','%s','%s') //data format	(string,...)			
			);
		}

		$message.="Libro añadido.";
	}
	?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Add new item</h2>
		
		<?php if($_POST['insert']){?>
			<div class="updated">
				<p style="margin-bottom:20px;"><?php echo $message;?></p>
				<a href="<?php echo admin_url('admin.php?page=bookr_list_items')?>">&laquo; Volver al listado de libros</a>
			</div>
		<?php }

			else {
		?>
		
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">			
			<table class='widefat crearlibro'>
				<tr><th>ID Item</th><td><input type="text" name="id_libro" value="<?php echo $id_libro;?>"/></td></tr>
				<tr><th>Título</th><td><input type="text" name="titulo" value="<?php echo $titulo;?>"/></td></tr>
				<tr><th>URL</th><td><input type="text" name="url" value="<?php echo $url;?>"/></td></tr>
				<tr><th>Thumbnail</th>
					<td>
						<input type="hidden" id="libro_thumb" name="thumbnail" value="<?php echo $thumbnail;?>"/>
						<label for="upload_image">
					    	<input id="upload_image" type="hidden" name="ad_image" value="http://" /> 
					    	<input id="upload_image_button" class="button" type="button" value="Seleccionar imagen" />
						</label>
					</td>
				</tr>
				<tr><th>Códigos</th><td><textarea name="codigos"></textarea></td></tr>
				<tr><th>Categoría</th><td>
					<select name="categoria">
					  <option value="profesor">Profesor</option>
					  <option value="alumno">Alumno</option>
					  <option value="digital">Digital</option>
					  <option value="online">Moodle</option>
					</select>
				</td></tr>

				<tr><th>Disponible</th><td>
					<select name="disponible">
					  <option value="1" selected>Si</option>
					  <option value="0">No</option>
					</select>
				</td></tr>

			</table>
			<input type='submit' name="insert" value='Guardar' class='button wp-core-ui button-primary'>
		</form>

		<?php }?>

	</div>
<?php
}