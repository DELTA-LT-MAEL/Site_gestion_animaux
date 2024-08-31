<?php
require_once("model/Animal.php");
require_once('Viewjson.php');

class View {
    private $title;
    private $content;
    private $liste;
    private $router;
    private $menu;
    private $error;
    private $feedback;
    private $script;

    public function __construct(Router $router, String $feedback) {
        $this->title = null;
        $this->content = null;
        $this->router = $router;
        $this->liste = "";
        $this->error = null;
        $this->menu = array(
            '0' => array('?action=accueil', 'Accueil'),
            '1' => array('?action=liste', 'Liste'),
            '2' => array('?action=nouveau', 'Nouveau'),
        );
        $this->feedback = $feedback;
        $this->script = null;
    }

    public function render() {
        require_once("template.php");
    }

    public function prepareTestPage() {
        $this->title = "salut";
        $this->content = "monde";
    }

    public function prepareAnimalPage($id,$animal) {
        $this->title = "Page sur " . $animal->getName();
        $this->content = $animal->getName() . " " . $animal->getAge() . " est un animal de l'espèce " . $animal->getSpecie();
        $form = '<form method="POST" action="' . $this->router->getAnimalModificationForButton($id) . '">';
        $form .= '<input type="hidden" name="animal_id" value="' . $animal->getName() . '">';
        $form .= '<input type="submit" name="modifier" value="modifier">';
        $form .= '</form>';
        $form .= '<form method="POST" action="' . $this->router->getAnimalDeletionForButton($id) . '">';
        $form .= '<input type="submit" name="supprimer" value="supprimer">';
        $form .= '</form>';
        $this->content .= $form;
    
    
    }

    public function prepareUnknownAnimalPage() {
        echo "<script>alert('Erreur animal inconnu')</script>";
        $this->title = "Unknown Animal";
        $this->content = "Can't Find The Requested Animal";
    }

    public function accueilPage() {
        $this->title = "Accueil";
        $this->content = "Ceci est un site d'animaux";
    }

    public function prepareListPage($animalsTab) {
        $this->title = "Liste";
        $this->content = "Ceci est la liste de tous les animaux";
        foreach ($animalsTab as $key => $valeur) {
            $this->liste .= "<a href=" . $this->router->getAnimalURL($key) . ">" . $valeur->getName() . "</a>";
            $this->liste .= '<div id="animal-details-' . $key . '"></div>';
            $this->liste .= '<button onclick="sendXHR(\'' . addslashes($key) . '\',this)">Détails</button>';
            $this->liste .= "<br>";
            }

            $this->script = <<<SCRIPT
            <script>
            function sendXHR(id, button) {
                var xhr = new XMLHttpRequest();
                var url = "?action=json&id=" + id;
                var animalElement = document.getElementById("animal-details-" + id);
                var buttonText = button.innerHTML;
        
                xhr.open("GET", url, true);
                xhr.onreadystatechange = function() {
                    if (xhr.readyState === 4 && xhr.status === 200) {
                        var animalDetails = JSON.parse(xhr.responseText);
                        var detailsHTML = "<p>" + animalDetails.name + " " + animalDetails.age + " est un animal de l'espèce " + animalDetails.specie + "</p>";
                        
                        if (buttonText === "Détails") {
                            animalElement.innerHTML = detailsHTML;
                            button.innerHTML = "Cacher les détails";
                        } else {
                            animalElement.innerHTML = "";
                            button.innerHTML = "Détails";
                        }
                    }
                };
                xhr.send();
            }
            </script>
            SCRIPT;
         
        }

    public function getMenu() {
        foreach ($this->menu as $valeur) {
            echo " <a href =' " . $valeur[0] . "'>" . $valeur[1] . "</a>";
        }
    }

    public function prepareDebugPage($variable) {
        $this->title = 'Debug';
        $this->content = '<pre>' . htmlspecialchars(var_export($variable, true)) . '</pre>';
    }

    /*CREATE */

    public function prepareAnimalCreationPage(AnimalBuilder $animalBuilder) {
        $this->title = "Création d'un animal";
        $this->content = "Ceci est la page de création d'un animal";

        $form = '<form method="POST" action="' . $this->router->getAnimalSaveURL() . '">';
        $this->error = $animalBuilder->getError();
        $form .= '<label for="' . AnimalBuilder::NAME_REF . '">Nom:</label>';
        if (empty($animalBuilder->getData())) {
            $form .= '<input type="text" name="' . AnimalBuilder::NAME_REF . '" id="' . AnimalBuilder::NAME_REF . '"><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::NAME_REF . '" id="' . AnimalBuilder::NAME_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::NAME_REF] . '"><br>';
        }

        $form .= '<label for="' . AnimalBuilder::SPECIES_REF . '">Espèce:</label>';
        if (empty($animalBuilder->getData())) {
            $form .= '<input type="text" name="' . AnimalBuilder::SPECIES_REF . '" id="' . AnimalBuilder::SPECIES_REF . '" ><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::SPECIES_REF . '" id="' . AnimalBuilder::SPECIES_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::SPECIES_REF] . '"><br>';
        }

        $form .= '<label for="' . AnimalBuilder::AGE_REF . '">Âge:</label>';
        if (empty($animalBuilder->getData())) {
            $form .= '<input type="text" name="' . AnimalBuilder::AGE_REF . '" id="' . AnimalBuilder::AGE_REF . '"  ><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::AGE_REF . '" id="' . AnimalBuilder::AGE_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::AGE_REF] . '"><br>';
        }

        $form .= '<input type="submit" value="Enregistrer">';
        $form .= '</form>';

        $this->content .= $form;
    }

    public function displayAnimalCreationSuccess($id) {
        $this->router->POSTredirect($this->router->getAnimalURL(($id)), "Votre animal a bien été créé");
    }


    /*MODIFY*/


    public function prepareAnimalModificationPage($id,AnimalBuilder $animalBuilder) {
        $this->title = "Modification d'un animal";
        $this->content = "Ceci est la page de modification d'un animal";
        $form = '<form method="POST" action="' . $this->router->getAnimalModificationURL($id) . '">';
        $this->error = $animalBuilder->getError();
        $form .= '<label for="' . AnimalBuilder::NAME_REF . '">Nom:</label>';
        if (empty($animalBuilder->getData()) || !isset($animalBuilder->getData()[AnimalBuilder::NAME_REF])) {
            $form .= '<input type="text" name="' . AnimalBuilder::NAME_REF . '" id="' . AnimalBuilder::NAME_REF . '" "><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::NAME_REF . '" id="' . AnimalBuilder::NAME_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::NAME_REF] . '"><br>';
        }

        $form .= '<label for="' . AnimalBuilder::SPECIES_REF . '">Espèce:</label>';
        if (empty($animalBuilder->getData()) || !isset($animalBuilder->getData()[AnimalBuilder::SPECIES_REF])) {
            $form .= '<input type="text" name="' . AnimalBuilder::SPECIES_REF . '" id="' . AnimalBuilder::SPECIES_REF . '" ><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::SPECIES_REF . '" id="' . AnimalBuilder::SPECIES_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::SPECIES_REF] . '"><br>';
        }

        $form .= '<label for="' . AnimalBuilder::AGE_REF . '">Âge:</label>';
        if (empty($animalBuilder->getData()) || !isset($animalBuilder->getData()[AnimalBuilder::AGE_REF])) {
            $form .= '<input type="text" name="' . AnimalBuilder::AGE_REF . '" id="' . AnimalBuilder::AGE_REF . '"  ><br>';
        } else {
            $form .= '<input type="text" name="' . AnimalBuilder::AGE_REF . '" id="' . AnimalBuilder::AGE_REF . '" value="' . $animalBuilder->getData()[AnimalBuilder::AGE_REF] . '"><br>';
        }

        $form .= '<input type="submit" value="Enregistrer">';
        $form .= '</form>';

        $this->content .= $form;
    }

    public function displayAnimalModificationSuccess($id) {
        $this->router->POSTredirect($this->router->getAnimalURL(($id)), "Votre animal a bien été Modidifié");
    }

    /*DELETE*/


    public function prepareAnimalDeletionPage($id, Animal $animal) {
        $this->title = "Suppression d'un animal";
        $this->content = "Ceci est la page de suppression d'un animal";
        $name = htmlspecialchars($animal->getName(), ENT_QUOTES | ENT_SUBSTITUTE | ENT_HTML5, 'UTF-8');
        $form = '<form method="POST" action="' . $this->router->getAnimalDeletionURL($id) . '">';
        $form .= '<input type="hidden" name="animal_id" value="' . $name . '">';
        $form .= '<input type="submit" name="delete" value="Supprimer">';
        $form .= '</form>';
        $form .= '<form method="POST" action="' . $this->router->getAnimalURL($id) . '">';
        $form .= '<input type="submit" value="Annuler">';
        $form .= '</form>';
        $this->content .= $form;
    }

    public function displayAnimalDeletionSuccess() {
        $this->router->POSTredirect($this->router->getListPageURL(), "Votre animal a bien été supprimé");
    }


}
?>
