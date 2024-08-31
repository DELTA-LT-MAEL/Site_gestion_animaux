<?php

interface AnimalStorage {
    
    /**
     * Récupère un animal par son identifiant unique.
     *
     * @param mixed $id L'identifiant unique de l'animal.
     * @return Animal|null L'objet Animal récupéré, ou null s'il n'est pas trouvé.
     */
    public function read($id);

    /**
     * Récupère tous les animaux stockés dans le stockage.
     *
     * @return array Un tableau d'objets Animal.
     */
    public function readAll();

    /**
     * Crée un nouvel enregistrement pour l'Animal spécifié.
     *
     * @param Animal $a L'objet Animal à créer.
     * @return mixed L'identifiant de l'animal créé.
     */
    public function create(Animal $a);
    
    /**
     * Supprime de la base l'animal correspondant à l'identifiant donné.
     *
     * @param mixed $id L'identifiant unique de l'animal à supprimer.
     * @return bool Retourne true si la suppression a été effectuée, et false si l'identifiant ne correspond à aucun animal.
     */
    public function delete($id);
    
    /**
     * Met à jour dans la base l'animal d'identifiant donné en le remplaçant par l'animal spécifié.
     *
     * @param mixed $id L'identifiant unique de l'animal à mettre à jour.
     * @param Animal $a L'objet Animal mis à jour.
     * @return bool Retourne true si la mise à jour a bien été effectuée, et false si l'identifiant n'existe pas (et donc rien n'a été modifié).
     */
    public function update($id, Animal $a);
}
?>
