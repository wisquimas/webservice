<?php
namespace Wisquimas;

class Mensajes{
	public $ok;
	public $mensajes;
	private $data;

	function __construct(){
		$this->ok		= true;
		$this->mensajes	= array();
		$this->data		= array();
	}
	public function add_mensaje( $mensaje = false ){
		if( !$mensaje ){ return; };

		$this->mensajes[] = $mensaje;
	}
	public function add_error( $mensaje = false ){
		if( !$mensaje ){ return; };

		$this->ok = false;
		$this->mensajes[] = $mensaje;

	}
	public function get_lista_mensajes(){
		$texto = '';

		foreach( $this->mensajes as $m ){
			$texto .= '<p>'.$m.'</p>';
		};
		return $texto;
	}
	public function imprimir( $modo = false, $force = false ){
		if( !count( $this->mensajes ) && $force === false ){ return; };
		$texto	= $this->get_lista_mensajes();
		$tipo	= $this->ok ? 'mensaje' : 'error';

		$a_devolver = $texto;

		if( $modo === 'JSON' ){
			echo json_encode(array(
				'ok'		=> $this->ok,
				'mensaje'	=> $texto,
				'data'		=> $this->data,
			));
		}else{
			if( $modo ){
				echo $a_devolver;
			}else{
				return $a_devolver;
			};
		};
	}
	public function add_data( $data = false, $force = false ){
		if( !$data ){ return; };
		if( $force ){/*SOBREESCRIBE LA INFO*/
			$this->data		= $data;
		}else{
			$this->data[]	= $data;
		};
	}

	/**
	 * Añade datos con un nombre asociado que se usará como key en el array asociativo.
	 * @param $name string nombre para el key.
	 * @param $data mixed dato a guardar.
	 */
	public function add_data_named($name, $data){
		$this->data[$name] = $data;
	}
}
