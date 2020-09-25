<?php

class GroupModel extends Model {

    /**
     * Fonction obtention des données d'un groupe d'adhérent sélectionné
     *
     * @return void
     */
    public function getGroupAdherentOne()
    {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT * 
        FROM groupeadherent as ga 
        WHERE ga.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $onlyOneGroupAdherent = $requete->fetch(PDO::FETCH_ASSOC);
        return $onlyOneGroupAdherent;
    }

    /**
     * Fonction obtention de tous les utilisateurs d'un groupe sélectionné par son id
     *
     * @return void
     */
    public function getAllUsersFromGroupSelected()
    {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, a.id as id_adherent, gr.id as id_group
        FROM adherent as a 
        LEFT JOIN groupeadherent as gr 
        ON a.id_groupeAdherent = gr.id
        WHERE gr.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $listUsersFromGroupSelected = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $listUsersFromGroupSelected;
    }

    /**
     * Fonction suppression d'un groupe dans la table groupeadherent
     *
     * @return void
     */
    public function suppDBGroup()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM groupeadherent
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    /**
     * Fonction insertion dans la table groupeadherent
     *
     * @return void
     */
    public function addDBGroup()
    {
        $nomGroupe = strtoupper($_POST['nomGroupe']);
        $emailGroupe = $_POST['emailGroupe'];
        $telGroupe = $_POST['telGroupe'];
        $telephone_formate = preg_replace("#^([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})$#", "$1 $2 $3 $4 $5", $telGroupe);
        $adresseGroupe = $_POST['adresseGroupe'];
        $codepostalGroupe = $_POST['codepostalGroupe'];
        $villeGroupe = $_POST['villeGroupe'];
        $representantPrenomGroupe = ucfirst($_POST['representantPrenomGroupe']);
        $representantNomGroupe = strtoupper($_POST['representantNomGroupe']);

        $phone_expr = '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/';
        if (preg_match($phone_expr, $telGroupe) && filter_var($emailGroupe, FILTER_VALIDATE_EMAIL)) {
            $requete = $this->connexion->prepare("INSERT INTO groupeadherent VALUES (NULL, :nomGroup, :adresseGroup, :CPGroup, :villeGroup, :telephoneGroup, :emailGroup, :representantPrenom, :representantNom)");
            $requete->bindParam(':nomGroup', $nomGroupe);
            $requete->bindParam(':adresseGroup', $adresseGroupe);
            $requete->bindParam(':CPGroup', $codepostalGroupe);
            $requete->bindParam(':villeGroup', $villeGroupe);
            $requete->bindParam(':telephoneGroup', $telephone_formate);
            $requete->bindParam(':emailGroup', $emailGroupe);
            $requete->bindParam(':representantPrenom', $representantPrenomGroupe);
            $requete->bindParam(':representantNom', $representantNomGroupe);
            $result = $requete->execute();
            // var_dump($result);
            // var_dump($requete->errorInfo());
        }
    }

    /**
     * Fonction modification de l'item dans la table groupeadherent
     *
     * @return void
     */
    public function updateDBGroup()
    {
        $memberLeaderGroupSelectedForChange = $_POST['MemberLeaderGroupSelectedForChange'];
        $leaderGroupSelected = explode(' ', $memberLeaderGroupSelectedForChange);
        $id = $_POST['id'];
        $nomGroupe = strtoupper($_POST['nomGroupe']);
        $emailGroupe = $_POST['emailGroupe'];
        $telGroupe = $_POST['telGroupe'];
        $telephone_formate = preg_replace("#^([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})([0-9]{1}[0-9]{1})$#", "$1 $2 $3 $4 $5", $telGroupe);
        $adresseGroupe = $_POST['adresseGroupe'];
        $codepostalGroupe = $_POST['codepostalGroupe'];
        $villeGroupe = $_POST['villeGroupe'];
        if (empty($memberLeaderGroupSelectedForChange)) {
            $representantPrenomGroupe = ucfirst($_POST['representantPrenomGroupe']);
            $representantNomGroupe = strtoupper($_POST['representantNomGroupe']);
        } else {
            $representantPrenomGroupe = ucfirst($leaderGroupSelected[0]);
            $representantNomGroupe = strtoupper($leaderGroupSelected[1]);
        }
        $phone_expr = '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/';

        if (preg_match($phone_expr, $telGroupe) && filter_var($emailGroupe, FILTER_VALIDATE_EMAIL)) {
            $requete = $this->connexion->prepare("UPDATE groupeadherent SET nomGroup=:nomGroup,adresseGroup=:adresseGroup,CPGroup=:CPGroup,villeGroup=:villeGroup,telephoneGroup=:telephoneGroup,emailGroup=:emailGroup,representantPrenom=:representantPrenom,representantNom=:representantNom WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':nomGroup', $nomGroupe);
            $requete->bindParam(':adresseGroup', $adresseGroupe);
            $requete->bindParam(':CPGroup', $codepostalGroupe);
            $requete->bindParam(':villeGroup', $villeGroupe);
            $requete->bindParam(':telephoneGroup', $telephone_formate);
            $requete->bindParam(':emailGroup', $emailGroupe);
            $requete->bindParam(':representantPrenom', $representantPrenomGroupe);
            $requete->bindParam(':representantNom', $representantNomGroupe);
            $result = $requete->execute();
            // var_dump($result);
            // var_dump($requete->errorInfo());
        }
    }

    /**
     * Fonction recherche autocomplete en ajax dans la table adherent
     * @param [type] $requeteBoard
     * @return void
     */
    public function ajaxFileMemberNameForGroup($requeteMemberName)
    {
        // Number of records fetch
        $numberofrecords = 20;

        $requeteMemberNames = '%' . $requeteMemberName . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT *, a.id as id_adherent 
        FROM adherent as a WHERE nom like :nom AND a.id_qualite = 1 ORDER BY a.nom LIMIT :limit");
        $requete->bindParam(':nom', $requeteMemberNames, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $memberNameList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];

        // Read Data
        foreach ($memberNameList as $nameList) {
            $response[] = [
                "id" => $nameList['id'],
                "text" => $nameList['nom']." ".$nameList['prenom']." (".$nameList['email'].")",
                "name" => $nameList['nom'],
                "firstname" => $nameList['prenom'],
                "email" => $nameList['email']
            ];
        }
        return $response;
    }
}