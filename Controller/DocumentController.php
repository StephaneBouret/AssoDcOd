<?php

include "Model/DocumentModel.php";
include "View/DocumentView.php";

class DocumentController extends Controller {


    public function __construct()
    {
        $this->view = new DocumentView();
        $this->model = new DocumentModel();
    }

    /**
     * Construction de la page affichage des dossiers de documents et de news
     *
     * @return void
     */
    public function listFiles(){
        $listAllNews = $this->model->getAllNews();
        $listSimplifiedFiles = $this->model->getSimplifiedFileDoc();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($listAllNews, $listSimplifiedFiles, $associationDisplay);
    }

    public function show(){
        $listSimplifiedFile = $this->model->getSimplifiedFileDocById();
        $listDocFromFiles = $this->model->getDocFileBreakdown();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->show($listSimplifiedFile, $listDocFromFiles, $associationDisplay);
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout de dossier
     *
     * @return void
     */
    public function addFormFile()
    {
        $listFunctions = $this->model->getFunctions();
        $listSimplifiedFiles = $this->model->getSimplifiedFileDoc();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addFormFile($listFunctions, $listSimplifiedFiles, $associationDisplay);
    }

    /**
     * Fonction gestion de l'insertion en base de données - creation de dossier
     *
     * @return void
     */
    public function addFile()
    {
        function valid_donneesForFile($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $newNameFile = valid_donneesForFile($_POST['newNameFile']);
        $authorFirstName = valid_donneesForFile($_POST['authorFirstName']);
        $authorName = valid_donneesForFile($_POST['authorName']);
        $authorFunction = valid_donneesForFile($_POST['authorFunction']);

        if (isset($newNameFile) && isset($authorFirstName) && isset($authorName) && isset($authorFunction) && !empty($newNameFile) && !empty($authorFirstName) 
        && !empty($authorName) && !empty($authorFunction)) {
            $this->model->addFileInDataBase();
            header('location:index.php?controller=document&action=listFiles');
        } else {
            header('location:index.php?controller=document&action=addForm');
        }
    }

    /**
     * Gestion de la modification d'un dossier
     *
     * @return void
     */
    public function updateFormForFile(){
        $listFunctions = $this->model->getFunctions();
        $listSimplifiedFile = $this->model->getSimplifiedFileDocById();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormForFile($listFunctions, $listSimplifiedFile, $associationDisplay);
    }

    public function updateDBFile(){
        $id = $_GET['id'];
        function valid_donneesFileBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $newNameFile = valid_donneesFileBeforeUpdating($_POST['newNameFile']);
        $authorFirstName = valid_donneesFileBeforeUpdating($_POST['authorFirstName']);
        $authorName = valid_donneesFileBeforeUpdating($_POST['authorName']);
        $authorFunction = valid_donneesFileBeforeUpdating($_POST['authorFunction']);

        if (isset($newNameFile) && isset($authorFirstName) && isset($authorName) && isset($authorFunction) && !empty($newNameFile) && !empty($authorFirstName) 
        && !empty($authorName) && !empty($authorFunction)) {
            $this->model->updateDBFile();
            header('location:index.php?controller=document&action=listFiles');
        } else {
            header('location:index.php?controller=document&action=updateFormForFile&id=' . $id . '');
        }
    }

    /**
     * Gestion de la supression d'un dossier
     *
     * @return void
     */
    public function suppDBFile()
    {
        $this->model->suppDBFile();
        header('location:index.php?controller=document&action=listFiles');
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout de document
     *
     * @return void
     */
    public function addFormDoc() {
        $id = $_GET['id'];
        $listSimplifiedFiles = $this->model->getSimplifiedFileDoc();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addFormDoc($listSimplifiedFiles, $associationDisplay, $id);
    }

    /**
     * Gestion de l'insertion dans la table document
     *
     * @return void
     */
    public function addDoc()
    {
        function valid_donneesDocBeforeAdding($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nomDossier = valid_donneesDocBeforeAdding($_POST['nomDossier']);
        $nameDoc = valid_donneesDocBeforeAdding($_POST['nameDoc']);
        $dateDoc = valid_donneesDocBeforeAdding($_POST['dateDoc']);
        $typeDoc = valid_donneesDocBeforeAdding($_POST['typeDoc']);
        $descriptionForDoc = valid_donneesDocBeforeAdding($_POST['descriptionForDoc']);

        if (isset($nomDossier) && isset($nameDoc) && isset($dateDoc) && isset($typeDoc) && isset($descriptionForDoc) && !empty($nomDossier) && !empty($nameDoc) 
        && !empty($dateDoc) && !empty($typeDoc) && !empty($descriptionForDoc)) {
            $this->model->addDocInDataBase();
            $this->model->setFlash('success', 'L\'ajout du document a bien été pris en compte');
            header('location:index.php?controller=document&action=show&id=' . $nomDossier . '');
        } else {
            $this->model->setFlash('danger', 'Erreur lors de l\'ajout du document');
            header('location:index.php?controller=document&action=addFormDoc&id=' . $nomDossier . '');
        }
    }

    /**
     * Gestion de l'affichage du formulaire de modification d'un document
     *
     * @return void
     */
    public function updateFormForDoc(){
        $selectedDoc = $this->model->getOnlyDoc();
        $listSimplifiedFiles = $this->model->getSimplifiedFileDoc();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormForDoc($selectedDoc, $listSimplifiedFiles, $associationDisplay);
    }

    /**
     * Gestion de la mise à jour du document sélectionné
     *
     * @return void
     */
    public function updateDBDoc() {
        $id = $_GET['id'];
        function valid_donneesDocBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nomDossier = valid_donneesDocBeforeUpdating($_POST['nomDossier']);
        $nameDoc = valid_donneesDocBeforeUpdating($_POST['nameDoc']);
        $dateDoc = valid_donneesDocBeforeUpdating($_POST['dateDoc']);
        $typeDoc = valid_donneesDocBeforeUpdating($_POST['typeDoc']);
        $descriptionForDoc = valid_donneesDocBeforeUpdating($_POST['descriptionForDoc']);

        if (isset($nomDossier) && isset($nameDoc) && isset($dateDoc) && isset($typeDoc) && isset($descriptionForDoc) && !empty($nomDossier) && !empty($nameDoc) 
        && !empty($dateDoc) && !empty($typeDoc) && !empty($descriptionForDoc)) {
            $this->model->updateDBDoc();
            $this->model->setFlash('success', 'Les modifications ont bien été prises en compte');
            header('location:index.php?controller=document&action=show&id=' . $nomDossier . '');
        } else {
            $this->model->setFlash('danger', 'Les modifications n\'ont pas été prises en compte');
            header('location:index.php?controller=document&action=updateFormForDoc&id=' . $id . '');
        }
    }

    /**
     * Gestion de la supression d'un document
     *
     * @return void
     */
    public function suppDBDoc()
    {
        $this->model->suppDBDoc();
        header('location:index.php?controller=document&action=listFiles');
    }

    /**
     * Gestion de l'affichage du formulaire de l'ajout d'une news
     *
     * @return void
     */
    public function addFormNews() {
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addFormNews($associationDisplay);
    }

    /**
     * Gestion de l'insertion en BDD - ajout de news
     *
     * @return void
     */
    public function addNews() {
        function valid_donneesNewsBeforeAdding($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nameNews = valid_donneesNewsBeforeAdding($_POST['nameNews']);
        $dateNews = valid_donneesNewsBeforeAdding($_POST['dateNews']);
        $descriptionForNews = valid_donneesNewsBeforeAdding($_POST['descriptionForNews']);

        if (isset($nameNews) && isset($dateNews) && isset($descriptionForNews) && !empty($nameNews) && !empty($dateNews) && !empty($descriptionForNews)) {
            $this->model->addDBNews();
            header('location:index.php?controller=document&action=listFiles');
        } else {
            header('location:index.php?controller=document&action=addNews');
        }
    }

    /**
     * Gestion de l'affichage du formulaire de modification d'une news
     *
     * @return void
     */
    public function updateFormForNews() {
        $selectedNews = $this->model->getOnlyNews();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormForNews($selectedNews, $associationDisplay);
    }

    public function updateNews() {
        function valid_donneesNewsBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $id = valid_donneesNewsBeforeUpdating($_POST['id']);
        $nameNews = valid_donneesNewsBeforeUpdating($_POST['nameNews']);
        $dateNews = valid_donneesNewsBeforeUpdating($_POST['dateNews']);
        $descriptionForNews = valid_donneesNewsBeforeUpdating($_POST['descriptionForNews']);

        if (isset($id) && isset($nameNews) && isset($dateNews) && isset($descriptionForNews) && !empty($nameNews) && !empty($dateNews) && !empty($descriptionForNews) 
        && !empty($id)) {
            $this->model->updateDBNews();
            header('location:index.php?controller=document&action=listFiles');
        } else {
            header('location:index.php?controller=document&action=updateFormForNews&id = ' .$id. '');
        }
    }

    /**
     * Gestion de la suppression d'un item dans la table news
     *
     * @return void
     */
    public function suppDBNews() {
        $this->model->suppDBNews();
        header('location:index.php?controller=document&action=listFiles');
    }

    /**
     * Autocompletion de la recherche de documents
     *
     * @return void
     */
    public function ajaxAutocompletionDocs()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteDocs = $_POST['searchTerm'];
            $data = $this->model->ajaxFileDocs($requeteDocs);
            echo json_encode($data);
        }
    }

        /**
     * Gestion du détail d'un document suite à sa recherche
     *
     * @return void
     */
    public function detailDoc()
    {
        if (isset($_POST['sel_docs'])) {
            $detailDocAsso = $_POST['sel_docs'];
            $listSimplifiedFile = $this->model->getOnlyFile($detailDocAsso);
            $listDocFromFiles = $this->model->getDocFileBreakdownAutoComplete($detailDocAsso);
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->show($listSimplifiedFile, $listDocFromFiles, $associationDisplay);
        }
    }

 }