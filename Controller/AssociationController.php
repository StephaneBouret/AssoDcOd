<?php

include 'Model/AssociationModel.php';
include 'View/AssociationView.php';

class AssociationController extends Controller
{


    public function __construct()
    {
        $this->view = new AssociationView();
        $this->model = new AssociationModel();
    }

    /**
     * Construction de la page paramétrages de l'association
     * Liste des informations
     *
     * @return void
     */
    public function start()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $initData = $this->model->IsAssociationPresent();
        $this->view->displayHome($associationDisplay, $initData);
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout de l'association
     *
     * @return void
     */
    public function createAsso() {
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addForm($associationDisplay);
    }

    /**
     * Gestion de l'ajout de l'association
     *
     * @return void
     */
    public function addDB()
    {
        function valid_donneesAsso($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nomAsso = valid_donneesAsso($_POST['AssoName']);
        $adresse = valid_donneesAsso($_POST['adresse']);
        $codepostal = valid_donneesAsso($_POST['codepostal']);
        $ville = valid_donneesAsso($_POST['ville']);
        $tel = $_POST['tel'];
        $email = $_POST['email'];

        if (isset($nomAsso) && isset($adresse) && isset($codepostal) && isset($ville) 
        && isset($tel) && isset($email) && !empty($nomAsso) && !empty($adresse) 
        && !empty($codepostal) && !empty($ville) && !empty($tel) && !empty($email)) {
            $this->model->addDB();
            header('location:index.php?controller=association');
        } else {
            header('location:index.php?controller=association&action=editAsso');
        }
    }

    /**
     * Gestion de l'affichage du formulaire de modification de l'association
     *
     * @return void
     */
    public function editAsso()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateForm($associationDisplay);
    }

    /**
     * Mise à jour de l'information dans la table association
     *
     * @return void
     */
    public function updateDB(){
        $nomAsso = $_POST['AssoName'];
        $adresse = $_POST['adresse'];
        $codepostal = $_POST['codepostal'];
        $ville = $_POST['ville'];
        $tel = $_POST['tel'];
        $email = $_POST['email'];
        if (isset($nomAsso) && !empty($nomAsso) && isset($adresse) && !empty($adresse) && isset($codepostal) && !empty($codepostal) 
        && isset($ville) && !empty($ville) && isset($tel) && !empty($tel) && isset($email) && !empty($email)) {
            $this->model->updateDB();
            header('location:index.php?controller=association&action=start');
        } else {
            header('location:index.php?controller=association&action=editAsso');
        }
    }

    /**
     * Vider la table association
     *
     * @return void
     */
    public function clearBdd()
    {
        $this->model->clearBdd();
        header("location:index.php?controller=association&action=start");
    }

    /**
     * Construction de la page d'edition des paramètres pour les documents
     *
     * @return void
     */
    public function editParamsFile()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $listJuridicalStatus = $this->model->getJuridicalStatus();
        $listBoardMembers = $this->model->getBoardMembers();
        $this->view->displayParamsFile($associationDisplay, $listJuridicalStatus, $listBoardMembers);
    }

    /**
     * Mise à jour de l'information dans la table association concernant les documents
     *
     * @return void
     */
    public function updateDBParamsFile()
    {
        function valid_donneesParamsAsso($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $assoName = valid_donneesParamsAsso($_POST['AssoName']);
        $juridique = valid_donneesParamsAsso($_POST['sel_juridical']);
        $rna = valid_donneesParamsAsso($_POST['rna']);
        $objetSocial = valid_donneesParamsAsso($_POST['objetsocial']);
        $footerDoc = valid_donneesParamsAsso($_POST['footerdoc']);
        if (isset($assoName) && isset($juridique) && isset($rna) && isset($objetSocial) 
        && isset($footerDoc) && !empty($assoName) && !empty($juridique) && !empty($rna) 
        && !empty($objetSocial) && !empty($footerDoc)) {
            $this->model->updateDBParamsFile();
            header("location:index.php?controller=association&action=start");
        } else {
            header("location:index.php?controller=association&action=editParamsFile");
        }
    }

    /**
     * Construction d'un PDF en exemple avec les données renseignées dans la table association
     *
     * @return void
     */
    public function createPdfExample()
    {
        $adherent = $this->model->getAdherent();
        $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
        $this->model->createPdfExample($associationDisplayForPdf, $adherent);
    }

        /**
     * Autocompletion de la recherche des statuts juridiques
     *
     * @return void
     */
    public function ajaxAutocompletionJuridicalStatus()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteStatus = $_POST['searchTerm'];
            $data = $this->model->ajaxFileStatus($requeteStatus);
            echo json_encode($data);
        }
    }

    /**
     * Autocompletion de la recherche des membres du bureau
     *
     * @return void
     */
    public function ajaxAutocompletionBoardMember()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteBoard = $_POST['searchTerm'];
            $data = $this->model->ajaxFileBoard($requeteBoard);
            echo json_encode($data);
        }
    }

}