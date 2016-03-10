<?php

namespace Wisquimas;

class ControladorRequests{
    protected $db_path = DIRECTORIO_BASE.DIRECTORY_SEPARATOR."db".DIRECTORY_SEPARATOR;
    protected $request;
    protected $mensajes;
    protected $db;
    protected $debug = false;

    function __construct(){
        $this->mensajes = new Mensajes();
        /*
        **CONFIGURAMOS BASES DE DATOS
        */
        $this->SetearBasesDeDatos();
        /*
        **COMPROBAMOS REQUEST
        */
        $this->request = $this->get_valid_requests();
    }
    protected function SetearBasesDeDatos(){
        $this->db = new Db( $this->db_path );
    }
    /*
    ** NOS PREPARARÁ UN SISTEMA CON HANDLE DE ERRORES
    */
    function get_valid_requests(){
        ob_start();//INICIAMOS CACHE
        if( isset( $_GET['funcion'] ) ){
        	$funcion = strip_tags( (string)$_GET['funcion'] );
        	$attr = isset( $_GET['attr'] ) ? $_GET['attr'] : '';
        	if( method_exists ( $this, $funcion ) ){
                /*
                **CORREMOS LAS FUNCIONES CORRRESPONDIENTES
                */
                if( strpos($attr,',') !== false ){
                    /*
                    **EL ATRIBUTO ES UN ARRAY
                    */
                    $attr = explode(',',$attr);
                };
        		if( is_array( $attr ) ){
        			call_user_func_array( array( $this,$funcion ) ,$attr);
        		}else{
        			call_user_func(array( $this,$funcion ),$attr);
        		};
        	}else{
        		$this->add_error('No existe la funcion: '.$funcion);
        	};
        }else{
        	$this->add_error( 'El webservice no ha recibido ninguna funcion' );
        };
        $this->mensajes->imprimir('JSON', true);
        $has_row = ob_get_clean();//FINALIZAMOS CACHE
        if( $this->debug ){
            LOG::write_Log( $has_row );
        };
        echo $has_row;
    }
    /*
    **ENVIAR ERROR
    */
    protected function add_error( $error = '' ){
        $this->mensajes->add_error( (string)$error );
    }
    /*
    ** ENVIAR DATA
    ** PARA PODER COLOCAR INFORMACION EN EL SISTEMA ES OBLIGADO COLOCAR UNA ESTRUCTURA DE CLASE
    */
    protected function add_data( $data = false, $estructuraClase = false ){
        if( !$data ){
            $this->add_error('La información enviada al sistema está vacía');
            return;
        };
        if( !$estructuraClase || !class_exists( 'Wisquimas\\'.(string)$estructuraClase ) ){
            $this->add_error('No se ha devuelto una estructura de Objecto dada de alta en el sistema');
            return;
        };
        /*
        **PARA DEVOLVER LA INFORMACIÓN DEBEMOS HACER UN PARSE CON LA INFO RECIBIDA
        */
        $informacion = $this->ParseObject( $data, $estructuraClase );

        $this->mensajes->add_data( $informacion );
    }
    /*
    ** RETORNA INFORMACION DE UN ARRAY CON LOS DATOS DE UN OBJETO
    */
    protected function ParseObject( $data = false, $estructuraClase = false, $namespace = 'Wisquimas\\' ){
        /*
        **PREFIJAMOS EL NAMESPACE
        */
        $estructuraClase = $namespace.(string)$estructuraClase;
        $data            = (array)$data;
        if( !$data ){
            $this->add_error('La información enviada al sistema está vacía');
            return;
        };
        if( !$estructuraClase || !class_exists( $estructuraClase ) ){
            $this->add_error('No se ha devuelto una estructura de Objecto dada de alta en el sistema');
            return;
        };
        $objetoBase = new $estructuraClase();
        if( !$objetoBase ){
            $this->add_error('Ha habido un error al crear el objeto modelo');
            return;
        };
        /*
        ** RECORREMOS OBJETO
        */
        foreach ($objetoBase as $key => $value) {
            if( isset( $data[$key] ) ){
                $objetoBase->$key = $data[$key];
            };
        }
        return $objetoBase;
    }
}
