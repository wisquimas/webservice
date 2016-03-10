<?php
namespace Wisquimas;
class Db extends \JsonDB{
    private $tablas = array();

    function __construct( $path = false, $tablas = array() ){
        if( $tablas ){
            /*
            **SETEAMOS LAS TABLAS DEL SISTEMA
            */
            $this->tablas = (array)$tablas;
        };
        parent::__construct($path);
        $this->check_Tables();
    }
    public function check_tables(){
        $tables = $this->tablas;
		if( count( $tables ) ){
			foreach ($tables as $tablaNombre ) {
				if( !in_array( $tablaNombre, $this->tables ) ){
					$this->createTable( $tablaNombre );
				};
			}
		};
	}
}
