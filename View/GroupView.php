<?php

class GroupView extends View {

    /**
     * Fonction affichage de la table groupeadherent
     *
     * @param [type] $listGroupAdherent
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($listGroupAdherent,$associationDisplay)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListGroup.html');
        $this->page = str_replace('{titreFormulaire}','Les groupes d\'utilisateurs',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page = str_replace('{hiddenSearchField}', "", $this->page);
            $this->page = str_replace('{liParamsAsso}', "<li><a data-dl-view='true' data-dl-title='Paramétrage de l'association' href='{actionParamAssoc}'>
            <span class='icon-container'><i class='fas fa-cog'></i></span><span class='text item'> Paramétrage de l'association</span></a></li>", $this->page);
            $this->page = str_replace('{liAddMembers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un adhérent' href='{actionAddMembers}' class='show-if-mobile'>
            <span class='icon-container'><i class='fa fa-plus-circle'></i></span><span class='text item'> Ajouter un adhérent </span></a></li>", $this->page);
            $this->page = str_replace('{liAddUsers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un utilisateur' href='{actionAddUsers}'>
            <span class='icon-container'><i class='fas fa-toolbox'></i></span><span class='text item'> Ajouter un utilisateur </span></a></li>", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "<li><a data-dl-view='true' data-dl-title='Créer une campagne' href='{actionCreateCampaign}'>
            <span class='icon-container'><i class='fas fa-globe-europe'></i></span><span class='text item'> Créer une campagne </span></a></li>", $this->page);
            $this->page = str_replace('{liAddDonations}', "<li><a data-dl-view='true' data-dl-title='Gérer vos dons' href='{actionAddDonations}'>
            <span class='icon-container'><i class='fas fa-gifts'></i></span><span class='text item'> Gérer vos dons </span></a></li>", $this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden", $this->page);
            $this->page = str_replace('{liParamsAsso}', "", $this->page);
            $this->page = str_replace('{liAddMembers}', "", $this->page);
            $this->page = str_replace('{liAddUsers}', "", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "", $this->page);
            $this->page = str_replace('{liAddDonations}', "", $this->page);
        }
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
        $this->page = str_replace('{fa-toolbox}', 'pres fas fa-hotel',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
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
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{photo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{photo}',$associationDisplay['logo'],$this->page);
        }
        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<h2 class='text-center mt-4 mb-4'>Liste des groupes d'utilisateurs</h2>";
        $this->page .= "<h4 class='text-center mt-4 mb-4'>{message}</h4>";
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        // Partie ajout d'un groupe
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                        ."<div class='bd-highlight'>"
                        ."<a href='index.php?controller=group&action=addGroupForm'>"
                        ."<button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter un groupe</button>"
                        ."</a>"
                        ."</div>"
                        ."</div>";
        }
        // tableau
        $this->page .= "<table id='tableGroup' class='table table-sm cellspacing='0' width='100%''>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr role='row'>";
        $this->page .= "<th scope='col' class='align-middle'>Nom</th>"
                    ."<th scope='col' class='align-middle'>Adresse</th>"
                    ."<th scope='col' class='align-middle'>Code Postal</th>"
                    ."<th scope='col' class='align-middle'>Ville</th>"
                    ."<th scope='col' class='align-middle'>Téléphone</th>"
                    ."<th scope='col' id='thEmail' class='align-middle'>E-mail</th>"
                    ."<th scope='col' class='align-middle'>Responsable</th>"
                    ."<th scope='col' class='align-middle'>Détail</th>";
        // fin entête tableau
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='GroupTable'>";
        foreach ($listGroupAdherent as $groupAdherent) {
            $this->page .= "<tr><th scope='row' class='align-middle'>".$groupAdherent['nomGroup']."</th>"
                        ."<td class='align-middle'>".$groupAdherent['adresseGroup']."</td>"
                        ."<td class='align-middle'>".$groupAdherent['CPGroup']."</td>"
                        ."<td class='align-middle'>".$groupAdherent['villeGroup']."</td>"
                        ."<td class='align-middle'>".$groupAdherent['telephoneGroup']."</td>"
                        ."<td id='tdEmail' class='align-middle'>".$groupAdherent['emailGroup']."</td>"
                        ."<td class='align-middle'>".$groupAdherent['representantPrenom']." ".$groupAdherent['representantNom']."</td>"
                        ."<td class='text-center align-middle'><a href='index.php?controller=group&action=detailForm&id=".$groupAdherent['id']
                        ."' class='btn btn-warning ml-auto mr-auto'><i class='fad fa-eye'></i></a></td>"
                        ."</tr>";
        }
        // fin corps du tableau
        $this->page .= "</tbody>";
        // fin tableau
        $this->page .= "</table>";
        // fin container
        $this->page .= "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    public function detailForm($onlyOneGroupAdherent, $listUsersFromGroupSelected, $associationDisplay)
    {
        $this->page .= file_get_contents('template/detailGroupAdherent.html');
        $this->page = str_replace('{titreFormulaire}','Détail du groupe '.$onlyOneGroupAdherent['nomGroup'].' ',$this->page);
        $this->page = str_replace('{fa-toolbox}', 'pres fas fa-hotel',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page = str_replace('{hiddenSearchField}', "", $this->page);
            $this->page = str_replace('{liParamsAsso}', "<li><a data-dl-view='true' data-dl-title='Paramétrage de l'association' href='{actionParamAssoc}'>
            <span class='icon-container'><i class='fas fa-cog'></i></span><span class='text item'> Paramétrage de l'association</span></a></li>", $this->page);
            $this->page = str_replace('{liAddMembers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un adhérent' href='{actionAddMembers}' class='show-if-mobile'>
            <span class='icon-container'><i class='fa fa-plus-circle'></i></span><span class='text item'> Ajouter un adhérent </span></a></li>", $this->page);
            $this->page = str_replace('{liAddUsers}', "<li><a data-dl-view='true' data-dl-title='Ajouter un utilisateur' href='{actionAddUsers}'>
            <span class='icon-container'><i class='fas fa-toolbox'></i></span><span class='text item'> Ajouter un utilisateur </span></a></li>", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "<li><a data-dl-view='true' data-dl-title='Créer une campagne' href='{actionCreateCampaign}'>
            <span class='icon-container'><i class='fas fa-globe-europe'></i></span><span class='text item'> Créer une campagne </span></a></li>", $this->page);
            $this->page = str_replace('{liAddDonations}', "<li><a data-dl-view='true' data-dl-title='Gérer vos dons' href='{actionAddDonations}'>
            <span class='icon-container'><i class='fas fa-gifts'></i></span><span class='text item'> Gérer vos dons </span></a></li>", $this->page);
            $this->page = str_replace('{hiddenIfUser}', "", $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=group&action=updateForm&id='.$onlyOneGroupAdherent['id'].'',$this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=group&action=suppDB&id='.$onlyOneGroupAdherent['id'].'',$this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden", $this->page);
            $this->page = str_replace('{liParamsAsso}', "", $this->page);
            $this->page = str_replace('{liAddMembers}', "", $this->page);
            $this->page = str_replace('{liAddUsers}', "", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "", $this->page);
            $this->page = str_replace('{liAddDonations}', "", $this->page);
            $this->page = str_replace('{hiddenIfUser}', "hidden", $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=group&action=detailForm&id='.$onlyOneGroupAdherent['id'].'',$this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=group&action=detailForm&id='.$onlyOneGroupAdherent['id'].'',$this->page);
        }
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
        $this->page = str_replace('{actionBack}', 'index.php?controller=group&action=listFiles',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logo}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{adresse}',$onlyOneGroupAdherent['adresseGroup'],$this->page);
        $this->page = str_replace('{codepostal}',$onlyOneGroupAdherent['CPGroup'],$this->page);
        $this->page = str_replace('{ville}',$onlyOneGroupAdherent['villeGroup'],$this->page);
        $this->page = str_replace('{telephone}',$onlyOneGroupAdherent['telephoneGroup'],$this->page);
        $this->page = str_replace('{email}',$onlyOneGroupAdherent['emailGroup'],$this->page);
        $this->page = str_replace('{representantPrenom}',$onlyOneGroupAdherent['representantPrenom'],$this->page);
        $this->page = str_replace('{representantNom}',$onlyOneGroupAdherent['representantNom'],$this->page);
        $contentUserSelected = "";
        foreach ($listUsersFromGroupSelected as $usersFromGroupSelected) {
            $contentUserSelected .= "<div class='blockContentUserSelected'><span class='firstnameUserSelected align-self-center'>".$usersFromGroupSelected['prenom']."</span>"
                                ."<span class='firstnameUserSelected align-self-center'>".$usersFromGroupSelected['nom']."</span>"
                                ."<span class='emailUserSelected align-self-center'>"."(".$usersFromGroupSelected['email'].")"."</span>"
                                ."</div>";
        }
        $this->page = str_replace('{contentUserSelected}',$contentUserSelected,$this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage du formulaire de création d'un groupe
     *
     * *@param [type] $associationDisplay
     * @return void
     */
    public function addGroupForm($associationDisplay)
    {
        $this->page .= file_get_contents('template/formGroup.html');
        $this->page = str_replace('{titreFormulaire}','Ajouter un groupe',$this->page);
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-hotel',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
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
        $this->page = str_replace('{actionBack}', 'index.php?controller=group&action=listFiles',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page = str_replace('{action}','addGroup',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{actionAddAdherent}','index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{searchMemberForGroupHidden}','',$this->page);
        $this->page = str_replace('{hiddenMemberLeaderGroupSelectedForChange}','hidden',$this->page);
        $this->page = str_replace('{readonlyMemberLeaderGroupSelectedForChange}', 'readonly',$this->page);
        $this->page = str_replace('{MemberLeaderGroupSelectedForChange}', '',$this->page);
        $this->page = str_replace('{hiddenResetButtonMemberForGroup}','hidden',$this->page);
        $this->page = str_replace('{formHelperLeaderGroupHidden}','',$this->page);
        $this->page = str_replace('{memberNameForGroup}','',$this->page);
        $this->page = str_replace('{id}', '',$this->page);
        $this->page = str_replace('{nomGroupe}', '',$this->page);
        $this->page = str_replace('{emailGroupe}', '',$this->page);
        $this->page = str_replace('{telGroupe}', '',$this->page);
        $this->page = str_replace('{adresseGroupe}','',$this->page);
        $this->page = str_replace('{codepostalGroupe}','',$this->page);
        $this->page = str_replace('{villeGroupe}','',$this->page);
        $this->page = str_replace('{representantPrenomGroupe}','',$this->page);
        $this->page = str_replace('{representantNomGroupe}','',$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}', 'Envoyer',$this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage du formulaire de modification d'un groupe
     *
     * @param [type] $onlyOneGroupAdherent
     * @param [type] $associationDisplay
     * @return void
     */
    public function updateFormForGroup($onlyOneGroupAdherent,$associationDisplay)
    {
        $this->page .= file_get_contents('template/formGroup.html');
        $this->page = str_replace('{titreFormulaire}','Modifier un groupe',$this->page);
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-hotel',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
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
        $this->page = str_replace('{actionBack}', 'index.php?controller=group&action=listFiles',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page = str_replace('{action}','updateGroup',$this->page);
        $this->page = str_replace('{actionAddAdherent}','index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{searchMemberForGroupHidden}','hidden',$this->page);
        $this->page = str_replace('{memberNameForGroup}','',$this->page);
        $this->page = str_replace('{hiddenMemberLeaderGroupSelectedForChange}','',$this->page);
        $MemberLeaderGroupComplete = $onlyOneGroupAdherent['representantPrenom']." ". $onlyOneGroupAdherent['representantNom'];
        $this->page = str_replace('{MemberLeaderGroupSelectedForChange}',$MemberLeaderGroupComplete,$this->page);
        $this->page = str_replace('{readonlyMemberLeaderGroupSelectedForChange}','readonly',$this->page);
        $this->page = str_replace('{formHelperLeaderGroupHidden}','hidden',$this->page);
        $this->page = str_replace('{hiddenResetButtonMemberForGroup}','',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}',$onlyOneGroupAdherent['id'],$this->page);
        $this->page = str_replace('{nomGroupe}',$onlyOneGroupAdherent['nomGroup'],$this->page);
        $this->page = str_replace('{emailGroupe}',$onlyOneGroupAdherent['emailGroup'],$this->page);
        $this->page = str_replace('{telGroupe}','+33'.$onlyOneGroupAdherent['telephoneGroup'],$this->page);
        $this->page = str_replace('{adresseGroupe}',$onlyOneGroupAdherent['adresseGroup'],$this->page);
        $this->page = str_replace('{codepostalGroupe}',$onlyOneGroupAdherent['CPGroup'],$this->page);
        $this->page = str_replace('{villeGroupe}',$onlyOneGroupAdherent['villeGroup'],$this->page);
        $this->page = str_replace('{representantPrenomGroupe}',$onlyOneGroupAdherent['representantPrenom'],$this->page);
        $this->page = str_replace('{representantNomGroupe}',$onlyOneGroupAdherent['representantNom'],$this->page);
        $this->page = str_replace('{valueButtonSubmitDocument}', 'Modifier',$this->page);
        $this->displayPage();
    }
}