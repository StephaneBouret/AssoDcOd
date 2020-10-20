<?php

class UserModel extends Model {

        /**
     * Fonction affichage de la table adherents filtrée par la présence et le role
     *
     * @return void
     */
    public function getUsers()
    {
        $requete = "SELECT *, r.nom as nom_roles, a.id as id_adherent, a.nom as nom_adherent, q.qualite as qualite_adherent
        FROM adherent as a 
        LEFT JOIN roles as r
        ON a.id_roles = r.id
        LEFT JOIN qualite as q 
        ON a.id_qualite = q.id
        WHERE (date_sortie IS NULL) OR (date_sortie IS NOT NULL AND id_groupeAdherent IS NOT NULL)
        ORDER BY a.id";
        $result = $this->connexion->query($requete);
        $listUsers = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listUsers;
    }

    /**
     * Fonction obtention des données d'un utilisateur sélectionné
     *
     * @return void
     */
    public function getUser(){
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, r.nom as nom_roles, a.id as id_adherent, a.nom as nom_adherent, 
        q.qualite as qualite_adherent, g.nomGroup as nom_groupe
        FROM adherent as a 
        LEFT JOIN roles as r
        ON a.id_roles = r.id
        LEFT JOIN qualite as q 
        ON a.id_qualite = q.id
        LEFT JOIN groupeadherent as g
        ON a.id_groupeAdherent = g.id
        WHERE a.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $user = $requete->fetch(PDO::FETCH_ASSOC);
        return $user;
    }

    /**
     * Fonction ajout d'un utilisateur dans la table adhérent
     *
     * @return void
     */
    public function addDBUser()
    {
        // // insert l'info
        $maxsize = 2097152;
        $acceptable = [
            'image/tif',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'];
        $nom = strtoupper($_POST['nom']);
        $prenom = ucfirst($_POST['prenom']);
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $telephone_formate = preg_replace("#^([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})$#", "$1 $2 $3 $4 $5", $tel);
        $sexe = $_POST['sexe'];
        $degree = $_POST['sel_degrees'];
        if (empty($_POST['sel_jobs'])) {
            $job = NULL;
        } else {
            $job = $_POST['sel_jobs'];
        }
        $adresse = $_POST['adresse'];
        $codepostal = $_POST['codepostal'];
        $ville = $_POST['ville'];
        $cotisation = $_POST['cotisation'];
        // // if (empty($cotisation)) {
        // //     $cotisation = NULL;
        // // }

        $photo = "img\undefined.jpg";

        if (isset($_FILES['photo']) && !empty($_FILES['photo']) && ($_FILES['photo']['size'] < $maxsize) 
        && (in_array($_FILES['photo']['type'], $acceptable)) && ($_FILES['photo']['size'] != 0)) {
            $emplacement_temporaire = $_FILES['photo']['tmp_name'];
            $nom_fichier = $_FILES['photo']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $photo = 'img/' . $nom_fichier;
            }
        }
        if (isset($_POST['icon']) && !empty($_POST['icon'])) {
            $photo = $_POST['icon'];
        }

        $phone_expr = '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/';
        if (preg_match($phone_expr, $tel) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $requete = $this->connexion->prepare("INSERT INTO adherent
            VALUES (NULL, :prenom, :nom, :sexe, :adresse, :CP, :ville, :telephone, :email, :degree, :avatar, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, NULL, :id_jobs, NULL, NULL, NULL, NULL, '2', :id_groupeAdherent, '2')");
            $requete->bindParam(':prenom', $prenom);
            $requete->bindParam(':nom', $nom);
            $requete->bindParam(':sexe', $sexe);
            $requete->bindParam(':adresse', $adresse);
            $requete->bindParam(':CP', $codepostal);
            $requete->bindParam(':ville', $ville);
            $requete->bindParam(':telephone', $telephone_formate);
            $requete->bindParam(':adresse', $adresse);
            $requete->bindParam(':email', $email);
            $requete->bindParam(':degree', $degree);
            $requete->bindParam(':avatar', $photo);
            $requete->bindParam(':id_jobs', $job);
            $requete->bindParam(':id_groupeAdherent', $cotisation);
            $result = $requete->execute();
            // var_dump($result);
            // var_dump($requete->errorInfo());
        }
    }

    /**
     * Fonction suppression d'un utilisateur dans la table adhérent
     *
     * @return void
     */
    public function suppDBUser()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM adherent
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    /**
     * Fonction radiation d'un utilisateur
     *
     * @return void
     */
    public function cancelledUser()
    {
        // insert l'info
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("UPDATE adherent SET id_groupeAdherent=NULL WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    /**
     * Fonction update du changement de rôle d'un utilisateur
     *
     * @return void
     */
    public function updateDB()
    {
        $id = $_POST['id'];
        $id_roles = $_POST['rolesUser'];
        $requete = $this->connexion->prepare("UPDATE adherent SET id_roles = :id_roles WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':id_roles', $id_roles);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction mise à jour de l'utilisateur
     *
     * @return void
     */
    public function updateDBUser()
    {
        $maxsize = 2097152;
        $acceptable = [
            'image/tif',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'];
        $id = $_POST['id'];
        $prenom = ucfirst($_POST['prenom']);
        $nom = strtoupper($_POST['nom']);
        $sexe = $_POST['sexe'];
        $degree = $_POST['sel_degrees'];
        $job = $_POST['sel_jobs'];
        $adresse = $_POST['adresse'];
        $codepostal = $_POST['codepostal'];
        $ville = $_POST['ville'];
        $tel = $_POST['tel'];
        $telephone_formate = preg_replace("#^([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})$#", "$1 $2 $3 $4 $5", $tel);
        $email = $_POST['email'];
        $idGroupeAdherent = $_POST['cotisation'];

        $photo = "img\undefined.jpg";

        if (isset($_FILES['photo']) && !empty($_FILES['photo']) && (in_array($_FILES['photo']['type'], $acceptable)) 
        && ($_FILES['photo']['size'] < $maxsize) && ($_FILES['photo']['size'] != 0)) {
            $emplacement_temporaire = $_FILES['photo']['tmp_name'];
            $nom_fichier = $_FILES['photo']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;

            $resultat = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($resultat) {
                $photo = 'img/' . $nom_fichier;
            }

            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,id_jobs=:id_jobs,id_groupeAdherent=:id_groupeAdherent WHERE id=:id");
            $requete->bindParam(':avatar', $photo);
        } else if (isset($_POST['icon']) && !empty($_POST['icon'])) {
            $avatar = $_POST['icon'];
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,id_jobs=:id_jobs,id_groupeAdherent=:id_groupeAdherent WHERE id=:id");
            $requete->bindParam(':avatar', $avatar);
        } else {
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,id_jobs=:id_jobs,id_groupeAdherent=:id_groupeAdherent WHERE id=:id");
        }

        $requete->bindParam(':id', $id);
        $requete->bindParam(':prenom', $prenom);
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':sexe', $sexe);
        $requete->bindParam(':adresse', $adresse);
        $requete->bindParam(':CP', $codepostal);
        $requete->bindParam(':ville', $ville);
        $requete->bindParam(':telephone', $telephone_formate);
        $requete->bindParam(':email', $email);
        $requete->bindParam(':degree', $degree);
        $requete->bindParam(':id_jobs', $job);
        $requete->bindParam(':id_groupeAdherent', $idGroupeAdherent);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction affichage du membre de la table adhérent en fonction de l'id pour l'envoi du mail d'enregistrement
     *
     * @return void
     */
    public function getMemberForRegister() {
        $id = $_POST['id'];
        $requete = $this->connexion->prepare("SELECT *
        FROM adherent as a 
        WHERE id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $memberForRegister = $requete->fetch(PDO::FETCH_ASSOC);
        return $memberForRegister;
    }

        /**
     * Fonction affichage du membre de la table adhérent en fonction de l'id pour l'envoi du mail d'enregistrement utilisateur vers adhérent
     *
     * @return void
     */
    public function switchUserToMemberBeforeSendingMail() {
        $id = $_POST['id'];
        $requete = $this->connexion->prepare("SELECT *, a.id as id_adherent, a.nom as nom, ro.nom as nom_roles, q.qualite as qualite_adherent, g.nomGroup as nom_groupe 
        FROM adherent as a
        LEFT JOIN jobs as j
        ON a.id_jobs = j.id 
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        LEFT JOIN statut as s 
        on a.id_statut = s.id
        LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r 
        ON a.id_reglement = r.id
        LEFT JOIN roles as ro
        ON a.id_roles = ro.id
        LEFT JOIN qualite as q 
        ON a.id_qualite = q.id
        LEFT JOIN groupeadherent as g
        ON a.id_groupeAdherent = g.id
        WHERE a.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $lastMemberRegistered = $requete->fetch(PDO::FETCH_ASSOC);
        return $lastMemberRegistered;
    }

    /**
     * Fonction mise à jour de la migration utilisateur vers adhérent dans la table adherent
     *
     * @return void
     */
    public function migrateDBUserToAdherent() {
        $id = $_POST['id'];
        $fonction = $_POST['fonction'];
        $statut = $_POST['statut'];
        $dateEntree = $_POST['dateEntree'];
        $cotisation = $_POST['cotisation'];
        $reglement = $_POST['reglement'];

        $requete = $this->connexion->prepare("UPDATE adherent SET date_entree=:date_entree,date_sortie=NULL,date_renouvellement=:date_renouvellement,id_statut=:id_statut,id_fonction=:id_fonction,id_cotisation=:id_cotisation,id_reglement=:id_reglement,id_groupeAdherent=NULL,id_qualite='1' WHERE id=:id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':date_entree', $dateEntree);
        $requete->bindParam(':date_renouvellement', $dateEntree);
        $requete->bindParam(':id_statut', $statut);
        $requete->bindParam(':id_fonction', $fonction);
        $requete->bindParam(':id_cotisation', $cotisation);
        $requete->bindParam(':id_reglement', $reglement);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction mise à jour de la migration adhérent vers utilisateur dans la table adherent
     *
     * @return void
     */
    public function migrateDBAdherentToUser() {
        $id = $_POST['id'];
        $dateSortie = $_POST['dateSortie'];
        $groupAdherent = $_POST['groupAdherent'];

        $requete = $this->connexion->prepare("UPDATE adherent SET date_sortie=:date_sortie,id_statut=NULL,id_fonction=NULL,id_cotisation=NULL,id_reglement=NULL,id_groupeAdherent=:id_groupeAdherent,id_qualite='2' WHERE id=:id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':date_sortie', $dateSortie);
        $requete->bindParam(':id_groupeAdherent', $groupAdherent);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }
}