<?php
function bookr_create () {
	$id_libro = $_POST["id_libro"];
	$titulo = $_POST["titulo"];
	$url = $_POST["url"];
	$thumbnail = $_POST["thumbnail"];
	$codigos = $_POST["codigos"];
	$disponible = $_POST["disponible"];			

	//insert
	if(isset($_POST['insert'])){
	   	global $wpdb;
		$dbtable = $wpdb->prefix . 'bookr_admin';

		$wpdb->insert(
			$dbtable, //table
			array('id_libro' => $id_libro,'titulo' => $titulo,'url' => $url,'thumbnail' => $thumbnail,'codigos' => $codigos,'disponible' => $disponible), //data
			array('%s','%s','%s','%s','%s') //data format	(string,...)		
		);
		$message.="Libro añadido";
	}
	?>
	<link type="text/css" href="<?php echo WP_PLUGIN_URL; ?>/bookr/css/style-admin-libros.css" rel="stylesheet" />
	<div class="wrap">
		<h2>Add new item</h2>
		
		<?php if (isset($message)): ?>
			<div class="updated">
				<p><?php echo $message;?></p>
			</div>
		<?php endif;?>
		
		<form method="post" action="<?php echo $_SERVER['REQUEST_URI']; ?>" enctype="multipart/form-data">			
			<table class='widefat crearlibro'>
				<tr><th>ID Item</th><td><input type="text" name="id_libro" value="<?php echo $id_libro;?>"/></td></tr>
				<tr><th>Título</th><td><input type="text" name="titulo" value="<?php echo $titulo;?>"/></td></tr>
				<tr><th>URL</th><td><input type="text" name="url" value="<?php echo $url;?>"/></td></tr>
				<!--
				<tr><th>Thumbnail</th><td><input type="file" name="edelsa_thumbnail" id="my_image_upload"  multiple="false" /></td></tr>
				-->
				<tr><th>Thumbnail</th>
					<td>
						<input type="hidden" id="libro_thumb" name="thumbnail" value="<?php echo $thumbnail;?>"/>
						<label for="upload_image">
					    	<input id="upload_image" type="hidden" name="ad_image" value="http://" /> 
					    	<input id="upload_image_button" class="button" type="button" value="Seleccionar imagen" />
					</label>
					</td>
				</tr>
				<tr><th>Códigos</th><td><textarea name="codigos"><?php echo $codigos;?></textarea></td></tr>
				<!--
				<tr><th>Códigos</th><td><input type="text" name="codigos" value="<?php echo $codigos;?>"/></td></tr>
				-->

				<tr><th>Disponible</th><td><input type="text" name="disponible" value="0"/></td></tr>

			</table>
			<input type='submit' name="insert" value='Guardar' class='button wp-core-ui button-primary'>
		</form>
	</div>
<?php
}