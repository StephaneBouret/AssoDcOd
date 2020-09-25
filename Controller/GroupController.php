<?php

include "Model/GroupModel.php";
include "View/GroupView.php";

class GroupController extends Controller {


    public function __construct()
    {
        $this->view = new GroupView();
        $this->model = new GroupModel();
    }

    /**
     * Construction de la page affichage des groupes d'adhérents
     *
     * @return void
     */
    public function listFiles(){
        $listGroupAdherent = $this->model->getGroupAdherentAll();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($listGroupAdherent,$associationDisplay);
    }

    public function detailForm(){
        $associationDisplay = $this->model->getFullAssociation();
        $onlyOneGroupAdherent = $this->model->getGroupAdherentOne();
        $listUsersFromGroupSelected  = $this->model->getAllUsersFromGroupSelected();
        $this->view->detailForm($onlyOneGroupAdherent, $listUsersFromGroupSelected, $associationDisplay);
    }

    /**
     * Gestion de la suppression d'un groupe
     *
     * @return void
     */
    public function suppDB()
    {
        $this->model->suppDBGroup();
        $this->model->setFlash('success', 'Le groupe a bien été supprimé');
        header('location:index.php?controller=group&action=listFiles');
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout de groupe
     *
     * @return void
     */
    public function addGroupForm()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addGroupForm($associationDisplay);
    }

    /**
     * Gestion de l'insertion en BDD - ajout d'un groupe
     *
     * @return void
     */
    public function addGroup()
    {
        function valid_donneesGroupBeforeAdding($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nomGroupe = valid_donneesGroupBeforeAdding($_POST['nomGroupe']);
        $emailGroupe = valid_donneesGroupBeforeAdding($_POST['emailGroupe']);
        $telGroupe = valid_donneesGroupBeforeAdding($_POST['telGroupe']);
        $adresseGroupe = valid_donneesGroupBeforeAdding($_POST['adresseGroupe']);
        $codepostalGroupe = valid_donneesGroupBeforeAdding($_POST['codepostalGroupe']);
        $villeGroupe = valid_donneesGroupBeforeAdding($_POST['villeGroupe']);
        $representantPrenomGroupe = valid_donneesGroupBeforeAdding($_POST['representantPrenomGroupe']);
        $representantNomGroupe = valid_donneesGroupBeforeAdding($_POST['representantNomGroupe']);

        if (isset($nomGroupe) && isset($emailGroupe) && isset($telGroupe) && isset($representantPrenomGroupe) && isset($representantNomGroupe) 
        && isset($adresseGroupe) && isset($codepostalGroupe) && isset($villeGroupe) && !empty($nomGroupe) 
        && filter_var($emailGroupe, FILTER_VALIDATE_EMAIL) && !empty($telGroupe) && !empty($representantPrenomGroupe) 
        && !empty($representantNomGroupe)) {
            $this->model->addDBGroup();
            $this->model->setFlash('success', 'L\'ajout du groupe a bien été pris en compte');
            header('location:index.php?controller=group&action=listFiles');
        } else {
            $this->model->setFlash('danger', 'Erreur lors de l\'ajout du groupe');
            header('location:index.php?controller=group&action=action=addGroupForm');
        }
    }

    /**
     * Gestion de l'affichage du formulaire de modification d'un groupe
     *
     * @return void
     */
    public function updateForm()
    {
        $onlyOneGroupAdherent = $this->model->getGroupAdherentOne();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormForGroup($onlyOneGroupAdherent, $associationDisplay);
    }

    /**
     * Fonction gestion de l'update du groupe adhérent
     *
     * @return void
     */
    public function updateGroup()
    {
        function valid_donneesGroupBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $id = valid_donneesGroupBeforeUpdating($_POST['id']);
        $nomGroupe = valid_donneesGroupBeforeUpdating($_POST['nomGroupe']);
        $emailGroupe = valid_donneesGroupBeforeUpdating($_POST['emailGroupe']);
        $telGroupe = valid_donneesGroupBeforeUpdating($_POST['telGroupe']);
        $adresseGroupe = valid_donneesGroupBeforeUpdating($_POST['adresseGroupe']);
        $codepostalGroupe = valid_donneesGroupBeforeUpdating($_POST['codepostalGroupe']);
        $villeGroupe = valid_donneesGroupBeforeUpdating($_POST['villeGroupe']);
        $representantPrenomGroupe = valid_donneesGroupBeforeUpdating($_POST['representantPrenomGroupe']);
        $representantNomGroupe = valid_donneesGroupBeforeUpdating($_POST['representantNomGroupe']);

        if (isset($id) && isset($nomGroupe) && isset($emailGroupe) && isset($telGroupe) && isset($representantPrenomGroupe) && isset($representantNomGroupe) 
        && isset($adresseGroupe) && isset($codepostalGroupe) && isset($villeGroupe) && !empty($id) && !empty($nomGroupe) 
        && filter_var($emailGroupe, FILTER_VALIDATE_EMAIL) && !empty($telGroupe)) {
            $this->model->updateDBGroup();
            $this->model->setFlash('success', 'La modification du groupe a bien été pris en compte');
            header('location:index.php?controller=group&action=listFiles');
        } else {
            $this->model->setFlash('danger', 'Erreur lors de la modification du groupe');
            header('location:index.php?controller=group&action=action=updateForm&id = ' .$id. '');
        }
    }

    /**
     * Autocompletion de la recherche des membres pour le leader du groupe
     *
     * @return void
     */
    public function ajaxAutocompletionMemberNameForGroup()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteMemberName = $_POST['searchTerm'];
            $data = $this->model->ajaxFileMemberNameForGroup($requeteMemberName);
            echo json_encode($data);
        }
    }
 }