<?php

include "Model/DonsModel.php";
include "View/DonsView.php";

class DonsController extends Controller {


    public function __construct()
    {
        $this->view = new DonsView();
        $this->model = new DonsModel();
    }

    /**
     * Construction de la page dons
     *
     * @return void
     */
    public function start(){
        $listDons = $this->model->getDons();
        $listRegulations = $this->model->getRegulations();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($listDons, $associationDisplay, $listRegulations);
    }

    /**
     * Fonction export du tableau en format CSV
     *
     * @return void
     */
    public function export_data_to_csv()
    {
        $listSimplifiedDonations = $this->model->getSimplifiedDonations();
        $this->model->export_data_to_csv($listSimplifiedDonations);
    }

    /**
     * Fonction permettant d'envoyer les ids sélectionnées afin de les matcher dans la table don
     *
     * @return void
     */
    public function prepareToSend()
    {
        if (isset($_POST['searchTable'])) {
            $donatorSelect = $this->model->prepareToSend();
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->prepareToSend($donatorSelect, $associationDisplay);
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
            $this->model->sendMail($associationDisplay);
            header('location:index.php?controller=dons');
        } else {
            header('location:index.php?controller=dons');
        }
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout
     *
     * @return void
     */
    public function addForm()
    {
        $listRegulations = $this->model->getRegulations();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addForm($listRegulations, $associationDisplay);
    }

    /**
     * Autocompletion de la recherche des membres pour les dons
     *
     * @return void
     */
    public function ajaxAutocompletionMemberNameForDonation()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteMemberName = $_POST['searchTerm'];
            $data = $this->model->ajaxFileMemberNameForDonation($requeteMemberName);
            echo json_encode($data);
        }
    }

    /**
     * Gestion de l'ajout d'un item
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
        $memberNameForDonation = valid_donnees($_POST['sel_MemberNameForDonation']);
        $montant = valid_donnees($_POST['montant']);
        $dateDons = valid_donnees($_POST['dateDons']);
        $reglement = valid_donnees($_POST['reglement']);
        $checkNotification = "";
        $checkNotification = $_POST['notification'];

        if ($checkNotification == "" && isset($memberNameForDonation) && isset($montant) && isset($dateDons) && isset($reglement) 
            && !empty($memberNameForDonation) && !empty($montant) && !empty($dateDons) && !empty($reglement)) {
            $this->model->addDB();
            header('location:index.php?controller=dons');
        } elseif ($checkNotification == "checked" && isset($memberNameForDonation) && isset($montant) && isset($dateDons) && isset($reglement) 
        && !empty($memberNameForDonation) && !empty($montant) && !empty($dateDons) && !empty($reglement)) {
            $this->model->addDB();
            $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
            $lastDonatorRegistered = $this->model->getDonatorOnlyBeforeSendingMail();
            $this->model->createPdfBeforeSending($associationDisplayForPdf, $lastDonatorRegistered);
            header('location:index.php?controller=dons');
        } else {
            header('location:index.php?controller=dons&action=addForm');
        }
    }

    /**
     * Gestion de la supression d'un item
     *
     * @return void
     */
    public function suppDB(){
        $this->model->suppDB();
        header('location:index.php?controller=dons');
    }
    /**
     * Gestion de la modification d'un item
     *
     * @return void
     */
    public function updateForm(){
        $don = $this->model->getDon();
        $associationDisplay = $this->model->getFullAssociation();
        $listRegulations = $this->model->getRegulations();
        $this->view->updateForm($don, $listRegulations, $associationDisplay);
    }

    /**
     * Mise à jour de l'information dans la table
     * Updating information in the table
     *
     * @return void
     */
    public function updateDB(){
        function valid_donneesBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $montant = valid_donneesBeforeUpdating($_POST['montant']);
        $dateDons = valid_donneesBeforeUpdating($_POST['dateDons']);
        $reglement = valid_donneesBeforeUpdating($_POST['reglement']);
        if (isset($montant) && !empty($montant) && isset($dateDons) && !empty($dateDons) && isset($reglement) && !empty($reglement)) {
            $this->model->updateDB();
            header('location:index.php?controller=dons');
        } else {
            header('location:index.php?controller=dons');
        }
    }

    /**
     * Fonction création du pdf pour un donateur sélectionné
     *
     * @return void
     */
    public function pdfReceipt(){
        $don = $this->model->getDon();
        $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
        $this->model->createPdfReceiptForDonator($associationDisplayForPdf, $don);
        header('location:index.php?controller=dons');
    }

    /**
     * Fonction permettant l'affichage des donateurs pour les reçus fiscaux
     *
     * @return void
     */
    public function taxReceipt(){
        $listDons = $this->model->getDons();
        $listTaxReceipt = $this->model->getTaxReceipt();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->taxReceipt($listDons, $associationDisplay, $listTaxReceipt);
    }

    /**
     * Fonction création du pdf reçu fiscal pour un donateur sélectionné
     *
     * @return void
     */
    public function pdfTaxReceipt(){
        $don = $this->model->getDon();
        $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
        $this->model->updateDonatorForTaxReceipt($don);
        $this->model->createPdfTaxReceiptForDonator($associationDisplayForPdf, $don);
        header('location:index.php?controller=dons');
    }

    /**
     * Fonction permettant d'envoyer un reçu fiscal à une ou plusieurs personnes sélectionnées
     *
     * @return void
     */
    public function prepareToSendMassTaxReceipt(){
        if (isset($_POST['searchTable'])) {
            $donatorSelect = $this->model->prepareToSend();
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->prepareToSendMassTaxReceipt($donatorSelect, $associationDisplay);
        }
    }

    /**
     * Fonction permettant l'envoi en masse des reçus fiscaux en fonction des donateurs sélectionnés
     *
     * @return void
     */
    public function sendMailMassTaxReceipt(){
        if (isset($_POST) && !empty($_POST)) {
            $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
            $this->model->updateDonatorForMassTaxReceipt();
            $this->model->sendMailMassTaxReceipt($associationDisplayForPdf);
            // header('location:index.php?controller=dons&action=taxReceipt');
        } else {
            header('location:index.php?controller=dons&action=taxReceipt');
        }
    }
}