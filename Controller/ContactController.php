<?php

include "Model/ContactModel.php";
include "View/ContactView.php";

class ContactController extends Controller {

    public function __construct()
    {
        $this->view = new ContactView();
        $this->model = new ContactModel();
    }

    /**
     * Construction de la page de contact
     *
     * @return void
     */
    public function start(){
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($associationDisplay);
    }

    /**
     * Gestion de l'envoi d'un mail page contact
     *
     * @return void
     */
    public function sendMessage() {
        function valid_donneesBeforeSendingMailForContact($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $phone_expr = '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/';
        $first_name = valid_donneesBeforeSendingMailForContact($_POST['first_name']);
        $last_name = valid_donneesBeforeSendingMailForContact($_POST['last_name']);
        $emailContact = valid_donneesBeforeSendingMailForContact($_POST['emailContact']);
        $phoneContact = valid_donneesBeforeSendingMailForContact($_POST['phoneContact']);
        $problem_type = valid_donneesBeforeSendingMailForContact($_POST['problem_type']);
        $messageContact = valid_donneesBeforeSendingMailForContact($_POST['messageContact']);

        if (isset($first_name) && isset($last_name) && isset($emailContact) && isset($phoneContact) && isset($problem_type) && isset($messageContact) 
        && !empty($first_name) && !empty($last_name) && !empty($emailContact) && !empty($phoneContact) && !empty($problem_type) && !empty($messageContact) 
        && filter_var($emailContact, FILTER_VALIDATE_EMAIL) && preg_match($phone_expr, $phoneContact)) {
            $associationDisplay = $this->model->getFullAssociation();
            $this->model->sendMailForContact($associationDisplay);
            $_SESSION['flash']['success'] = 'Votre message a bien été envoyé';
            header('location:index.php?controller=dashboard');
        } else {
            $_SESSION['flash']['danger'] = "Les champs ne sont pas remplis correctement";
            header('location:index.php?controller=Contact&action=start');
        }
    }
}