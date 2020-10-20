<?php

/**
* Import des classes PHPMailer dans l’espace de nommage
* Ces instructions doivent être placées en début de script
*/
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
require 'lib/PHPMailer-master/src/PHPMailer.php';
require 'lib/PHPMailer-master/src/Exception.php';

abstract class Model
{
    const SERVER = "localhost";
    const USER = "root";
    const PASSWORD = "";
    const BASE = "associationdcd";

    protected $connexion;

    /**
     * Connexion à la BDD en PHP en local ou à distance
     */
    public function __construct()
    {
        // Connexion
        try {
            $this->connexion = new PDO("mysql:host=" . self::SERVER . ";dbname="
            . self::BASE, self::USER, self::PASSWORD);
        } catch (Exception $e) {
            echo 'Erreur : ' . $e->getMessage();
        }
        //Résoudre problèmes d'encodages (accents)
        $this->connexion->exec("SET NAMES 'UTF8'");

    }

    /**
     * Fonction permettant de générer un code aléatoire
     *
     * @param [type] $length
     * @return void
     */
    public function str_random($length){
        $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
    }

    /**
     * Fonction générant un token aléatoire - Génère des octets pseudo-aléatoire cryptographiquement sécurisé
     *
     * @param [type] $length
     * @return void
     */
    public function generate_token($length){
        $bytes = random_bytes($length);
        return bin2hex($bytes);
    }

    /**
     * Fonction générant les messages de session
     *
     * @param [type] $key
     * @param [type] $message
     * @return void
     */
    public function setFlash($key, $message){
        $_SESSION['flash'][$key] = $message;
    }
    
    /**
     * Fonction affichage de la BDD association
     *
     * @return void
     */
    public function getFullAssociation(){
        $requete = "SELECT * FROM association";
        $result = $this->connexion->query($requete);
        $associationDisplay = $result->fetch(PDO::FETCH_ASSOC);
        return $associationDisplay;
    }

    /**
     * Fonction affichage de la table association ventilée par la forme juridique
     *
     * @return void
     */
    public function getFullAssociationForPdf()
    {
        $requete = "SELECT *
        FROM association as a
        LEFT JOIN forme_juridique as fj
        ON a.id_jurid = fj.id";
        $result = $this->connexion->query($requete);
        $associationDisplayForPdf = $result->fetch(PDO::FETCH_ASSOC);
        return $associationDisplayForPdf;
    }

    /**
     * Fonction affichage de la BDD adherents
     *
     * @return void
     */
    public function getAdherents()
    {
        $requete = "SELECT *, a.id as id_adherent, f.fonction as nom_fonction 
        FROM adherent as a 
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        LEFT JOIN statut as s 
        on a.id_statut = s.id
        LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r 
        ON a.id_reglement = r.id
        LEFT JOIN qualite as q 
        ON a.id_qualite = q.id
        WHERE a.id_qualite = 1
        AND date_sortie IS NULL
        ORDER BY a.id";
        $result = $this->connexion->query($requete);
        $listAdherents = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listAdherents;
    }

    /**
     * Fonction affichage d'une donnée de la BDD adhérents
     *
     * @return void
     */
    public function getAdherent()
    {
        $id = $_GET['id'];
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
        $adherent = $requete->fetch(PDO::FETCH_ASSOC);
        // var_dump($result);
        // var_dump($requete->errorInfo());
        return $adherent;
    }

            /**
     * Fonction comptage du nombre d'adhérent
     *
     * @return void
     */
    public function getAssociation()
    {
        $requete = "SELECT COUNT(a.id) as nombre_adherent
        FROM adherent as a
        WHERE a.date_sortie IS NULL";
        $result = $this->connexion->query($requete);
        $listAssociation = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listAssociation;
    }

    /**
     * Fonction affichage de la BDD fonction
     *
     * @return void
     */
    public function getFunctions()
    {
        $requete = "SELECT *
        FROM fonction";
        $result = $this->connexion->query($requete);
        $listFunctions = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listFunctions;
    }

        /**
     * Fonction affichage de la BDD statuts
     *
     * @return void
     */
    public function getStatuts()
    {
        $requete = "SELECT * 
        FROM statut";
        $result = $this->connexion->query($requete);
        $listStatuts = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listStatuts;
    }

    /**
     * Fonction affichage de la BDD reglement
     *
     * @return void
     */
    public function getRegulations()
    {
        $requete = "SELECT * 
        FROM reglement ORDER BY mode_reglement";
        $result = $this->connexion->query($requete);
        $listRegulations = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listRegulations;
    }

    public function getAdherentGroup()
    {
        $requete = "SELECT * 
        FROM groupeadherent";
        $result = $this->connexion->query($requete);
        $listAdherentGroup = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listAdherentGroup;
    }

    /**
     * Fonction affichage de la table recufiscal
     *
     * @return void
     */
    public function getTaxReceipt()
    {
        $requete = "SELECT * 
        FROM recufiscal";
        $result = $this->connexion->query($requete);
        $listTaxReceipt = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listTaxReceipt;
    }

    /**
     * Fonction affichage de la BDD cotisation
     *
     * @return void
     */
    public function getCotisations()
    {
        $requete = "SELECT * 
        FROM cotisation";
        $result = $this->connexion->query($requete);
        $listCotisations = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listCotisations;
    }
    
    /**
     * Fonction affichage de la BDD dons
     *
     * @return void
     */
    public function getDons()
    {
        $requete = "SELECT *, a.id as id_memb, d.id as id_don, r.id as id_reg, rf.id as id_recufisc
        FROM don as d 
        LEFT JOIN adherent as a
        ON d.id_adherent = a.id
        LEFT JOIN reglement as r
        on d.id_reglement = r.id
        LEFT JOIN recufiscal as rf
        ON d.id_recuFiscal = rf.id
        ORDER BY d.id";
        $result = $this->connexion->query($requete);
        $listDons = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listDons;
    }

    /**
     * Fonction récupération des données de la table roles
     *
     * @return void
     */
    public function getRoles()
    {
        $requete = "SELECT *
        FROM roles ORDER BY id";
        $result = $this->connexion->query($requete);
        $listRoles = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listRoles;
    }

    /**
     * Fonction récupération des données de la table jobs
     *
     * @return void
     */
    public function getJobs()
    {
        $requete = "SELECT *
        FROM jobs ORDER BY id";
        $result = $this->connexion->query($requete);
        $listJobs = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listJobs;
    }

        /**
     * Fonction récupération des données de la table forme_juridique
     *
     * @return void
     */
    public function getJuridicalStatus()
    {
        $requete = "SELECT *
        FROM forme_juridique ORDER BY libelle";
        $result = $this->connexion->query($requete);
        $listJuridicalStatus = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listJuridicalStatus;
    }

    /**
     * Fonction récupération de toutes les datas dans la table groupeadherent
     *
     * @return void
     */
    public function getGroupAdherentAll()
    {
        $requete = "SELECT *
        FROM groupeadherent ORDER BY nomGroup";
        $result = $this->connexion->query($requete);
        $listGroupAdherent = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listGroupAdherent;
    }

    /**
     * Fonction récupération depuis la table adhérent filtrée par les membres du bureau
     *
     * @return void
     */
    public function getBoardMembers()
    {
        $requete = "SELECT prenom, nom, fonction, a.id as id_adherent 
        FROM adherent as a 
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        WHERE 
        fonction LIKE 'P%' 
        OR fonction LIKE 'T%'
        OR fonction LIKE 'S%'
        OR fonction LIKE 'V%'
        ORDER BY a.nom";
        $result = $this->connexion->query($requete);
        $listBoardMembers = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listBoardMembers;
    }

    /**
     * Fonction de verification si doublon email lors de l'enregistrement d'un adhérent ou utilisateur
     *
     * @return void
     */
    public function checkMemberOrUserWithEmail($email)
    {
        $requete = $this->connexion->prepare("SELECT * FROM adherent WHERE email = :email");
        $requete->bindParam(':email', $email);
        $result = $requete->execute();
        $mailChecked = $requete->fetch(PDO::FETCH_ASSOC);
        return $mailChecked;
    }

    /**
     * Fonction récupération du dernier enregistrement de la table dons en fonction de la date du don
     *
     * @return void
     */
    public function getDonatorOnlyBeforeSendingMail()
    {
        $requete = "SELECT *, d.id as id_don 
        FROM don as d
        LEFT JOIN adherent as a
        ON d.id_adherent = a.id
        LEFT JOIN reglement as r
        on d.id_reglement = r.id
        ORDER BY id_don 
        DESC LIMIT 0,1";
        $result = $this->connexion->query($requete);
        $lastDonatorRegistered = $result->fetch(PDO::FETCH_ASSOC);
        return $lastDonatorRegistered;
    }

        /**
     * Fonction récupération du dernier enregistrement de la table adhérent en fonction de la date de cotisation
     *
     * @return void
     */
    public function getMemberOnlyBeforeSendingMail()
    {
        $requete = "SELECT *, a.id as id_adherent, g.nomGroup as nom_groupeAdherent 
        FROM adherent as a
        LEFT JOIN cotisation as c
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r
        on a.id_reglement = r.id
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        LEFT JOIN statut as s 
        ON a.id_statut = s.id
        LEFT JOIN groupeadherent as g 
        ON a.id_groupeAdherent = g.id
        ORDER BY id_adherent
        DESC LIMIT 0,1";
        $result = $this->connexion->query($requete);
        $lastMemberRegistered = $result->fetch(PDO::FETCH_ASSOC);
        return $lastMemberRegistered;
    }

    /**
     * Fonction envoi de mail
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function sendMail($associationDisplay)
    {
        $destinataire = [];
        foreach($_POST["destinataire"] as $dataUserSelected){
            $dataUserSelected = explode("|", $dataUserSelected);
            array_push($destinataire, $dataUserSelected);
        }
        $dataAssociation = explode("|", $_POST['association']);
        $filteredData = [];
        foreach ($dataAssociation as $newData => $newValue) {
            $filteredData[$newData] = array(
                'name' => $dataAssociation[0],
                'firstnameSign' => $dataAssociation[1],
                'nameSign' => $dataAssociation[2],
                'functionSign' => $dataAssociation[3],
                'logo' => $dataAssociation[4],
            );
        }
        // Vérification des données reçues du formulaire
        $nameAsso = "Aucun nom d'association";
        if (isset($filteredData[0]['name']) && !empty($filteredData[0]['name'])) {
            $nameAsso = $filteredData[0]['name'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($filteredData[0]['firstnameSign']) && !empty($filteredData[0]['firstnameSign'])) {
            $firstnameSign = $filteredData[0]['firstnameSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($filteredData[0]['nameSign']) && !empty($filteredData[0]['nameSign'])) {
            $nameSign = $filteredData[0]['nameSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($filteredData[0]['functionSign']) && !empty($filteredData[0]['functionSign'])) {
            $functionSign = $filteredData[0]['functionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($filteredData[0]['logo']) && !empty($filteredData[0]['logo'])) {
            $logoAsso = $filteredData[0]['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($_POST['AssoEmail']) && !empty($_POST['AssoEmail']))
        {
        $emailAsso = $_POST['AssoEmail'];
        }
        $subjectMail = "Aucun sujet";
        if (isset($_POST['subjectMail']) && !empty($_POST['subjectMail']))
        {
        $subjectMail = $_POST['subjectMail'];
        }
        $textForMail = "Aucun message";
        if (isset($_POST['textForMail']) && !empty($_POST['textForMail']))
        {
        $textForMail = $_POST['textForMail'];
        }

        /**
        * Instanciation de la variable
        */
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            foreach ($destinataire as $dest) {
                $mail = new PHPMailer();
                $mail->From = $emailAsso; // mail Expéditeur
                $mail->FromName = $nameAsso; // nom Expéditeur
                $mail->Subject = $subjectMail; // sujet du mail
                // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
                $contenu = "A l'attention de : <b>".$dest[0]." ".$dest[1]."</b><hr>
                ".Stripslashes($textForMail)."<br>
                Cordialement<br>
                <b>".$nameAsso."</b>
                ".$firstnameSign." ".$nameSign."
                <b>".$functionSign."</b>
                <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
                ";
                $mail->MsgHTML(nl2br($contenu));
                $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
                // Méthodes
                // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
                $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
                $logo = $logoAsso;
                $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
                $mail->AddAddress($dest[2]);
                $mail->AddBCC($emailAsso);
                if (isset($_FILES['file']) && ($_FILES['file']['error'] == 0)) {
                    $fichier = $_FILES['file']['name'];
                    $chemin = $_FILES['file']['tmp_name'];
                    // On met notre fichier en pièce jointe
                    // $fichier contient le nom du fichier
                    // $chemin contient le chemin d'accès à la piece jointe.
                    $mail->AddAttachment($chemin,$fichier);
                }
                $envoiOK = $mail->Send();
            }
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
        echo 'Message non envoyé';
        echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php");
        }
        else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

        /**
     * Fonction envoi de mail pour relance adhésion
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function sendMailForRelaunch($associationDisplay)
    {
        $destinataire = [];
        foreach($_POST["destinataire"] as $dataUserSelected){
            $dataUserSelected = explode("|", $dataUserSelected);
            array_push($destinataire, $dataUserSelected);
        }
        $dataAssociation = explode("|", $_POST['association']);
        $filteredData = [];
        foreach ($dataAssociation as $newData => $newValue) {
            $filteredData[$newData] = array(
                'name' => $dataAssociation[0],
                'firstnameSign' => $dataAssociation[1],
                'nameSign' => $dataAssociation[2],
                'functionSign' => $dataAssociation[3],
                'logo' => $dataAssociation[4],
            );
        }
        $campaignTypeSelected = [];
        foreach($_POST["campaignTypeSelected"] as $campaignSelected){
            $campaignSelected = explode("|", $campaignSelected);
            array_push($campaignTypeSelected, $campaignSelected);
        }
        $typeSelected = [];
        foreach ($campaignTypeSelected as $selected) {
            if ($_POST['campaignType'] == $selected[0]) {
                $typeSelected = array(
                    'libelleAdhesion' => $selected[2],
                    'montantAdhesion' => $selected[1],
                    'debutAdhesion' => $selected[3],
                    'finAdhesion' => $selected[4],
                    'dureeMoisAdhesion' => $selected[5],
                );
            }
        }
        // Vérification des données reçues du formulaire
        $nameAsso = "Aucun nom d'association";
        if (isset($filteredData[0]['name']) && !empty($filteredData[0]['name'])) {
            $nameAsso = $filteredData[0]['name'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($filteredData[0]['firstnameSign']) && !empty($filteredData[0]['firstnameSign'])) {
            $firstnameSign = $filteredData[0]['firstnameSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($filteredData[0]['nameSign']) && !empty($filteredData[0]['nameSign'])) {
            $nameSign = $filteredData[0]['nameSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($filteredData[0]['functionSign']) && !empty($filteredData[0]['functionSign'])) {
            $functionSign = $filteredData[0]['functionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($filteredData[0]['logo']) && !empty($filteredData[0]['logo'])) {
            $logoAsso = $filteredData[0]['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($_POST['AssoEmail']) && !empty($_POST['AssoEmail']))
        {
        $emailAsso = $_POST['AssoEmail'];
        }
        $subjectMail = "Aucun sujet";
        if (isset($_POST['subjectMail']) && !empty($_POST['subjectMail']))
        {
        $subjectMail = $_POST['subjectMail'];
        }
        $textForMail = "Aucun message";
        if (isset($_POST['textForMail']) && !empty($_POST['textForMail']))
        {
        $textForMail = $_POST['textForMail'];
        }
        $libelleAdhesion = "Aucun libellé";
        if (isset($typeSelected['libelleAdhesion']) && !empty($typeSelected['libelleAdhesion'])) {
            $libelleAdhesion = $typeSelected['libelleAdhesion'];
        }
        $montantAdhesion = "0.00";
        if (isset($typeSelected['montantAdhesion']) && !empty($typeSelected['montantAdhesion'])) {
            $montantAdhesion = $typeSelected['montantAdhesion'];
        }
        $debutAdhesion = "";
        if ($typeSelected['dureeMoisAdhesion'] == 0) {
            $debutAdhesion = date("d-m-Y", strtotime($typeSelected['debutAdhesion']));
        } else {
            $debutAdhesion = "Cette campagne d'adhésion débute à la date de réception du mail";
        }
        $finAdhesion = "";
        if ($typeSelected['dureeMoisAdhesion'] == 0) {
            $finAdhesion = date("d-m-Y", strtotime($typeSelected['finAdhesion']));
        } else {
            $finAdhesion = "L'adhésion prend fin ".$typeSelected['dureeMoisAdhesion']." mois à partir de l'enregistrement de la cotisation";
        }
        /**
        * Instanciation de la variable
        */
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            foreach ($destinataire as $dest) {
                $mail = new PHPMailer();
                $mail->From = $emailAsso; // mail Expéditeur
                $mail->FromName = $nameAsso; // nom Expéditeur
                $mail->Subject = $subjectMail; // sujet du mail
                // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
                $contenu = "A l'attention de : <b>".$dest[0]." ".$dest[1]."</b><hr>
                Vous êtes adhérent depuis ".date("d-m-Y", strtotime($dest[3]))."<br>
                ".Stripslashes($textForMail)."<br>
                Cordialement<br>
                Nom de la nouvelle adhésion : <b>".$libelleAdhesion."</b>
                Montant de l'adhésion : <b>".$montantAdhesion." €</b>
                Début de la campagne d'adhésion : <b>".$debutAdhesion."</b>
                Fin de l'adhésion : <b>".$finAdhesion."</b><br>
                <b>".$nameAsso."</>
                ".$firstnameSign." ".$nameSign."
                <b>".$functionSign."</b>
                <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
                ";
                $mail->MsgHTML(nl2br($contenu));
                $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
                // Méthodes
                // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
                $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
                $logo = $logoAsso;
                $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
                $mail->AddAddress($dest[2]);
                $mail->AddBCC($emailAsso);
                if (isset($_FILES['file']) && ($_FILES['file']['error'] == 0)) {
                    $fichier = $_FILES['file']['name'];
                    $chemin = $_FILES['file']['tmp_name'];
                    // On met notre fichier en pièce jointe
                    // $fichier contient le nom du fichier
                    // $chemin contient le chemin d'accès à la piece jointe.
                    $mail->AddAttachment($chemin,$fichier);
                }
                $envoiOK = $mail->Send();
            }
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
        echo 'Message non envoyé';
        echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php");
        }
        else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

    /**
     * Fonction création du PDF puis envoi en PJ par mail lors de l'ajout du donateur
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $lastDonatorRegistered
     * @return void
     */
    public function createPdfBeforeSending($associationDisplayForPdf,$lastDonatorRegistered)
    {
        $dayToday = date("d");
        $monthToday = date("m");
        $yearToday = date("Y");
        $jourCotisation = date("d", strtotime($lastDonatorRegistered['date_don']));
        $moisCotisation = date("m", strtotime($lastDonatorRegistered['date_don']));
        $anneeCotisation = date("Y", strtotime($lastDonatorRegistered['date_don']));
        $numMois = $moisCotisation;
        switch ($numMois) {
            case '01':
                $numMois = "Janvier";
                break;
            case '02':
                $numMois = "Février";
                break;
            case '03':
                $numMois = "Mars";
                break;
            case '04':
                $numMois = "Avril";
                break;
            case '05':
                $numMois = "Mai";
                break;
            case '06':
                $numMois = "Juin";
                break;
            case '07':
                $numMois = "Juillet";
                break;
            case '08':
                $numMois = "Août";
                break;
            case '09':
                $numMois = "Septembre";
                break;
            case '10':
                $numMois = "Octobre";
                break;
            case '11':
                $numMois = "Novembre";
                break;
            case '12':
                $numMois = "Décembre";
                break;
            default:
                break;
        }
        $code = $associationDisplayForPdf['code'];
        $code1 = substr($code,0,2);
        $len = 1;   // total number of numbers
        $min = 100;  // minimum
        $max = 999;  // maximum
        $range = []; // initialize array
        foreach (range(0, $len - 1) as $i) {
            while(in_array($num = mt_rand($min, $max), $range));
            $range[] = $num;
        }
        // Format portrait (>P) ou paysage (>L), en mm (ou en points > pts), A4 (ou A5, etc.)
        $pdf = new FPDF('P','mm','A4');
        // Nouvelle page A4 (incluant ici logo, titre et pied de page) 
        $pdf->AddPage();
        // Logo : 8 >position à gauche du document (en mm), 2 >position en haut du document, 30 >largeur de l'image en mm). La hauteur est calculée automatiquement.
        $pdf->Image($associationDisplayForPdf['logo'],8,2,30);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80);
        // position du coin supérieur gauche par rapport à la marge gauche (mm) 
        $pdf->SetX(70);
        // Texte : 80 >largeur ligne, 10 >hauteur ligne. Premier 1 >bordure, 0 >pas de retour à la ligne, C >centrer texte, pas de couleur de fond
        $pdf->Cell(80,10,utf8_decode('Facture pour un don'),1,0,'C');
        // Saut de ligne 20 mm
        $pdf->Ln(20);
        // Infos de l'association calées à gauche
        $pdf->SetFont('Arial','B',14);
        $pdf->Text(8,43,utf8_decode($associationDisplayForPdf['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(8,48,utf8_decode($associationDisplayForPdf['adresse']));
        $pdf->Text(8,53,utf8_decode($associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']));
        $pdf->Text(8,58,utf8_decode($associationDisplayForPdf['email']));
        $pdf->Text(8,63,utf8_decode($associationDisplayForPdf['site']));
        // Infos de la facture calées à droite
        $pdf->SetFont('Arial','B',14);
        $pdf->Text(120,43,utf8_decode('Facture'));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,48,utf8_decode('Numéro de facture : '.$code1.'-'.$dayToday.$monthToday.$yearToday.'-'.$range[0]));
        $pdf->Text(120,53,utf8_decode('Date : '.date("d-m-Y")));
        // Infos du client calées à droite
        $pdf->Text(120,63,utf8_decode('A l\'attention de :'));
        $pdf->SetFont('Arial','B',11);
        $pdf->Text(120,73,utf8_decode($lastDonatorRegistered['prenom'].' '.$lastDonatorRegistered['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($lastDonatorRegistered['adresse']));
        $pdf->Text(120,83,utf8_decode($lastDonatorRegistered['CP']).' '.$lastDonatorRegistered['ville']);
        // Position de l'entête à 10mm des infos (83 + 30)
        $position_entete = 113;
        $pdf->SetDrawColor(0); // Couleur des filets (RVB)
        $pdf->SetFillColor(154,214,1); // Couleur du fond (RVB)
        $pdf->SetTextColor(0); // Couleur du texte (RVB)
        $pdf->SetY($position_entete);
        $pdf->SetX(8); // position de colonne 1 (10mm à gauche)
        $pdf->Cell(120,8,utf8_decode('Désignation'),1,0,'L',1); // 140 >largeur colonne, 8 >hauteur colonne, L >gauche, 1 >cadre
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('Qté'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode('Montant net'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste des détails
        $position_detail = 121; // Position à 8mm de l'entête (113+8)
        $pdf->SetFillColor(239,240,242); // Couleur du fond (RVB)
        $pdf->SetY($position_detail);
        $pdf->SetX(8);
        $pdf->Cell(120,8,utf8_decode('Don '.$lastDonatorRegistered['numDon']),1,0,'L',1);
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('1'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode($lastDonatorRegistered['montant_don'].' Euros'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste Réglement
        $position_endDetail = 137; // Position à 16mm de l'entête (121+16)
        $pdf->SetY($position_endDetail);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',11);
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.$lastDonatorRegistered['montant_don'].' Euros*** de la part de '.$lastDonatorRegistered['prenom'].' '.$lastDonatorRegistered['nom'].' en règlement de son don '.$lastDonatorRegistered['numDon'].' en date du '.$jourCotisation.' '.$numMois.' '.$anneeCotisation.'.'),0,'L',0);
        $pdf->Ln(); // Retour à la ligne
        // Recapitulatif
        $position_recap = 153; // Position à 16mm de l'entête (137+16)
        $pdf->SetY($position_recap);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(50,8,utf8_decode('Type du paiement :'),0,0,'L',0);
        $pdf->SetFont('Arial','',11);
        $pdf->SetX(58); // 8 + 50
        $pdf->Cell(50,8,utf8_decode('Don'),0,0,'L',0);
        $pdf->SetY(160);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(50,8,utf8_decode('Mode de versement :'),0,0,'L',0);
        $pdf->SetFont('Arial','',11);
        $pdf->SetX(58);
        $pdf->Cell(50,8,utf8_decode($lastDonatorRegistered['mode_reglement']),0,0,'L',0);
        $pdf->Ln(); // Retour à la ligne
        // Signature
        $position_sign = 176; // Position à 16mm de l'entête (160+16)
        $pdf->SetY($position_sign);
        $pdf->SetX(116);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(70,8,utf8_decode('Fait à '.$associationDisplayForPdf['footerdoc'].' le '.date("d-m-Y")),0,0,'R',0);
        $pdf->SetY(182);
        $pdf->SetX(116);
        $pdf->Cell(70,8,utf8_decode($associationDisplayForPdf['prenomSign'].' '.$associationDisplayForPdf['nomSign']),0,0,'R',0);
        $pdf->SetY(188);
        $pdf->SetX(116);
        $pdf->Cell(70,8,utf8_decode($associationDisplayForPdf['fonctionSign']),0,0,'R',0);
        $pdf->Image($associationDisplayForPdf['signature'],124,170,80);
        $pdf->Ln(); // Retour à la ligne
        // Footer
        $position_footer = 260; // Position à 16mm de l'entête (188+72)
        $pdf->SetY($position_footer);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['libelle'].' - Déclaration à la Préfecture d\''.$associationDisplayForPdf['footerdoc'].' sous le numéro '.$associationDisplayForPdf['rna']),0,0,'C');
        $pdf->SetY($position_footer+4);
        $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['nom'].' - '.$associationDisplayForPdf['adresse'].' '.$associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']),0,0,'C');
        $pdf->SetY(268);
        $pdf->Cell('auto',8,utf8_decode('Numéro SIRENE : '.$associationDisplayForPdf['siret']),0,0,'C');
        $nomPdf = 'FactureDon-'.$code1.'-'.$jourCotisation.$moisCotisation.$anneeCotisation.'-'.$range[0].'.pdf';
        $path = "pdf/".$nomPdf;
        $pdf->Output($path,'F');

        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplayForPdf['nom']) && !empty($associationDisplayForPdf['nom'])) {
            $nameAsso = $associationDisplayForPdf['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplayForPdf['prenomSign']) && !empty($associationDisplayForPdf['prenomSign'])) {
            $firstnameSign = $associationDisplayForPdf[0]['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplayForPdf['nomSign']) && !empty($associationDisplayForPdf['nomSign'])) {
            $nameSign = $associationDisplayForPdf['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplayForPdf['fonctionSign']) && !empty($associationDisplayForPdf['fonctionSign'])) {
            $functionSign = $associationDisplayForPdf['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplayForPdf['logo']) && !empty($associationDisplayForPdf['logo'])) {
            $logoAsso = $associationDisplayForPdf['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplayForPdf['email']) && !empty($associationDisplayForPdf['email']))
        {
        $emailAsso = $associationDisplayForPdf['email'];
        }
        $textForMail = "Bonjour,<br>
        Votre don a bien été enregistré. Vous trouverez ci-joint le reçu de votre paiement lié à votre don";
        $corpsMessage = "A l'attention de : <b>".$lastDonatorRegistered['prenom']." ".$lastDonatorRegistered['nom']."</b><hr>
        ".Stripslashes($textForMail)."<br>
        Cordialement<br>
        <b>".$nameAsso."</b>
        ".$firstnameSign." ".$nameSign."
        <b>".$functionSign."</b>
        <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
        ";
        /**
        * Instanciation de la variable
        */
        $mail = new PHPMailer();
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = "Votre facture liée à votre don"; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $mail->MsgHTML(nl2br($corpsMessage));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            $mail->addAttachment($path, $nomPdf, $encoding='base64', $type='.pdf');
            $logo = $logoAsso;
            $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($lastDonatorRegistered['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php?controller=dons");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

        /**
     * Fonction création du PDF puis envoi en PJ par mail lors de l'ajout de l'adhérent
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $lastMemberRegistered
     * @return void
     */
    public function createPdfBeforeSendingMember($associationDisplayForPdf,$lastMemberRegistered)
    {
        $dayToday = date("d");
        $monthToday = date("m");
        $yearToday = date("Y");
        $jourCotisation = date("d", strtotime($lastMemberRegistered['date_entree']));
        $moisCotisation = date("m", strtotime($lastMemberRegistered['date_entree']));
        $anneeCotisation = date("Y", strtotime($lastMemberRegistered['date_entree']));
        $numMois = $moisCotisation;
        switch ($numMois) {
            case '01':
                $numMois = "Janvier";
                break;
            case '02':
                $numMois = "Février";
                break;
            case '03':
                $numMois = "Mars";
                break;
            case '04':
                $numMois = "Avril";
                break;
            case '05':
                $numMois = "Mai";
                break;
            case '06':
                $numMois = "Juin";
                break;
            case '07':
                $numMois = "Juillet";
                break;
            case '08':
                $numMois = "Août";
                break;
            case '09':
                $numMois = "Septembre";
                break;
            case '10':
                $numMois = "Octobre";
                break;
            case '11':
                $numMois = "Novembre";
                break;
            case '12':
                $numMois = "Décembre";
                break;
            default:
                break;
        }
        $code = $associationDisplayForPdf['code'];
        $code1 = substr($code,0,2);
        $len = 1;   // total number of numbers
        $min = 100;  // minimum
        $max = 999;  // maximum
        $range = []; // initialize array
        foreach (range(0, $len - 1) as $i) {
            while(in_array($num = mt_rand($min, $max), $range));
            $range[] = $num;
        }
        // Format portrait (>P) ou paysage (>L), en mm (ou en points > pts), A4 (ou A5, etc.)
        $pdf = new FPDF('P','mm','A4');
        // Nouvelle page A4 (incluant ici logo, titre et pied de page) 
        $pdf->AddPage();
        // Logo : 8 >position à gauche du document (en mm), 2 >position en haut du document, 30 >largeur de l'image en mm). La hauteur est calculée automatiquement.
        $pdf->Image($associationDisplayForPdf['logo'],8,2,30);
        $pdf->SetFont('Arial','B',14);
        $pdf->Cell(80);
        // position du coin supérieur gauche par rapport à la marge gauche (mm) 
        $pdf->SetX(70);
        // Texte : 80 >largeur ligne, 10 >hauteur ligne. Premier 1 >bordure, 0 >pas de retour à la ligne, C >centrer texte, pas de couleur de fond
        $pdf->Cell(80,10,utf8_decode('Facture pour une adhésion'),1,0,'C');
        // Saut de ligne 20 mm
        $pdf->Ln(20);
        // Infos de l'association calées à gauche
        $pdf->SetFont('Arial','B',14);
        $pdf->Text(8,43,utf8_decode($associationDisplayForPdf['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(8,48,utf8_decode($associationDisplayForPdf['adresse']));
        $pdf->Text(8,53,utf8_decode($associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']));
        $pdf->Text(8,58,utf8_decode($associationDisplayForPdf['email']));
        $pdf->Text(8,63,utf8_decode($associationDisplayForPdf['site']));
        // Infos de la facture calées à droite
        $pdf->SetFont('Arial','B',14);
        $pdf->Text(120,43,utf8_decode('Facture'));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,48,utf8_decode('Numéro de facture : '.$code1.'-'.$dayToday.$monthToday.$yearToday.'-'.$range[0]));
        $pdf->Text(120,53,utf8_decode('Date : '.date("d-m-Y")));
        // Infos du client calées à droite
        $pdf->Text(120,63,utf8_decode('A l\'attention de :'));
        $pdf->SetFont('Arial','B',11);
        $pdf->Text(120,73,utf8_decode($lastMemberRegistered['prenom'].' '.$lastMemberRegistered['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($lastMemberRegistered['adresse']));
        $pdf->Text(120,83,utf8_decode($lastMemberRegistered['CP']).' '.$lastMemberRegistered['ville']);
        // Position de l'entête à 10mm des infos (83 + 30)
        $position_entete = 113;
        $pdf->SetDrawColor(0); // Couleur des filets (RVB)
        $pdf->SetFillColor(154,214,1); // Couleur du fond (RVB)
        $pdf->SetTextColor(0); // Couleur du texte (RVB)
        $pdf->SetY($position_entete);
        $pdf->SetX(8); // position de colonne 1 (10mm à gauche)
        $pdf->Cell(120,8,utf8_decode('Désignation'),1,0,'L',1); // 140 >largeur colonne, 8 >hauteur colonne, L >gauche, 1 >cadre
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('Qté'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode('Montant net'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste des détails
        $position_detail = 121; // Position à 8mm de l'entête (113+8)
        $pdf->SetFillColor(239,240,242); // Couleur du fond (RVB)
        $pdf->SetY($position_detail);
        $pdf->SetX(8);
        $pdf->Cell(120,8,utf8_decode('Adhésion '.$lastMemberRegistered['libelle']),1,0,'L',1);
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('1'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode($lastMemberRegistered['montant_cotisation'].' Euros'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste Réglement
        $position_endDetail = 137; // Position à 16mm de l'entête (121+16)
        $pdf->SetY($position_endDetail);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',11);
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.number_format($lastMemberRegistered['montant_cotisation'], 2, ',', '').' Euros*** de la part de '.$lastMemberRegistered['prenom'].' '.$lastMemberRegistered['nom'].' en règlement de sa cotisation '.$lastMemberRegistered['libelle'].' en date du '.$jourCotisation.' '.$numMois.' '.$anneeCotisation.'.'),0,'L',0);
        $pdf->Ln(); // Retour à la ligne
        // Recapitulatif
        $position_recap = 153; // Position à 16mm de l'entête (137+16)
        $pdf->SetY($position_recap);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(50,8,utf8_decode('Type du paiement :'),0,0,'L',0);
        $pdf->SetFont('Arial','',11);
        $pdf->SetX(58); // 8 + 50
        $pdf->Cell(50,8,utf8_decode('Adhésion'),0,0,'L',0);
        $pdf->SetY(160);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','B',11);
        $pdf->Cell(50,8,utf8_decode('Mode de versement :'),0,0,'L',0);
        $pdf->SetFont('Arial','',11);
        $pdf->SetX(58);
        $pdf->Cell(50,8,utf8_decode($lastMemberRegistered['mode_reglement']),0,0,'L',0);
        $pdf->Ln(); // Retour à la ligne
        // Signature
        $position_sign = 176; // Position à 16mm de l'entête (160+16)
        $pdf->SetY($position_sign);
        $pdf->SetX(116);
        $pdf->SetFont('Arial','',11);
        $pdf->Cell(70,8,utf8_decode('Fait à '.$associationDisplayForPdf['footerdoc'].' le '.date("d-m-Y")),0,0,'R',0);
        $pdf->SetY(182);
        $pdf->SetX(116);
        $pdf->Cell(70,8,utf8_decode($associationDisplayForPdf['prenomSign'].' '.$associationDisplayForPdf['nomSign']),0,0,'R',0);
        $pdf->SetY(188);
        $pdf->SetX(116);
        $pdf->Cell(70,8,utf8_decode($associationDisplayForPdf['fonctionSign']),0,0,'R',0);
        $pdf->Image($associationDisplayForPdf['signature'],124,170,80);
        $pdf->Ln(); // Retour à la ligne
        // Footer
        $position_footer = 260; // Position à 16mm de l'entête (188+72)
        $pdf->SetY($position_footer);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','I',8);
        $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['libelle'].' - Déclaration à la Préfecture d\''.$associationDisplayForPdf['footerdoc'].' sous le numéro '.$associationDisplayForPdf['rna']),0,0,'C');
        $pdf->SetY($position_footer+4);
        $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['nom'].' - '.$associationDisplayForPdf['adresse'].' '.$associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']),0,0,'C');
        $pdf->SetY(268);
        $pdf->Cell('auto',8,utf8_decode('Numéro SIRENE : '.$associationDisplayForPdf['siret']),0,0,'C');
        $nomPdf = 'FactureCotisation-'.$code1.'-'.$jourCotisation.$moisCotisation.$anneeCotisation.'-'.$range[0].'.pdf';
        $path = "pdf/".$nomPdf;
        $pdf->Output($path,'F');

        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplayForPdf['nom']) && !empty($associationDisplayForPdf['nom'])) {
            $nameAsso = $associationDisplayForPdf['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplayForPdf['prenomSign']) && !empty($associationDisplayForPdf['prenomSign'])) {
            $firstnameSign = $associationDisplayForPdf['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplayForPdf['nomSign']) && !empty($associationDisplayForPdf['nomSign'])) {
            $nameSign = $associationDisplayForPdf['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplayForPdf['fonctionSign']) && !empty($associationDisplayForPdf['fonctionSign'])) {
            $functionSign = $associationDisplayForPdf['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplayForPdf['logo']) && !empty($associationDisplayForPdf['logo'])) {
            $logoAsso = $associationDisplayForPdf['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplayForPdf['email']) && !empty($associationDisplayForPdf['email']))
        {
        $emailAsso = $associationDisplayForPdf['email'];
        }
        $username = $lastMemberRegistered['prenom'];
        $firstname = $lastMemberRegistered['prenom'];
        $surname = $lastMemberRegistered['nom'];
        $emailForRegister = $lastMemberRegistered['email'];
        $textForMail = file_get_contents('template/emailRegisterForMember.html');
        $textForMail = str_replace('%username%', $username, $textForMail);
        $textForMail = str_replace('%firstname%', $firstname, $textForMail);
        $textForMail = str_replace('%surname%', $surname, $textForMail);
        $textForMail = str_replace('%emailForRegister%', $emailForRegister, $textForMail);
        // $textForMail = "Bonjour,<br>
        // Votre adhésion a bien été enregistrée. Vous trouverez ci-joint le reçu de votre paiement lié à votre cotisation<br>
        // Vous êtes membre de l'association Dis, comment on dit et vous avez accès aux différentes ressources.<br>
        // Afin d'accéder à l'application, enregistrez-vous en cliquant sur le lien ci-dessous.<br>
        // Pour vous enregistrer, merci d'utiliser votre email communiqué : ".$lastMemberRegistered['email']."<br>
        // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#ff2f92' fillcolor='#ff2f92'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je m'enregistre !</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='background-color:#ff2f92;border:1px solid #ff2f92;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je m'enregistre !</a></div>";
        // $corpsMessage = "A l'attention de : <b>".$lastMemberRegistered['prenom']." ".$lastMemberRegistered['nom']."</b><hr>
        // ".Stripslashes($textForMail)."<br>
        // Cordialement<br>
        // <b>".$nameAsso."</b>
        // ".$firstnameSign." ".$nameSign."
        // <b>".$functionSign."</b>
        // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
        // ";
        /**
        * Instanciation de la variable
        */
        $mail = new PHPMailer();
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = "Votre facture liée à votre don"; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $mail->MsgHTML($textForMail);
            // $mail->MsgHTML(nl2br($corpsMessage));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            $mail->addAttachment($path, $nomPdf, $encoding='base64', $type='.pdf');
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($lastMemberRegistered['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php?controller=dons");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

    /**
     * Fonction qui envoie en PJ le PDF reçu fiscal aux donateurs sélectionnés
     *
     * @param [type] $associationDisplayForPdf
     * @return void
     */
    public function sendMailMassTaxReceipt($associationDisplayForPdf){
        $destinataire = [];
        foreach($_POST["destinataire"] as $dataUserSelected){
            $dataUserSelected = explode("|", $dataUserSelected);
            array_push($destinataire, $dataUserSelected);
        }
        // Vérification des données reçues du formulaire
        $nameAsso = $associationDisplayForPdf['nom'];
        $firstnameSign = $associationDisplayForPdf['prenomSign'];
        $nameSign = $associationDisplayForPdf['nomSign'];
        $functionSign = $associationDisplayForPdf['fonctionSign'];
        $logoAsso = $associationDisplayForPdf['logo'];
        $emailAsso = $associationDisplayForPdf['email'];
        $subjectMail = "Votre reçu fiscal";
        foreach ($destinataire as $dest) {
            $pdf = new FPDF('P','mm','A4');
            // Nouvelle page A4 (incluant ici logo, titre et pied de page) 
            $pdf->AddPage();
            // Logo : 8 >position à gauche du document (en mm), 2 >position en haut du document, 30 >largeur de l'image en mm). La hauteur est calculée automatiquement.
            $pdf->Image($logoAsso,8,2,30);
            $pdf->SetFont('Arial','B',14);
            $pdf->Cell(80);
            // position du coin supérieur gauche par rapport à la marge gauche (mm) 
            $pdf->SetX(70);
            // Texte : 80 >largeur ligne, 10 >hauteur ligne. Premier 1 >bordure, 0 >pas de retour à la ligne, C >centrer texte, pas de couleur de fond
            $pdf->Cell(80,10,utf8_decode('Reçu au titre des dons'),1,0,'C');
            // Saut de ligne 20 mm
            $pdf->Ln(20);
            // Infos de l'association calées à gauche
            $pdf->SetFont('Arial','B',14);
            $pdf->Text(8,43,utf8_decode($nameAsso));
            $pdf->SetFont('Arial','',11);
            $pdf->Text(8,48,utf8_decode($associationDisplayForPdf['adresse']));
            $pdf->Text(8,53,utf8_decode($associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']));
            $pdf->Text(8,58,utf8_decode($associationDisplayForPdf['email']));
            $pdf->Text(8,63,utf8_decode($associationDisplayForPdf['site']));
            // Infos de la facture calées à droite
            $pdf->SetFont('Arial','B',14);
            $pdf->Text(120,43,utf8_decode('Reçu pour un don'));
            $pdf->SetFont('Arial','',11);
            $pdf->Text(120,48,utf8_decode('Don N° : '.$dest[7]));
            $pdf->Text(120,53,utf8_decode('Date : '.date("d-m-Y")));
            // Infos du client calées à droite
            $pdf->Text(120,63,utf8_decode('A l\'attention de :'));
            $pdf->SetFont('Arial','B',11);
            $pdf->Text(120,73,utf8_decode($dest[1].' '.$dest[2]));
            $pdf->SetFont('Arial','',11);
            $pdf->Text(120,78,utf8_decode($dest[3]));
            $pdf->Text(120,83,utf8_decode($dest[4]).' '.utf8_decode($dest[5]));
            // Position de l'entête à 10mm des infos (83 + 30)
            $position_entete = 93;
            $pdf->SetY($position_entete);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50,6,utf8_decode('Bénéficiaire du don :'),0,0,'L',0);
            $pdf->SetY(100);
            $pdf->SetX(16);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Nom :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($nameAsso),0,0,'L',0);
            $pdf->SetY(106);
            $pdf->SetX(16);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Adresse :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($associationDisplayForPdf['adresse']),0,0,'L',0);
            $pdf->SetY(112);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']),0,0,'L',0);
            $pdf->SetY(120);
            $pdf->SetX(16);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,5,utf8_decode('Objet social :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->MultiCell(140,5,utf8_decode($associationDisplayForPdf['objetsocial']),0,'L',0);
            $pdf->Line(8,143,210-8,143);
            $pdf->SetY(146);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','',12);
            $pdf->Cell(50,6,utf8_decode('Donateur :'),0,0,'L',0);
            $pdf->SetY(153);
            $pdf->SetX(16);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Nom :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($dest[1].' '.$dest[2]),0,0,'L',0);
            $pdf->SetY(159);
            $pdf->SetX(16);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Adresse :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($dest[3]),0,0,'L',0);
            $pdf->SetY(165);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($dest[4].' '.$dest[5]),0,0,'L',0);
            $pdf->Ln(); // Retour à la ligne
            $pdf->Line(8,173,210-8,173);
            $pdf->SetY(176);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','',10);
            $pdf->MultiCell(194,6,utf8_decode('L\'association '.$nameAsso.' reconnait avoir reçu la somme de ***'.number_format($dest[8], 2, ',', '').' Euros*** de la part de '.$dest[1].' '.$dest[2].' en règlement de son don '.$dest[7].' en date du '.date("d-m-Y", strtotime($dest[9])).'.'),0,'L',0);
            $pdf->SetX(8);
            $pdf->MultiCell(194,6,utf8_decode('Le bénéficiaire certifie sur l\'honneur que les dons et versements qu\'il reçoit ouvrent droit à la réduction d\'impôt prévue aux articles 200, 238 bis et 978 du Code Général des Impôts. '),0,'L',0);
            // Recapitulatif
            $position_recap = 206; // Position à 16mm de l'entête (137+16)
            $pdf->SetY($position_recap);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Type du paiement :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58); // 8 + 50
            $pdf->Cell(50,6,utf8_decode('Déclaration de don manuel'),0,0,'L',0);
            $pdf->SetY(212);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Nature du don :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58); // 8 + 50
            $pdf->Cell(50,6,utf8_decode('Numéraire'),0,0,'L',0);
            $pdf->SetY(218);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','B',10);
            $pdf->Cell(50,6,utf8_decode('Mode de versement :'),0,0,'L',0);
            $pdf->SetFont('Arial','',10);
            $pdf->SetX(58);
            $pdf->Cell(50,6,utf8_decode($dest[10]),0,0,'L',0);
            // Signature
            $position_sign = 224; // Position à 16mm de l'entête (160+16)
            $pdf->SetY($position_sign);
            $pdf->SetX(116);
            $pdf->SetFont('Arial','',10);
            $pdf->Cell(70,6,utf8_decode('Fait à '.$associationDisplayForPdf['footerdoc'].' le '.date("d-m-Y")),0,0,'R',0);
            $pdf->SetY(228);
            $pdf->SetX(116);
            $pdf->Cell(70,6,utf8_decode($firstnameSign.' '.$nameSign),0,0,'R',0);
            $pdf->SetY(232);
            $pdf->SetX(116);
            $pdf->Cell(70,6,utf8_decode($functionSign),0,0,'R',0);
            $pdf->Image($associationDisplayForPdf['signature'],124,210,80);
            $pdf->Ln(); // Retour à la ligne
            // Footer
            $position_footer = 260; // Position à 16mm de l'entête (188+72)
            $pdf->SetY($position_footer);
            $pdf->SetX(8);
            $pdf->SetFont('Arial','I',8);
            $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['libelle'].' - Déclaration à la Préfecture d\''.$associationDisplayForPdf['footerdoc'].' sous le numéro '.$associationDisplayForPdf['rna']),0,0,'C');
            $pdf->SetY($position_footer+4);
            $pdf->Cell('auto',8,utf8_decode($associationDisplayForPdf['nom'].' - '.$associationDisplayForPdf['adresse'].' '.$associationDisplayForPdf['CP'].' '.$associationDisplayForPdf['ville']),0,0,'C');
            $pdf->SetY(268);
            $pdf->Cell('auto',8,utf8_decode('Numéro SIRENE : '.$associationDisplayForPdf['siret']),0,0,'C');
            $nomPdf = utf8_decode('Recu Fiscal-'.$dest[7].'.pdf');
            $path = "pdf/".$nomPdf;
            $pdf->Output($path, 'F');
                    /*** Tentative d’envoi de mail*/
        try {
            $mail = new PHPMailer();
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = $subjectMail;
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $contenu = "A l'attention de : <b>".$dest[1]." ".$dest[2]."</b><hr>
            Bonjour,<br>
            Vous trouverez en pièce jointe votre reçu fiscal concernant votre don.<br>
            Cordialement<br>
            <b>".$nameAsso."</b>
            ".$firstnameSign." ".$nameSign."
            <b>".$functionSign."</b>
            <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
            ";
            $mail->MsgHTML(nl2br($contenu));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            $logo = $logoAsso;
            $mail->addAttachment($path, $nomPdf, $encoding='base64', $type='.pdf');
            $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($dest[6]);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send(); // sujet du mail
        }
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php?controller=dons&action=taxReceipt");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
        }

    }
    
    /**
     * Fonction enregistrement de l'adhérent dans la BDD avec mot de passe crypté
     *
     * @return void
     */
    public function registerMember($associationDisplay,$userFound) {

        $email = $_POST['email'];
        $password = $_POST['password'];
        $password_confirm = $_POST['password_confirm'];

        // function str_random($length){
        //     $alphabet = "0123456789azertyuiopqsdfghjklmwxcvbnAZERTYUIOPQSDFGHJKLMWXCVBN";
        //     return substr(str_shuffle(str_repeat($alphabet, $length)), 0, $length);
        // }
        $id = $userFound['id'];
        // $token = $this->str_random(60);
        $token = $this->generate_token(60);
        $encryptedPassword = password_hash($password, PASSWORD_BCRYPT);
        $requete = $this->connexion->prepare("UPDATE adherent
        SET password = :password, confirmation_token = :confirmation_token 
        WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':password', $encryptedPassword);
        $requete->bindParam(':confirmation_token', $token);
        $userRegistered = $requete->execute();
        $this->setFlash('success', 'Un email de confirmation vous a été envoyé pour valider votre compte');

        $nameAsso = $associationDisplay['nom'];
        $firstnameSign = $associationDisplay['prenomSign'];
        $nameSign = $associationDisplay['nomSign'];
        $functionSign = $associationDisplay['fonctionSign'];
        $logoAsso = $associationDisplay['logo'];
        $emailAsso = $associationDisplay['email'];
        $subjectMail = "Confirmation de votre compte";
    
        try {
            $mail = new PHPMailer();
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = $subjectMail;
            $linkForConfirm = "https://www.discommentondit.fr/app/index.php?controller=security&action=confirm&id=".$id."&token=".$token."";
            $contenu = file_get_contents('template/emailConfirm.html');
            $contenu = str_replace('%linkForConfirmAccount%', $linkForConfirm, $contenu);
            // $contenu = "Bonjour,<br>
            // Afin de valider votre compte merci de cliquer sur ce lien<br>
            // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index.php?controller=security&action=confirm&id=".$id."&token=".$token."' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#0096FF' fillcolor='#0096FF'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je confirme mon inscription</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index.php?controller=security&action=confirm&id=".$id."&token=".$token."' style='background-color:#0096FF;border:1px solid #0096FF;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je confirme mon inscription</a></div>
            // Cordialement<br>
            // <b>".$nameAsso."</b>
            // ".$firstnameSign." ".$nameSign."
            // <b>".$functionSign."</b>
            // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
            // ";
            $mail->MsgHTML($contenu);
            // $mail->MsgHTML(nl2br($contenu));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($email);
            $envoiOK = $mail->Send(); // sujet du mail
        }
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php?controller=security&action=formLogin");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }
    
    /**
     * Fonction pour tester si le mail renseigné existe dans la BDD pour la modif du password
     *
     * @return void
     */
    public function testForget($associationDisplay)
    {
        unset($_SESSION['user']);
        unset($_SESSION['flash']);
        $email = $_POST['loginForget'];

        if(!empty($_POST) && !empty($email)){
            $requete = $this->connexion->prepare("SELECT *
            FROM adherent WHERE email = :email AND confirmed_at IS NOT NULL");
            $requete->bindParam(':email', $email);
            $result = $requete->execute();
            $userForget = $requete->fetch(PDO::FETCH_ASSOC);
            if ($userForget) {
                // $reset_token = $this->str_random(60);
                $reset_token = $this->generate_token(60);
                $requete = $this->connexion->prepare("UPDATE adherent 
                SET password = NULL, reset_token = :reset_token, reset_at = NOW() WHERE id = :id");
                $requete->bindParam(':id', $userForget['id']);
                $requete->bindParam(':reset_token', $reset_token);
                $result = $requete->execute();
                $this->setFlash('success', 'Les instructions du rappel de mot de passe vous ont été envoyées par email
                <br>Si vous n\'avez pas reçu d\'e-mail, vérifiez le dossier «Spam» ou contactez-nous <a href="mailto:contact@discommentondit.fr">contact@discommentondit.fr</a>');

                $nameAsso = $associationDisplay['nom'];
                $firstnameSign = $associationDisplay['prenomSign'];
                $nameSign = $associationDisplay['nomSign'];
                $functionSign = $associationDisplay['fonctionSign'];
                $logoAsso = $associationDisplay['logo'];
                $emailAsso = $associationDisplay['email'];
                $subjectMail = "Votre mot de passe";
            
                try {
                    $mail = new PHPMailer();
                    $mail->From = $emailAsso; // mail Expéditeur
                    $mail->FromName = $nameAsso; // nom Expéditeur
                    $mail->Subject = $subjectMail;
                    $username = $userForget['prenom'];
                    $firstname = $userForget['prenom'];
                    $surname = $userForget['nom'];
                    $linkForPwForget = "https://www.discommentondit.fr/app/index.php?controller=security&action=confirmForget&id=".$userForget['id']."&token=".$reset_token."";
                    $contenu = file_get_contents('template/emailForget.html');
                    $contenu = str_replace('%username%', $username, $contenu);
                    $contenu = str_replace('%firstname%', $firstname, $contenu);
                    $contenu = str_replace('%surname%', $surname, $contenu);
                    $contenu = str_replace('%linkForForgetPassword%', $linkForPwForget, $contenu);
                    // $contenu = "Bonjour ".$userForget['prenom'].",<br>
                    // Vous avez oublié votre mot de passe ou vous souhaitez le modifier ?<br>Définissez votre mot de passe et connectez-vous en cliquant sur le lien ci-dessous.<br>
                    // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index.php?controller=security&action=confirmForget&id=".$userForget['id']."&token=".$reset_token."' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#9ad601' fillcolor='#9ad601'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je mets à jour mon mot de passe</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index.php?controller=security&action=confirmForget&id=".$userForget['id']."&token=".$reset_token."' style='background-color:#9ad601;border:1px solid #9ad601;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je mets à jour mon mot de passe</a></div>
                    // Cordialement<br>
                    // <b>".$nameAsso."</b>
                    // ".$firstnameSign." ".$nameSign."
                    // <b>".$functionSign."</b>
                    // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
                    // ";
                    $mail->MsgHTML($contenu);
                    // $mail->MsgHTML(nl2br($contenu));
                    $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
                    // Méthodes
                    // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
                    $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
                    // $logo = $logoAsso;
                    // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
                    $mail->AddAddress($email);
                    $envoiOK = $mail->Send(); // sujet du mail
                }
                catch (Exception $e) {
                    echo 'Message non envoyé';
                    echo 'Erreur: ' . $mail->ErrorInfo;
                }
                if ($envoiOK) {
                    // echo 'votre mail est bien envoyé';
                    header("location: index.php?controller=security&action=formLogin");
                } else {
                    echo "Problème lors de l'envoi du mail";
                }
                echo '<pre>';
            }
            else {
                $this->setFlash('danger', 'Aucun compte ne correspond à cet adresse');
            }
        } else {
            $this->setFlash('danger', 'Merci de renseigner votre adresse email');
        }
        return $userForget;
    }

    /**
     * Fonction envoi de mail pour l'enregistrement à l'application
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function sendMailForRegister($associationDisplay,$memberForRegister)
    {
        // Vérification des données reçues du formulaire
        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplay['nom']) && !empty($associationDisplay['nom'])) {
            $nameAsso = $associationDisplay['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplay['prenomSign']) && !empty($associationDisplay['prenomSign'])) {
            $firstnameSign = $associationDisplay['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplay['nomSign']) && !empty($associationDisplay['nomSign'])) {
            $nameSign = $associationDisplay['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplay['fonctionSign']) && !empty($associationDisplay['fonctionSign'])) {
            $functionSign = $associationDisplay['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplay['logo']) && !empty($associationDisplay['logo'])) {
            $logoAsso = $associationDisplay['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplay['email']) && !empty($associationDisplay['email']))
        {
        $emailAsso = $associationDisplay['email'];
        }
        $subjectMail = "Enregistrement sur l'application Dis, comment on dit ?";

        /**
        * Instanciation de la variable
        */
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail = new PHPMailer();
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = $subjectMail; // sujet du mail
            $username = $memberForRegister['prenom'];
            $firstname = $memberForRegister['prenom'];
            $surname = $memberForRegister['nom'];
            $emailForRegister = $memberForRegister['email'];
            $contenu = file_get_contents('template/emailRegister.html');
            $contenu = str_replace('%username%', $username, $contenu);
            $contenu = str_replace('%firstname%', $firstname, $contenu);
            $contenu = str_replace('%surname%', $surname, $contenu);
            $contenu = str_replace('%emailForRegister%', $emailForRegister, $contenu);
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            // $contenu = "A l'attention de : <b>".$memberForRegister['prenom']." ".$memberForRegister['nom']."</b><hr>
            //     <h2>Enregistrez-vous sur l'application</h2><br>
            //     Bonjour ".$memberForRegister['prenom'].",<br>
            //     Vous êtes membre de l'association Dis, comment on dit et vous avez accès aux différentes ressources.<br>
            //     Afin d'accéder à l'application, enregistrez-vous en cliquant sur le lien ci-dessous.<br>
            //     Pour vous enregistrer, merci d'utiliser votre email communiqué : ".$memberForRegister['email']."<br>
            //     <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#ff2f92' fillcolor='#ff2f92'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je m'enregistre !</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='background-color:#ff2f92;border:1px solid #ff2f92;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je m'enregistre !</a></div>
            //     Cordialement<br>
            //     <b>".$nameAsso."</b>
            //     ".$firstnameSign." ".$nameSign."
            //     <b>".$functionSign."</b>
            //     <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
            //     ";
            $mail->MsgHTML($contenu);
            // $mail->MsgHTML(nl2br($contenu));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($memberForRegister['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
        echo 'Message non envoyé';
        echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            header("location: index.php");
        }
        else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

    /**
     * Fonction envoi de mail suite page contact
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function sendMailForContact($associationDisplay)
    {
        // Vérification des données reçues du formulaire
        $first_name = ucfirst($_POST['first_name']);
        $last_name = ucfirst($_POST['last_name']);
        $emailContact = $_POST['emailContact'];
        $phoneContact = $_POST['phoneContact'];
        $problem_type = $_POST['problem_type'];
        $messageContact = $_POST['messageContact'];
        $emailAsso = "Aucun mail de l'association";
        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplay['nom']) && !empty($associationDisplay['nom'])) {
            $nameAsso = $associationDisplay['nom'];
        }
        if (isset($associationDisplay['email']) && !empty($associationDisplay['email']))
        {
        $emailAsso = $associationDisplay['email'];
        }
        $subjectMail = "Contact provenant de l'application Dis, comment on dit ?";

        /**
        * Instanciation de la variable
        */
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail = new PHPMailer();
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = $subjectMail; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $contenu = "De la part de : <b>".$first_name." ".$last_name."</b><hr>
                Type de problème : ".$problem_type."<br>
                Message de l'adhérent :<br>
                ".$messageContact."<br>
                Email de l'adhérent : ".$emailContact."<br>
                Téléphone de l'adhérent : ".$phoneContact."<br>
                ";
            $mail->MsgHTML(nl2br($contenu));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            $mail->AddAddress($emailAsso);
            $mail->AddBCC($emailContact);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
        echo 'Message non envoyé';
        echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            // echo 'votre mail est bien envoyé';
            $this->setFlash('success', 'Votre message a bien été envoyé');
            header("location:index.php?controller=dashboard");
        }
        else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

    /**
     * Fonction envoi de mail pour l'enregistrement à l'application lors de l'ajout de l'utilisateur
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $lastMemberRegistered
     * @return void
     */
    public function sendMailForRegisterForUser($associationDisplayForPdf,$memberForRegister)
    {
        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplayForPdf['nom']) && !empty($associationDisplayForPdf['nom'])) {
            $nameAsso = $associationDisplayForPdf['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplayForPdf['prenomSign']) && !empty($associationDisplayForPdf['prenomSign'])) {
            $firstnameSign = $associationDisplayForPdf['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplayForPdf['nomSign']) && !empty($associationDisplayForPdf['nomSign'])) {
            $nameSign = $associationDisplayForPdf['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplayForPdf['fonctionSign']) && !empty($associationDisplayForPdf['fonctionSign'])) {
            $functionSign = $associationDisplayForPdf['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplayForPdf['logo']) && !empty($associationDisplayForPdf['logo'])) {
            $logoAsso = $associationDisplayForPdf['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplayForPdf['email']) && !empty($associationDisplayForPdf['email']))
        {
        $emailAsso = $associationDisplayForPdf['email'];
        }
        $username = $memberForRegister['prenom'];
        $firstname = $memberForRegister['prenom'];
        $surname = $memberForRegister['nom'];
        $emailForRegister = $memberForRegister['email'];
        $groupUser = $memberForRegister['nom_groupe'];
        $textForMail = file_get_contents('template/emailRegisterForUser.html');
        $textForMail = str_replace('%username%', $username, $textForMail);
        $textForMail = str_replace('%firstname%', $firstname, $textForMail);
        $textForMail = str_replace('%surname%', $surname, $textForMail);
        $textForMail = str_replace('%emailForRegister%', $emailForRegister, $textForMail);
        $textForMail = str_replace('%nomgroupe%', $groupUser, $textForMail);
        // $textForMail = "Bonjour ".$memberForRegister['prenom'].",<br>
        // Votre enregistrement a bien été effectué et vous appartenez au groupe : ".$memberForRegister['nom_groupeAdherent'].".<br>
        // Etant nouvel utilisateur de l'association Dis, comment on dit, vous avez accès aux différentes ressources.<br>
        // Afin d'accéder à l'application, enregistrez-vous en cliquant sur le lien ci-dessous.<br>
        // Pour vous enregistrer, merci d'utiliser votre email communiqué : ".$memberForRegister['email']."<br>
        // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#ff2f92' fillcolor='#ff2f92'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je m'enregistre !</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='background-color:#ff2f92;border:1px solid #ff2f92;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je m'enregistre !</a></div>";
        // $corpsMessage = "A l'attention de : <b>".$memberForRegister['prenom']." ".$memberForRegister['nom']."</b><hr>
        // ".Stripslashes($textForMail)."<br>
        // Cordialement<br>
        // <b>".$nameAsso."</b>
        // ".$firstnameSign." ".$nameSign."
        // <b>".$functionSign."</b>
        // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
        // ";
        /**
        * Instanciation de la variable
        */
        $mail = new PHPMailer();
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = "Enregistrement sur l'application Dis, comment on dit ?"; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $mail->MsgHTML($textForMail);
            // $mail->MsgHTML(nl2br($corpsMessage));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($memberForRegister['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            $this->setFlash('success', 'Votre message a bien été envoyé');
            header("location: index.php?controller=user");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

        /**
     * Fonction envoi de mail pour l'enregistrement à l'application lors de l'ajout de l'utilisateur
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $lastMemberRegistered
     * @return void
     */
    public function sendMailForRegisterForUserSwitch($associationDisplayForPdf,$memberForRegister)
    {
        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplayForPdf['nom']) && !empty($associationDisplayForPdf['nom'])) {
            $nameAsso = $associationDisplayForPdf['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplayForPdf['prenomSign']) && !empty($associationDisplayForPdf['prenomSign'])) {
            $firstnameSign = $associationDisplayForPdf['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplayForPdf['nomSign']) && !empty($associationDisplayForPdf['nomSign'])) {
            $nameSign = $associationDisplayForPdf['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplayForPdf['fonctionSign']) && !empty($associationDisplayForPdf['fonctionSign'])) {
            $functionSign = $associationDisplayForPdf['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplayForPdf['logo']) && !empty($associationDisplayForPdf['logo'])) {
            $logoAsso = $associationDisplayForPdf['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplayForPdf['email']) && !empty($associationDisplayForPdf['email']))
        {
        $emailAsso = $associationDisplayForPdf['email'];
        }
        $username = $memberForRegister['prenom'];
        $firstname = $memberForRegister['prenom'];
        $surname = $memberForRegister['nom'];
        $emailForRegister = $memberForRegister['email'];
        $groupUser = $memberForRegister['nom_groupe'];
        $textForMail = file_get_contents('template/emailRegisterForUser.html');
        $textForMail = str_replace('%username%', $username, $textForMail);
        $textForMail = str_replace('%firstname%', $firstname, $textForMail);
        $textForMail = str_replace('%surname%', $surname, $textForMail);
        $textForMail = str_replace('%emailForRegister%', $emailForRegister, $textForMail);
        $textForMail = str_replace('%nomgroupe%', $groupUser, $textForMail);
        // $textForMail = "Bonjour ".$memberForRegister['prenom'].",<br>
        // Votre enregistrement a bien été effectué et vous appartenez au groupe : ".$memberForRegister['nom_groupe'].".<br>
        // Etant nouvel utilisateur de l'association Dis, comment on dit, vous avez accès aux différentes ressources.<br>
        // Afin d'accéder à l'application, enregistrez-vous en cliquant sur le lien ci-dessous.<br>
        // Pour vous enregistrer, merci d'utiliser votre email communiqué : ".$memberForRegister['email']."<br>
        // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#ff2f92' fillcolor='#ff2f92'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je m'enregistre !</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='background-color:#ff2f92;border:1px solid #ff2f92;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je m'enregistre !</a></div>";
        // $corpsMessage = "A l'attention de : <b>".$memberForRegister['prenom']." ".$memberForRegister['nom']."</b><hr>
        // ".Stripslashes($textForMail)."<br>
        // Cordialement<br>
        // <b>".$nameAsso."</b>
        // ".$firstnameSign." ".$nameSign."
        // <b>".$functionSign."</b>
        // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
        // ";
        /**
        * Instanciation de la variable
        */
        $mail = new PHPMailer();
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = "Enregistrement sur l'application Dis, comment on dit ?"; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $mail->MsgHTML($textForMail);
            // $mail->MsgHTML(nl2br($corpsMessage));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($memberForRegister['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            $this->setFlash('success', 'Votre message a bien été envoyé');
            header("location: index.php?controller=user");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }

    /**
     * Fonction envoi de mail pour l'enregistrement à l'application lors du click d'envoi pour l'utilisateur uniquement
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $lastMemberRegistered
     * @return void
     */
    public function sendMailForUserAfterAdding($associationDisplayForPdf,$lastMemberRegistered)
    {
        $nameAsso = "Aucun nom d'association";
        if (isset($associationDisplayForPdf['nom']) && !empty($associationDisplayForPdf['nom'])) {
            $nameAsso = $associationDisplayForPdf['nom'];
        }
        $firstnameSign = "Aucun prénom du signataire";
        if (isset($associationDisplayForPdf['prenomSign']) && !empty($associationDisplayForPdf['prenomSign'])) {
            $firstnameSign = $associationDisplayForPdf['prenomSign'];
        }
        $nameSign = "Aucun nom du signataire";
        if (isset($associationDisplayForPdf['nomSign']) && !empty($associationDisplayForPdf['nomSign'])) {
            $nameSign = $associationDisplayForPdf['nomSign'];
        }
        $functionSign = "Aucune fonction";
        if (isset($associationDisplayForPdf['fonctionSign']) && !empty($associationDisplayForPdf['fonctionSign'])) {
            $functionSign = $associationDisplayForPdf['fonctionSign'];
        }
        $logoAsso = "img/undefined.jpg";
        if (isset($associationDisplayForPdf['logo']) && !empty($associationDisplayForPdf['logo'])) {
            $logoAsso = $associationDisplayForPdf['logo'];
        }
        $emailAsso = "Aucun mail de l'association";
        if (isset($associationDisplayForPdf['email']) && !empty($associationDisplayForPdf['email']))
        {
        $emailAsso = $associationDisplayForPdf['email'];
        }
        $username = $lastMemberRegistered['prenom'];
        $firstname = $lastMemberRegistered['prenom'];
        $surname = $lastMemberRegistered['nom'];
        $emailForRegister = $lastMemberRegistered['email'];
        $groupUser = $lastMemberRegistered['nom_groupeAdherent'];
        $textForMail = file_get_contents('template/emailRegisterForUser.html');
        $textForMail = str_replace('%username%', $username, $textForMail);
        $textForMail = str_replace('%firstname%', $firstname, $textForMail);
        $textForMail = str_replace('%surname%', $surname, $textForMail);
        $textForMail = str_replace('%emailForRegister%', $emailForRegister, $textForMail);
        $textForMail = str_replace('%nomgroupe%', $groupUser, $textForMail);
        // $textForMail = "Bonjour ".$lastMemberRegistered['prenom'].",<br>
        // Votre enregistrement a bien été effectué et vous appartenez au groupe : ".$lastMemberRegistered['nom_groupeAdherent'].".<br>
        // Etant nouvel utilisateur de l'association Dis, comment on dit, vous avez accès aux différentes ressources.<br>
        // Afin d'accéder à l'application, enregistrez-vous en cliquant sur le lien ci-dessous.<br>
        // Pour vous enregistrer, merci d'utiliser votre email communiqué : ".$lastMemberRegistered['email']."<br>
        // <div><!--[if mso]><v:roundrect xmlns:v='urn:schemas-microsoft-com:vml' xmlns:w='urn:schemas-microsoft-com:office:word' href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='height:40px;v-text-anchor:middle;width:280px;' arcsize='13%' strokecolor='#ff2f92' fillcolor='#ff2f92'><w:anchorlock/><center style='color:#ffffff;font-family:sans-serif;font-size:13px;font-weight:bold;'>Je m'enregistre !</center></v:roundrect><![endif]--><a href='https://www.discommentondit.fr/app/index?controller=security&action=formRegister' style='background-color:#ff2f92;border:1px solid #ff2f92;border-radius:5px;color:#ffffff;display:inline-block;font-family:sans-serif;font-size:16px;font-weight:bold;line-height:40px;text-align:center;text-decoration:none;width:280px;-webkit-text-size-adjust:none;mso-hide:all;'>Je m'enregistre !</a></div>";
        // $corpsMessage = "A l'attention de : <b>".$lastMemberRegistered['prenom']." ".$lastMemberRegistered['nom']."</b><hr>
        // ".Stripslashes($textForMail)."<br>
        // Cordialement<br>
        // <b>".$nameAsso."</b>
        // ".$firstnameSign." ".$nameSign."
        // <b>".$functionSign."</b>
        // <img alt='Logo ".$nameAsso."' <img height='auto' width='65' src='cid:my-attach'>
        // ";
        /**
        * Instanciation de la variable
        */
        $mail = new PHPMailer();
        /*** Tentative d’envoi de mail*/
        try {
            // Ajout des attributs
            $mail->From = $emailAsso; // mail Expéditeur
            $mail->FromName = $nameAsso; // nom Expéditeur
            $mail->Subject = "Enregistrement sur l'application Dis, comment on dit ?"; // sujet du mail
            // Création d'une variable qui va contenir tout le commentaires de l'email et autoformat HTML
            $mail->MsgHTML($textForMail);
            // $mail->MsgHTML(nl2br($corpsMessage));
            $mail->CharSet = 'UTF-8'; // On précise l'encodage de caractères.
            // Méthodes
            // Définition d'un message alternatif pour les boîtes de messagerie n'acceptant pas le html
            $mail->AltBody = "Ce message est au format HTML, votre messagerie n'accepte pas ce format.";
            // $logo = $logoAsso;
            // $mail->AddEmbeddedImage($logo, "my-attach", 'logo_association', $encoding = "base64", $type = "image/png");
            $mail->AddAddress($lastMemberRegistered['email']);
            $mail->AddBCC($emailAsso);
            $envoiOK = $mail->Send();
        }
        /**
        * Traitement de l’exception levée en cas d’erreur
        */
        catch (Exception $e) {
            echo 'Message non envoyé';
            echo 'Erreur: ' . $mail->ErrorInfo;
        }
        if ($envoiOK) {
            $this->setFlash('success', 'Votre message a bien été envoyé');
            header("location: index.php?controller=user");
        } else {
            echo "Problème lors de l'envoi du mail";
        }
        echo '<pre>';
    }
}
