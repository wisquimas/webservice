<?php

namespace Wisquimas;
class CustomRequest extends ControladorRequests{
    protected $debug = false;
    /*
    **SETEAMOS LAS TABLAS DEL SISTEMA
    */
    private $tablasSistemaCustom = array(
        'reservas',
    );
    /*
    **CREAMOS BASE DE DATOS CON TABLAS QUE NECESITAMOS
    */
    protected function SetearBasesDeDatos(){
        $this->db = new Db( $this->db_path, $this->tablasSistemaCustom );
    }
    
}
