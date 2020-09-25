<?php

class SecurityModel extends Model {


    /**
     * Fonction recherche présence de l'adhérent dans la table en fonction de l'email
     *
     * @param [type] $email
     * @return void
     */
    public function getMemberFound($email) {
        $requete = $this->connexion->prepare("SELECT id
        FROM adherent WHERE email=:email");
        $requete->bindParam(':email', $email);
        $result = $requete->execute();
        $userFound = $requete->fetch(PDO::FETCH_ASSOC);
        return $userFound;
    }

    /**
     * Fonction recherche si l'adhérent est actif (date de sortie null ou date de sortie non null et id_groupeadherent null) avant l'enregistrement à l'app
     *
     * @return void
     */
    public function checkMemberIfValidByDateOutBeforeRegister($email)
    {
        $requete = $this->connexion->prepare("SELECT date_sortie, id_groupeAdherent
        FROM adherent WHERE email=:email");
        $requete->bindParam(':email', $email);
        $result = $requete->execute();
        $userCheckedDateOut = $requete->fetch(PDO::FETCH_ASSOC);
        return $userCheckedDateOut;
    }

    /**
     * Fonction gestion de la création d'un cookie
     *
     * @param [type] $user_id
     * @param [type] $user_firstname
     * @param [type] $user_pwd
     * @return void
     */
    public function remember($user_id, $user_firstname, $user_name){
        $remember_token = $this->str_random(250);
        $secondReq = $this->connexion->prepare("UPDATE adherent SET remember_token = :remember_token WHERE id = :id");
        $secondReq->bindParam(':remember_token', $remember_token);
        $secondReq->bindParam(':id', $user_id);
        $secondResult = $secondReq->execute();
        setcookie('auth', $user_id . '----' . $remember_token . sha1($user_firstname . $user_name . $_SERVER['REMOTE_ADDR']), 
        time() + 60 * 60 * 24 * 3, '/', null, true, true);
    }

    /**
     * Fonction reconnexion avec cookie
     *
     * @return void
     */
    public function connectFromCookie() {
        if (isset($_COOKIE['auth']) && !isset($_SESSION['user'])) {
            $auth = $_COOKIE['auth'];
            $parts = explode('----', $auth);
            $user_id = $parts[0];
            $thirdReq = $this->connexion->prepare("SELECT * FROM adherent WHERE id = :id");
            $thirdReq->bindParam('id', $user_id);
            $result = $thirdReq->execute();
            $user = $thirdReq->fetch(PDO::FETCH_ASSOC);
            $remember_token = $user['remember_token'];
            $key = $remember_token . sha1($user['prenom'] . $user['nom'] . $_SERVER['REMOTE_ADDR']);
            if($key == $parts[1]) {
                $_SESSION['user'] = $user;
                setcookie('auth', $user['id'] . '----' . $remember_token . sha1($user['prenom'] . $user['nom'] . $_SERVER['REMOTE_ADDR']), 
                time() + 60 * 60 * 24 * 3, '/', null, true, true);
                return $user;
            } else {
                setcookie('auth', '', time() - 3600, '/', null, true, true);
            }
        } else {
            setcookie('auth', '', time() - 3600, '/', null, true, true);
        }
    }

    /**
     * Fonction confirmation si token reçu correspond au token enregistré dans la table
     *
     * @param [type] $user_id
     * @param [type] $token
     * @return void
     */
    public function confirmToken($user_id, $token) {
        $requete = $this->connexion->prepare("SELECT * FROM adherent WHERE id = :id");
        $requete->bindParam(':id', $user_id);
        $result = $requete->execute();
        $userToBeConfirmed = $requete->fetch(PDO::FETCH_ASSOC);

        if ($userToBeConfirmed && $userToBeConfirmed['confirmation_token'] == $token) {
            $requeteConfimed = $this->connexion->prepare("UPDATE adherent 
            SET confirmation_token = NULL, confirmed_at = NOW() WHERE id = :id");
            $requeteConfimed->bindParam(':id', $user_id);
            $userConfirmed = $requeteConfimed->execute();
            return $userConfirmed;
        }
    }

    /**
     * Fonction test Login
     *
     * @return void
     */
    public function testlogin(){
        $login = $_POST['login'];
        $password = $_POST['password'];
        unset($_SESSION['flash']);
        if (empty($_POST['remember'])) {
            $remember = "";
        } else {
            $remember = $_POST['remember'];
        }
        if (!empty($login) && !empty($password)) {
            /** Cadre PW crypté */
            $requete = $this->connexion->prepare("SELECT * 
            FROM adherent 
            WHERE email = :email AND confirmed_at IS NOT NULL");
            $requete->bindParam(':email', $login);
            $result = $requete->execute();
            $user = $requete->fetch(PDO::FETCH_ASSOC);
            $verifPw = password_verify($password, $user['password']);
            if ($user != false && $verifPw !=false) {
                if (!empty($remember)) {
                    $this->remember($user['id'], $user['prenom'], $user['nom']);
                }
                $_SESSION['user'] = $user;
                $this->setFlash('success', 'Vous êtes maintenant connecté(e)');
                // $_SESSION['flash']['success'] = 'Vous êtes maintenant connecté(e)';
                return $user;
                return $verifPw;
            } else{
                $this->setFlash('danger', 'Identifiant ou mot de passe incorrect');
                // $_SESSION['flash']['danger'] = 'Identifiant ou mot de passe incorrect';
            }
        } else {
            $this->setFlash('danger', 'Merci de renseigner votre adresse email et votre mot de passe');
            // $_SESSION['flash']['danger'] = 'Merci de renseigner votre adresse email et votre mot de passe';
        }
    }
    /**
     * Fonction déconnexion
     *
     * @return void
     */
    public function logout()
    {
        setcookie('auth', '', time() - 3600, '/', null, false, true);
        unset($_SESSION['user']);
        unset($_SESSION['flash']);
    }

    /**
     * Fonction confirmation si token reçu correspond au token enregistré dans la table lors de l'oubli du mot de passe
     *
     * @param [type] $user_id
     * @param [type] $token
     * @return void
     */
    public function confirmTokenAfterForget($user_id, $token) {
        $requete = $this->connexion->prepare("SELECT * FROM adherent 
        WHERE id = :id AND reset_token IS NOT NULL AND reset_token = :reset_token AND reset_at > DATE_SUB(NOW(), INTERVAL 30 MINUTE)");
        $requete->bindParam(':id', $user_id);
        $requete->bindParam(':reset_token', $token);
        $result = $requete->execute();
        $userToBeConfirmedAfterForget = $requete->fetch(PDO::FETCH_ASSOC);

        if ($userToBeConfirmedAfterForget) {
            $requete = $this->connexion->prepare("UPDATE adherent 
            SET reset_at = NULL, reset_token = NULL WHERE id = :id");
            $requete->bindParam(':id', $userToBeConfirmedAfterForget['id']);
            $userForgetConfirmed = $requete->execute();
            $_SESSION['userForget'] = $userToBeConfirmedAfterForget;
            return $userForgetConfirmed;
        }
    }

    /**
     * Fonction reset du mot de passe
     *
     * @return void
     */
    public function resetPW()
    {
        $password = $_POST['password'];
        $passwordConfirm = $_POST['password_confirm'];
        $id = $_SESSION['userForget']['id'];
        $regexCharacterChoice = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*[0-9])(?=.*\W)/';
        if (!empty($password) && preg_match($regexCharacterChoice, $password) && strlen($password) >= 8) {
            if($password == $passwordConfirm){
                $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
                $requete = $this->connexion->prepare("UPDATE adherent
                SET password = :password
                WHERE id = :id");
                $requete->bindParam(':id', $id);
                $requete->bindParam(':password', $encryptedPassword);
                $userReset = $requete->execute();
                if ($userReset) {
                    $this->setFlash('success', 'Votre mot de passe a bien été modifié');
                    // $_SESSION['flash']['success'] = 'Votre mot de passe a bien été modifié';
                    unset($_SESSION['userForget']);
                }
            } else {
                $this->setFlash('alert', 'Les mots de passe ne correspondent pas');
                // $_SESSION['flash']['alert'] = 'Les mots de passe ne correspondent pas';
            }
        } else {
            $this->setFlash('alert', 'Merci de renseigner votre mot de passe');
            // $_SESSION['flash']['alert'] = 'Merci de renseigner votre mot de passe';
        }
        return $userReset;
    }
}