<?php
require_once("view/View.php");
require_once("model/AnimalStorage.php");

class Controller {
    private $view;
    private $id;
    private $animalsTab;
    private $animalStorage;

    public function __construct($view, AnimalStorage $animalStorage) {
        $this->view = $view;
        $this->animalStorage = $animalStorage;
    }

    public function showInformation($id) {
        $animal = $this->animalStorage->read($id);
        if ($animal !== null) {
            $this->view->prepareAnimalPage($id,$animal);
        } else {
            $this->view->prepareUnknownAnimalPage();
        }
    }

    public function showHomePage() {
        $this->view->accueilPage();
    }

    public function showList() {
        $animals = $this->animalStorage->readAll();
        $this->view->prepareListPage($animals);
    }

    public function saveNewAnimal(array $data) {
        $animalbuilder = new AnimalBuilder($data);
        if ($animalbuilder->isValid() === false) {
            $this->view->prepareAnimalCreationPage($animalbuilder);
        } else {
            $animal = $animalbuilder->createAnimal();
            $id = $this->animalStorage->create($animal);
            $this->view->displayAnimalCreationSuccess($id);
        }
    }

    public function modifyAnimal($id) {
        $animal = $this->animalStorage->read($id);
        if ($animal === null) {
            $this->view->prepareUnknownAnimalPage();
        } else {
            $animalbuilder = AnimalBuilder::builtFromAnimal($animal);
            $this->view->prepareAnimalModificationPage($id,$animalbuilder);
        }
    }
    public function saveModifiedAnimal($id, array $data) {
        $animal = $this->animalStorage->read($id);
        if ($animal === null) {
            $this->view->prepareUnknownAnimalPage();
        } else {
            $animalBuilder = new AnimalBuilder($data);
            if ($animalBuilder->isValid()) {
                $animalBuilder->updateAnimal($animal);
                $this->animalStorage->update($id,$animal);
                $this->view->displayAnimalModificationSuccess($id);
                
            } else {
                $this->view->prepareAnimalModificationPage($id, $animalBuilder);
            } 
        }
    }
    public function deleteAnimal($id) {
        $animal = $this->animalStorage->read($id);
        if ($animal === null){
            $this->view->prepareUnknownAnimalPage();
        }else{
        $this->view->prepareAnimalDeletionPage($id,$animal);
        }
    }
    public function serveJSON($id) {
        $animal = $this->animalStorage->read($id);
        if ($animal !== null) {
            Viewjson::render($id,[
                'name' => $animal->getName(),
                'specie' => $animal->getSpecie(),
                'age' => $animal->getAge()
            ]);
        } else {
            Viewjson::render($id,['error' => 'Animal not found']);
        }
    
        return;
    }

    public function confirmAnimalDeletion($id) {
        $animal = $this->animalStorage->delete($id);
        if ($animal === false) {
            $this->view->prepareUnknownAnimalPage();
        } else {
            $this->view->displayAnimalDeletionSuccess();
            
        }
    }

}
?>