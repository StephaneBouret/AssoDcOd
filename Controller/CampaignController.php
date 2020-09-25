<?php

include "Model/CampaignModel.php";
include "View/CampaignView.php";

class CampaignController extends Controller {


    public function __construct()
    {
        $this->view = new CampaignView();
        $this->model = new CampaignModel();
    }

    /**
     * Construction de la page campagne d'adhésion
     *
     * @return void
     */
    public function start(){
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($associationDisplay);
    }

    /**
     * Gestion de l'ajout d'une campagne
     *
     * @return void
     */
    public function addDB()
    {
        function valid_donnees($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        } 
        $libelleCotisation = valid_donnees($_POST['libelleCotisation']);
        $tarifCotisation = valid_donnees($_POST['tarifCotisation']);
        $campaignStartDate = valid_donnees($_POST['campaignStartDate']);
        $campaignEndDate = valid_donnees($_POST['campaignEndDate']);
        $campaignLength = valid_donnees($_POST['campaignLength']);
        $rolesCampaign = $_POST['rolesCampaign'];

        if ($rolesCampaign == "1" && isset($libelleCotisation) && !empty($libelleCotisation) && isset($tarifCotisation) && !empty($tarifCotisation) 
        && isset($campaignStartDate) && !empty($campaignStartDate) && isset($campaignEndDate) && !empty($campaignEndDate)) {
            $this->model->addDBWithDateToDate();
            header('location:index.php?controller=campaign&action=listCampaigns');
        } elseif ($rolesCampaign == "2" && isset($libelleCotisation) && !empty($libelleCotisation) && isset($tarifCotisation) && !empty($tarifCotisation) 
        && isset($campaignLength) && !empty($campaignLength)) {
            $this->model->addDBWithMounthFixed();
            header('location:index.php?controller=campaign&action=listCampaigns');
        } else {
            header('location:index.php?controller=campaign&action=start');
        }
    }

    /**
     * Gestion de la page listant les campagnes d'adhésions existantes
     *
     * @return void
     */
    public function listCampaigns()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $listCotisations = $this->model->getCotisations();
        $this->view->displayCampaign($associationDisplay, $listCotisations);
    }

    /**
     * Fonction export du tableau en format CSV
     *
     * @return void
     */
    public function export_data_to_csv()
    {
        $listCotisations = $this->model->getCotisations();
        $this->model->export_data_to_csv($listCotisations);
    }

    /**
     * Gestion de l'affichage du formulaire de mise à jour de la campagne
     *
     * @return void
     */
    public function updateForm()
    {
        $cotisation = $this->model->getCotisation();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateForm($cotisation, $associationDisplay);
    }

    /**
     * Gestion de la supression d'une campagne
     *
     * @return void
     */
    public function suppDB(){
        $this->model->suppDB();
        header('location:index.php?controller=campaign&action=listCampaigns');
    }

        /**
     * Mise à jour de l'information dans la table campagne
     * Updating information in the table
     *
     * @return void
     */
    public function updateDB(){
        function valid_datasBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $libelleCotisation = valid_datasBeforeUpdating($_POST['libelleCotisation']);
        $tarifCotisation = valid_datasBeforeUpdating($_POST['tarifCotisation']);
        $tarifCotisationNette = valid_datasBeforeUpdating($_POST['tarifCotisationNette']);
        $campaignStartDate = valid_datasBeforeUpdating($_POST['campaignStartDate']);
        $campaignEndDate = valid_datasBeforeUpdating($_POST['campaignEndDate']);
        $campaignLength = valid_datasBeforeUpdating($_POST['campaignLength']);
        $rolesCampaign = $_POST['rolesCampaign'];
        $id = $_POST['idCotisation'];

        if ($rolesCampaign == "1" && isset($libelleCotisation) && !empty($libelleCotisation) && isset($tarifCotisationNette) && !empty($tarifCotisationNette) 
        && isset($tarifCotisation) && !empty($tarifCotisation) && isset($campaignStartDate) && !empty($campaignStartDate) && isset($campaignEndDate) && !empty($campaignEndDate)) {
            $this->model->updateDBWithDateToDate();
            header('location:index.php?controller=campaign&action=listCampaigns');
        } elseif ($rolesCampaign == "2" && isset($libelleCotisation) && !empty($libelleCotisation) && isset($tarifCotisationNette) && !empty($tarifCotisationNette) 
        && isset($tarifCotisation) && !empty($tarifCotisation) && isset($campaignLength) && !empty($campaignLength)) {
            $this->model->updateDBWithMounthFixed();
            header('location:index.php?controller=campaign&action=listCampaigns');
        } else {
            header('location:index.php?controller=campaign&action=updateForm&id=' . $id . '');
        }
    }

    /**
     * Fonction gérant l'affichage des membres arrivant à échéance
     *
     * @return void
     */
    public function listMembersForAlert(){
        $listCotisations = $this->model->getCotisations();
        $listAdherents = $this->model->getAdherents();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->listMembersForAlert($listCotisations, $associationDisplay, $listAdherents);
    }

    /**
     * Fonction permettant d'envoyer les ids sélectionnées afin de les matcher dans la table adherent
     *
     * @return void
     */
    public function prepareToSend()
    {
        if (isset($_POST['searchTable'])) {
            $memberSelect = $this->model->prepareToSend();
            $listCotisations = $this->model->getCotisations();
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->prepareToSend($memberSelect, $associationDisplay, $listCotisations);
        }
    }

    /**
     * Fonction permettant d'envoyer un mail à une ou plusieurs personnes sélectionnées
     *
     * @return void
     */
    public function sendMail()
    {
        function valid_donneesBeforeSendingMail($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $assoMail = valid_donneesBeforeSendingMail($_POST['AssoEmail']);
        $subjectMail = valid_donneesBeforeSendingMail($_POST['subjectMail']);
        $textForMail = valid_donneesBeforeSendingMail($_POST['textForMail']);
        if (isset($assoMail) && !empty($assoMail) && isset($subjectMail) && !empty($subjectMail) && isset($textForMail) && !empty($textForMail)) {
            $associationDisplay = $this->model->getFullAssociation();
            $this->model->sendMailForRelaunch($associationDisplay);
            header('location:index.php');
        } else {
            header('location:index.php?controller=campaign&action=listMembersForAlert');
        }
    }
}