<?php

class DocumentView extends View {

    /**
     * Fonction affichage des tables dossiers de document et news
     *
     * @param [type] $listAllNews
     * @param [type] $listSimplifiedFiles
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($listAllNews,$listSimplifiedFiles,$associationDisplay)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListFiles.html');
        $this->page = str_replace('{titreFormulaire}','Les documents de l\'association',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page = str_replace('{hiddenSearchField}', "",$this->page);
            $this->page = str_replace('{liParamsAsso}', "<li><a data-dl-view='true' data-dl-title='Paramétrage de l'association' href='{actionParamAssoc}'>
            <span class='icon-container'><i class='fas fa-cog'></i></span><span class='text item'> Paramétrage de l'association</span></a></li>",$this->page);
            $this->page = str_replace('{liAddMembers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un adhérent' href='{actionAddMembers}' class='show-if-mobile'>
            <span class='icon-container'><i class='fa fa-plus-circle'></i></span><span class='text item'> Ajouter un adhérent </span></a></li>",$this->page);
            $this->page = str_replace('{liAddUsers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un utilisateur' href='{actionAddUsers}'>
            <span class='icon-container'><i class='fas fa-toolbox'></i></span><span class='text item'> Ajouter un utilisateur </span></a></li>",$this->page);
            $this->page = str_replace('{liCreateCampaign}', "<li><a data-dl-view='true' data-dl-title='Créer une campagne' href='{actionCreateCampaign}'>
            <span class='icon-container'><i class='fas fa-globe-europe'></i></span><span class='text item'> Créer une campagne </span></a></li>",$this->page);
            $this->page = str_replace('{liAddDonations}', "<li><a data-dl-view='true' data-dl-title='Gérer vos dons' href='{actionAddDonations}'>
            <span class='icon-container'><i class='fas fa-gifts'></i></span><span class='text item'> Gérer vos dons </span></a></li>",$this->page);
            $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>",$this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden",$this->page);
            $this->page = str_replace('{liParamsAsso}', "",$this->page);
            $this->page = str_replace('{liAddMembers}', "",$this->page);
            $this->page = str_replace('{liAddUsers}', "",$this->page);
            $this->page = str_replace('{liCreateCampaign}', "",$this->page);
            $this->page = str_replace('{liAddDonations}', "",$this->page);
            $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>",$this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{fa-plus}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        // div container
        $this->page .= "<div class='container-fluid topListFiles'>";
        $this->page .= "<div class='row'>";
        // Partie gauche
        $this->page .= "<div class='col-md-8 order-1 order-md-1 d-flex flex-column'>";
        // Partie ajout
        if($_SESSION['user']['id_roles'] == "1") { 
            $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                        ."<div class='p-2 bd-highlight'>"
                        ."<a href='index.php?controller=document&action=addFormFile'>"
                        ."<button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter un dossier</button>"
                        ."</a>"
                        ."</div>"
                        ."</div>";
        }
        $this->page .= "<div class='modules order-1 order-md-1'>"
                    ."<h2 class='text-center'>Les documents de l'association</h2>"
                    ."<div class='separator mb-4'></div>"
                    ."<div class=''>"
                    ."<form method='POST' name='doc-filter' class='doc-filters'
                    action='index.php?controller=document&action=detailDoc' id='doc-filter'>"
                    ."<div class=''>"
                    ."<div class='row my-form-element-container mb-4'>"
                    ."<div class='form-element search-button font-family-FA'>"
                    ."<input type='submit' name='submit' class='btn btn-primary' value='&#xf002'>"
                    ."</div>"
                    ."<div class='form-element search-field'>"
                    ."<div id='search_doc' class='form-group'>"
                    ."<select id='sel_docs' name='sel_docs'></select>"
                    ."</div>"
                    ."</div>"
                    ."</div>"
                    ."</div>"
                    ."</form>"
                    ."</div>"
                    ."</div>";
        
        // tableau
        $this->page .= "<table id='tableFiles' class='table table-striped order-2 order-md-2' cellspacing='0' width='100%'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr role='row'>";
        $this->page .= "<th scope='col' hidden='' class='align-middle'>id</th>"
                    ."<th scope='col' class='align-middle'>Documents</th>";
        // fin entête tableau
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='FilesTable'>";
        foreach ($listSimplifiedFiles as $files) {
            $this->page .= "<tr>"
            ."<td data-label='ID' scope='row' hidden='' class='align-middle'>1</td>"
            ."<td data-label='Date' class='align-middle d-flex'>"
            ."<a class='noHover mr-auto' href='index.php?controller=document&action=show&id=".$files['id_dossier']."'>"
            ."<button type='button' class='btn btn-success mr-2 align-middle text-center'>"
            ."<i class='fas fa-eye' title='document ouvert'></i>"
            ."</button>"
            .$files['nomDossier']
            ."</a>";
            if($_SESSION['user']['id_roles'] == "1") {
                $this->page .= "<a class='noHover text-right' href='index.php?controller=document&action=updateFormForFile&id=".$files['id_dossier']."'>"
                            ."<button type='button' class='btn btn-warning mr-2 align-middle text-center'>"
                            ."<i class='fas fa-wrench' title='dossier à modifier'></i>"
                            ."</button>"
                            ."</a>";
            }
            $this->page .= "</td>"
                        ."</tr>";
        }
        // fin corps du tableau
        $this->page .= "</tbody>";
        // fin tableau
        $this->page .= "</table>";
        // fin partie gauche
        $this->page .= "</div>";
        // Partie droite
        $this->page .= "<div class='col-md-4 order-2 order-md-2'>";
        // Partie ajout
        if($_SESSION['user']['id_roles'] == "1") {
        $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                    ."<div class='p-2 bd-highlight'>"
                    ."<a href='index.php?controller=document&action=addFormNews'>"
                    ."<button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter une news</button>"
                    ."</a>"
                    ."</div>"
                    ."</div>";
        }
        $this->page .= "<h2 class='lhTitle text-center mb-4'>Infos et nouveautés"
                    ."<br>"
                    ."<small class='text-secondary'>Les news de ".$associationDisplay['nom']."</small>"
                    ."</h2>";
        foreach ($listAllNews as $allNews) {
        setlocale (LC_TIME, 'fr_FR.utf8','fra');
        $this->page .= "<p>"
                    ."<img src='".$allNews['image']."' class='img-fluid'>"
            ."</p>"
            ."<time datetime='".$allNews['dateCreation']."' pubdate=''>".strftime("%d %B %Y", strtotime($allNews['dateCreation']))."</time>"
            ."<h4>".$allNews['titre'].""
            ."</h4>"
            ."<p><small>".nl2br($allNews['contenu'])."</small>"
            ."</p>";
        if($_SESSION['user']['id_roles'] == "1") {
        $this->page .= "<a class='noHover text-right' href='index.php?controller=document&action=updateFormForNews&id=".$allNews['id_news']."'>"
                    ."<button type='button' class='btn btn-warning mr-2 align-middle text-center'>"
                    ."<i class='fas fa-wrench' title='dossier à modifier'></i>"
                    ."</button>"
                    ."</a>";
        }
        $this->page .= "<hr>";
        }
        // Fin partie droite
        $this->page .= "</div>";
        // fin container
        $this->page .= "</div>";
        $this->page .= "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Fonction affichage d'un dossier de document sélectionné
     *
     * @param [type] $listSimplifiedFile
     * @param [type] $listDocFromFiles
     * @param [type] $associationDisplay
     * @return void
     */
    public function show($listSimplifiedFile,$listDocFromFiles,$associationDisplay) {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerDossierDocument.html');
        $this->page = str_replace('{header-css}', 'detailFiles',$this->page);
        $this->page = str_replace('{fa-plus}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page = str_replace('{hiddenSearchField}', "",$this->page);
            $this->page = str_replace('{liParamsAsso}', "<li><a data-dl-view='true' data-dl-title='Paramétrage de l'association' href='{actionParamAssoc}'>
            <span class='icon-container'><i class='fas fa-cog'></i></span><span class='text item'> Paramétrage de l'association</span></a></li>",$this->page);
            $this->page = str_replace('{liAddMembers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un adhérent' href='{actionAddMembers}' class='show-if-mobile'>
            <span class='icon-container'><i class='fa fa-plus-circle'></i></span><span class='text item'> Ajouter un adhérent </span></a></li>",$this->page);
            $this->page = str_replace('{liAddUsers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un utilisateur' href='{actionAddUsers}'>
            <span class='icon-container'><i class='fas fa-toolbox'></i></span><span class='text item'> Ajouter un utilisateur </span></a></li>",$this->page);
            $this->page = str_replace('{liCreateCampaign}', "<li><a data-dl-view='true' data-dl-title='Créer une campagne' href='{actionCreateCampaign}'>
            <span class='icon-container'><i class='fas fa-globe-europe'></i></span><span class='text item'> Créer une campagne </span></a></li>",$this->page);
            $this->page = str_replace('{liAddDonations}', "<li><a data-dl-view='true' data-dl-title='Gérer vos dons' href='{actionAddDonations}'>
            <span class='icon-container'><i class='fas fa-gifts'></i></span><span class='text item'> Gérer vos dons </span></a></li>",$this->page);
            $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>",$this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden",$this->page);
            $this->page = str_replace('{liParamsAsso}', "",$this->page);
            $this->page = str_replace('{liAddMembers}', "",$this->page);
            $this->page = str_replace('{liAddUsers}', "",$this->page);
            $this->page = str_replace('{liCreateCampaign}', "",$this->page);
            $this->page = str_replace('{liAddDonations}', "",$this->page);
            $this->page = str_replace('{liAddGroup}', "",$this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $nomDossier = $listSimplifiedFile['nomDossier'];
        $nomDossierExplode = explode(" - ", $nomDossier);
        $this->page = str_replace('{titreFormulaire}',$nomDossierExplode[1],$this->page);
        // début container
        $this->page .= "<div id='detail-fileDoc' class='container'>";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $this->page .= "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        if($_SESSION['user']['id_roles'] == "1") { 
        $this->page .= "<div class='d-flex flex-wrap bd-highlight'>"
                    ."<div class='p-2 bd-highlight'>"
                    ."<a href='index.php?controller=document&action=addFormDoc&id=".$listSimplifiedFile['id_dossier']."'>"
                    ."<button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter un document</button>"
                    ."</a>"
                    ."</div>"
                    ."</div>";
        }
        $this->page .= "<div class='col-md-12 mt-3 mb-5'>";
        $this->page .="<h3>".$nomDossierExplode[1]." - "
                    ."<small class='text-secondary'>".$nomDossierExplode[0]." / ".ucfirst($listSimplifiedFile['prenomAuteur'])." ".mb_strtoupper($listSimplifiedFile['fonctionAuteur'])
                    ."</small>"
                    ."</h3>"
                    ."<div class='separatorHorizontal'></div>";
        // début content
        $this->page .= "<div id='content'>";
        // début premère card
        $this->page .= "<div class='card'>";
        $this->page .= "<div class='card-body'>"
                    ."<h5 class='card-title'>Documents du dossier <strong>$nomDossierExplode[0]</strong></h5>"
                    ."<h6 class='card-subtitle'>Équivalents de l'état civil d'une personne physique, les principaux
                    documents de l'association doivent être conservés par cette dernière. Retrouvez dans cette
                    rubrique :</h6>"
                    ."<ul class='mt-2'><li>les <strong>documents</strong>, lisibles et téléchargeables en format PDF,</li>
                    <li>les <strong>liens</strong> vers des ressources externes</li>
                    </ul>"
                    ."<div class='p-2 bd-highlight'>"
                    ."<a href='index.php?controller=document&action=listFiles'>"
                    ."<button type='button' class='btn btn-primary'><i class='fas fa-fast-backward'></i></button>"
                    ."</a>"
                    ."</div>"
                    ."</div>";
        // fin première card
        $this->page .= "</div>";
        // début tiles
        $this->page .= "<div class='tiles mt-4'>";
            foreach ($listDocFromFiles as $docFiles) {
                setlocale (LC_TIME, 'fr_FR.utf8','fra');
                if ($docFiles ['typeDoc'] == "doc") {
                    $path = $docFiles['cheminDocument'];
                    $filesize = filesize($path);
                    $resultat = $filesize / 1024;
                    $newFilesize = round($resultat);
                    $ctx = "ctxt-doc";
                    // Récupère l'extension d'un fichier
                    $info = new SplFileInfo($path);
                    $infoFile = strtoupper($info->getExtension());
                    $linkForFile = "<a href='".$docFiles['cheminDocument']."' class='button' title='".htmlspecialchars($docFiles['nom'], ENT_QUOTES).".pdf'>Télécharger <span class='soft'>[<abbr>".$infoFile."</abbr> - ".$newFilesize." Ko]</span></a>";
                } elseif ($docFiles ['typeDoc'] == "focus") {
                    $ctx = "ctxt-focus";
                    $linkForFile = "<a href='".$docFiles['cheminDocument']."' class='button' title='".htmlspecialchars($docFiles['nom'], ENT_QUOTES)."'>En savoir plus</a>";
                }
                else {
                    $ctx = "ctxt-link";
                    $linkForFile = "<a href='".$docFiles['cheminDocument']."' class='button' title='".htmlspecialchars($docFiles['nom'], ENT_QUOTES)."' target='_blank'>Voir le texte</a>";
                }
                $this->page .= "<article class='tile col-md-4 col-sm-12 d-flex align-items-stretch'>"
                            ."<div class='card $ctx'>"
                            ."<div class='card-body'>"
                            ."<time datetime=".$docFiles['dateCreation']." pubdate=''>".strftime("%d %B %Y", strtotime($docFiles['dateCreation']))."</time>"
                            ."<h5 class='card-title'>".htmlspecialchars($docFiles['nom'], ENT_QUOTES)
                            ."</h5>"
                            ."<div class='separatorDocFile $ctx mb-4'></div>"
                            ."<p class='intro'>".nl2br($docFiles['description'])."</p>"
                            ."</div>";
                if($_SESSION['user']['id_roles'] == "1") {
                $this->page .= "<div class='text-right'>"
                            ."<a href='index.php?controller=document&action=updateFormForDoc&id=".$docFiles['id_document']."' class='btn btn-warning mr-3'>"
                            ."<i class='fas fa-wrench'></i>"
                            ."</a>"
                            ."</div>";
                }
                $this->page .= "<div class='actions'>"
                            .$linkForFile
                            ."</div>"
                            ."</div>"
                            ."</article>";
            }
        // fin tiles
        $this->page .="</div>";
        // fin content
        $this->page .= "</div>";
        $this->page .= "</div>";
        // fin container
        $this->page .= "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Fonction affichage du formulaire de création d'un document et d'un dossier
     *
     * @param [type] $listFunctions
     * @param [type] $associationDisplay
     * @return void
     */    
    public function addFormFile($listFunctions,$associationDisplay)
    {
        $this->page .= file_get_contents('template/formDossier.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{titreFormulaire}','Ajouter un dossier',$this->page);
        $this->page = str_replace('{action}','addFile',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}', '',$this->page);
        $this->page = str_replace('{hiddenButtonDoc}','hidden',$this->page);
        $this->page = str_replace('{titre1Formulaire}','Ajouter un dossier',$this->page);
        $nomDossier = "";
        $this->page = str_replace('{nomDossier}', $nomDossier,$this->page);
        $this->page = str_replace('{newNameFile}','',$this->page);
        $this->page = str_replace('{authorFirstName}','',$this->page);
        $this->page = str_replace('{authorName}','',$this->page);
        $authorFunction = "";
        foreach ($listFunctions as $fct) {
            $authorFunction .= "<option value='" . $fct['fonction'] . "'>" . $fct['fonction'] ."</option>";
        }
        $this->page = str_replace('{authorFunction}', $authorFunction,$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}', 'Envoyer',$this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage du formulaire de modification du dossier
     *
     * @param [type] $listFunctions
     * @param [type] $listSimplifiedFile
     * @param [type] $associationDisplay
     * @return void
     */
    public function updateFormForFile($listFunctions,$listSimplifiedFile,$associationDisplay){
        $this->page .= file_get_contents('template/formDossier.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{titreFormulaire}','Modifier un dossier',$this->page);
        $this->page = str_replace('{titre1Formulaire}','Modifier un dossier',$this->page);
        $this->page = str_replace('{action}','updateDBFile&id='.$listSimplifiedFile['id_dossier'].'',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{id}',$listSimplifiedFile['id_dossier'],$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{hiddenButtonDoc}','',$this->page);
        $this->page = str_replace('{actionDelDossier}','index.php?controller=document&action=suppDBFile&id='.$listSimplifiedFile['id_dossier'].'',$this->page);
        $nomDossierfirst = explode(" ", $listSimplifiedFile['nomDossier']);
        $nomDossier = $nomDossierfirst[2]." ".$nomDossierfirst[3]." ".$nomDossierfirst[4];
        $this->page = str_replace('{newNameFile}',$nomDossier,$this->page);
        $this->page = str_replace('{authorFirstName}',$listSimplifiedFile['prenomAuteur'],$this->page);
        $this->page = str_replace('{authorName}',$listSimplifiedFile['nomAuteur'],$this->page);
        $fonctions = "";
        foreach ($listFunctions as $fct) {
            $selected = "";
            if ($listSimplifiedFile['fonctionAuteur'] == $fct['fonction']) {
                $selected = "selected";
            }
            $fonctions .= "<option $selected value='" . $fct['fonction'] . "'>" . $fct['fonction'] ."</option>";
        }
        $this->page = str_replace('{authorFunction}', $fonctions,$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}','Modifier',$this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage du formation de création de document
     *
     * @param [type] $listSimplifiedFiles
     * @param [type] $associationDisplay
     * @param [type] $id
     * @return void
     */
    public function addFormDoc($listSimplifiedFiles,$associationDisplay,$id) {
        $this->page .= file_get_contents('template/formDocument.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','addDoc',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{titreFormulaire}','Ajouter un document',$this->page);
        $this->page = str_replace('{hiddenButtonFile}','hidden',$this->page);
        $this->page = str_replace('{actionDelFile}','',$this->page);
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        $nomDossier = "";
        foreach ($listSimplifiedFiles as $listFiles) {
            $selected = "";
            if ($id == $listFiles['id_dossier']) {
                $selected = "selected";
            }
            $nomDossier .= "<option $selected value='" . $listFiles['id_dossier'] . "'>" . $listFiles['nomDossier'] ."</option>";
        }
        $this->page = str_replace('{nomDossier}', $nomDossier,$this->page);
        $this->page = str_replace('{titleDocument}', 'Ajouter un document',$this->page);
        $this->page = str_replace('{nameDoc}','',$this->page);
        $today = date("Y-m-d");
        $this->page = str_replace('{dateDoc}',$today,$this->page);
        $this->page = str_replace('{readonlyDateDons}', 'readonly',$this->page);
        $typeDoc = "";
        $type = [
            'doc' => 'Document',
            'link' => 'Lien externe',
            'focus' => 'En savoir plus'
        ];
        foreach ($type as $cle => $gender) {
            $typeDoc .= "<option value='" . $cle . "'>" . $gender ."</option>";
        }
        $this->page = str_replace('{typeDoc}', $typeDoc,$this->page);
        $this->page = str_replace('{textareaForDoc}', '',$this->page);
        $this->page = str_replace('{fileReturn}', '',$this->page);
        $this->page = str_replace('{linkPath}', '',$this->page);
        $this->page = str_replace('{buttonDoc}','buttonAddDoc',$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}','Ajouter',$this->page);
        $this->displayPage();
    }

    /**
     * GEstion de l'affichage du formulaire de modification d'un document
     *
     * @param [type] $selectedDoc
     * @param [type] $listSimplifiedFiles
     * @param [type] $associationDisplay
     * @return void
     */
    public function updateFormForDoc($selectedDoc,$listSimplifiedFiles,$associationDisplay) {
        $this->page .= file_get_contents('template/formDocument.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-atlas',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        $this->page = str_replace('{action}','updateDBDoc&id='.$selectedDoc['id_document'].'',$this->page);
        $this->page = str_replace('{titreFormulaire}','Modifier un document',$this->page);
        $this->page = str_replace('{hiddenButtonFile}','',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=show&id='.$selectedDoc['id_dossierDoc'].'',$this->page);
        $this->page = str_replace('{actionDelFile}','index.php?controller=document&action=suppDBDoc&id='.$selectedDoc['id_document'].'',$this->page);
        $nomDossier = "";
        foreach ($listSimplifiedFiles as $listFiles) {
            $selected = "";
            if ($selectedDoc['id_dossierDoc'] == $listFiles['id_dossier']) {
                $selected = "selected";
            }
            $nomDossier .= "<option $selected value='" . $listFiles['id_dossier'] . "'>" . $listFiles['nomDossier'] ."</option>";
        }
        $this->page = str_replace('{nomDossier}', $nomDossier,$this->page);
        $this->page = str_replace('{titleDocument}', 'Modifier un document',$this->page);
        $this->page = str_replace('{nameDoc}',$selectedDoc['nom'],$this->page);
        $this->page = str_replace('{dateDoc}',$selectedDoc['dateCreation'],$this->page);
        $typeDoc = "";
        $type = [
            'doc' => 'Document',
            'link' => 'Lien externe',
            'focus' => 'En savoir plus'
        ];
        foreach ($type as $cle => $gender) {
            $selected = "";
            if ($selectedDoc['typeDoc'] == $cle) {
                $selected = "selected";
            }
            $typeDoc .= "<option $selected value='" . $cle . "'>" . $gender ."</option>";
        }
        $this->page = str_replace('{typeDoc}', $typeDoc,$this->page);
        $firstPath = explode("/", $selectedDoc['cheminDocument']);
        $this->page = str_replace('{textareaForDoc}',$selectedDoc['description'],$this->page);
        if ($selectedDoc['typeDoc'] == 'doc') {
            $this->page = str_replace('{fileReturn}',$firstPath[1],$this->page);
            $this->page = str_replace('{linkPath}', '',$this->page);
        } elseif ($selectedDoc['typeDoc'] == 'focus') {
            $this->page = str_replace('{fileReturn}',$firstPath[1],$this->page);
            $this->page = str_replace('{linkPath}', '',$this->page);
        } else {
            $this->page = str_replace('{linkPath}', $selectedDoc['cheminDocument'],$this->page);
            $this->page = str_replace('{fileReturn}', '',$this->page);
        }
        $this->page = str_replace('{buttonDoc}','buttonUpDoc',$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}','Modifier',$this->page);
        $this->displayPage();
    }

        /**
     * Fonction affichage du formulaire de création d'un document et d'un dossier
     *
     * @param [type] $associationDisplay
     * @return void
     */    
    public function addFormNews($associationDisplay)
    {
        $this->page .= file_get_contents('template/formNews.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-rss',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{titreFormulaire}','Ajouter une news',$this->page);
        $this->page = str_replace('{action}','addNews',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}', '',$this->page);
        $this->page = str_replace('{hiddenButtonDoc}','hidden',$this->page);
        $typeNews = "";
        $type = [
            'img/infos.png' => 'Infos',
            'img/news.png' => 'Nouveautés',
        ];
        foreach ($type as $cle => $gender) {
            $typeNews .= "<option value='" . $cle . "'>" . $gender ."</option>";
        }
        $this->page = str_replace('{typeNews}', $typeNews,$this->page);
        $this->page = str_replace('{nameNews}', '',$this->page);
        $today = date("Y-m-d");
        $this->page = str_replace('{dateNews}',$today,$this->page);
        $this->page = str_replace('{readonlyDateNews}', 'readonly',$this->page);
        $this->page = str_replace('{textareaForNews}','',$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}', 'Envoyer',$this->page);
        $this->displayPage();
    }

    public function updateFormForNews($selectedNews,$associationDisplay) {
        $this->page .= file_get_contents('template/formNews.html');
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-rss',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{titreFormulaire}','Modifier une news',$this->page);
        $this->page = str_replace('{action}','updateNews',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionDelNews}','index.php?controller=document&action=suppDBNews&id='.$selectedNews['id_news'].'',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}', $selectedNews['id_news'],$this->page);
        $this->page = str_replace('{hiddenButtonDoc}','',$this->page);
        $typeNews = "";
        $type = [
            'img/infos.png' => 'Infos',
            'img/news.png' => 'Nouveautés',
        ];
        foreach ($type as $cle => $gender) {
            $selected = "";
            if ($selectedNews['image'] == $cle) {
                $selected = "selected";
            }
            $typeNews .= "<option $selected value='" . $cle . "'>" . $gender ."</option>";
        }
        $this->page = str_replace('{typeNews}', $typeNews,$this->page);
        $this->page = str_replace('{nameNews}', $selectedNews['titre'],$this->page);
        $this->page = str_replace('{dateNews}',$selectedNews['dateCreation'],$this->page);
        $this->page = str_replace('{readonlyDateNews}', '',$this->page);
        $this->page = str_replace('{textareaForNews}',$selectedNews['contenu'],$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}', 'Modifier',$this->page);
        $this->displayPage();
    }
}