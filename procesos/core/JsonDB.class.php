<?php
class JsonTable {

	protected $jsonFile;
	protected $fileHandle;
	protected $fileData = array();

	public function __construct($_jsonFile, $create = false) {
		if (!file_exists($_jsonFile)) {
			if($create === true)
			{
				$this->createTable($_jsonFile, true);
			}
			else
			{
				throw new Exception("JsonTable Error: Table not found: ".$_jsonFile);
			}
		}

		$this->jsonFile = $_jsonFile;
		$this->fileData = json_decode(file_get_contents($this->jsonFile), true);
		$this->lockFile();
	}

	public function __destruct() {
		$this->save();
		fclose($this->fileHandle);
	}

	protected function lockFile() {
		$handle = fopen($this->jsonFile, "w");
		if (flock($handle, LOCK_EX)) $this->fileHandle = $handle;
		else throw new Exception("JsonTable Error: Can't set file-lock");
	}

	protected function save() {
		if (fwrite($this->fileHandle, json_encode($this->fileData))) return true;
		else throw new Exception("JsonTable Error: Can't write data to: ".$this->jsonFile);
	}

	public function selectAll() {
		return $this->fileData;
	}

	public function select($key, $val = 0) {
		$result = array();
		if (is_array($key)){
			$result = $this->select($key[1], $key[2]);
		}else {
			$data = (array)$this->fileData;
			//mario( $data );
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						$result[] = $data[$_key];
					}
				}
			}
		}
		return $result;
	}

	public function updateAll($data = array()) {
		if (isset($data[0]) && substr_compare($data[0],$this->jsonFile,0)) $data = $data[1];
		return $this->fileData = array($data);
	}

	public function update($key, $val = 0, $newData = array()) {
		$result = false;
		if (is_array($key)) $result = $this->update($key[1], $key[2], $key[3]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						$data[$_key] = $newData;
						$result = true;
						break;
					}
				}
			}
			if ($result) $this->fileData = $data;
		}
		return $result;
	}
	public function updateValue($key, $val = 0, $newData = array()) {
		$result = false;
		if (is_array($key)) $result = $this->updateValue($key[1], $key[2], $key[3]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						$data[$_key] = array_merge( $data[$_key], $newData );
						$result = true;
						break;
					}
				}
			}
			if ($result) $this->fileData = $data;
		}
		return $result;
	}
	/*
	** FUNCION DE INSERTADO
	** SI ENVIAMOS UN 3ER VALOR A DATA VAMOS A COMPROBAR EL NO INSERT
	*/
	public function insert($data = array(), $create = false ) {
		if( isset($data[2]) && isset($data[2][0]) && isset($data[2][1]) ){
			/*
			**COMPROBAMOS SU HAY checkMasterKey
			*/
			$existe =  $this->select( (string)$data[2][0],$data[2][1] );
			if( $existe ){
				return;
			};
		};
		if (isset($data[0]) && substr_compare($data[0],$this->jsonFile,0)) $data = $data[1];
		$this->fileData[] = $data;
		return true;
	}

	public function deleteAll() {
		$this->fileData = array();
		return true;
	}

	public function delete($key, $val = 0) {
		$result = 0;
		if (is_array($key)) $result = $this->delete($key[1], $key[2]);
		else {
			$data = $this->fileData;
			foreach($data as $_key => $_val) {
				if (isset($data[$_key][$key])) {
					if ($data[$_key][$key] == $val) {
						unset($data[$_key]);
						$result++;
					}
				}
			}
			if ($result) {
				sort($data);
				$this->fileData = $data;
			}
		}
		return $result;
	}

	public function createTable($tablePath) {
		if(is_array($tablePath)) $tablePath = $tablePath[0];
		if(file_exists($tablePath))
			throw new Exception("Table already exists: ".$tablePath);

		if(fclose(fopen($tablePath, 'a')))
		{
			return true;
		}
		else
		{
			throw new Exception("New table couldn't be created: ".$tablePath);
		}
	}

}

class JsonDB {

	protected $path = "./";
	protected $fileExt = ".json";
	protected $tables = array();

	public function __construct($path) {
		if (is_dir($path))
		{
			$this->path = $path;
			$this->get_tables();
		}else{
			throw new Exception("JsonDB Error: Database not found");
		};
	}
	/*
	** SIRVE PARA LLENAR CORRECTAMENTE LA VARIABLE TABLES
	*/
	private function get_tables(){
		$tablas = array();
		foreach ( glob( $this->path . "*.json") as $x => $filename)
		{
			$base_name = basename( $filename );
			$base_name = str_replace('.json','',$base_name);

			$tablas[]	= $base_name;
		}
		$this->tables = $tablas;
	}

	protected function getTableInstance($table, $create) {
		if (isset($tables[$table])) return $tables[$table];
		else return $tables[$table] = new JsonTable($this->path.$table, $create);
	}

	public function __call($op, $args) {
		if ($args && method_exists("JsonTable", $op)) {
			$table = $args[0].$this->fileExt;
			$create = false;
			if($op == "createTable")
			{
				return $this->getTableInstance($table, true);
			}
			elseif($op == "insert" && isset($args[2]) && $args[2] === true)
			{
				$create = true;
			}
			return $this->getTableInstance($table, $create)->$op( $args );
		} else throw new Exception("JsonDB Error: Unknown method or wrong arguments ");
	}
	public function setExtension($_fileExt) {
		$this->fileExt = $_fileExt;
		return $this;
	}

}
