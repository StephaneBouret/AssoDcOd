<?php

include "Model/UserModel.php";
include "View/UserView.php";

class UserController extends Controller {

    public function __construct()
    {
        $this->view = new UserView();
        $this->model = new UserModel();
    }

    /**
     * Construction de la page d'accueil - table users
     *
     * @return void
     */
    public function start(){
        $associationDisplay = $this->model->getFullAssociation();
        $listUsers = $this->model->getUsers();
        $this->view->displayHome($listUsers, $associationDisplay);
    }

    /**
     * Gestion de l'affichage du formulaire d'ajout d'un utilisateur
     *
     * @return void
     */
    public function addUserForm()
    {
        $listAdherentGroup = $this->model->getAdherentGroup();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->addUserForm($listAdherentGroup, $associationDisplay);
    }

    /**
     * Gestion de l'ajout d'un utilisateur
     *
     * @return void
     */
    public function addDBUser()
    {
        function valid_donneesForUser($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nom = valid_donneesForUser($_POST['nom']);
        $prenom = valid_donneesForUser($_POST['prenom']);
        $email = valid_donneesForUser($_POST['email']);
        $tel = valid_donneesForUser($_POST['tel']);
        $cotisation = $_POST['cotisation'];
        $checkNotification = "";
        if (empty($_POST['notification'])) {
            $checkNotification = "";
        } else {
            $checkNotification = $_POST['notification'];
        }

        $mailChecked = $this->model->checkMemberOrUserWithEmail($email);
        if ($mailChecked == false && $checkNotification == "" && isset($nom) && isset($prenom) && isset($email) && isset($tel) 
        && isset($cotisation) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($tel)  
        && !empty($cotisation)) {
            $this->model->addDBUser();
            $this->model->setFlash('success', 'L\'ajout de l\'utilisateur a bien été pris en compte');
            header('location:index.php?controller=user');
        } elseif ($mailChecked == false && $checkNotification == "checked" && isset($nom) && isset($prenom) && isset($email) && isset($tel) 
        && isset($cotisation) && !empty($nom) && !empty($prenom) && !empty($email) && !empty($tel) && !empty($cotisation)) {
            $this->model->addDBUser();
            $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
            $lastMemberRegistered = $this->model->getMemberOnlyBeforeSendingMail();
            $this->model->sendMailForUserAfterAdding($associationDisplayForPdf, $lastMemberRegistered);
            $this->model->setFlash('success', 'Le mail d\'enregistrement à l\'application a bien été envoyé et l\'ajout de l\'utilisateur a bien été pris en compte');
            header('location:index.php?controller=User');
        } else {
            $this->model->setFlash('danger', 'Le mail renseigné est déjà pris, vous ne pouvez pas effectuer d\'ajout');
            header('location:index.php?controller=user&action=addUserForm');
        }
    }

    /**
     * Gestion de la suppression d'un utilisateur
     *
     * @return void
     */
    public function suppDBUser()
    {
        $this->model->suppDBUser();
        $this->model->setFlash('success', 'L\'utilisateur a bien été supprimé');
        header('location:index.php?controller=user');
    }

    /**
     * Gestion de la radiation d'un utilisateur
     *
     * @return void
     */
    public function cancelledUser()
    {
        $this->model->cancelledUser();
        $this->model->setFlash('success', 'L\'utilisateur a bien été radié');
        header('location:index.php?controller=user');
    }

    /**
     * Gestion de la modification des droits dans la table adherents
     *
     * @return void
     */
    public function updateForm(){
        $associationDisplay = $this->model->getFullAssociation();
        $user = $this->model->getUser();
        $this->view->updateForm($user, $associationDisplay);
    }

    /**
     * Mise à jour de l'information dans la table users
     *
     * @return void
     */
    public function updateDB(){
        $this->model->updateDB();
        $this->model->setFlash('success', 'Les modifications des droits ont bien été prises en compte');
        header('location:index.php?controller=User');
    }

    /**
     * Fonction de la gestion de la mise à jour de l'utilisateur
     *
     * @return void
     */
    public function updateDBUser()
    {
        $id = $_POST['id'];
        function valid_donneesBeforeUpdatingForUser($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $nom = valid_donneesBeforeUpdatingForUser($_POST['nom']);
        $prenom = valid_donneesBeforeUpdatingForUser($_POST['prenom']);
        $email = valid_donneesBeforeUpdatingForUser($_POST['email']);
        $tel = valid_donneesBeforeUpdatingForUser($_POST['tel']);

        if (isset($nom) && isset($prenom) && isset($email) && isset($tel) && !empty($nom) && !empty($prenom) && !empty($email) 
        && !empty($tel) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $this->model->updateDBUser();
            $this->model->setFlash('success', 'Les modifications ont bien été prises en compte');
            header('location:index.php?controller=adherent&action=modal&id=' . $id . '');
        } else {
            $this->model->setFlash('danger', 'Les modifications n\'ont pas été prises en compte');
            header('location:index.php?controller=adherent&action=updateForm&id=' . $id . '');
        }
    }

    /**
     * Gestion de l'affichage du formulaire pour la migration de l'utilisateur
     *
     * @return void
     */
    public function updateDBQualite(){
        $qualityUSer = $_POST['qualityUser'];
        $listFunctions = $this->model->getFunctions();
        $listStatuts = $this->model->getStatuts();
        $listRegulations = $this->model->getRegulations();
        $listCotisations = $this->model->getCotisations();
        $listJobs = $this->model->getJobs();
        $adherent = $this->model->getAdherent();
        $listAdherentGroup = $this->model->getAdherentGroup();
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->updateFormUserToAdherent($adherent, $listFunctions, $listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay, $listAdherentGroup, $qualityUSer);
    }

    /**
     * Fonction de la migration de l'utilisateur vers l'adhérent
     *
     * @return void
     */
    public function migrateDBUserToAdherent()
    {
        $id = $_POST['id'];
        function valid_donneesBeforeMigratingToAdherent($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $fonction = valid_donneesBeforeMigratingToAdherent($_POST['fonction']);
        $statut = valid_donneesBeforeMigratingToAdherent($_POST['statut']);
        $dateEntree = valid_donneesBeforeMigratingToAdherent($_POST['dateEntree']);
        $cotisation = valid_donneesBeforeMigratingToAdherent($_POST['cotisation']);
        $reglement = valid_donneesBeforeMigratingToAdherent($_POST['reglement']);

        $checkNotification = "";
        if (empty($_POST['notification'])) {
            $checkNotification = "";
        } else {
            $checkNotification = $_POST['notification'];
        }

        if ($checkNotification == "" && isset($fonction) && isset($statut) && isset($dateEntree) && isset($cotisation) && isset($reglement) 
        && !empty($fonction) && !empty($statut) && !empty($dateEntree) && !empty($cotisation) && !empty($reglement)) {
            $this->model->migrateDBUserToAdherent();
            $this->model->setFlash('success', 'La migration vers le statut adhérent a bien été prise en compte');
            header('location:index.php?controller=user&action=start');
        } elseif ($checkNotification == "checked" && isset($fonction) && isset($statut) && isset($dateEntree) && isset($cotisation) && isset($reglement) 
        && !empty($fonction) && !empty($statut) && !empty($dateEntree) && !empty($cotisation) && !empty($reglement)) {
            $this->model->migrateDBUserToAdherent();
            $associationDisplayForPdf = $this->model->getFullAssociationForPdf();
            $lastMemberRegistered = $this->model->switchUserToMemberBeforeSendingMail();
            $this->model->createPdfBeforeSendingMember($associationDisplayForPdf, $lastMemberRegistered);
            $this->model->setFlash('success', 'Le mail d\'enregistrement à l\'application a bien été envoyé et l\'ajout de l\'adhérent a bien été pris en compte');
            header('location:index.php?controller=User');
        } else {
            $this->model->setFlash('danger', 'Erreur, la migration ne s\'est pas effectuée');
            header('location:index.php?controller=user&action=updateDBQualite&id=' . $id . '');
        }
    }

    /**
     * Fonction de la migration de l'adhérent vers l'utilisateur
     *
     * @return void
     */
    public function migrateDBAdherentToUser()
    {
        $id = $_POST['id'];
        function valid_donneesBeforeMigratingToUser($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $dateSortie = valid_donneesBeforeMigratingToUser($_POST['dateSortie']);
        $groupAdherent = valid_donneesBeforeMigratingToUser($_POST['groupAdherent']);
        $checkNotification = "";
        if (empty($_POST['notification'])) {
            $checkNotification = "";
        } else {
            $checkNotification = $_POST['notification'];
        }

        if ($checkNotification == "" && isset($dateSortie) && isset($groupAdherent) && !empty($dateSortie) && !empty($groupAdherent)) {
            $this->model->migrateDBAdherentToUser();
            $this->model->setFlash('success', 'La migration vers le statut utilisateur a bien été prise en compte');
            header('location:index.php?controller=user&action=start');
        } elseif ($checkNotification == "checked" && isset($dateSortie) && isset($groupAdherent) && !empty($dateSortie) && !empty($groupAdherent)) {
            $this->model->migrateDBAdherentToUser();
            $associationDisplay = $this->model->getFullAssociation();
            $memberForRegister = $this->model->switchUserToMemberBeforeSendingMail();
            $this->model->sendMailForRegisterForUserSwitch($associationDisplay, $memberForRegister);
            $this->model->setFlash('success', 'Le mail d\'enregistrement à l\'application a bien été envoyé et l\'ajout de l\'utilisateur a bien été pris en compte');
            header('location:index.php?controller=User');
        } else {
            $this->model->setFlash('danger', 'Erreur, la migration ne s\'est pas effectuée');
            header('location:index.php?controller=user&action=updateDBQualite&id=' . $id . '');
        }
    }

    /**
     * Gestion de l'envoi d'un mail à l'utilisateur adhérent pour s'enregistrer à l'appli
     *
     * @return void
     */
    public function sendMailForRegister() {
        function valid_donneesBeforeSendingMailForRegister($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $id = valid_donneesBeforeSendingMailForRegister($_POST['id']);
        if (isset($id) && !empty($id)) {
            $associationDisplay = $this->model->getFullAssociation();
            $memberForRegister = $this->model->getMemberForRegister();
            $this->model->sendMailForRegister($associationDisplay, $memberForRegister);
            $this->model->setFlash('success', 'Le mail d\'enregistrement à l\'application a bien été envoyé');
            header('location:index.php?controller=User');
        } else {
            header('location:index.php?controller=User&action=updateForm&id='.$id.'');
        }
    }

    /**
     * Gestion de l'envoi d'un mail à l'utilisateur adhérent pour s'enregistrer à l'appli
     *
     * @return void
     */
    public function sendMailForRegisterForUser() {
        function valid_donneesBeforeSendingMailForRegisterForUser($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $id = valid_donneesBeforeSendingMailForRegisterForUser($_POST['id']);
        if (isset($id) && !empty($id)) {
            $associationDisplay = $this->model->getFullAssociation();
            $memberForRegister = $this->model->switchUserToMemberBeforeSendingMail();
            $this->model->sendMailForRegisterForUser($associationDisplay, $memberForRegister);
            // $this->model->setFlash('success', 'Le mail d\'enregistrement à l\'application a bien été envoyé');
            // header('location:index.php?controller=User');
        } else {
            header('location:index.php?controller=User&action=updateForm&id='.$id.'');
        }
    }
}