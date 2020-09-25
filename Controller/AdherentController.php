<?php

include 'Model/AdherentModel.php';
include 'View/AdherentView.php';

class AdherentController extends Controller
{


    public function __construct()
    {
        $this->view = new AdherentView();
        $this->model = new AdherentModel();
    }
    //######################################################
    /**
     * Construction de la page d'accueil
     * Liste des informations
     * @return void
     ******************************************************/
    public function start()
    {
        $listAdherents = $this->model->getAdherents();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($listAdherents, $associationDisplay);
    }

    /**
     * Fonction gestion de la page anciens adhérents
     *
     * @return void
     */
    public function showOldMember()
    {
        $listOldAdherents = $this->model->getOldAdherents();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->showOldMember($listOldAdherents, $associationDisplay);
    }

    /**
     * Fonction export du tableau adhérent en format CSV
     *
     * @return void
     */
    public function export_data_to_csv()
    {
        $listSimplifiedMembers = $this->model->getSimplifiedMembers();
        $this->model->export_data_to_csv($listSimplifiedMembers);
    }

    /**
     * Affichage de la page de l'adhérent
     *
     * @return void
     */
    public function modal()
    {
        $adherent = $this->model->getAdherent();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->modal($adherent, $associationDisplay);
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout
     *
     * @return void
     */
    public function addForm()
    {
        $listFunctions = $this->model->getFunctions();
        $listStatuts = $this->model->getStatuts();
        $listRegulations = $this->model->getRegulations();
        $listCotisations = $this->model->getCotisations();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addForm($listFunctions, $listStatuts, $listRegulations, $listCotisations, $associationDisplay);
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
        $nom = valid_donnees($_POST['nom']);
        $prenom = valid_donnees($_POST['prenom']);
        $email = valid_donnees($_POST['email']);
        $tel = valid_donnees($_POST['tel']);
        $fonction = $_POST['fonction'];
        $statut = $_POST['statut'];
        $cotisation = $_POST['cotisation'];
        $reglement = $_POST['reglement'];
        $checkNotification = "";
        if (empty($_POST['notification'])) {
            $checkNotification = "";
        } else {
            $checkNotification = $_POST['notification'];
        }
        // $checkNotification = $_POST['notification'];
        $mailChecked = $this->model->checkMemberOrUserWithEmail($email);

        if ($mailChecked == false && $checkNotification == "" && isset($nom) && isset($prenom) && isset($email) && isset($tel) 
        && isset($fonction) && isset($statut) && isset($cotisation) && isset($reglement) && !empty($nom) 
        && !empty($prenom) && !empty($email) && !empty($tel) && !empty($fonction) && !empty($statut) 
        && !empty($cotisation) && !empty($reglement)) {
            $this->model->addDB();
            $this->model->setFlash('success', 'L\'ajout de l\'adhérent a bien été effectué');
            header('location:index.php?controller=adherent');
        } elseif ($mailChecked == false && $checkNotification == "checked" && isset($nom) && isset($prenom) && isset($email) && isset($tel) 
        && isset($fonction) && isset($statut) && isset($cotisation) && isset($reglement) && !empty($nom) 
        && !empty($prenom) && !empty($email) && !empty($tel) && !empty($fonction) && !empty($statut) 
        && !empty($cotisation) && !empty($reglement)) {
            $this->model->addDB();
            $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
            $lastMemberRegistered = $this->model->getMemberOnlyBeforeSendingMail();
            $this->model->createPdfBeforeSendingMember($associationDisplayForPdf, $lastMemberRegistered);
            $this->model->setFlash('success', 'L\'ajout de l\'adhérent et l\'envoi du mail de confirmation d\'enregistrement ont bien été effectués');
            header('location:index.php?controller=adherent');
        } else {
            $this->model->setFlash('danger', 'Le mail renseigné est déjà pris, vous ne pouvez pas effectuer d\'ajout');
            header('location:index.php?controller=adherent&action=addForm');
        }
    }

    /**
     * Gestion de la supression d'un item
     *
     * @return void
     */
    public function suppDB()
    {
        $this->model->suppDB();
        $this->model->setFlash('success', 'L\'adhérent a bien été supprimé');
        header('location:index.php?controller=adherent');
    }

    /**
     * Gestion de l'affichage de la radiation d'un adhérent
     *
     * @return void
     */
    public function cancelledMemberForm()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $adherent = $this->model->getAdherent();
        $this->view->cancelledMemberForm($associationDisplay, $adherent);
    }

    /**
     * Gestion de la radiation de l'adhérent
     *
     * @return void
     */
    public function cancelledMember()
    {
        $this->model->cancelledMember();
        $this->model->setFlash('success', 'L\'adhérent a bien été radié');
        header('location:index.php?controller=adherent');
    }

    /**
     * Gestion de la modification d'un item adhérent
     *
     * @return void
     */
    public function updateForm()
    {
        $listFunctions = $this->model->getFunctions();
        $listStatuts = $this->model->getStatuts();
        $listRegulations = $this->model->getRegulations();
        $listCotisations = $this->model->getCotisations();
        $listJobs = $this->model->getJobs();
        $adherent = $this->model->getAdherent();
        $listAdherentGroup = $this->model->getAdherentGroup();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateForm($adherent, $listFunctions, $listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay, $listAdherentGroup);
    }

    /**
     * Gestion de la modification d'un item adhérent si le membre est connecté
     *
     * @return void
     */
    public function updateFormOnlyMember()
    {
        $listFunctions = $this->model->getFunctions();
        $listStatuts = $this->model->getStatuts();
        $listRegulations = $this->model->getRegulations();
        $listCotisations = $this->model->getCotisations();
        $listJobs = $this->model->getJobs();
        $adherent = $this->model->getAdherent();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormOnlyMember($adherent, $listFunctions, $listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay);
    }

    /**
     * Mise à jour de l'information dans la table adhérent
     *
     * @return void
     */
    public function updateDB()
    {
        $id = $_GET['id'];
        function valid_donneesBeforeUpdating($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nom = valid_donneesBeforeUpdating($_POST['nom']);
        $prenom = valid_donneesBeforeUpdating($_POST['prenom']);
        $email = valid_donneesBeforeUpdating($_POST['email']);
        $tel = valid_donneesBeforeUpdating($_POST['tel']);
        $dateEntree = $_POST['dateEntree'];
        $fonction = $_POST['fonction'];
        $statut = $_POST['statut'];
        $cotisation = $_POST['cotisation'];
        $reglement = $_POST['reglement'];
        if (isset($nom) && isset($prenom) && isset($email) && isset($tel) && isset($dateEntree) && isset($fonction) && isset($statut) && isset($cotisation) && isset($reglement) 
        && !empty($nom) && !empty($prenom) && !empty($email) && !empty($tel) && !empty($dateEntree) && !empty($fonction) && !empty($statut) && !empty($cotisation) 
        && !empty($reglement) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->model->updateDB();
            $this->model->setFlash('success', 'Les modifications ont bien été prises en compte');
            header('location:index.php?controller=adherent&action=modal&id=' . $id . '');
        } else {
            $this->model->setFlash('danger', 'Les modifications n\'ont pas été prises en compte');
            header('location:index.php?controller=adherent&action=updateForm&id=' . $id . '');
        }
    }

    /**
     * Mise à jour de l'information dans la table adhérent cas du membre connecté
     *
     * @return void
     */
    public function updateDBOnlyMember()
    {
        $id = $_GET['id'];
        function valid_donneesBeforeUpdatingOnlyMember($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nom = valid_donneesBeforeUpdatingOnlyMember($_POST['nom']);
        $prenom = valid_donneesBeforeUpdatingOnlyMember($_POST['prenom']);
        $email = valid_donneesBeforeUpdatingOnlyMember($_POST['email']);
        $tel = valid_donneesBeforeUpdatingOnlyMember($_POST['tel']);

        if (isset($nom) && isset($prenom) && isset($email) && isset($tel) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($tel) 
        && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->model->updateDBOnlyMember();
            $this->model->setFlash('success', 'Les modifications ont bien été prises en compte');
            header('location:index.php?controller=adherent&action=modal&id=' . $id . '');
        } else {
            header('location:index.php?controller=adherent&action=updateFormOnlyMember&id=' . $id . '');
        }
    }

    /**
     * Autocompletion de la recherche des postes
     *
     * @return void
     */
    public function ajaxAutocompletionJobs()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteJobs = $_POST['searchTerm'];
            $data = $this->model->ajaxFileJobs($requeteJobs);
            echo json_encode($data);
        }
    }

    /**
     * Autocompletion de la recherche des membres adhérents
     *
     * @return void
     */
    public function ajaxAutocompletionMembers()
    {
        if (isset($_POST['searchTerm'])) {
            $requeteMembers = $_POST['searchTerm'];
            $data = $this->model->ajaxFileMembers($requeteMembers);
            echo json_encode($data);
        }
    }

    /**
     * Gestion du détail d'un adhérent suite à sa recherche
     *
     * @return void
     */
    public function detailMember()
    {
        if (isset($_POST['sel_members'])) {
            $detailMemberAsso = $_POST['sel_members'];
            $adherent = $this->model->getOnlyMember($detailMemberAsso);
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->modal($adherent, $associationDisplay);
        }
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
            $associationDisplay = $this->model->getFullAssociation();
            $this->view->prepareToSend($memberSelect, $associationDisplay);
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
            header('location:index.php?controller=adherent');
        } else {
            header('location:index.php?controller=adherent');
        }
    }

    /**
     * Fonction création du pdf pour un adhérent sélectionné
     *
     * @return void
     */
    public function pdfReceipt(){
        $id = $_GET['id'];
        $adherent = $this->model->getAdherent();
        $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
        $this->model->createPdfReceiptForMember($associationDisplayForPdf, $adherent);
        header('location:index.php?controller=modal&id='.$id.'');
    }
}
