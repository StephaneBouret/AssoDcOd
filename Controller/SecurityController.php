<?php

include "Model/SecurityModel.php";
include "View/SecurityView.php";

class SecurityController extends Controller {

    public function __construct()
    {
        $this->view = new SecurityView();
        $this->model = new SecurityModel();
    }

    /**
     * Afficher le formulaire de Login
      *
     * @return void
     */
    public function formLogin(){
        $user = $this->model->connectFromCookie();
        if ($user) {
            header('location:index.php?controller=dashboard');
        } else {
            $this->view->addFormLogin();
        }
    }

    /**
     * Vérification du login
     *
     * @return void
     */
    public function login()
    {
        $user = $this->model->testlogin();
        $verifPw = $this->model->testlogin();
        if ($user && $verifPw) {
            header('location:index.php?controller=dashboard');
        } else {
            header('location:index.php?controller=security&action=formLogin');
        }
    }

    /**
     * Afficher le formulaire d'enregistrement
     *
     * @return void
     */
    public function formRegister(){
        $this->view->registerForm();
    }

    /**
     * Gestion de l'enregistrement d'un membre à l'application
     *
     * @return void
     */
    public function registerMember() {
        function valid_donneesBeforeRecording($donnees){
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }

        $email = valid_donneesBeforeRecording($_POST['email']);
        $password = valid_donneesBeforeRecording($_POST['password']);
        $password_confirm = valid_donneesBeforeRecording($_POST['password_confirm']);
        $errors = [];

        $regexCharacterChoice = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)/';
        // (?=.*[a-z]) permet de tester la présence de minuscules.
        // (?=.*[A-Z]) permet de tester la présence de majuscules.
        // (?=.*[0-9]) permet de tester la présence de chiffres.
        // (?=.*\W) permet de tester la présence de caractères spéciaux (\W indique ce qui ne correspond pas à un mot).

        if (empty($password) || strlen($password) < 8 || !preg_match($regexCharacterChoice, $password) || $password != $password_confirm) {
            $errors['password'] = 'Vous devez rentrer un mot de passe valide';
            $_SESSION['flash']['danger'] = 'Vous devez rentrer un mot de passe valide';
            header('location:index.php?controller=security&action=formRegister');
        }
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $errors['email'] = "Attention, votre email doit respecter la syntaxe suivante :<br>xxx@xx.com(fr)";
            $_SESSION['flash']['danger'] = 'Attention, votre email doit respecter la syntaxe suivante :<br>xxx@xx.com(fr)';
            header('location:index.php?controller=security&action=formRegister');
        } else {
            $userFound = $this->model->getMemberFound($email);
            $userCheckedDateOut = $this->model->checkMemberIfValidByDateOutBeforeRegister($email);
            $dateSortie = $userCheckedDateOut['date_sortie'];
            $idGroupeAdherent = $userCheckedDateOut['id_groupeAdherent'];

            if($userFound == false){
                $errors['email'] = 'Cet email est inconnu';
                $this->model->setFlash('danger', "Cet email est inconnu");
                header('location:index.php?controller=security&action=formRegister');
            }
            if($dateSortie != NULL && $idGroupeAdherent == NULL){
                $errors['email'] = 'Vous n\'avez plus accès à l\'application';
                $this->model->setFlash('danger', "Vous n'avez plus accès à l'application");
                header('location:index.php?controller=security&action=formRegister');
            }
        }
        if (empty($errors)) {
            $associationDisplay = $this->model->getFullAssociation();
            $this->model->registerMember($associationDisplay, $userFound);
            header('location:index.php?controller=security&action=formRegister');
        }
    }

    /**
     * Gestion de la confirmation du token
     *
     * @return void
     */
    public function confirm() {
        $user_id = $_GET['id'];
        $token = $_GET['token'];
        $userConfirmed = $this->model->confirmToken($user_id, $token);

        if ($userConfirmed) {
            $this->model->setFlash('success', 'Votre compte a bien été validé');
            // $_SESSION['flash']['success'] = 'Votre compte a bien été validé';
            header('location:index.php?controller=security&action=formLogin');
        } else {
            $this->model->setFlash('danger', "Ce token n'est plus valide");
            // $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
            header('location:index.php?controller=security&action=formRegister');
        }
    }

    /**
     * Suppression de la connexion
     *
     * @return void
     */
    public function logout()
    {
        $this->model->logout();
        header('location:index.php?controller=security&action=formLogin');
    }

    /**
     * Afficher le formulaire oubli du mot de passe
     *
     * @return void
     */
    public function formForget()
    {
        $this->view->addForget();
    }

    /**
     * Vérification de l'email suite oubli mot de passe
     *
     * @return void
     */
    public function forget()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $userForget = $this->model->testForget($associationDisplay);
        if ($userForget){
            header('location:index.php?controller=security&action=formLogin');
        } else {
            header('location:index.php?controller=security&action=formForget');
        }
    }

    /**
     * Gestion de la confirmation du token lors de l'oubli du mot de passe
     *
     * @return void
     */
    public function confirmForget() {
        $user_id = $_GET['id'];
        $token = $_GET['token'];
        $userForgetConfirmed = $this->model->confirmTokenAfterForget($user_id, $token);

        if ($userForgetConfirmed) {
            $this->model->setFlash('success', 'Votre compte est à nouveau validé');
            // $_SESSION['flash']['success'] = 'Votre compte est à nouveau validé';
            header('location:index.php?controller=security&action=formReset');
        } else {
            $this->model->setFlash('danger', "Ce token n'est plus valide");
            // $_SESSION['flash']['danger'] = "Ce token n'est plus valide";
            header('location:index.php?controller=security&action=formLogin');
        }
    }

    /**
     * Gestion de la modification du mot de passe
     *
     * @return void
     */
    public function changePW()
    {
        $id = $_GET['id'];
        $passwordInDB = $this->model->getPasswordInDB($id);
        // $passwordInDB = $_SESSION['user']['password'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];
        $verifOldPW = password_verify($_POST['old-password'], $passwordInDB['password']);
        $regexCharacterChoice = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)/';
        $errors = [];
        
        if ($verifOldPW === false) {
            $errors['password'] = 'Votre ancien mot de passe est incorrect';
            $this->model->setFlash('danger', 'Votre ancien mot de passe est incorrect');
            header('location:index.php?controller=adherent&action=connexionForm&id='.$id.'');
        }
        if (empty($password) || strlen($password) < 8 || !preg_match($regexCharacterChoice, $password)) {
            $errors['password'] = 'Vous devez rentrer un mot de passe valide';
            $this->model->setFlash('danger', 'Vous devez rentrer un mot de passe valide');
            header('location:index.php?controller=adherent&action=connexionForm&id='.$id.'');
        }
        if ($password != $password_confirm) {
            $errors['password'] = 'Les mots de passe ne correspondent pas';
            $this->model->setFlash('danger', 'Les mots de passe ne correspondent pas');
            header('location:index.php?controller=adherent&action=connexionForm&id='.$id.'');
        }
        if (empty($errors)) {
            $this->model->changePW();
            $this->model->setFlash('success', 'Votre mot de passe a bien été modifié');
            header('location:index.php?controller=adherent&action=connexionForm&id='.$id.'');
        }
    }

    /**
     * Gestion de l'affichage du formulaire pour réinitialiser le mot de passe
     *
     * @return void
     */
    public function formReset()
    {
        $this->view->addReset();
    }

    /**
     * Fonction pour réinitialiser le mot de passe
     *
     * @return void
     */
    public function reset()
    {
        $userReset = $this->model->resetPW();
        if ($userReset){
            unset($_SESSION);
            header('location:index.php?controller=security&action=formLogin');
        } else {
            header('location:index.php?controller=security&action=formReset');
        }

    }

}