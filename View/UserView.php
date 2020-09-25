<?php

class UserView extends View {

    /**
     * Fonction affichage de la table des adhérents (filtrée par la date de sortie nulle et par le rôle)
     *
     * @param [type] $listUsers
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($listUsers,$associationDisplay)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListUsers.html');
        $this->page = str_replace('{titreFormulaire}','Liste des utilisateurs',$this->page);
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
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
        $this->page = str_replace('{fa-toolbox}', 'pres fas fa-toolbox',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{photo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{photo}',$associationDisplay['logo'],$this->page);
        }
        $this->page .= "<div class='container'>";
        $this->page .= "<h2 class='text-center mt-4 mb-4'>Liste des utilisateurs</h2>";
        $this->page .= "<h4 class='text-center mt-4 mb-4'>{message}</h4>";
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        // if(isset($_SESSION['user'])){
        // }
        $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                    ."<div class='bd-highlight'>"
                    ."<a href='index.php?controller=user&action=addUserForm'>"
                    ."<button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter un utilisateur</button>"
                    ."</a>"
                    ."</div>"
                    ."</div>";
        $this->page .= "<table id='tableUsers' class='table table-sm cellspacing='0' width='100%''>";
        $this->page .= "<thead class='thead-dark'><tr>";
        $this->page .= "<th scope='col' class='align-middle'>Id</th>";
        $this->page .= "<th scope='col' class='align-middle'>Nom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' id='rulesUser' class='text-center align-middle'>Roles</th>";
        $this->page .= "<th scope='col' id='thQuality' class='text-center align-middle'>Qualité</th>";
        $this->page .= "<th scope='col' class='text-center align-middle'>Droit</th>";
        // if(isset($_SESSION['user'])){
        // }
        $this->page .= "</tr></thead><tbody id='UsersTable'>";
        foreach ($listUsers as $users) {
            $this->page .= "<tr><th scope='row' class='align-middle'>".$users['id_adherent']."</th>"
                        ."<td class='align-middle'>".$users['nom_adherent']."</td>"
                        ."<td class='align-middle'>".$users['prenom']."</td>"
                        ."<td id='rulesUser' class='text-center align-middle'>".$users['nom_roles']."</td>"
                        ."<td id='rulesUser' id='tdQuality' class='text-center align-middle'>".$users['qualite_adherent']."</td>";
            $this->page .= "<td class='text-center align-middle'><a href='index.php?controller=user&action=updateForm&id=".$users['id_adherent']
            ."' class='btn btn-warning ml-auto mr-auto'><i class='fas fa-user-shield'></i></a></td>";
            // if (isset($_SESSION['user'])) {
            // }
            $this->page .= "</tr>";
        }
        $this->page .= "</tbody></table>";
        $this->page .= "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Affichage formulaire de saisie d'un nouvel utilisateur
     *
     * @param [type] $listAdherentGroup
     * @param [type] $associationDisplay
     * @return void
     */
    public function addUserForm($listAdherentGroup,$associationDisplay){
        $this->page .= file_get_contents('template/formUserInAdherentGroup.html');
        $this->page = str_replace('{titreFormulaire}','Ajout d\'un utilisateur',$this->page);
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
        $this->page = str_replace('{fa-plus}', 'pres fas fa-users-cog',$this->page);
        $this->page = str_replace('{header-css}', '',$this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"',$this->page);
        $this->page = str_replace('{action}','addDBUser',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logo}',$associationDisplay['logo'],$this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page = str_replace('{id}','',$this->page);
        $this->page = str_replace('{inputID}','hidden',$this->page);
        $this->page = str_replace('{resetButton}','',$this->page);
        $this->page = str_replace('{hiddenCheckedBox}','',$this->page);
        $this->page = str_replace('{nom}','',$this->page);
        $this->page = str_replace('{prenom}','',$this->page);
        $this->page = str_replace('{email}','',$this->page);
        $this->page = str_replace('{tel}','',$this->page);
        $sexe = "";
        $genre = [
            'M' => 'Masculin',
            'F' => 'Féminin',
        ];
        foreach ($genre as $cle => $gender) {
            $sexe .= "<option value='" . $cle . "'>" . $gender ."</option>";
        }
        $this->page = str_replace('{sexe}', $sexe,$this->page);
        $degrees = "";
        $level = [
            'Aucun' => 'Aucun',
            'Bac' => 'Bac',
            'BT' => 'Brevet de technicien',
            'BTS' => 'BTS / DUT',
            'CAP' => 'CAP / BEP',
            'Doctorat' => 'Doctorat',
            'Licence' => 'Licence',
            'Master' => 'Master',
            'Professionnel' => 'Professionnel',
            'TP' => 'TP de niveau III',
            'TPII' => 'TP de niveau II',
        ];
        foreach ($level as $niveau => $lvl) {
           $degrees .= "<option value'" . $niveau. "'>" . $lvl ."</option>";
        }
        $this->page = str_replace('{degrees}', $degrees,$this->page);
        $this->page = str_replace('{job}', '',$this->page);
        $this->page = str_replace('{family}', 'Votre poste',$this->page);
        $this->page = str_replace('{adresse}','',$this->page);
        $this->page = str_replace('{degrees}','',$this->page);
        $this->page = str_replace('{codepostal}','',$this->page);
        $this->page = str_replace('{ville}','',$this->page);
        $groupeAdherent = "";
        foreach ($listAdherentGroup as $adherentGroup) {
            $groupeAdherent .= "<option value='" . $adherentGroup['id'] . "'>" . $adherentGroup['nomGroup'] ."</option>";
        }
        $this->page = str_replace('{groupeAdherent}', $groupeAdherent,$this->page);
        $this->page = str_replace('{photo}','',$this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage afin de modifier les droits de l'utilisateur
     *
     * @param [type] $user
     * @param [type] $associationDisplay
     * @return void
     */
    public function updateForm($user,$associationDisplay){
        $this->page .= file_get_contents('template/formUser.html');
        $this->page = str_replace('{titreFormulaire}','Modifier les droits de '.$user['prenom'].' '.$user['nom_adherent'].'',$this->page);
        $this->page = str_replace('{fa-toolbox}', 'pres fas fa-toolbox',$this->page);
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
        $this->page = str_replace('{cssActionBackAndActionSend}', 'class="d-flex flex-wrap justify-content-start mb-0"',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=user&action=start',$this->page);
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
        $this->page = str_replace('{deleteUser}', 'index.php?controller=user&action=suppDBUser&id='.$user['id_adherent'].'',$this->page);
        if ($user['id_qualite'] == 1) {
            $this->page = str_replace('{detailUser}', 'index.php?controller=adherent&action=modal&id='.$user['id_adherent'].'',$this->page);
        } else {
            $this->page = str_replace('{detailUser}', 'index.php?controller=adherent&action=modal&id='.$user['id_adherent'].'',$this->page);
        }
        $this->page = str_replace('{action}','updateDB',$this->page);
        $this->page = str_replace('{actionQualite}','updateDBQualite&id='.$user['id_adherent'].'',$this->page);
        $this->page = str_replace('{id}',$user['id_adherent'],$this->page);
        $this->page = str_replace('{prenomUser}',$user['prenom'],$this->page);
        $this->page = str_replace('{nomUser}',$user['nom_adherent'],$this->page);
        if ($user['id_roles'] == 1) {
            $this->page = str_replace('{checkedAdmin}','checked',$this->page);
            $this->page = str_replace('{checkedMember}','',$this->page);
        } else {
            $this->page = str_replace('{checkedAdmin}','',$this->page);
            $this->page = str_replace('{checkedMember}','checked',$this->page);
        }
        if ($user['id_qualite'] == 1) {
            $this->page = str_replace('{checkedAdherent}','checked',$this->page);
            $this->page = str_replace('{checkedUser}','',$this->page);
            $this->page = str_replace('{displayIfGroup}', 'style="display: none;"',$this->page);
            $this->page = str_replace('{groupAdherent}',$user['nom_groupe'],$this->page);
            $this->page = str_replace('{actionSendMailForRegister}','sendMailForRegister',$this->page);
        } else {
            $this->page = str_replace('{checkedAdherent}','',$this->page);
            $this->page = str_replace('{checkedUser}','checked',$this->page);
            $this->page = str_replace('{displayIfGroup}', 'style="display: block;"',$this->page);
            $this->page = str_replace('{groupAdherent}',$user['nom_groupe'],$this->page);
            $this->page = str_replace('{actionSendMailForRegister}','sendMailForRegisterForUser',$this->page);
        }
        $this->displayPage();
    }

    /**
     * Fonction affichage du formulaire pour la migration d'un adhérent vers un utilisateur et inversement
     *
     * @param [type] $adherent
     * @param [type] $listFunctions
     * @param [type] $listStatuts
     * @param [type] $listRegulations
     * @param [type] $listCotisations
     * @param [type] $listJobs
     * @param [type] $associationDisplay
     * @param [type] $listAdherentGroup
     * @param [type] $qualityUSer
     * @return void
     */
    public function updateFormUserToAdherent($adherent,$listFunctions,$listStatuts,$listRegulations,$listCotisations,$listJobs,$associationDisplay,$listAdherentGroup,$qualityUSer){
        $today = date("Y-m-d");
        $this->page .= file_get_contents('template/formSwitchUpdateAdherentUser.html');
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
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
        $this->page = str_replace('{fa-plus}', 'pres fas fa-user-edit',$this->page);
        $this->page = str_replace('{header-css}', 'user-edit',$this->page);
        $this->page = str_replace('{activeAdd}', '',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logo}',$associationDisplay['logo'],$this->page);
        }
        if ($qualityUSer == "1") {
            $this->page = str_replace('{titreFormulaire}','Migrer '.$adherent['prenom'].' '.$adherent['nom'].' vers un statut d\'adhérent',$this->page);
            $this->page = str_replace('{action}','migrateDBUserToAdherent&id='.$adherent['id_adherent'].'',$this->page);
            $this->page = str_replace('{sortieMasqueUser}', '',$this->page);
            $this->page = str_replace('{sortieMasqueAdherent}', 'hidden',$this->page);
            $this->page = str_replace('{labelForAlert}', 'Alerter l\'adhérent par mail de son ajout',$this->page);
            $fonctions = "";
            foreach ($listFunctions as $fct) {
                $fonctions .= "<option value='" . $fct['id'] . "'>" . $fct['fonction'] ."</option>";
            }
            $this->page = str_replace('{fonction}', $fonctions,$this->page);
            $statuts = "";
            foreach ($listStatuts as $statut) {
                $statuts .= "<option value='" . $statut['id'] . "'>" . $statut['statut'] ."</option>";
            }
            $this->page = str_replace('{statut}', $statuts,$this->page);
            $this->page = str_replace('{dateEntree}', $today,$this->page);
            $this->page = str_replace('{dateSortie}', '',$this->page);
            $regulations = "";
            foreach ($listRegulations as $regul) {
                $regulations .= "<option value='" . $regul['id'] . "'>" . $regul['mode_reglement'] ."</option>";
            }
            $this->page = str_replace('{reglement}', $regulations,$this->page);
            $cotisations = "";
            foreach ($listCotisations as $cotis) {
                $tarifEtLibelleCotisation = $cotis['montant_cotisation']."€ (".$cotis['libelle'].")";
                $cotisations .= "<option value='" . $cotis['id'] . "'>" . $tarifEtLibelleCotisation ."</option>";
            }
            $this->page = str_replace('{cotisation}', $cotisations,$this->page);
            $groupeAdherent = "";
            $this->page = str_replace('{groupeAdherent}', $groupeAdherent,$this->page);
            $this->page = str_replace('{idSubmitSwitchMemberUser}', 'submitSwitchToMember',$this->page);
        } else {
            $this->page = str_replace('{titreFormulaire}','Migrer '.$adherent['prenom'].' '.$adherent['nom'].' vers un statut d\'utilisateur',$this->page);
            $this->page = str_replace('{action}','migrateDBAdherentToUser&id='.$adherent['id_adherent'].'',$this->page);
            $this->page = str_replace('{sortieMasqueUser}', 'hidden',$this->page);
            $this->page = str_replace('{sortieMasqueAdherent}', '',$this->page);
            $this->page = str_replace('{labelForAlert}', 'Alerter l\'utilisateur par mail de son ajout',$this->page);
            $fonctions = "";
            $this->page = str_replace('{fonction}', $fonctions,$this->page);
            $statuts = "";
            $this->page = str_replace('{statut}', $statuts,$this->page);
            $this->page = str_replace('{dateEntree}', '',$this->page);
            $this->page = str_replace('{dateSortie}', $today,$this->page);
            $regulations = "";
            $this->page = str_replace('{reglement}', $regulations,$this->page);
            $cotisations = "";
            $this->page = str_replace('{cotisation}', $cotisations,$this->page);
            $groupeAdherent = "";
            foreach ($listAdherentGroup as $adh) {
                $groupeAdherent .= "<option value='" . $adh['id'] . "'>" . $adh['nomGroup'] ."</option>";
            }
            $this->page = str_replace('{groupeAdherent}', $groupeAdherent,$this->page);
            $this->page = str_replace('{idSubmitSwitchMemberUser}', 'submitSwitchToUser',$this->page);
        }
        $this->page = str_replace('{id}',$adherent['id_adherent'],$this->page);
        $this->page = str_replace('{inputID}','hidden',$this->page);
        $this->displayPage();
    }
}
