<?php

class CampaignModel extends Model {

    /**
     * Fonction ajout de donnée dans la table cotisation si le bouton radio date à date est sélectionné
     *
     * @return void
     */
    public function addDBWithDateToDate()
    {
        $libelleCotisation = ucfirst($_POST['libelleCotisation']);
        $montantCotisation = floatval($_POST['tarifCotisation']);
        $montantBrut = $montantCotisation;
        $campaignStartDate = $_POST['campaignStartDate'];
        $campaignEndDate = $_POST['campaignEndDate'];
        $libelleReduction = $_POST['libelleReduction'];
        $libelleIfReduction = $libelleCotisation." - ".ucfirst($libelleReduction);
        $valueReduction = floatval($_POST['valueReduction']);
        $rate = $_POST['rate'];
        $valueReductionPercent = $valueReduction;
        $valueReductionPercentForDB = $valueReductionPercent." %";
        $valueReductionByMounth = $valueReduction;
        $valueReductionByMounthForDB = $valueReductionByMounth." €";
        $newSubscriptionFeePercent = "";
        if (isset($valueReductionPercent) && !empty($valueReductionPercent) && $valueReductionPercent >=0 && $valueReductionPercent <= 100) {
            $newSubscriptionFeePercent = $montantCotisation * ((100 - $valueReduction) / 100);
        }
        $newSubscriptionFeeMounth = "";
        if (isset($valueReductionByMounth) && !empty($valueReductionByMounth) && $valueReductionByMounth >=0 && $valueReductionByMounth <= $montantCotisation) {
            $newSubscriptionFeeMounth = $montantCotisation - $valueReduction;
        }
        
        if (isset($libelleReduction) && !empty($libelleReduction) && $rate == "percent") {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, :debutDateCotisation, :finDateCotisation, '0', :montantReduction, :montantBrut)");
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeePercent);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':montantReduction', $valueReductionPercentForDB);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        } elseif (isset($libelleReduction) && !empty($libelleReduction) && $rate == "currency") {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, :debutDateCotisation, :finDateCotisation, '0', :montantReduction, :montantBrut)");
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeeMounth);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':montantReduction', $valueReductionByMounthForDB);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        } else {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, :debutDateCotisation, :finDateCotisation, '0', '', :montantBrut)");
            $requete->bindParam(':montant_cotisation', $montantCotisation);
            $requete->bindParam(':libelle', $libelleCotisation);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        }
    }

    /**
     * Fonction ajout de donnée dans la table cotisation si le bouton radio nombre de mois est sélectionné
     *
     * @return void
     */
    public function addDBWithMounthFixed()
    {
        $libelleCotisation = ucfirst($_POST['libelleCotisation']);
        $montantCotisation = floatval($_POST['tarifCotisation']);
        $montantBrut = $montantCotisation;
        $campaignLength = $_POST['campaignLength'];
        $libelleReduction = $_POST['libelleReduction'];
        $libelleIfReduction = $libelleCotisation." - ".ucfirst($libelleReduction);
        $valueReduction = floatval($_POST['valueReduction']);
        $rate = $_POST['rate'];
        $valueReductionPercent = $valueReduction;
        $valueReductionPercentForDB = $valueReductionPercent." %";
        $valueReductionByMounth = $valueReduction;
        $valueReductionByMounthForDB = $valueReductionByMounth." €";
        $newSubscriptionFeePercent = "";
        if (isset($valueReductionPercent) && !empty($valueReductionPercent) && $valueReductionPercent >=0 && $valueReductionPercent <= 100) {
            $newSubscriptionFeePercent = $montantCotisation * ((100 - $valueReduction) / 100);
        }
        $newSubscriptionFeeMounth = "";
        if (isset($valueReductionByMounth) && !empty($valueReductionByMounth) && $valueReductionByMounth >=0 && $valueReductionByMounth <= $montantCotisation) {
            $newSubscriptionFeeMounth = $montantCotisation - $valueReduction;
        }

        if (isset($libelleReduction) && !empty($libelleReduction) && $rate == "percent") {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, '', '', :dureeMois, :montantReduction, :montantBrut)");
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeePercent);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionPercentForDB);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        } elseif (isset($libelleReduction) && !empty($libelleReduction) && $rate == "currency") {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, '', '', :dureeMois, :montantReduction, :montantBrut)");
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeeMounth);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionByMounthForDB);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        } else {
            $requete = $this->connexion->prepare("INSERT INTO cotisation 
            VALUES (NULL, :montant_cotisation, :libelle, '', '', :dureeMois, '', :montantBrut)");
            $requete->bindParam(':montant_cotisation', $montantCotisation);
            $requete->bindParam(':libelle', $libelleCotisation);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantBrut', $montantBrut);
            $result = $requete->execute();
        }
    }

    /**
     * Fonction export de la table cotisation vers csv
     *
     * @param [type] $listCotisations
     * @param string $filename
     * @param [type] $delimiter
     * @return void
     */
    function export_data_to_csv($listCotisations,$filename='ListeCotisations',$delimiter = ';',$enclosure = '"'){
        // Tells to the browser that a file is returned, with its name : $filename.csv
        header("Content-disposition: attachment; filename=$filename.csv");
        // Tells to the browser that the content is a csv file
        header("Content-Type: text/csv");
    
        // I open PHP memory as a file
        $fp = fopen("php://output", 'w');
    
        // Insert the UTF-8 BOM in the file
        fputs($fp, $bom=(chr(0xEF) . chr(0xBB) . chr(0xBF)));
    
        // I add the array keys as CSV headers
        fputcsv($fp,array_keys($listCotisations[0]),$delimiter,$enclosure);
    
        // Add all the data in the file
        foreach ($listCotisations as $cotisations) {
            fputcsv($fp, $cotisations,$delimiter,$enclosure);
        }
    
        // Close the file
        fclose($fp);
    
        // Stop the script
        die();
    }

    /**
     * Fonction affichage d'une donnée de la BDD cotisation
     *
     * @return void
     */
    public function getCotisation()
    {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, c.id as id_cotisation 
        FROM cotisation as c 
        WHERE c.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $cotisation = $requete->fetch(PDO::FETCH_ASSOC);
        return $cotisation;
    }

    /**
     * Fonction suppression des données dans la table cotisation
     *
     * @return void
     */
    public function suppDB()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM cotisation
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    /**
     * Fonction mise à jour des données dans la table cotisation si le bouton radio date à date est sélectionné
     *
     * @return void
     */
    public function updateDBWithDateToDate()
    {
        $id = $_POST['idCotisation'];
        $libelleCotisationFirstTreatment = explode(" - ", $_POST['libelleCotisation']);
        $libelleCotisationSecondTreatment = ucfirst($libelleCotisationFirstTreatment[0]);
        $montantCotisation = floatval($_POST['tarifCotisation']);
        $campaignStartDate = $_POST['campaignStartDate'];
        $campaignEndDate = $_POST['campaignEndDate'];
        $libelleReduction = $_POST['libelleReduction'];
        $libelleIfReduction = $libelleCotisationSecondTreatment." - ".ucfirst($libelleReduction);
        $valueReduction = floatval($_POST['valueReduction']);
        $rate = $_POST['rate'];
        $valueReductionPercent = $valueReduction;
        $valueReductionPercentForDB = $valueReductionPercent." %";
        $valueReductionByMounth = $valueReduction;
        $valueReductionByMounthForDB = $valueReductionByMounth." €";
        $newSubscriptionFeePercent = "";
        $campaignLength = "";
        if (isset($valueReductionPercent) && !empty($valueReductionPercent) && $valueReductionPercent >=0 && $valueReductionPercent <= 100) {
            $newSubscriptionFeePercent = $montantCotisation * ((100 - $valueReduction) / 100);
        }
        $newSubscriptionFeeMounth = "";
        if (isset($valueReductionByMounth) && !empty($valueReductionByMounth) && $valueReductionByMounth >=0 && $valueReductionByMounth <= $montantCotisation) {
            $newSubscriptionFeeMounth = $montantCotisation - $valueReduction;
        }
        if (!isset($valueReduction) && empty($valueReduction)) {
            $valueUnReduced = "";
        }

        if (isset($libelleReduction) && !empty($libelleReduction) && $rate == "percent") {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeePercent);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionPercentForDB);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        } elseif (isset($libelleReduction) && !empty($libelleReduction) && $rate == "currency") {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeeMounth);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionByMounthForDB);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        } else {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $montantCotisation);
            $requete->bindParam(':libelle', $libelleCotisationSecondTreatment);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueUnReduced);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        }
    }

    /**
     * Fonction ajout des données dans la table cotisation si le bouton radio nombre de mois est sélectionné
     *
     * @return void
     */
    public function updateDBWithMounthFixed()
    {
        $id = $_POST['idCotisation'];
        $libelleCotisationFirstTreatment = explode(" - ", $_POST['libelleCotisation']);
        $libelleCotisationSecondTreatment = ucfirst($libelleCotisationFirstTreatment[0]);
        $montantCotisation = floatval($_POST['tarifCotisation']);
        $campaignLength = $_POST['campaignLength'];
        $libelleReduction = $_POST['libelleReduction'];
        $libelleIfReduction = $libelleCotisationSecondTreatment." - ".ucfirst($libelleReduction);
        $valueReduction = floatval($_POST['valueReduction']);
        $rate = $_POST['rate'];
        $valueReductionPercent = $valueReduction;
        $valueReductionPercentForDB = $valueReductionPercent." %";
        $valueReductionByMounth = $valueReduction;
        $valueReductionByMounthForDB = $valueReductionByMounth." €";
        $campaignStartDate = "";
        $campaignEndDate = "";
        $newSubscriptionFeePercent = "";
        if (isset($valueReductionPercent) && !empty($valueReductionPercent) && $valueReductionPercent >=0 && $valueReductionPercent <= 100) {
            $newSubscriptionFeePercent = $montantCotisation * ((100 - $valueReduction) / 100);
        }
        $newSubscriptionFeeMounth = "";
        if (isset($valueReductionByMounth) && !empty($valueReductionByMounth) && $valueReductionByMounth >=0 && $valueReductionByMounth <= $montantCotisation) {
            $newSubscriptionFeeMounth = $montantCotisation - $valueReduction;
        }
        if (!isset($valueReduction) && empty($valueReduction)) {
            $valueUnReduced = "";
        }

        if (isset($libelleReduction) && !empty($libelleReduction) && $rate == "percent") {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeePercent);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionPercentForDB);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        } elseif (isset($libelleReduction) && !empty($libelleReduction) && $rate == "currency") {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $newSubscriptionFeeMounth);
            $requete->bindParam(':libelle', $libelleIfReduction);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueReductionByMounthForDB);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        } else {
            $requete = $this->connexion->prepare("UPDATE cotisation SET montant_cotisation=:montant_cotisation,libelle=:libelle,debutDateCotisation=:debutDateCotisation,
            finDateCotisation=:finDateCotisation,dureeMois=:dureeMois,montantReduction=:montantReduction,montantBrut=:montantBrut WHERE id=:id");
            $requete->bindParam(':id', $id);
            $requete->bindParam(':montant_cotisation', $montantCotisation);
            $requete->bindParam(':libelle', $libelleCotisationSecondTreatment);
            $requete->bindParam(':debutDateCotisation', $campaignStartDate);
            $requete->bindParam(':finDateCotisation', $campaignEndDate);
            $requete->bindParam(':dureeMois', $campaignLength);
            $requete->bindParam(':montantReduction', $valueUnReduced);
            $requete->bindParam(':montantBrut', $montantCotisation);
            $result = $requete->execute();
        }
    }

    /**
     * Fonction permettant de matcher les ids sélectionnées à la table adherent
     *
     * @return void
     */
    public function prepareToSend()
    {
        $memberSelected = [];
        foreach($_POST['searchTable'] as $idMember){
            array_push($memberSelected, $idMember);
        }
        $ids = join(",",$memberSelected);
        $requete = $this->connexion->prepare("SELECT a.id as id_membre, a.prenom, a.nom, a.adresse, a.CP, a.ville, a.email, a.date_entree, c.montant_cotisation, c.libelle, c.debutDateCotisation, c.finDateCotisation, c.id as id_cotisation
        FROM adherent as a 
        LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        WHERE a.id IN ($ids)
        ORDER BY a.nom");
        $result = $requete->execute();
        $memberSelect = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $memberSelect;
    }
}