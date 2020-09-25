<?php

class DonsModel extends Model {

    /**
     * Fonction affichage de la BDD dons
     *
     * @return void
     */
    public function getSimplifiedDonations()
    {
        $requete = "SELECT a.id as id_donateur, numDon, a.prenom, a.nom, a.email, a.telephone, montant_don, date_don, r.mode_reglement, d.id as id_don
        FROM don as d 
        LEFT JOIN adherent as a
        ON d.id_adherent = a.id
        LEFT JOIN reglement as r
        on d.id_reglement = r.id
        ORDER BY d.id";
        $result = $this->connexion->query($requete);
        $listSimplifiedDonations = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listSimplifiedDonations;
    }

    /**
     * Fonction affichage de la BDD adherents
     *
     * @return void
     */
    public function getAdherents(){
        $requete = "SELECT *, a.id as id_adherent 
        FROM adherent as a 
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        LEFT JOIN statut as s 
        on a.id_statut = s.id
        LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r 
        ON a.id_reglement = r.id
        ORDER BY a.id";
        $result = $this->connexion->query($requete);
        $listAdherents = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listAdherents;
    }

    /**
     * Fonction export de la table simplifiée dons vers csv
     *
     * @param [type] $listSimplifiedDonations
     * @param string $filename
     * @param [type] $delimiter
     * @return void
     */
    function export_data_to_csv($listSimplifiedDonations,$filename='ListeDonateurs',$delimiter = ';',$enclosure = '"'){
        // Tells to the browser that a file is returned, with its name : $filename.csv
        header("Content-disposition: attachment; filename=$filename.csv");
        // Tells to the browser that the content is a csv file
        header("Content-Type: text/csv");
    
        // I open PHP memory as a file
        $fp = fopen("php://output", 'w');
    
        // Insert the UTF-8 BOM in the file
        fputs($fp, $bom=(chr(0xEF) . chr(0xBB) . chr(0xBF)));
    
        // I add the array keys as CSV headers
        fputcsv($fp,array_keys($listSimplifiedDonations[0]),$delimiter,$enclosure);
    
        // Add all the data in the file
        foreach ($listSimplifiedDonations as $simplifiedDonations) {
            fputcsv($fp, $simplifiedDonations,$delimiter,$enclosure);
        }
    
        // Close the file
        fclose($fp);
    
        // Stop the script
        die();
    }

    /**
     * Fonction permettant de matcher les ids sélectionnées à la table don
     *
     * @return void
     */
    public function prepareToSend()
    {
        $donatorSelected = [];
        foreach($_POST['searchTable'] as $idDonator){
            array_push($donatorSelected, $idDonator);
        }
        $ids = join(",",$donatorSelected);
        // $requete = $this->connexion->prepare("SELECT a.id as id_donateur, numDon, a.prenom, a.nom, a.email, a.telephone, montant_don, date_don, r.mode_reglement, d.id as id_don
        // FROM don as d 
        // LEFT JOIN adherent as a
        // ON d.id_adherent = a.id
        // LEFT JOIN reglement as r
        // on d.id_reglement = r.id
        // WHERE a.id IN ($ids)
        // ORDER BY a.nom");
        // $result = $requete->execute();
        // $donatorSelect = $requete->fetchAll(PDO::FETCH_ASSOC);
        // return $donatorSelect;
        $requete = $this->connexion->prepare("SELECT a.id as id_donateur, numDon, a.prenom, a.nom, a.adresse, a.CP, a.ville,a.email, a.telephone, montant_don, date_don, r.mode_reglement, d.id as id_don
        FROM don as d 
        LEFT JOIN adherent as a
        ON d.id_adherent = a.id
        LEFT JOIN reglement as r
        on d.id_reglement = r.id
        WHERE d.id IN ($ids)
        ORDER BY a.nom");
        $result = $requete->execute();
        $donatorSelect = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $donatorSelect;
    }

    /**
     * Fonction ajout de donnée dans la table don en générant un numéro aléatoire après vérification de sa présence en table
     *
     * @return void
     */
    public function addDB()
    {
        $dateDon = date("dmY", strtotime($_POST['dateDons']));
        $numDon = 'D-'.$dateDon.mt_rand(100, 999);
        $date = $_POST['dateDons'];
        $amount = $_POST['montant'];
        $adherent = $_POST['sel_MemberNameForDonation'];
        $regulation = $_POST['reglement'];
        $recuFiscal = 1;
        $firstRequete = "SELECT COUNT(*) AS existe_num
        FROM don
        WHERE numDon = '$numDon'";
        $result = $this->connexion->query($firstRequete);
        $firstData = $result->fetch(PDO::FETCH_ASSOC);
        if (is_numeric($amount)) {
            if ($firstData['existe_num'] == 1) {
                $numDon = 'D-'.$dateDon.mt_rand(100, 999);
                $requete = $this->connexion->prepare("INSERT INTO don
                VALUES (NULL, :numDon, :montant_don, :date_don, :id_adherent, :id_reglement, :id_recuFiscal)");
                $requete->bindParam(':numDon', $numDon);
                $requete->bindParam(':montant_don', $amount);
                $requete->bindParam(':date_don', $date);
                $requete->bindParam(':id_adherent', $adherent);
                $requete->bindParam(':id_reglement', $regulation);
                $requete->bindParam(':id_recuFiscal', $recuFiscal);
                $result = $requete->execute();
            } else {
                $requete = $this->connexion->prepare("INSERT INTO don
                VALUES (NULL, :numDon, :montant_don, :date_don, :id_adherent, :id_reglement, :id_recuFiscal)");
                $requete->bindParam(':numDon', $numDon);
                $requete->bindParam(':montant_don', $amount);
                $requete->bindParam(':date_don', $date);
                $requete->bindParam(':id_adherent', $adherent);
                $requete->bindParam(':id_reglement', $regulation);
                $requete->bindParam(':id_recuFiscal', $recuFiscal);
                $result = $requete->execute();
            }
        }
    }

    /**
     * Fonction suppression des données dans la table don
     *
     * @return void
     */
    public function suppDB()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM don
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }
    /**
     * Fonction modification d'une donnée de la table don
     *
     * @return void
     */
    public function getDon(){
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, d.id as id_don, d.id_reglement as id_reglement, rf.id as id_recufisc 
        FROM don as d
        LEFT JOIN adherent as a
        ON d.id_adherent = a.id
        LEFT JOIN reglement as r
        on d.id_reglement = r.id
        LEFT JOIN recufiscal as rf
        on d.id_recuFiscal = rf.id
        WHERE d.id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $don = $requete->fetch(PDO::FETCH_ASSOC);
        return $don;
    }

    public function updateDB()
    {
        $id = $_POST['id'];
        $amount = $_POST['montant'];
        $reglement = $_POST['reglement'];

        $requete = $this->connexion->prepare("UPDATE don
        SET montant_don = :montant_don, id_reglement = :id_reglement
        WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':montant_don', $amount);
        $requete->bindParam(':id_reglement', $reglement);
        $result = $requete->execute();
    }

        /**
     * Fonction recherche autocomplete en ajax dans la table adherent filtre par nom pour don
     *
     * @param [type] $requeteBoard
     * @return void
     */
    public function ajaxFileMemberNameForDonation($requeteMemberName)
    {
        // Number of records fetch
        $numberofrecords = 20;

        $requeteMemberNames = '%' . $requeteMemberName . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT *, a.id as id_adherent 
        FROM adherent as a WHERE nom like :nom
		ORDER BY a.nom LIMIT :limit");
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

    /**
     * Fonction création du PDF facture du don pour un donateur sélectionné
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $don
     * @return void
     */
    public function createPdfReceiptForDonator($associationDisplayForPdf,$don)
    {
        $dayToday = date("d");
        $monthToday = date("m");
        $yearToday = date("Y");
        $jourCotisation = date("d", strtotime($don['date_don']));
        $moisCotisation = date("m", strtotime($don['date_don']));
        $anneeCotisation = date("Y", strtotime($don['date_don']));
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
        $pdf->Text(120,73,utf8_decode($don['prenom'].' '.$don['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($don['adresse']));
        $pdf->Text(120,83,utf8_decode($don['CP']).' '.utf8_decode($don['ville']));
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
        $pdf->Cell(120,8,utf8_decode('Don '.$don['numDon']),1,0,'L',1);
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('1'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode($don['montant_don'].' Euros'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste Réglement
        $position_endDetail = 137; // Position à 16mm de l'entête (121+16)
        $pdf->SetY($position_endDetail);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',11);
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.$don['montant_don'].' Euros*** de la part de '.$don['prenom'].' '.$don['nom'].' en règlement de son don '.$don['numDon'].' en date du '.$jourCotisation.' '.$numMois.' '.$anneeCotisation.'.'),0,'L',0);
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
        $pdf->Cell(50,8,utf8_decode($don['mode_reglement']),0,0,'L',0);
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
        // Va forcer le navigateur à enregistrer le pdf dans télépchargement
        $pdf->Output($nomPdf, 'D');
    }

    /**
     * Fonction update l'id-recuFiscal si ce dernier est généré
     *
     * @param [type] $don
     * @return void
     */
    public function updateDonatorForTaxReceipt($don){
        $id = $don['id_don'];
        $id_recuFiscal = 2;

        $requete = $this->connexion->prepare("UPDATE don
        SET id_recuFiscal = :id_recuFiscal
        WHERE id = :id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':id_recuFiscal', $id_recuFiscal);
        $result = $requete->execute();
    }

    /**
     * Fonction génération du pdf reçu fiscal pour un donateur donné
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $don
     * @return void
     */
    public function createPdfTaxReceiptForDonator($associationDisplayForPdf,$don){
        $dayToday = date("d");
        $monthToday = date("m");
        $yearToday = date("Y");
        $jourCotisation = date("d", strtotime($don['date_don']));
        $moisCotisation = date("m", strtotime($don['date_don']));
        $anneeCotisation = date("Y", strtotime($don['date_don']));
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
        $pdf->Cell(80,10,utf8_decode('Reçu au titre des dons'),1,0,'C');
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
        $pdf->Text(120,43,utf8_decode('Reçu pour un don'));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,48,utf8_decode('Don N° : '.$don['numDon']));
        $pdf->Text(120,53,utf8_decode('Date : '.date("d-m-Y")));
        // Infos du client calées à droite
        $pdf->Text(120,63,utf8_decode('A l\'attention de :'));
        $pdf->SetFont('Arial','B',11);
        $pdf->Text(120,73,utf8_decode($don['prenom'].' '.$don['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($don['adresse']));
        $pdf->Text(120,83,utf8_decode($don['CP']).' '.utf8_decode($don['ville']));
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
        $pdf->Cell(50,6,utf8_decode($associationDisplayForPdf['nom']),0,0,'L',0);
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
        $pdf->Cell(50,6,utf8_decode($don['prenom'].' '.$don['nom']),0,0,'L',0);
        $pdf->SetY(159);
        $pdf->SetX(16);
        $pdf->SetFont('Arial','B',10);
        $pdf->Cell(50,6,utf8_decode('Adresse :'),0,0,'L',0);
        $pdf->SetFont('Arial','',10);
        $pdf->SetX(58);
        $pdf->Cell(50,6,utf8_decode($don['adresse']),0,0,'L',0);
        $pdf->SetY(165);
        $pdf->SetX(58);
        $pdf->Cell(50,6,utf8_decode($don['CP'].' '.$don['ville']),0,0,'L',0);
        $pdf->Ln(); // Retour à la ligne
        $pdf->Line(8,173,210-8,173);
        $pdf->SetY(176);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',10);
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.number_format($don['montant_don'], 2, ',', '').' Euros*** de la part de '.$don['prenom'].' '.$don['nom'].' en règlement de son don '.$don['numDon'].' en date du '.$jourCotisation.' '.$numMois.' '.$anneeCotisation.'.'),0,'L',0);
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
        $pdf->Cell(50,6,utf8_decode($don['mode_reglement']),0,0,'L',0);
        // Signature
        $position_sign = 224; // Position à 16mm de l'entête (160+16)
        $pdf->SetY($position_sign);
        $pdf->SetX(116);
        $pdf->SetFont('Arial','',10);
        $pdf->Cell(70,6,utf8_decode('Fait à '.$associationDisplayForPdf['footerdoc'].' le '.date("d-m-Y")),0,0,'R',0);
        $pdf->SetY(228);
        $pdf->SetX(116);
        $pdf->Cell(70,6,utf8_decode($associationDisplayForPdf['prenomSign'].' '.$associationDisplayForPdf['nomSign']),0,0,'R',0);
        $pdf->SetY(232);
        $pdf->SetX(116);
        $pdf->Cell(70,6,utf8_decode($associationDisplayForPdf['fonctionSign']),0,0,'R',0);
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
        $recuF = utf8_decode('Reçu Fiscal');
        $nomPdf = ''.$recuF.'-'.$don['numDon'].'.pdf';
        // Va forcer le navigateur à enregistrer le pdf dans télépchargement
        $pdf->Output($nomPdf, 'D');
        $pdf->Close();
    }

    /**
     * Fonction qui modifier le statut du reçu fiscal dans la table don lors de l'envoi massif
     *
     * @return void
     */
    public function updateDonatorForMassTaxReceipt(){
        $destinataire = [];
        foreach($_POST["destinataire"] as $dataUserSelected){
            $dataUserSelected = explode("|", $dataUserSelected);
            array_push($destinataire, $dataUserSelected);
        }
        $id_recuFiscal = 2;
        $idSelected = [];
        foreach($destinataire as $dest) {
            array_push($idSelected, $dest[0]);
        }
        $id_recuFiscal = 2;
        $ids = join(",",$idSelected);
        $requete = $this->connexion->prepare("UPDATE don
        SET id_recuFiscal = :id_recuFiscal
        WHERE id IN ($ids)");
        $requete->bindParam(':id_recuFiscal', $id_recuFiscal);
        $result = $requete->execute();
    }
}