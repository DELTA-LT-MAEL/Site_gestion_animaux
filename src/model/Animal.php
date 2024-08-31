<?php
class Animal {
  private $name;
  private $specie;
  private $age;

  public function __construct($name, $specie, $age) {
    $this->name = $name;
    $this->specie = $specie;
    $this->age = $age;
  }

  public function getName() {
    return $this->name;
  }

  public function getSpecie() {
    return $this->specie;
  }

  public function getAge() {
    return $this->age;
  }

  public function setName($name) {
    $this->name = $name;
  }

  public function setSpecie($specie) {
    $this->specie = $specie;
  }

  public function setAge($age) {
    $this->age = $age;
  }
  
}