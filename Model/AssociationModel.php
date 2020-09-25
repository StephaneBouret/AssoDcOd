<?php
require('fpdf/fpdf.php');

class AssociationModel extends Model
{
    /**
     * Fonction calcul nombre d'association dans la table association
     *
     * @return void
     */
    public function IsAssociationPresent()
    {
        $initRequete = "SELECT COUNT(a.id) as nombre_association
        FROM association as a";
        $result = $this->connexion->query($initRequete);
        $initData = $result->fetch(PDO::FETCH_ASSOC);
        return $initData;
    }

    /**
     * Fonction ajout de données dans la table association
     *
     * @return void
     */
    public function addDB()
    {
        // insert l'info
        $maxsize = 2097152;
        $acceptable = [
            'image/tif',
            'image/jpeg',
            'image/pg',
            'image/gif',
            'image/png'];
        $nomAsso = $_POST['AssoName'];
        $email = $_POST['email'];
        $tel = $_POST['tel'];
        $adresse = $_POST['adresse'];
        $codepostal = $_POST['codepostal'];
        $ville = $_POST['ville'];

        $logo = "img\undefined.jpg";

        if (isset($_FILES['photo']) && !empty($_FILES['photo']) && ($_FILES['photo']['size'] < $maxsize) 
        && (in_array($_FILES['photo']['type'], $acceptable))) {
            $emplacement_temporaire = $_FILES['photo']['tmp_name'];
            $nom_fichier = $_FILES['photo']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $logo = 'img/' . $nom_fichier;
            }
        }
        $url_exp = '/(https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|www\.[a-zA-Z0-9][a-zA-Z0-9-]+[a-zA-Z0-9]\.[^\s]{2,}|https?:\/\/(?:www\.|(?!www))[a-zA-Z0-9]+\.[^\s]{2,}|www\.[a-zA-Z0-9]+\.[^\s]{2,})/gi';
        $phone_expr = '/^(0|\+33)[1-9]([-. ]?[0-9]{2}){4}$/';
        
        if (!empty($nomAsso) && !empty($tel) && preg_match($phone_expr, $tel) && !empty($email) && filter_var($email, FILTER_VALIDATE_EMAIL)) {
            $tel = "+33".substr($tel, 0);
            $requete = $this->connexion->prepare("INSERT INTO association
            VALUES (NULL, :nom, :adresse, :CP, :ville, :telephone, :email, '', '', :logo, '', '', '', '', '', '', '', '', '', '', '', '7')");
            $requete->bindParam(':nom', $nomAsso);
            $requete->bindParam(':adresse', $adresse);
            $requete->bindParam(':CP', $codepostal);
            $requete->bindParam(':ville', $ville);
            $requete->bindParam(':telephone', $tel);
            $requete->bindParam(':email', $email);
            $requete->bindParam(':logo',$logo);
            $result = $requete->execute();
            // var_dump($result);
            // var_dump($requete->errorInfo());
        }
    }

        /**
     * Fonction modification de l'association
     *
     * @return void
     */
    public function updateDB()
    {
        $maxsize = 2097152;
        $acceptable = [
            'image/tif',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'];
        function valid_donnees($donnees) {
            $donnees = trim($donnees);
            $donnees = stripslashes($donnees);
            $donnees = htmlspecialchars($donnees);
            return $donnees;
        }
        $id=1;
        $nomAsso = valid_donnees($_POST['AssoName']);
        $email = filter_var($_POST['email'], FILTER_VALIDATE_EMAIL);
        $tel = $_POST['tel'];
        if(stristr($tel, '+33')) {
            $tel = substr_replace($tel, '', 0, 3);
            $tel = preg_replace('/\s\s+/', ' ', $tel);
        }
        $adresse = valid_donnees($_POST['adresse']);
        $codepostal = valid_donnees($_POST['codepostal']);
        $ville = valid_donnees($_POST['ville']);
        $netwLinkedin = valid_donnees($_POST['linkedin']);
        $netwTwitter = valid_donnees($_POST['twitter']);
        $netwFacebook = valid_donnees($_POST['facebook']);
        $netwSite = valid_donnees($_POST['internetSite']);

        if (isset($netwLinkedin) && !empty($netwLinkedin)) {
            $netwLinkedin = filter_var($netwLinkedin, FILTER_VALIDATE_URL);
        }
        if (isset($netwTwitter) && !empty($netwTwitter)) {
            $netwTwitter = filter_var($netwTwitter, FILTER_VALIDATE_URL);
        }
        if (isset($netwFacebook) && !empty($netwFacebook)) {
            $netwFacebook = filter_var($netwFacebook, FILTER_VALIDATE_URL);
        }
        if (isset($netwSite) && !empty($netwSite)) {
            $netwSite = filter_var($netwSite, FILTER_VALIDATE_URL);
        }

        $logo = "img\undefined.jpg";

        if (isset($_FILES['photo']) && !empty($_FILES['photo']) && ($_FILES['photo']['size'] < $maxsize) 
        && (in_array($_FILES['photo']['type'], $acceptable)) && ($_FILES['photo']['size'] != 0)) {
            $emplacement_temporaire = $_FILES['photo']['tmp_name'];
            $nom_fichier = $_FILES['photo']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;

            $resultat = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($resultat) {
                $logo = 'img/' . $nom_fichier;
            }
            $tel = "+33".substr($tel, 0);
            $requete = $this->connexion->prepare("UPDATE association SET nom=:nom,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,logo=:logo,linkedin=:linkedin,twitter=:twitter,facebook=:facebook,site=:site WHERE id=:id");
            $requete->bindParam(':logo', $logo);
        } else {
            $tel = "+33".substr($tel, 0);
            $requete = $this->connexion->prepare("UPDATE association SET nom=:nom,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,linkedin=:linkedin,twitter=:twitter,facebook=:facebook,site=:site WHERE id=:id");
        }
        $requete->bindParam(':id', $id);
        $requete->bindParam(':nom', $nomAsso);
        $requete->bindParam(':adresse', $adresse);
        $requete->bindParam(':CP', $codepostal);
        $requete->bindParam(':ville', $ville);
        $requete->bindParam(':telephone', $tel);
        $requete->bindParam(':email', $email);
        $requete->bindParam(':linkedin', $netwLinkedin);
        $requete->bindParam(':twitter', $netwTwitter);
        $requete->bindParam(':facebook', $netwFacebook);
        $requete->bindParam(':site', $netwSite);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction modification des données documents dans la table association
     *
     * @return void
     */
    public function updateDBParamsFile()
    {
        $boardSelectedWithoutHyphen = str_replace(' -','',$_POST['boardReal']);
        $boardSelected = explode(' ',$boardSelectedWithoutHyphen);
        $stringBeforeCompare = $_POST['boardReal'];
        if (isset($_POST['sel_boardMember'])) {
            $sel_boardMember = $_POST['sel_boardMember'];
        } elseif (!isset($_POST['sel_boardMember']) && $boardSelected[2] === 'Président') {
            $sel_boardMember = 1;
        } elseif (!isset($_POST['sel_boardMember']) && $boardSelected[2] === 'Secrétaire Général') {
            $sel_boardMember = 3;
        } else {
            $sel_boardMember = 2;
        }
        $id=1;
        $siret = $_POST['siret'];
        $rna = $_POST['rna'];
        $objetsocial = $_POST['objetsocial'];
        $footerdoc = $_POST['footerdoc'];
        $boardMemberFirstname = "";
        if (isset($_POST['boardMemberFirstname']) && !empty($_POST['boardMemberFirstname'])) {
            $boardMemberFirstname = $_POST['boardMemberFirstname'];
        } else {
            $boardMemberFirstname = $boardSelected[1];
        }
        $boardMemberName = "";
        if (isset($_POST['boardMemberName']) && !empty($_POST['boardMemberName'])) {
            $boardMemberName = $_POST['boardMemberName'];
        } else {
            $boardMemberName = $boardSelected[0];
        }
        $boardMemberFonction = "";
        if (isset($_POST['boardMemberFonction']) && !empty($_POST['boardMemberFonction'])) {
            $boardMemberFonction = $_POST['boardMemberFonction'];
        } else {
            $boardMemberFonction = $boardSelected[2];
        }
        // $sel_boardMember = $_POST['sel_boardMember'];
        $sel_juridical = $_POST['sel_juridical'];

        function isValidSiret($siret) {
            $siret = str_replace ( ' ', '', $siret );
            if (strlen($siret) != 14 || !is_numeric ($siret)) {
                return false;
            }
            $sum = 0;
            for ($index = 0; $index < 14; $index ++)
            {
                $number = (int) $siret[$index];
                if (($index % 2) == 0) { if (($number *= 2) > 9) $number -= 9; }
                $sum += $number;
            }
            if (($sum % 10) != 0) return false; else return true;
            return $siret;
        }

        $rna_expr = '/^W[0-9]{9}$/';
        $signature = "";
        if (isset($_FILES['photo']) && !empty($_FILES['photo']) && ($_FILES['photo']['size'] > 0) && (preg_match($rna_expr, $rna)) && (isValidSiret($siret) == true)) {
            $emplacement_temporaire = $_FILES['photo']['tmp_name'];
            $nom_fichier = $_FILES['photo']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;
            $resultat = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($resultat) {
                $signature = 'img/' . $nom_fichier;
            }
            $requete = $this->connexion->prepare("UPDATE association SET siret=:siret,rna=:rna,objetsocial=:objetsocial,footerdoc=:footerdoc,prenomSign=:prenomSign,nomSign=:nomSign,fonctionSign=:fonctionSign,signature=:signature,sel_boardMember=:sel_boardMember,id_jurid=:id_jurid WHERE id=:id");
            $requete->bindParam(':signature', $signature);
        } else {
            $requete = $this->connexion->prepare("UPDATE association SET siret=:siret,rna=:rna,objetsocial=:objetsocial,footerdoc=:footerdoc,prenomSign=:prenomSign,nomSign=:nomSign,fonctionSign=:fonctionSign,sel_boardMember=:sel_boardMember,id_jurid=:id_jurid WHERE id=:id");
        }
        $requete->bindParam(':id', $id);
        $requete->bindParam(':siret', $siret);
        $requete->bindParam(':rna', $rna);
        $requete->bindParam(':objetsocial', $objetsocial);
        $requete->bindParam(':footerdoc', $footerdoc);
        $requete->bindParam(':prenomSign', $boardMemberFirstname);
        $requete->bindParam(':nomSign', $boardMemberName);
        $requete->bindParam(':fonctionSign', $boardMemberFonction);
        $requete->bindParam(':sel_boardMember', $sel_boardMember);
        $requete->bindParam(':id_jurid', $sel_juridical);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction vider la table association
     *
     * @return void
     */
    public function clearBdd()
    {
        $request = $this->connexion->prepare("TRUNCATE TABLE association");
        $result = $request->execute();
    }

        /**
     * Fonction recherche autocomplete en ajax dans la table forme_juridique
     *
     * @param [type] $requeteStatus
     * @return void
     */
    public function ajaxFileStatus($requeteStatus)
    {
        // Number of records fetch
        $numberofrecords = 20;

        $requeteStatus = '%' . $requeteStatus . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT * FROM forme_juridique WHERE libelle like :libelle ORDER BY libelle LIMIT :limit");
        // $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT 20");
        $requete->bindParam(':libelle', $requeteStatus, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $statusList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];

        // Read Data
        foreach ($statusList as $status) {
            $response[] = [
                "id" => $status['id'],
                "text" => $status['libelle'].' - '.$status['code']
            ];
        }
        return $response;
    }

    /**
     * Fonction recherche autocomplete en ajax dans la table adherent filtre par fonction
     *
     * @param [type] $requeteBoard
     * @return void
     */
    public function ajaxFileBoard($requeteBoard)
    {
        // Number of records fetch
        $numberofrecords = 20;

        $requeteBoard = '%' . $requeteBoard . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT *, a.id as id_adherent 
        FROM adherent as a 
        LEFT JOIN fonction as f 
        ON a.id_fonction = f.id
        WHERE fonction like :fonction ORDER BY a.nom LIMIT :limit");
        // $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT 20");
        $requete->bindParam(':fonction', $requeteBoard, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $BoardList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];

        // Read Data
        foreach ($BoardList as $board) {
            $response[] = [
                "id" => $board['id'],
                "text" => $board['nom']." ".$board['prenom']." - ".$board['fonction'],
                "name" => $board['nom'],
                "firstname" => $board['prenom'],
                "function" => $board['fonction']
            ];
        }
        return $response;
    }

    public function createPdfExample($associationDisplayForPdf,$adherent)
    {
        $jourCotisation = date("d", strtotime($adherent['date_entree']));
        $moisCotisation = date("m", strtotime($adherent['date_entree']));
        $anneeCotisation = date("Y", strtotime($adherent['date_entree']));
        $numMois = date('m', $moisCotisation);
        switch ($numMois) {
            case '1':
                $mois = "Janvier";
                break;
            case '2':
                $mois = "Février";
                break;
            case '3':
                $mois = "Mars";
                break;
            case '4':
                $mois = "Avril";
                break;
            case '5':
                $mois = "Mai";
                break;
            case '6':
                $mois = "Juin";
                break;
            case '7':
                $mois = "Juillet";
                break;
            case '8':
                $mois = "Août";
                break;
            case '9':
                $mois = "Septembre";
                break;
            case '10':
                $mois = "Octobre";
                break;
            case '11':
                $mois = "Novembre";
                break;
            case '12':
                $mois = "Décembre";
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
        $pdf->Text(120,48,utf8_decode('Numéro de facture : '.$code1.'-'.$jourCotisation.$moisCotisation.$anneeCotisation.'-'.$range[0]));
        $pdf->Text(120,53,utf8_decode('Date : '.date("d-m-Y")));
        // Infos du client calées à droite
        $pdf->Text(120,63,utf8_decode('A l\'attention de :'));
        $pdf->SetFont('Arial','B',11);
        $pdf->Text(120,73,utf8_decode($adherent['prenom'].' '.$adherent['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($adherent['adresse']));
        $pdf->Text(120,83,utf8_decode($adherent['CP']).' '.$adherent['ville']);
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
        $pdf->Cell(120,8,utf8_decode('Adhésion '.$adherent['libelle']),1,0,'L',1);
        $pdf->SetX(128); // 8 + 120
        $pdf->Cell(28,8,utf8_decode('1'),1,0,'C',1);
        $pdf->SetX(156); // 128 + 28
        $pdf->Cell(46,8,utf8_decode($adherent['montant_cotisation'].' Euros'),1,0,'C',1);
        $pdf->Ln(); // Retour à la ligne
        // Liste Réglement
        $position_endDetail = 137; // Position à 16mm de l'entête (121+16)
        $pdf->SetY($position_endDetail);
        $pdf->SetX(8);
        $pdf->SetFont('Arial','',11);
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.$adherent['montant_cotisation'].' Euros*** de la part de '.$adherent['prenom'].' '.$adherent['nom'].'  en règlement de son adhésion '.$adherent['libelle'].' en date du '.$jourCotisation.' '.$mois.' '.$anneeCotisation.'.'),0,'L',0);
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
        $pdf->Cell(50,8,utf8_decode($adherent['mode_reglement']),0,0,'L',0);
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
        $nomPdf = 'Facture-'.$code1.'-'.$jourCotisation.$moisCotisation.$anneeCotisation.'-'.$range[0].'.pdf';
        $pdf->Output($nomPdf,'I');
    }
}
