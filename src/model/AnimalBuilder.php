<?php

class AnimalBuilder {
    private $data;
    private $error = null;

    const NAME_REF = 'Nom';
    const SPECIES_REF = 'Espece';
    const AGE_REF = 'Age';

    public function __construct($data){
        $this->data = $data;
    }

    public static function builtFromAnimal(Animal $animal){
		return new AnimalBuilder(array(
            self::NAME_REF => $animal->getName(),
            self::SPECIES_REF => $animal->getSpecie(),
            self::AGE_REF => $animal->getAge()
        ));
    }
    public function getData(){
        return $this->data;
    }

    public function getError(){
        return $this->error;
    }

    public function isValid(){
        if (empty($this->data[self::NAME_REF]) || empty($this->data[self::SPECIES_REF]) || empty($this->data[self::AGE_REF]) || intval($this->data[self::AGE_REF]) < 0 || intval($this->data[self::AGE_REF]) == 0) {
            if (empty($this->data[self::NAME_REF])) {
                $this->error .= "| Erreur NOM non renseigné |";
                return false;
            }
            if (empty($this->data[self::SPECIES_REF])) {
                $this->error .= "| Erreur ESPECE non renseigné |";
                return false;
            }
            if (empty($this->data[self::AGE_REF]) || intval($this->data[self::AGE_REF]) < 0 || intval($this->data[self::AGE_REF]) == 0) {
                $this->error .= "| Erreur AGE non renseigné |";
                return false;
            }
        }
        return true;
    }

    public function createAnimal() {
        // Sanitize input with htmlspecialchars
        $name = htmlspecialchars(trim($this->data[self::NAME_REF]), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $species = htmlspecialchars(trim($this->data[self::SPECIES_REF]), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $age = intval($this->data[self::AGE_REF]); // Sanitize age as an integer
    
        // Create the Animal object with sanitized and escaped values
        $newAnimal = new Animal($name, $species, $age);
        return $newAnimal;
    }
    public function updateAnimal(Animal $animal) {
        // Sanitize and escape input with htmlspecialchars
        $name = htmlspecialchars(trim($this->data[self::NAME_REF]), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $species = htmlspecialchars(trim($this->data[self::SPECIES_REF]), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $age = intval($this->data[self::AGE_REF]); // Sanitize age as an integer
    
        // Update the Animal object with sanitized and escaped values
        $animal->setName($name);
        $animal->setSpecie($species);
        $animal->setAge($age);
    }
}

?>