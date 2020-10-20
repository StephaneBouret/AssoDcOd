<?php

class AdherentModel extends Model
{
    /**
     * Fonction export de la table simplifiée adhérent vers csv
     *
     * @param [type] $listSimplifiedDonations
     * @param string $filename
     * @param [type] $delimiter
     * @return void
     */
    function export_data_to_csv($listSimplifiedMembers,$filename='ListeAdhérents',$delimiter = ';',$enclosure = '"'){
        // Tells to the browser that a file is returned, with its name : $filename.csv
        header("Content-disposition: attachment; filename=$filename.csv");
        // Tells to the browser that the content is a csv file
        header("Content-Type: text/csv");
    
        // I open PHP memory as a file
        $fp = fopen("php://output", 'w');
    
        // Insert the UTF-8 BOM in the file
        fputs($fp, $bom=(chr(0xEF) . chr(0xBB) . chr(0xBF)));
    
        // I add the array keys as CSV headers
        fputcsv($fp,array_keys($listSimplifiedMembers[0]),$delimiter,$enclosure);
    
        // Add all the data in the file
        foreach ($listSimplifiedMembers as $simplifiedMembers) {
            fputcsv($fp, $simplifiedMembers,$delimiter,$enclosure);
        }
    
        // Close the file
        fclose($fp);
    
        // Stop the script
        die();
    }

    /**
     * Fonction affichage des anciens adhérents
     *
     * @return void
     */
    public function getOldAdherents()
    {
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
        LEFT JOIN qualite as q 
        ON a.id_qualite = q.id
        WHERE a.id_qualite = 1
        AND date_sortie IS NOT NULL
        ORDER BY a.nom";
        $result = $this->connexion->query($requete);
        $listOldAdherents = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listOldAdherents;
    }

    /**
     * Fonction affichage de la BDD adherent pour export en CSV
     *
     * @return void
     */
    public function getSimplifiedMembers()
    {
        $requete = "SELECT a.id as id_adherent, a.prenom, a.nom, a.email, a.telephone, a.date_entree, a.date_sortie, s.statut, f.fonction, c.montant_cotisation, r.mode_reglement
        FROM adherent as a
        LEFT JOIN statut as s
        ON a.id_statut = s.id
        LEFT JOIN fonction as f	
        ON a.id_fonction = f.id
        LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r 
        ON a.id_reglement = r.id
        ORDER BY a.id";
        $result = $this->connexion->query($requete);
        $listSimplifiedMembers = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listSimplifiedMembers;
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
        $requete = $this->connexion->prepare("SELECT a.id as id_member, a.prenom, a.nom, a.adresse, a.CP, a.ville, a.email, a.telephone, s.statut, c.montant_cotisation, r.mode_reglement
        FROM adherent as a 
        LEFT JOIN statut as s
        ON a.id_statut = s.id
		LEFT JOIN cotisation as c 
        ON a.id_cotisation = c.id
        LEFT JOIN reglement as r 
        ON a.id_reglement = r.id
        WHERE a.id IN ($ids)
        ORDER BY a.nom");
        $result = $requete->execute();
        $memberSelect = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $memberSelect;
    }

    /**
     * Fonction ajout de donnée dans la BDD adhérents
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
        $fonction = $_POST['fonction'];
        // // // if (empty($fonction)) {
        // // //     $fonction = NULL;
        // // // }
        $statut = $_POST['statut'];
        // // // if (empty($statut)) {
        // // //     $statut = NULL;
        // // // }
        $dateEntree = $_POST['dateEntree'];
        $today = date("Y-m-d");
        if (empty($dateEntree)) {
            $dateEntree = $today;
        }
        $dateSortie = $_POST['dateSortie'];
        if (empty($dateSortie)) {
            $dateSortie = NULL;
        }
        $cotisation = $_POST['cotisation'];
        // // if (empty($cotisation)) {
        // //     $cotisation = NULL;
        // // }

        $reglement = $_POST['reglement'];
        // // if (empty($reglement)) {
        // //     $reglement = NULL;
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
            VALUES (NULL, :prenom, :nom, :sexe, :adresse, :CP, :ville, :telephone, :email, :degree, :avatar, NULL, :date_entree, :date_sortie, :date_renouvellement, NULL, NULL, NULL, NULL, NULL, NULL, :id_jobs, :id_statut, :id_fonction, :id_cotisation, :id_reglement, '2', NULL, '1')");
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
            $requete->bindParam(':date_entree', $dateEntree);
            $requete->bindParam(':date_sortie', $dateSortie);
            $requete->bindParam(':date_renouvellement', $dateEntree);
            $requete->bindParam(':id_jobs', $job);
            $requete->bindParam(':id_statut', $statut);
            $requete->bindParam(':id_fonction', $fonction);
            $requete->bindParam(':id_cotisation', $cotisation);
            $requete->bindParam(':id_reglement', $reglement);
            $result = $requete->execute();
            // var_dump($result);
            // var_dump($requete->errorInfo());
        }
    }

    /**
     * Fonction suppression des données dans la BDD adhérents
     *
     * @return void
     */
    public function suppDB()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM adherent
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    /**
     * Fonction radiation de l'adhérent sélectionné
     *
     * @return void
     */
    public function cancelledMember()
    {
        // insert l'info
        $id = $_GET['id'];
        $dateSortie = $_POST['dateSortieCancelled'];

        $requete = $this->connexion->prepare("UPDATE adherent SET date_sortie=:date_sortie,id_statut='6' WHERE id=:id");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':date_sortie', $dateSortie);
        $result = $requete->execute();
    }

    /**
     * Fonction modification de la donnée sélectionnée dans la BDD adhérents
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
        $fonction = $_POST['fonction'];
        $statut = $_POST['statut'];
        $dateEntree = $_POST['dateEntree'];
        $dateSortie = $_POST['dateSortie'];
        if (empty($dateSortie)) {
            $dateSortie = NULL;
        }
        $dateRenouvellement = $_POST['dateRenewal'];
        if (empty($dateRenouvellement)) {
            $dateRenouvellement = $dateEntree;
        }
        $cotisation = $_POST['cotisation'];
        $reglement = $_POST['reglement'];

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

            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,date_entree=:date_entree,date_sortie=:date_sortie,date_renouvellement=:date_renouvellement,id_jobs=:id_jobs,id_statut=:id_statut,id_fonction=:id_fonction,id_cotisation=:id_cotisation,id_reglement=:id_reglement WHERE id=:id");
            $requete->bindParam(':avatar', $photo);
        } else if (isset($_POST['icon']) && !empty($_POST['icon'])) {
            $avatar = $_POST['icon'];
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,date_entree=:date_entree,date_sortie=:date_sortie,date_renouvellement=:date_renouvellement,id_jobs=:id_jobs,id_statut=:id_statut,id_fonction=:id_fonction,id_cotisation=:id_cotisation,id_reglement=:id_reglement WHERE id=:id");
            $requete->bindParam(':avatar', $avatar);
        } else {
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,date_entree=:date_entree,date_sortie=:date_sortie,date_renouvellement=:date_renouvellement,id_jobs=:id_jobs,id_statut=:id_statut,id_fonction=:id_fonction,id_cotisation=:id_cotisation,id_reglement=:id_reglement WHERE id=:id");
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
        $requete->bindParam(':date_entree', $dateEntree);
        $requete->bindParam(':date_sortie', $dateSortie);
        $requete->bindParam(':date_renouvellement', $dateRenouvellement);
        $requete->bindParam(':id_jobs', $job);
        $requete->bindParam(':id_statut', $statut);
        $requete->bindParam(':id_fonction', $fonction);
        $requete->bindParam(':id_cotisation', $cotisation);
        $requete->bindParam(':id_reglement', $reglement);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    public function updateBG()
    {
        $id = $_GET['id'];
        $maxsize = 2097152;
        $acceptable = [
            'image/tif',
            'image/jpeg',
            'image/jpg',
            'image/gif',
            'image/png'];
        $backgroundMember = "img/cover-00.jpg";

        if (isset($_FILES['backgroundMember']) && !empty($_FILES['backgroundMember']) && (in_array($_FILES['backgroundMember']['type'], $acceptable)) 
        && ($_FILES['backgroundMember']['size'] < $maxsize) && ($_FILES['backgroundMember']['size'] != 0)) {
            $emplacement_temporaire = $_FILES['backgroundMember']['tmp_name'];
            $nom_fichier = $_FILES['backgroundMember']['name'];
            $emplacement_destination = 'img/' . $nom_fichier;

            $resultat = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($resultat) {
                $backgroundMember = 'img/' . $nom_fichier;
            }

            $requete = $this->connexion->prepare("UPDATE adherent SET background=:background WHERE id=:id");
            $requete->bindParam(':background', $backgroundMember);
        } else if (isset($_POST['icon']) && !empty($_POST['icon'])) {
            $backgroundMember = $_POST['icon'];
            $requete = $this->connexion->prepare("UPDATE adherent SET background=:background WHERE id=:id");
            $requete->bindParam(':background', $backgroundMember);
        } else {
            $backgroundMember = "img/cover-00.jpg";
            $requete = $this->connexion->prepare("UPDATE adherent SET background=:background WHERE id=:id");
            $requete->bindParam(':background', $backgroundMember);
        }
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction modification de la donnée sélectionnée dans la BDD adhérents - cas du membre connecté
     *
     * @return void
     */
    public function updateDBOnlyMember()
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

            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,id_jobs=:id_jobs WHERE id=:id");
            $requete->bindParam(':avatar', $photo);
        } else if (isset($_POST['icon']) && !empty($_POST['icon'])) {
            $avatar = $_POST['icon'];
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,avatar=:avatar,id_jobs=:id_jobs WHERE id=:id");
            $requete->bindParam(':avatar', $avatar);
        } else {
            $requete = $this->connexion->prepare("UPDATE adherent SET prenom=:prenom,nom=:nom,sexe=:sexe,adresse=:adresse,CP=:CP,ville=:ville,telephone=:telephone,email=:email,degree=:degree,id_jobs=:id_jobs WHERE id=:id");
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
        $result = $requete->execute();
        // var_dump($result);
        // var_dump($requete->errorInfo());
    }

    /**
     * Fonction recherche autocomplete en ajax dans la table jobs
     *
     * @param [type] $requeteJobs
     * @return void
     */
    public function ajaxFileJobs($requeteJobs)
    {
        // Number of records fetch
        $numberofrecords = 20;

        $requeteJob = '%' . $requeteJobs . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT :limit");
        // $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT 20");
        $requete->bindParam(':name', $requeteJob, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $jobsList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];

        // Read Data
        foreach ($jobsList as $jobs) {
            $response[] = [
                "id" => $jobs['id'],
                "text" => $jobs['name']
            ];
        }
        return $response;
    }

    /**
     * Fonction recherche autocomplete dans la table adherent
     *
     * @param [type] $requeteMembers
     * @return void
     */
    public function ajaxFileMembers($requeteMembers)
    {
        // Number of records fetch) 
        $numberofrecords = 20;

        $requeteMember = '%' . $requeteMembers . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT * FROM adherent WHERE nom like :nom 
        AND ((date_sortie IS NULL) OR (date_sortie IS NOT NULL AND id_groupeAdherent IS NOT NULL)) ORDER BY nom LIMIT :limit");
        // $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT 20");
        $requete->bindParam(':nom', $requeteMember, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $MembersList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];
        // Read Data
        foreach ($MembersList as $members) {
            $response[] = [
                "id" => $members['id'],
                "text" => $members['nom'].' '.$members['prenom']
            ];
        }
        return $response;
    }

    /**
     * Fonction selection dans la table adherent d'un item en fonction de son id
     *
     * @param [type] $detailMemberAsso
     * @return void
     */
    public function getOnlyMember($detailMemberAsso)
    {
        $id = $detailMemberAsso;
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
        return $adherent;
    }

    /**
     * Fonction création du PDF facture de l'adhésion pour un adhérent sélectionné
     *
     * @param [type] $associationDisplayForPdf
     * @param [type] $adherent
     * @return void
     */
    public function createPdfReceiptForMember($associationDisplayForPdf,$adherent)
    {
        $dayToday = date("d");
        $monthToday = date("m");
        $yearToday = date("Y");
        $jourCotisation = date("d", strtotime($adherent['date_renouvellement']));
        $moisCotisation = date("m", strtotime($adherent['date_renouvellement']));
        $anneeCotisation = date("Y", strtotime($adherent['date_renouvellement']));
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
        $pdf->Text(120,73,utf8_decode($adherent['prenom'].' '.$adherent['nom']));
        $pdf->SetFont('Arial','',11);
        $pdf->Text(120,78,utf8_decode($adherent['adresse']));
        $pdf->Text(120,83,utf8_decode($adherent['CP']).' '.utf8_decode($adherent['ville']));
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
        $pdf->Cell(120,8,utf8_decode($adherent['libelle']),1,0,'L',1);
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
        $pdf->MultiCell(194,6,utf8_decode('L\'association '.$associationDisplayForPdf['nom'].' reconnait avoir reçu la somme de ***'.number_format($adherent['montant_cotisation'], 2, ',', '').' Euros*** de la part de '.$adherent['prenom'].' '.$adherent['nom'].' en règlement de sa cotisation '.$adherent['libelle'].' en date du '.$jourCotisation.' '.$numMois.' '.$anneeCotisation.'.'),0,'L',0);
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
        $recuC = utf8_decode('Facture Adhésion');
        $nomPdf = ''.$recuC.'-'.$code1.'-'.$jourCotisation.$moisCotisation.$anneeCotisation.'-'.$range[0].'.pdf';
        // Va forcer le navigateur à enregistrer le pdf dans télépchargement
        $pdf->Output($nomPdf, 'D');
    }

    public function sendMailAfterAdding($checkNotification)
    {
        echo 'Le mail dénommé '. $checkNotification.' est prêt à être envoyé';
    }
}
