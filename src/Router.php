<?php

require_once("view/View.php");
require_once("view/Viewjson.php");
require_once("control/Controller.php");
require_once("model/AnimalStorage.php");
require_once("model/AnimalBuilder.php");

class Router {
    private $animalStorage;
    private $view;

    public function main(AnimalStorage $animalStorage) {
        $this->animalStorage = $animalStorage;
        $this->view = new View($this, $_SESSION['feedback'] ?? "");
        unset($_SESSION['feedback']); 
        $ctl = new Controller($this->view, $this->animalStorage);

        $id = isset($_GET['id']) ? $_GET['id'] : null;
        $action = isset($_GET['action']) ? $_GET['action'] : null;

        if ($action === null) {
            $action = ($id === null) ? "accueil" : "voir";
        }

        try {
            switch ($action) {
                case "voir":
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->showInformation($id);
                    }
                    break;
                case 'accueil':
                    $ctl->showHomePage();
                    break;
                case 'nouveau':
                    $animalBuilder = new AnimalBuilder(null);
                    $this->view->prepareAnimalCreationPage($animalBuilder);
                    break;
                case 'sauverNouveau':
                    $ctl->saveNewAnimal($_POST);
                    break;
                case 'modifier':
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->modifyAnimal($id);
                    }
                    break;
                case 'json':
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->serveJSON($id);
                    }
                        break;
                case 'sauverModification':
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->saveModifiedAnimal($id, $_POST);
                    }
                    break;
                case 'supprimer':
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->deleteAnimal($id);
                    }
                    break;
                case 'confirmationSuppression':
                    if ($id === null) {
                        $this->view->prepareUnknownAnimalPage();
                    } else {
                        $ctl->confirmAnimalDeletion($id);
                    }
                    break;
                case 'liste':
                    $ctl->showlist();
                    break;
                case 'debug':
                    $this->view->prepareDebugPage('<strong>Bonjour</strong>');
                    break;
                default:
                    $this->view->prepareUnknownAnimalPage();
                    break;
            }
        } catch (Exception $e) {
            echo $e->getMessage();
        }
        $this->view->render();
    }

    public function getHomePageURL() {
        return 'http://localhost/tests/groupe-8/site.php?action=accueil';
    }
    public function getListPageURL() {
        return 'http://localhost/tests/groupe-8/site.php?action=liste';
    }
    
    public function getAnimalURL($id) {
        return 'http://localhost/tests/groupe-8/site.php?id=' . $id;
    }
    public function getJsonURL($id) {
        return 'http://localhost/tests/groupe-8/site.php?id=' . $id . '&action=json';
    }

    public function getCreateURL() {
        return 'http://localhost/tests/groupe-8/site.php?action=nouveau';
    }
    
    public function getAnimalSaveURL() {
        return 'http://localhost/tests/groupe-8/site.php?action=sauverNouveau';
    }
    public function getAnimalModificationForButton($id) {
        return 'http://localhost/tests/groupe-8/site.php?id='.$id.'&action=modifier';
    }
    public function getAnimalDeletionForButton($id) {
        return 'http://localhost/tests/groupe-8/site.php?id='.$id.'&action=supprimer';
    }
    public function getAnimalModificationURL($id) {
        return 'http://localhost/tests/groupe-8/site.php?id='.$id.'&action=sauverModification';
    }

    public function getAnimalDeletionURL($id) {
        return 'http://localhost/tests/groupe-8/site.php?id=' . $id . '&action=confirmationSuppression';
    }

    public function POSTredirect($url, $feedback) {
        $_SESSION['feedback'] = $feedback; 
        header("Location: ".htmlspecialchars_decode($url), true, 303);
        die;
    }
}
?>
