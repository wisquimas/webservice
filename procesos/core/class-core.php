<?php

namespace Wisquimas;

class Core{
    private $__dir__;

    function __construct( $dir_base = '' ){
        $this->__dir__ = $dir_base;
    }
    /*
    ** INICIA EL PROCESO DE ARRANQUE DE REQUESTS
    */
    public function ControlarRequest(){
        $funcion = isset( $_GET['funcion'] ) ? $_GET['funcion'] : false;
        if( $funcion && method_exists('Wisquimas\CustomRequest',(string)$funcion) ){
            new CustomRequest();
        }else{
            new ControladorRequests();
        };
    }
}
