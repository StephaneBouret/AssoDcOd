<?php

class DocumentModel extends Model {

    /**
     * Fonction affichage de la table dossierdoc
     *
     * @return void
     */
    public function getSimplifiedFileDoc()
    {
        $requete = "SELECT *, dd.id as id_dossier
        FROM dossierdoc as dd
        ORDER BY dd.id";
        $result = $this->connexion->query($requete);
        $listSimplifiedFiles = $result->fetchAll(PDO::FETCH_ASSOC);
        return $listSimplifiedFiles;
    }

    /**
     * Fonction affichage de la table dossierdoc ventilée par l'id du dossier sélectionné
     *
     * @return void
     */
    public function getSimplifiedFileDocById()
    {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, dd.id as id_dossier
        FROM dossierdoc as dd
        WHERE dd.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $listSimplifiedFile = $requete->fetch(PDO::FETCH_ASSOC);
        return $listSimplifiedFile;
    }

    /**
     * Fonction selection dans la table dossier d'un item en fonction de son id sélectionné par l'autocompletion
     *
     * @param [type] $$detailDocAsso
     * @return void
     */
    public function getOnlyFile($detailDocAsso)
    {
        $str = explode(" ", $detailDocAsso, 2);
        $id = $str[0];
        $requete = $this->connexion->prepare("SELECT *, dd.id as id_dossier
        FROM dossierdoc as dd
        WHERE dd.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $listSimplifiedFile = $requete->fetch(PDO::FETCH_ASSOC);
        return $listSimplifiedFile;
    }

    /**
     * Fonction affichage de la table document ventilée par dossier en fonction de l'id en autocompletion
     *
     * @param [type] $detailDocAsso
     * @return void
     */
    public function getDocFileBreakdownAutoComplete($detailDocAsso) {
        $str = explode(" ", $detailDocAsso, 2);
        $id = $str[0];
        $requeteDocument = '%' . $str[1] . '%';
        $requete = $this->connexion->prepare("SELECT *, do.id as id_document
        FROM document as do 
        LEFT JOIN dossierdoc as dd 
        ON do.id_dossierDoc = dd.id
        WHERE do.id_dossierDoc = :id AND nom LIKE :nom");
        $requete->bindParam(':id', $id);
        $requete->bindParam(':nom', $requeteDocument);
        $result = $requete->execute();
        $listDocFromFiles = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $listDocFromFiles;
    }

    /**
     * Fonction affichage de la table document ventilée par dossier en fonction de l'id
     *
     * @return void
     */
    public function getDocFileBreakdown() {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, do.id as id_document
        FROM document as do 
        LEFT JOIN dossierdoc as dd 
        ON do.id_dossierDoc = dd.id
        WHERE do.id_dossierDoc = :id
        ORDER BY do.dateCreation");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $listDocFromFiles = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $listDocFromFiles;
    }

    /**
     * fonction selection d'un document en fonction de l'id
     *
     * @return void
     */
    public function getOnlyDoc() {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, do.id as id_document
        FROM document as do 
        LEFT JOIN dossierdoc as dd 
        on do.id_dossierDoc = dd.id
        WHERE do.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $selectedDoc = $requete->fetch(PDO::FETCH_ASSOC);
        return $selectedDoc;
    }

    /**
     * Fonction affichage de la table document ventilée par dossier
     *
     * @return void
     */
    public function getAllFiles() {
        $requete = $this->connexion->prepare("SELECT *, do.id as id_document, dd.id as id_dossier
        FROM document as do 
        LEFT JOIN dossierdoc as dd 
        ON do.id_dossierDoc = dd.id
        ORDER BY do.id");
        $result = $requete->execute();
        $listAllFiles = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $listAllFiles;
    }

    /**
     * Fonction affichage de la table news
     *
     * @return void
     */
    public function getAllNews() {
        $requete = $this->connexion->prepare("SELECT *, n.id as id_news
        FROM news as n 
        ORDER BY n.dateCreation DESC LIMIT 3");
        $result = $requete->execute();
        $listAllNews = $requete->fetchAll(PDO::FETCH_ASSOC);
        return $listAllNews;
    }

    /**
     * fonction selection d'un document en fonction de l'id
     *
     * @return void
     */
    public function getOnlyNews() {
        $id = $_GET['id'];
        $requete = $this->connexion->prepare("SELECT *, n.id as id_news
        FROM news as n 
        WHERE n.id = :id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        $selectedNews = $requete->fetch(PDO::FETCH_ASSOC);
        return $selectedNews;
    }

    /**
     * Fonction insertion dans la table dossierdoc du nouveau dossier
     *
     * @return void
     */
    public function addFileInDataBase()
    {
        // insert l'info
        $nomDossierfirst = ucwords($_POST['newNameFile']);
        $nomDossierSecond = explode(" ", $nomDossierfirst);
        $partOneDossierSecond = substr($nomDossierSecond[0],0,1);
        $partTwoDossierSecond = substr($nomDossierSecond[1],0,1);
        $partThreeDossierSecond = substr($nomDossierSecond[2],0,1);
        $nomDossier = $partOneDossierSecond.$partTwoDossierSecond.$partThreeDossierSecond." - ".ucfirst($_POST['newNameFile']);
        $prenomAuteur = ucfirst($_POST['authorFirstName']);
        $nomAuteur = ucwords($_POST['authorName']);
        $fonctionAuteur = $_POST['authorFunction'];

        $requete = $this->connexion->prepare("INSERT INTO dossierdoc
        VALUES (NULL, :nomDossier, :prenomAuteur, :nomAuteur, :fonctionAuteur, '1')");
        $requete->bindParam(':nomDossier', $nomDossier);
        $requete->bindParam(':prenomAuteur', $prenomAuteur);
        $requete->bindParam(':nomAuteur', $nomAuteur);
        $requete->bindParam(':fonctionAuteur', $fonctionAuteur);
        $result = $requete->execute();
    }

    /**
     * Fonction update de la table dossierdoc en fonction de l'id sélectionné
     *
     * @return void
     */
    public function updateDBFile()
    {
        $id = $_POST['id'];
        $nomDossierfirst = ucwords($_POST['newNameFile']);
        $nomDossierSecond = explode(" ", $nomDossierfirst);
        $partOneDossierSecond = substr($nomDossierSecond[0],0,1);
        $partTwoDossierSecond = substr($nomDossierSecond[1],0,1);
        $partThreeDossierSecond = substr($nomDossierSecond[2],0,1);
        $nomDossier = $partOneDossierSecond.$partTwoDossierSecond.$partThreeDossierSecond." - ".ucfirst($_POST['newNameFile']);
        $prenomAuteur = ucfirst($_POST['authorFirstName']);
        $nomAuteur = ucwords($_POST['authorName']);
        $fonctionAuteur = $_POST['authorFunction'];

        $requete = $this->connexion->prepare("UPDATE dossierdoc SET nomDossier=:nomDossier,prenomAuteur=:prenomAuteur,nomAuteur=:nomAuteur,fonctionAuteur=:fonctionAuteur WHERE id=:id");

        $requete->bindParam(':id', $id);
        $requete->bindParam(':nomDossier', $nomDossier);
        $requete->bindParam(':prenomAuteur', $prenomAuteur);
        $requete->bindParam(':nomAuteur', $nomAuteur);
        $requete->bindParam(':fonctionAuteur', $fonctionAuteur);
        $result = $requete->execute();
    }

    /**
     * Fonction suppression des données dans la table dossierDoc en fonction de l'id sélectionné
     *
     * @return void
     */
    public function suppDBFile()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM dossierdoc
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
        if (!$result) {
            echo "Plop";
        }
    }

    // /**
    //  * fonction insertion dans la table document
    //  *
    //  * @return void
    //  */
    public function addDocInDataBase()
    {
        // insert l'info
        $maxsize = 2097152;
        $allowedExts = array("pdf", "doc", "docx");
        $allowedExtsHtml = array("html");
        $fileExplode = explode(".", $_FILES["fileDoc"]["name"]);
        $fileHtmlExplode = explode(".", $_FILES["fileFocus"]["name"]);
        $extension = strtolower(end($fileExplode));
        $extensionHtml = strtolower(end($fileHtmlExplode));
        $nomDossier = $_POST['nomDossier'];
        $nom = ucfirst($_POST['nameDoc']);
        $dateCreation = $_POST['dateDoc'];
        // limitation du textarea à 170 caractères (espace inclu)
        $description = substr($_POST['descriptionForDoc'], 0, 170);
        $typeDoc = $_POST['typeDoc'];
        $linkUrl = $_POST['linkUrl'];

        $fileDoc = "";

        if (($_FILES["fileDoc"]["type"] == "application/pdf") || ($_FILES["fileDoc"]["type"] == "application/msword") || 
        ($_FILES["fileDoc"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") && 
        ($_FILES["fileDoc"]["size"] < $maxsize) && in_array($extension, $allowedExts) && $typeDoc == "doc" && isset($_FILES['fileDoc']) 
        && !empty($_FILES['fileDoc'])) {
            $emplacement_temporaire = $_FILES['fileDoc']['tmp_name'];
            $nom_fichier = $_FILES['fileDoc']['name'];
            $emplacement_destination = 'doc/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $fileDoc = 'doc/' . $nom_fichier;
            }
        } elseif (($_FILES["fileFocus"]["type"] == "text/html") && $_FILES["fileFocus"]["size"] < $maxsize 
        && in_array($extensionHtml, $allowedExtsHtml) && $typeDoc == "focus" && isset($_FILES['fileFocus']) && !empty($_FILES['fileFocus'])) {
            $emplacement_temporaire = $_FILES['fileFocus']['tmp_name'];
            $nom_fichier = $_FILES['fileFocus']['name'];
            $emplacement_destination = 'html/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $fileDoc = 'html/' . $nom_fichier;
            }
        }
        else {
            $fileDoc = filter_var($linkUrl, FILTER_VALIDATE_URL);
        }

        $requete = $this->connexion->prepare("INSERT INTO document
        VALUES (NULL, :nom, :dateCreation, :description, :typeDoc, :cheminDocument, '1', :id_dossierDoc)");
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':dateCreation', $dateCreation);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':typeDoc', $typeDoc);
        $requete->bindParam(':cheminDocument', $fileDoc);
        $requete->bindParam(':id_dossierDoc', $nomDossier);
        $result = $requete->execute();
    }

    /**
     * Fonction suppression des données dans la table document en fonction de l'id sélectionné
     *
     * @return void
     */
    public function suppDBDoc()
    {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM document
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

    public function updateDBDoc() {
        var_dump($_POST);
        var_dump($_FILES);
        $maxsize = 2097152;
        $allowedExts = array("pdf", "doc", "docx");
        $fileExplode = explode(".", $_FILES["fileDoc"]["name"]);
        $extension = strtolower(end($fileExplode));
        $allowedExtsHtml = array("html");
        $fileHtmlExplode = explode(".", $_FILES["fileFocus"]["name"]);
        $extensionHtml = strtolower(end($fileHtmlExplode));
        $id = $_GET['id'];
        $nomDossier = $_POST['nomDossier'];
        $nom = ucfirst($_POST['nameDoc']);
        $dateCreation = $_POST['dateDoc'];
        // limitation du textarea à 170 caractères (espace inclu)
        $description = substr($_POST['descriptionForDoc'], 0, 170);
        $typeDoc = $_POST['typeDoc'];
        $linkUrl = $_POST['linkUrl'];

        $fileDoc = "";

        if (($_FILES["fileDoc"]["type"] == "application/pdf") || ($_FILES["fileDoc"]["type"] == "application/msword") || 
        ($_FILES["fileDoc"]["type"] == "application/vnd.openxmlformats-officedocument.wordprocessingml.document") && 
        ($_FILES["fileDoc"]["size"] < $maxsize) && in_array($extension, $allowedExts) && $typeDoc == "doc" && isset($_FILES['fileDoc']) 
        && !empty($_FILES['fileDoc'])) {
            $emplacement_temporaire = $_FILES['fileDoc']['tmp_name'];
            $nom_fichier = $_FILES['fileDoc']['name'];
            $emplacement_destination = 'doc/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $fileDoc = 'doc/' . $nom_fichier;
            }

            $requete = $this->connexion->prepare("UPDATE document SET nom=:nom,dateCreation=:dateCreation,description=:description,typeDoc=:typeDoc,cheminDocument=:cheminDocument,id_dossierDoc=:id_dossierDoc WHERE id=:id");
            $requete->bindParam(':cheminDocument', $fileDoc);
        } elseif (($_FILES["fileFocus"]["type"] == "text/html") && $_FILES["fileFocus"]["size"] < $maxsize 
        && in_array($extensionHtml, $allowedExtsHtml) && $typeDoc == "focus" && isset($_FILES['fileFocus']) && !empty($_FILES['fileFocus'])) {
            $emplacement_temporaire = $_FILES['fileFocus']['tmp_name'];
            $nom_fichier = $_FILES['fileFocus']['name'];
            $emplacement_destination = 'html/' . $nom_fichier;

            $result = move_uploaded_file($emplacement_temporaire, $emplacement_destination);
            if ($result) {
                $fileDoc = 'html/' . $nom_fichier;
            }

            $requete = $this->connexion->prepare("UPDATE document SET nom=:nom,dateCreation=:dateCreation,description=:description,typeDoc=:typeDoc,cheminDocument=:cheminDocument,id_dossierDoc=:id_dossierDoc WHERE id=:id");
            $requete->bindParam(':cheminDocument', $fileDoc);
        } else if ($typeDoc == "link" && isset($linkUrl) && !empty($linkUrl)) {
            $fileDoc = filter_var($linkUrl, FILTER_VALIDATE_URL);
            $requete = $this->connexion->prepare("UPDATE document SET nom=:nom,dateCreation=:dateCreation,description=:description,typeDoc=:typeDoc,cheminDocument=:cheminDocument,id_dossierDoc=:id_dossierDoc WHERE id=:id");
            $requete->bindParam(':cheminDocument', $fileDoc);
        } else {
            $requete = $this->connexion->prepare("UPDATE document SET nom=:nom,dateCreation=:dateCreation,description=:description,typeDoc=:typeDoc,id_dossierDoc=:id_dossierDoc WHERE id=:id");
        }

        $requete->bindParam(':id', $id);
        $requete->bindParam(':nom', $nom);
        $requete->bindParam(':dateCreation', $dateCreation);
        $requete->bindParam(':description', $description);
        $requete->bindParam(':typeDoc', $typeDoc);
        $requete->bindParam(':id_dossierDoc', $nomDossier);
        $result = $requete->execute();
    }

    /**
     * Fonction insertion dans la table news
     *
     * @return void
     */
    public function addDBNews() {
        $nameNews = ucfirst($_POST['nameNews']);
        $dateNews = $_POST['dateNews'];
        $descriptionForNews = $_POST['descriptionForNews'];
        $image = $_POST['imgNews'];

        $requete = $this->connexion->prepare("INSERT INTO news
        VALUES (NULL, :image, :titre, :contenu, :dateCreation)");
        $requete->bindParam(':image', $image);
        $requete->bindParam(':titre', $nameNews);
        $requete->bindParam(':contenu', $descriptionForNews);
        $requete->bindParam(':dateCreation', $dateNews);
        $result = $requete->execute();
    }

    /**
     * Fonction modification de l'item dans la table news
     *
     * @return void
     */
    public function updateDBNews() {
        $id = $_POST['id'];
        $image = $_POST['imgNews'];
        $titre = ucfirst($_POST['nameNews']);
        $contenu = $_POST['descriptionForNews'];
        $dateCreation = $_POST['dateNews'];

        $requete = $this->connexion->prepare("UPDATE news SET image=:image,titre=:titre,contenu=:contenu,dateCreation=:dateCreation WHERE id=:id");

        $requete->bindParam(':id', $id);
        $requete->bindParam(':image', $image);
        $requete->bindParam(':titre', $titre);
        $requete->bindParam(':contenu', $contenu);
        $requete->bindParam(':dateCreation', $dateCreation);
        $result = $requete->execute();
    }

    /**
     * Fonction suppression d'un élément dans la table news
     *
     * @return void
     */
    public function suppDBNews() {
        // insert l'info
        $id = $_GET['id'];

        $requete = $this->connexion->prepare("DELETE FROM news
        WHERE id=:id");
        $requete->bindParam(':id', $id);
        $result = $requete->execute();
    }

        /**
     * Fonction recherche autocomplete dans la table adherent
     *
     * @param [type] $requeteMembers
     * @return void
     */
    public function ajaxFileDocs($requeteDocs)
    {
        // Number of records fetch) 
        $numberofrecords = 20;

        $requeteDoc = '%' . $requeteDocs . '%';

        // Fetch records
        $requete = $this->connexion->prepare("SELECT * FROM document WHERE nom like :nom ORDER BY nom LIMIT :limit");
        // $requete = $this->connexion->prepare("SELECT * FROM jobs WHERE name like :name ORDER BY name LIMIT 20");
        $requete->bindParam(':nom', $requeteDoc, PDO::PARAM_STR);
        $requete->bindParam(':limit', $numberofrecords, PDO::PARAM_INT);
        $requete->execute();
        $DocsList = $requete->fetchAll(PDO::FETCH_ASSOC);
        $response = [];
        // Read Data
        foreach ($DocsList as $docs) {
            $response[] = [
                "id" => $docs['id_dossierDoc'].' '.$docs['nom'],
                "text" => $docs['nom']
            ];
        }
        return $response;
    }
}