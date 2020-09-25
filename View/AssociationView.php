<?php
class AssociationView extends View
{
    /**
     * Fonction affichage page des paramètres de l'association
     *
     * @param [type] $associationDisplay, $initData
     * @return void
     */
    public function displayHome($associationDisplay,$initData)
    {
        $this->page .= file_get_contents('template/homeAssociation.html');
        $this->page = str_replace('{titreFormulaire}','Paramètres de l\'association',$this->page);
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
        $this->page = str_replace('{fa-plus}', 'pres fas fa-cogs',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if ($initData['nombre_association'] != 0) {
            $this->page = str_replace('{hiddenFirstStep}', 'hidden',$this->page);
            $this->page = str_replace('{hiddenSecStep}', '',$this->page);
        } else {
            $this->page = str_replace('{hiddenFirstStep}', '',$this->page);
            $this->page = str_replace('{hiddenSecStep}', 'hidden',$this->page);
        }
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{actionCreateAsso}', 'index.php?controller=association&action=createAsso',$this->page);
        $this->page = str_replace('{actionParamsAsso}', 'index.php?controller=association&action=editAsso',$this->page);
        $this->displayPage();
    }

    /**
     * Formulaire de saisie d'une nouvelle association
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function addForm($associationDisplay)
    {
        $this->page .= file_get_contents('template/formAssociation.html');
        $this->page = str_replace('{titreFormulaire}','Paramètres de l\'association',$this->page);
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
        $this->page = str_replace('{fa-plus}', 'pres fas fa-cogs',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','addDB',$this->page);
        $this->page = str_replace('{addOrUpdateAssociationTitle}','Création',$this->page);
        $this->page = str_replace('{associationTitle}','',$this->page);
        $this->page = str_replace('{associationName}','',$this->page);
        $this->page = str_replace('{hidden}','hidden',$this->page);
        $this->page = str_replace('{hiddenButtonReset}','',$this->page);
        $this->page = str_replace('{hiddenButtonResetUrl}','hidden',$this->page);
        $this->page = str_replace('{logo}', '',$this->page);
        $this->page = str_replace('{adresse}', '',$this->page);
        $this->page = str_replace('{codepostal}', '',$this->page);
        $this->page = str_replace('{ville}', '',$this->page);
        $this->page = str_replace('{email}', '',$this->page);
        $this->page = str_replace('{tel}', '',$this->page);
        $this->page = str_replace('{linkedinPath}', '',$this->page);
        $this->page = str_replace('{twitterPath}', '',$this->page);
        $this->page = str_replace('{facebookPath}', '',$this->page);
        $this->page = str_replace('{sitePath}', '',$this->page);
        $this->page = str_replace('{nameSubmit}', 'envoyer',$this->page);
        $this->displayPage();
    }

    /**
     * Formulaire de saisie pour la modification de l'association
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function updateForm($associationDisplay)
    {
        $this->page .= file_get_contents('template/formAssociation.html');
        $this->page = str_replace('{titreFormulaire}','Paramètres de l\'association',$this->page);
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
        $this->page = str_replace('{fa-plus}', 'pres fas fa-cogs',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','updateDB',$this->page);
        $this->page = str_replace('{actionEditFile}','editParamsFile',$this->page);
        $this->page = str_replace('{hidden}','',$this->page);
        $this->page = str_replace('{hiddenButtonReset}','hidden',$this->page);
        $this->page = str_replace('{hiddenButtonResetUrl}','',$this->page);
        $this->page = str_replace('{addOrUpdateAssociationTitle}','Paramétrage - ',$this->page);
        $this->page = str_replace('{associationTitle}',$associationDisplay['nom'],$this->page);
        $this->page = str_replace('{associationName}',$associationDisplay['nom'],$this->page);
        $this->page = str_replace('{adresse}',$associationDisplay['adresse'],$this->page);
        $this->page = str_replace('{codepostal}',$associationDisplay['CP'],$this->page);
        $this->page = str_replace('{ville}',$associationDisplay['ville'],$this->page);
        $this->page = str_replace('{tel}',$associationDisplay['telephone'],$this->page);
        $this->page = str_replace('{email}',$associationDisplay['email'],$this->page);
        $this->page = str_replace('{logo}',$associationDisplay['logo'],$this->page);
        $this->page = str_replace('{linkedinPath}', $associationDisplay['linkedin'],$this->page);
        $this->page = str_replace('{twitterPath}', $associationDisplay['twitter'],$this->page);
        $this->page = str_replace('{facebookPath}', $associationDisplay['facebook'],$this->page);
        $this->page = str_replace('{sitePath}', $associationDisplay['site'],$this->page);
        $this->page = str_replace('{nameSubmit}', 'modifier',$this->page);
        $this->displayPage();
    }

    /**
     * Affichage du formulaire de paramétrage des documents
     *
     * @param [type] $associationDisplay, $listJuridicalStatus, $listBoardMembers
     * @return void
     */
    public function displayParamsFile($associationDisplay,$listJuridicalStatus, $listBoardMembers)
    {
        $this->page .= file_get_contents('template/formParamsFile.html');
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
        $this->page = str_replace('{titreFormulaire}','Paramétrage des documents',$this->page);
        $this->page = str_replace('{fa-wrench}', 'pres fas fa-wrench',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logo}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','updateDBParamsFile',$this->page);
        $this->page = str_replace('{viewPdf}','createPdfExample&&id=3',$this->page);
        $this->page = str_replace('{associationName}',$associationDisplay['nom'],$this->page);
        $juridique = "";
        foreach ($listJuridicalStatus as $juridicalStatus) {
            $selected = "";
            if ($associationDisplay['id_jurid'] == $juridicalStatus['id']) {
                $selected = "selected";
            }
            $juridique .= "<option $selected value='" .$juridicalStatus['id']. "'>" . $juridicalStatus['libelle'] ."</option>";
        }
        $this->page = str_replace('{juridique}', $juridique,$this->page);
        $codeInsee = "";
        foreach ($listJuridicalStatus as $juridicalStatus) {
            if ($associationDisplay['id_jurid'] == $juridicalStatus['id']) {
                $codeInsee = $juridicalStatus['code'];
            }
        }
        $boardMemberComplete = $associationDisplay['nomSign']." ". $associationDisplay['prenomSign'] ." - " .$associationDisplay['fonctionSign'];
        if (!empty($associationDisplay['sel_boardMember'])) {
            $this->page = str_replace('{boardRealHidden}', '',$this->page);
            $this->page = str_replace('{searchBoardMemberHidden}', 'hidden',$this->page);
            $this->page = str_replace('{boardReal}',$boardMemberComplete,$this->page);
        } else {
            $this->page = str_replace('{boardRealHidden}', 'hidden',$this->page);
            $this->page = str_replace('{searchBoardMemberHidden}', '',$this->page);
            $this->page = str_replace('{boardReal}', $boardMemberComplete,$this->page);
        }
        $this->page = str_replace('{codeInsee}',$codeInsee,$this->page);
        $this->page = str_replace('{siret}',$associationDisplay['siret'],$this->page);
        $this->page = str_replace('{rna}',$associationDisplay['rna'],$this->page);
        $this->page = str_replace('{objetsocial}',$associationDisplay['objetsocial'],$this->page);
        $this->page = str_replace('{footerdoc}',nl2br($associationDisplay['footerdoc']),$this->page);

        if (empty($associationDisplay['signature'])) {
            $this->page = str_replace('{signature}', '',$this->page);
        } else {
            $this->page = str_replace('{signature}',$associationDisplay['signature'],$this->page);
        }
        $this->displayPage();
    }

}
