<?php
require_once("model/Animal.php");
require_once("model/AnimalStorage.php");

class AnimalStorageStub implements AnimalStorage {

	private $db;

	public function __construct() {
		$this->db = array(
			'medor' => new Animal('Médor', 'chien', 8),
			'felix' => new Animal('Félix', 'chat',  4),
			'denver' => new Animal('Denver', 'dinosaure', 65000000 ),
			
		);
	}

	public function read($id) {
		if (key_exists($id, $this->db)) {
			return $this->db[$id];
		}
		return null;
	}

	public function readAll() {
		return $this->db;
	}

	public function create(Animal $a) {
		$id = $a->getName();
		$this->db[$id] = $a;
		return $id;
	}

	public function update($id, Animal $a) {
		if (array_key_exists($id, $this->db)) {
			$this->db[$id] = $a;
			return true;
		}
		return false;
	}
	
	public function delete($id){
		if (key_exists($id, $this->db)) {
			unset($this->db[$id]);
			return true;
		}
		return false;
	}
}
