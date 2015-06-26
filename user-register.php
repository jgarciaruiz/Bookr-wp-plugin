<?php

    //1. añadir elemento nuevo al formulario
    add_action( 'register_form', 'edelsalibros_registro_usuarios' );
    function edelsalibros_registro_usuarios() {

    $codigo_libro = ( ! empty( $_POST['codigo_libro'] ) ) ? trim( $_POST['codigo_libro'] ) : '';
        
        ?>
        <p>
            <label for="codigo_libro"><?php _e('Código Libro') ?><br />
                <input type="text" name="codigo_libro" id="codigo_libro" class="input" value="<?php echo esc_attr( wp_unslash( $codigo_libro ) ); ?>" size="25" /></label>
        </p>
        <?php
    }

    //2. Validar campo código libro, que es obligatorio para registrarse
    /*
    add_filter( 'registration_errors', 'edelsalibros_erroes_registro_usuarios', 10, 3 );
    function edelsalibros_erroes_registro_usuarios( $errors, $sanitized_user_login, $user_email ) {
        
        if ( empty( $_POST['codigo_libro'] ) || ! empty( $_POST['codigo_libro'] ) && trim( $_POST['codigo_libro'] ) == '' ) {
            $errors->add( 'codigo_libro_error', __( '<strong>ERROR</strong>: Debes incluir un código de libro.') );
        }

        return $errors;
    }
    */

    //3. Guardar valor del campo añadido al formulario
    add_action( 'user_register', 'edelsalibros_guardar_codigolibro' );
    function edelsalibros_guardar_codigolibro( $user_id ) {
        if ( ! empty( $_POST['codigo_libro'] ) ) {
            
            //graba la información en user_meta
            update_user_meta( $user_id, 'codigo_libro', trim( $_POST['codigo_libro'] ) );

            //grabar también la información en la tabla bookr_register_form
            $id_user = $user_id;
            $user_login = $_POST["user_login"];
            $user_email = $_POST["user_email"];
            $codigo_libro = $_POST["codigo_libro"];

            global $wpdb;
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

        }
    }




