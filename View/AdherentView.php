<?php
class AdherentView extends View
{

    /**
     * Affichage de la liste des adhérents
     *
     * @param [type] $listAdherents
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($listAdherents, $associationDisplay)
    {
        // var_dump($listAdherents);
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListAdherent.html');
        $this->page = str_replace('{titreFormulaire}', 'Liste des adhérents', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>", $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
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
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{fa-plus}', 'pres fas fa-users', $this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}', $associationDisplay['logo'], $this->page);
        }

        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<h2 class='text-center mt-4 mb-4'>Liste des adhérents actifs</h2>";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $this->page .= "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        // bouton envoi CSV
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<div class='hide-on-csv bd-highlight'>"
                . "<div class='revealCsv-on-hover'><button type='button' class='btn buttonXls'><i class='fas fa-file-excel'></i><img src='img/arrow-select.png' class='icoXls align-middle'></button></div>"
                . "<div class='revealCsv-on-click'>"
                . "<h2>Exporter vers Excel</h2>"
                . "<div class='xlsContent'>"
                . "<p>Vous allez exporter le tableau en fichier CSV, lisible sous Excel</p>"
                . "<p>Une fois ouvert sous Excel, vous pouvez l'enregistrer sous format xls ou xlsx</p>"
                . "<a href='index.php?controller=adherent&action=export_data_to_csv'><button type='button' class='btn btn-primary align-middle text-center exportBtn'><i class='fad fa-file-export'></i> Exporter</button></a>"
                . "</div>"
                . "</div>"
                . "</div>";
        }
        // début formulaire
        $this->page .= "<form action='index.php?controller=adherent&action=prepareToSend' method='POST'>";
        // boutons d'action
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                . "<span class='tableMemberLinesNb p-2 bd-highlight'>3 ligne(s).</span>"
                . "<div class='p-2 ml-auto mt-2 mb-2 text-center align-middle'><a href='index.php?controller=adherent&action=showOldMember' class='btn btn-info ml-auto mr-auto'><i class='fas fa-parachute-box'></i> Liste des anciens adhérents</a></div>"
                . "<div class='p-2 ml-0 bd-highlight sendMessage'>"
                . "<button type='submit' class='btn btn-grey'><i class='fas fa-paper-plane'></i> Envoyer un message</button>"
                . "</div>"
                . "</div>";
        }
        // tableau
        $this->page .= "<table id='tableMembers' class='table table-sm'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // checkbox générale entête tableau
        $this->page .= "<th scope='col' class='align-middle'>";
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<label class='checkbox' for='searchTableCheckbox'>"
                . "<input id='searchTableCheckbox' type='checkbox' class='custom-checkbox'>"
                . "<span class='icons'>"
                . "<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                . "</span>";
        }
        $this->page .= "<span>Nom</span>"
            . "</label></th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' id='thActif' class='text-center align-middle'>Actif</th>";
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<th scope='col' class='text-center align-middle'>Lire</th>";
        }
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='MembersTable'>";
        foreach ($listAdherents as $adherents) {
            $actif = "<td id='tdActif' class='text-center text-success align-middle'><i class='fas fa-circle'></i></td>";
            if ($adherents['date_sortie'] != NULL) {
                $actif = "<td id='tdActif' class='text-center text-danger align-middle'><i class='fas fa-circle'></i></td>";
            }
            $this->page .= "<tr>"
                . "<td data-label='Nom' scope='row' class='align-middle'>";
            if ($_SESSION['user']['id_roles'] == "1") {
                $this->page .= "<label class='checkbox' for='searchTable_input_checkbox" . $adherents['id_adherent'] . "' id='searchTable_label" . $adherents['id_adherent'] . "'>"
                    . "<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox" . $adherents['id_adherent'] . "' value='" . $adherents['id_adherent'] . "' class='custom-checkbox'>"
                    . "<span class='icons'>"
                    . "<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                    . "</span>";
            }
            $this->page .= "<span class='font-weight-bold'>" . $adherents['nom'] . "</span>"
                . "</label>"
                . "</td>"
                . "<td data-label='Date' class='align-middle'>" . $adherents['prenom'] . "</td>"
                . $actif;
            if ($_SESSION['user']['id_roles'] == "1") {
                $this->page .= "<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=" . $adherents['id_adherent']
                    . "' class='btn btn-success ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>";
            }
            $this->page .= "</tr>";
        }
        // fin corps du tableau
        $this->page .= "</tbody>";
        // fin tableau
        $this->page .= "</table>";
        // fin formulaire
        $this->page .= "</form>";
        // fin container
        $this->page .= "</div>";
        // popup
        $this->page .= "<div id='popup'>"
            . "<div id='contenuPop'>Veuillez sélectionner au moins une personne.</div>"
            . "<div class='buttonpane'><div class='buttonSet'>Fermer</div></div>"
            . "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Affichage d'un adhérent
     *
     * @param [type] $adherent
     * @param [type] $associationDisplay
     * @return void
     */
    public function modal($adherent, $associationDisplay)
    {
        $newDate = date("d-m-Y", strtotime($adherent['date_entree']));
        $today = date("Y-m-d");
        $dateExit = $adherent['date_sortie'];
        $datetime1 = new DateTime($today);
        $datetime2 = new DateTime($adherent['date_entree']);
        $datetime3 = new DateTime($adherent['date_sortie']);
        if ($dateExit != NULL) {
            $age = $datetime3->diff($datetime2);
        } else {
            $age = $datetime1->diff($datetime2);
        }
        $anciennete = $age->format('%y année(s), %m mois, %d jours');
        $this->page .= file_get_contents('template/detailAdherentNew.html');
        $this->page = str_replace('{header-css}', 'detail', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
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
            $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>", $this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden", $this->page);
            $this->page = str_replace('{liParamsAsso}', "", $this->page);
            $this->page = str_replace('{liAddMembers}', "", $this->page);
            $this->page = str_replace('{liAddUsers}', "", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "", $this->page);
            $this->page = str_replace('{liAddDonations}', "", $this->page);
            $this->page = str_replace('{liAddGroup}', "", $this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}', $associationDisplay['logo'], $this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        /** Modification new template */
        $backgroundImage = "";
        $background = $adherent['background'];
        if ($background == NULL) {
            $backgroundImage = "img/cover-00.jpg";
        } else {
            $backgroundImage = $background;
        }
        $this->page = str_replace('{backgroundImage}', $backgroundImage, $this->page);
        $this->page = str_replace('{action}', 'addBG&id='.$adherent['id_adherent'].'', $this->page);
        $this->page = str_replace('{connexion}', 'index.php?controller=adherent&action=connexionForm&id='.$adherent['id_adherent'].'', $this->page);

        if ($adherent['id_qualite'] == '1') {
            $this->page = str_replace('{labelFunctionAssociation}', 'Fonction :', $this->page);
            $this->page = str_replace('{functionAssociation}', $adherent['fonction'], $this->page);
            $this->page = str_replace('{statutAssociation}', $adherent['statut'], $this->page);
            $this->page = str_replace('{actionBack}', 'index.php?controller=adherent&action=start', $this->page);
            $this->page = str_replace('{labeDeleteUserOrAdherent}', 'Supprimer l\'adhérent', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSendUser}', '', $this->page);
            $this->page = str_replace('{actionSendReceiptMember}', 'index.php?controller=adherent&action=pdfReceipt&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{detailUserOrAdherent}', 'Détails de l\'adhérent', $this->page);
            $this->page = str_replace('{switchUserOrAdherent}', '', $this->page);
            $this->page = str_replace('{switchAdherentOrUser}', 'hidden', $this->page);
            $this->page = str_replace('{groupeAdherent}', '', $this->page);
        } else {
            $this->page = str_replace('{labelFunctionAssociation}', 'Groupe :', $this->page);
            $this->page = str_replace('{functionAssociation}', $adherent['nom_groupe'], $this->page);
            $this->page = str_replace('{statutAssociation}', 'Utilisateur', $this->page);
            $this->page = str_replace('{actionBack}', 'index.php?controller=user&action=start', $this->page);
            $this->page = str_replace('{labeDeleteUserOrAdherent}', 'Supprimer l\'utilisateur', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSendUser}', 'hidden', $this->page);
            $this->page = str_replace('{actionSendReceiptMember}', '', $this->page);
            $this->page = str_replace('{detailUserOrAdherent}', 'Détails de l\'utilisateur', $this->page);
            $this->page = str_replace('{switchUserOrAdherent}', 'hidden', $this->page);
            $this->page = str_replace('{switchAdherentOrUser}', '', $this->page);
            $this->page = str_replace('{groupeAdherent}', $adherent['nom_groupe'], $this->page);
        }
        if ($adherent['id_roles'] == '1' && $adherent['id_qualite'] == '1') {
            $this->page = str_replace('{detail-role}', 'Administrateur', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-admin.png" alt="Administrateur" title="Administrateur" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-admin.png" alt="Administrateur" title="Administrateur" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A tous les droits dont celui d\'ajouter les autres administrateurs.<br>Gère l\'enregistrement des adhésions et dons.<br>Il peut ajouter, modifier ou supprimer les éléments dans l\'application.', $this->page);
        } elseif ($adherent['id_roles'] == '2' && $adherent['id_qualite'] == '1') {
            $this->page = str_replace('{detail-role}', 'Adhérent', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-member.png" alt="Adhérent" title="Adhérent" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-member.png" alt="Adhérent" title="Adhérent" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A le droit de voir et de modifier son profil.<br>A le droit de consulter ou télécharger des documents.<br>L\'adhérent de l\'association est membre de celle-ci et a payé une cotisation pour adhérer.', $this->page);
        } else {
            $this->page = str_replace('{detail-role}', 'Utilisateur', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-user.png" alt="Utilisateur" title="Utilisateur" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-user.png" alt="Utilisateur" title="Utilisateur" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A le droit de voir et de modifier son profil.<br>A le droit de consulter ou télécharger des documents.<br>L\'utilisateur ne participe ni au fonctionnement de l\'association ni aux délibérations.', $this->page);
        }
        $this->page = str_replace('{detail-name}', $adherent['prenom'] . ' ' . $adherent['nom'], $this->page);
        $this->page = str_replace('{photo}', $adherent['avatar'], $this->page);
        $this->page = str_replace('{nom}', $adherent['nom'], $this->page);
        $this->page = str_replace('{prenom}', $adherent['prenom'], $this->page);
        $this->page = str_replace('{adresse}', $adherent['adresse'], $this->page);
        $this->page = str_replace('{codepostal}', $adherent['CP'], $this->page);
        $this->page = str_replace('{ville}', $adherent['ville'], $this->page);
        $this->page = str_replace('{telephone}', $adherent['telephone'], $this->page);
        $this->page = str_replace('{email}', $adherent['email'], $this->page);
        $this->page = str_replace('{degree}', $adherent['degree'], $this->page);
        $this->page = str_replace('{job}', $adherent['name'], $this->page);
        $this->page = str_replace('{family}', $adherent['family'], $this->page);
        $this->page = str_replace('{fonction}', $adherent['fonction'], $this->page);
        $this->page = str_replace('{statut}', $adherent['statut'], $this->page);
        $this->page = str_replace('{dateEntree}', $newDate, $this->page);
        $lastDate = "";
        if ($adherent['date_sortie'] != NULL) {
            $lastDate = date("d-m-Y", strtotime($adherent['date_sortie']));
        }
        $this->page = str_replace('{dateSortie}', $lastDate, $this->page);
        $dateRenewal = "";
        if ($adherent['date_renouvellement'] == $adherent['date_entree']) {
            $dateRenewal = "";
        } else {
            $dateRenewal = date("d-m-Y", strtotime($adherent['date_renouvellement']));
        }
        $this->page = str_replace('{dateRenewal}', $dateRenewal, $this->page);
        $this->page = str_replace('{anciennete}', $anciennete, $this->page);
        $this->page = str_replace('{cotisations}', $adherent['montant_cotisation'] . "€ (" . $adherent['libelle'] . ")", $this->page);
        $this->page = str_replace('{reglement}', $adherent['mode_reglement'], $this->page);
        if ($_SESSION['user']['id_roles'] == "1" && $adherent['id_qualite'] == "1") {
            $this->page = str_replace('{hiddenButtonSuppMember}', '', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', '', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', '', $this->page);
            // $this->page = str_replace('{cssActionBackAndActionSend}', 'class="d-flex justify-content-start mb-0"', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', '', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier l\'adhérent', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateForm&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=adherent&action=suppDB&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{cancelled}', 'index.php?controller=adherent&action=cancelledMemberForm&id=' . $adherent['id_adherent'] . '', $this->page);
        } elseif ($_SESSION['user']['id_roles'] == "1" && $adherent['id_qualite'] == "2") {
            $this->page = str_replace('{hiddenButtonSuppMember}', '', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', '', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', '', $this->page);
            // $this->page = str_replace('{cssActionBackAndActionSend}', 'class="d-flex justify-content-start mb-0"', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', '', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier l\'utilisateur', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateForm&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=user&action=suppDBUser&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{cancelled}', 'index.php?controller=user&action=cancelledUser&id=' . $adherent['id_adherent'] . '', $this->page);
        } else {
            $this->page = str_replace('{hiddenButtonSuppMember}', 'hidden', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', 'hidden', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', 'hidden', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', 'hidden', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier votre profil', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateFormOnlyMember&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', '', $this->page);
        }
        $this->displayPage();
    }

    /**
     * Affichage formulaire de saisie d'un nouvel adhérent
     *
     * @param [type] $listFunctions
     * @param [type] $listStatuts
     * @param [type] $listRegulations
     * @param [type] $listCotisations
     * @param [type] $associationDisplay
     * @return void
     */
    public function addForm($listFunctions, $listStatuts, $listRegulations, $listCotisations, $associationDisplay)
    {
        $this->page .= file_get_contents('template/formAdherent.html');
        $this->page = str_replace('{titreFormulaire}', 'Ajout d\'un adhérent', $this->page);
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{fa-plus}', 'pres fa fa-plus-circle', $this->page);
        $this->page = str_replace('{header-css}', '', $this->page);
        $this->page = str_replace('{activeAdd}', 'class="active"', $this->page);
        $this->page = str_replace('{action}', 'addDB', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logo}', $associationDisplay['logo'], $this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        $this->page = str_replace('{id}', '', $this->page);
        $this->page = str_replace('{inputID}', 'hidden', $this->page);
        $this->page = str_replace('{resetButton}', '', $this->page);
        $this->page = str_replace('{hiddenCheckedBox}', '', $this->page);
        $this->page = str_replace('{nom}', '', $this->page);
        $this->page = str_replace('{prenom}', '', $this->page);
        $this->page = str_replace('{email}', '', $this->page);
        $this->page = str_replace('{tel}', '', $this->page);
        $sexe = "";
        $genre = [
            'M' => 'Masculin',
            'F' => 'Féminin',
        ];
        foreach ($genre as $cle => $gender) {
            $sexe .= "<option value='" . $cle . "'>" . $gender . "</option>";
        }
        $this->page = str_replace('{sexe}', $sexe, $this->page);
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
            'TPIII' => 'TP de niveau III',
            'TPII' => 'TP de niveau II',
        ];
        foreach ($level as $niveau => $lvl) {
            $degrees .= "<option value'" . $niveau . "'>" . $lvl . "</option>";
        }
        $this->page = str_replace('{degrees}', $degrees, $this->page);
        $this->page = str_replace('{job}', '', $this->page);
        $this->page = str_replace('{family}', 'Votre poste', $this->page);
        $this->page = str_replace('{adresse}', '', $this->page);
        $this->page = str_replace('{degrees}', '', $this->page);
        $this->page = str_replace('{codepostal}', '', $this->page);
        $this->page = str_replace('{ville}', '', $this->page);
        $this->page = str_replace('{sortieMasque}', 'hidden', $this->page);
        $fonctions = "";
        foreach ($listFunctions as $fct) {
            $fonctions .= "<option value='" . $fct['id'] . "'>" . $fct['fonction'] . "</option>";
        }
        $this->page = str_replace('{fonction}', $fonctions, $this->page);
        $statuts = "";
        foreach ($listStatuts as $statut) {
            $statuts .= "<option value='" . $statut['id'] . "'>" . $statut['statut'] . "</option>";
        }
        $this->page = str_replace('{statut}', $statuts, $this->page);
        $regulations = "";
        foreach ($listRegulations as $regul) {
            $regulations .= "<option value='" . $regul['id'] . "'>" . $regul['mode_reglement'] . "</option>";
        }
        $this->page = str_replace('{reglement}', $regulations, $this->page);
        $cotisations = "";
        foreach ($listCotisations as $cotis) {
            $tarifEtLibelleCotisation = $cotis['montant_cotisation'] . "€ (" . $cotis['libelle'] . ")";
            $cotisations .= "<option value='" . $cotis['id'] . "'>" . $tarifEtLibelleCotisation . "</option>";
        }
        $this->page = str_replace('{cotisation}', $cotisations, $this->page);
        $this->page = str_replace('{photo}', '', $this->page);
        $this->displayPage();
    }

    /**
     * Affichage du formulaire contenant l'information adhérent à modifier
     *
     * @param [type] $adherent
     * @param [type] $listFunctions
     * @param [type] $listStatuts
     * @param [type] $listRegulations
     * @param [type] $listCotisations
     * @param [type] $listJobs
     * @param [type] $associationDisplay
     * @param [type] $listAdherentGroup
     * @return void
     */
    public function updateForm($adherent, $listFunctions, $listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay, $listAdherentGroup)
    {
        if ($adherent['id_qualite'] == 1) {
            $this->page .= file_get_contents('template/formAdherent.html');
            $this->page = str_replace('{titreFormulaire}', 'Modifier un adhérent', $this->page);
            $this->page = str_replace('{action}', 'updateDB&id=' . $adherent['id_adherent'] . '', $this->page);
        } else {
            $this->page .= file_get_contents('template/formUserInAdherentGroup.html');
            $this->page = str_replace('{titreFormulaire}', 'Modifier un utilisateur', $this->page);
            $message = "";
            if (isset($_SESSION['flash'])) {
                foreach ($_SESSION['flash'] as $type => $content) {
                    $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
                }
                unset($_SESSION['flash']);
            }
            $this->page = str_replace('{message}', $message, $this->page);
            $this->page = str_replace('{action}', 'updateDBUser&id=' . $adherent['id_adherent'] . '', $this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{fa-plus}', 'pres fas fa-user-edit', $this->page);
        $this->page = str_replace('{header-css}', 'user-edit', $this->page);
        $this->page = str_replace('{activeAdd}', '', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logo}', $associationDisplay['logo'], $this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        $this->page = str_replace('{id}', $adherent['id_adherent'], $this->page);
        $this->page = str_replace('{inputID}', 'hidden', $this->page);
        $this->page = str_replace('{resetButtonForUpdate}', 'hidden', $this->page);
        $this->page = str_replace('{hiddenCheckedBox}', 'hidden', $this->page);
        $this->page = str_replace('{nom}', $adherent['nom'], $this->page);
        $this->page = str_replace('{prenom}', $adherent['prenom'], $this->page);
        $this->page = str_replace('{email}', $adherent['email'], $this->page);
        $this->page = str_replace('{tel}', '+33' . $adherent['telephone'], $this->page);
        $sexe = "";
        $genre = [
            'M' => 'Masculin',
            'F' => 'Féminin',
        ];
        foreach ($genre as $cle => $gender) {
            $selected = "";
            if ($adherent['sexe'] == $cle) {
                $selected = "selected";
            }
            $sexe .= "<option $selected value='" . $cle . "'>" . $gender . "</option>";
        }
        $this->page = str_replace('{sexe}', $sexe, $this->page);
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
            'TPIII' => 'TP de niveau III',
            'TPII' => 'TP de niveau II',
        ];
        foreach ($level as $niveau => $lvl) {
            $selected = "";
            if ($adherent['degree'] == $lvl) {
                $selected = "selected";
            }
            $degrees .= "<option $selected value'" . $niveau . "'>" . $lvl . "</option>";
        }
        $this->page = str_replace('{degrees}', $degrees, $this->page);
        $jobs = "";
        foreach ($listJobs as $job) {
            $selected = "";
            if ($adherent['id_jobs'] == $job['id']) {
                $selected = "selected";
            }
            $jobs .= "<option $selected value='" . $job['id'] . "'>" . $job['name'] . "</option>";
        }
        $this->page = str_replace('{job}', $jobs, $this->page);
        $family = "";
        foreach ($listJobs as $job) {
            if ($adherent['id_jobs'] == $job['id']) {
                $family = $job['family'];
            }
        }
        $this->page = str_replace('{family}', $family, $this->page);
        $this->page = str_replace('{adresse}', $adherent['adresse'], $this->page);
        $this->page = str_replace('{codepostal}', $adherent['CP'], $this->page);
        $this->page = str_replace('{ville}', $adherent['ville'], $this->page);
        $fonctions = "";
        foreach ($listFunctions as $fct) {
            $selected = "";
            if ($adherent['id_fonction'] == $fct['id']) {
                $selected = "selected";
            }
            $fonctions .= "<option $selected value='" . $fct['id'] . "'>" . $fct['fonction'] . "</option>";
        }
        $this->page = str_replace('{fonction}', $fonctions, $this->page);
        $statuts = "";
        foreach ($listStatuts as $statut) {
            $selected = "";
            if ($adherent['id_statut'] == $statut['id']) {
                $selected = "selected";
            }
            $statuts .= "<option $selected value='" . $statut['id'] . "'>" . $statut['statut'] . "</option>";
        }
        $this->page = str_replace('{statut}', $statuts, $this->page);
        $this->page = str_replace('{dateEntree}', $adherent['date_entree'], $this->page);
        $dateRenewal = "";
        if ($adherent['date_renouvellement'] == $adherent['date_entree']) {
            $dateRenewal = "";
        } else {
            $dateRenewal = $adherent['date_renouvellement'];
        }
        $this->page = str_replace('{dateRenewal}', $dateRenewal, $this->page);
        $this->page = str_replace('{sortieMasque}', '', $this->page);
        $this->page = str_replace('{dateSortie}', $adherent['date_sortie'], $this->page);
        $cotisations = "";
        foreach ($listCotisations as $cotis) {
            $selected = "";
            if ($adherent['id_cotisation'] == $cotis['id']) {
                $selected = "selected";
            }
            $tarifEtLibelleCotisation = $cotis['montant_cotisation'] . "€ (" . $cotis['libelle'] . ")";
            $cotisations .= "<option $selected value='" . $cotis['id'] . "'>" . $tarifEtLibelleCotisation . "</option>";
        }
        $this->page = str_replace('{cotisation}', $cotisations, $this->page);
        $regulations = "";
        foreach ($listRegulations as $regul) {
            $selected = "";
            if ($adherent['id_reglement'] == $regul['id']) {
                $selected = "selected";
            }
            $regulations .= "<option $selected value='" . $regul['id'] . "'>" . $regul['mode_reglement'] . "</option>";
        }
        $this->page = str_replace('{reglement}', $regulations, $this->page);
        $groupAdherent = "";
        foreach ($listAdherentGroup as $adh) {
            $selected = "";
            if ($adherent['id_groupeAdherent'] == $adh['id']) {
                $selected = "selected";
            }
            $groupAdherent .= "<option $selected value='" . $adh['id'] . "'>" . $adh['nomGroup'] . "</option>";
        }
        $this->page = str_replace('{groupeAdherent}', $groupAdherent, $this->page);
        $this->page = str_replace('{photo}', $adherent['avatar'], $this->page);
        $this->displayPage();
    }

    /**
     * Affichage du formulaire contenant l'information adhérent à modifier - cas du membre connecté
     *
     * @param [type] $adherent, $listFunctions, ,$listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay
     * @return void
     */
    public function updateFormOnlyMember($adherent, $listFunctions, $listStatuts, $listRegulations, $listCotisations, $listJobs, $associationDisplay)
    {
        $this->page .= file_get_contents('template/formOnlyMember.html');
        $this->page = str_replace('{titreFormulaire}', 'Modifier votre profil', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{fa-plus}', 'pres fas fa-user-edit', $this->page);
        $this->page = str_replace('{header-css}', 'user-edit', $this->page);
        $this->page = str_replace('{activeAdd}', '', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logo}', $associationDisplay['logo'], $this->page);
        }
        $this->page = str_replace('{action}', 'updateDBOnlyMember&id=' . $adherent['id_adherent'] . '', $this->page);
        $this->page = str_replace('{id}', $adherent['id_adherent'], $this->page);
        $this->page = str_replace('{inputID}', 'hidden', $this->page);
        $this->page = str_replace('{resetButtonForUpdate}', 'hidden', $this->page);
        $this->page = str_replace('{hiddenCheckedBox}', 'hidden', $this->page);
        $this->page = str_replace('{nom}', $adherent['nom'], $this->page);
        $this->page = str_replace('{prenom}', $adherent['prenom'], $this->page);
        $this->page = str_replace('{email}', $adherent['email'], $this->page);
        $this->page = str_replace('{tel}', '+33' . $adherent['telephone'], $this->page);
        $sexe = "";
        $genre = [
            'M' => 'Masculin',
            'F' => 'Féminin',
        ];
        foreach ($genre as $cle => $gender) {
            $selected = "";
            if ($adherent['sexe'] == $cle) {
                $selected = "selected";
            }
            $sexe .= "<option $selected value='" . $cle . "'>" . $gender . "</option>";
        }
        $this->page = str_replace('{sexe}', $sexe, $this->page);
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
            $selected = "";
            if ($adherent['degree'] == $lvl) {
                $selected = "selected";
            }
            $degrees .= "<option $selected value'" . $niveau . "'>" . $lvl . "</option>";
        }
        $this->page = str_replace('{degrees}', $degrees, $this->page);
        $jobs = "";
        foreach ($listJobs as $job) {
            $selected = "";
            if ($adherent['id_jobs'] == $job['id']) {
                $selected = "selected";
            }
            $jobs .= "<option $selected value='" . $job['id'] . "'>" . $job['name'] . "</option>";
        }
        $this->page = str_replace('{job}', $jobs, $this->page);
        $family = "";
        foreach ($listJobs as $job) {
            if ($adherent['id_jobs'] == $job['id']) {
                $family = $job['family'];
            }
        }
        $this->page = str_replace('{family}', $family, $this->page);
        $this->page = str_replace('{adresse}', $adherent['adresse'], $this->page);
        $this->page = str_replace('{codepostal}', $adherent['CP'], $this->page);
        $this->page = str_replace('{ville}', $adherent['ville'], $this->page);
        $this->page = str_replace('{photo}', $adherent['avatar'], $this->page);
        $this->displayPage();
    }

    public function prepareToSend($memberSelect, $associationDisplay)
    {
        $this->page .= file_get_contents('template/formSendMail.html');
        $this->page = str_replace('{titreFormulaire}', 'Envoyer un mail', $this->page);
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{fa-paper-plane}', 'pres fas fa-paper-plane pink', $this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers', $this->page);
        $this->page = str_replace('{action}', 'index.php?controller=adherent&action=sendMail', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}', $associationDisplay['logo'], $this->page);
        }
        $this->page = str_replace('{memberOrDonatorSelected}', 'Membre(s)', $this->page);
        $contentUserSelected = "";
        foreach ($memberSelect as $selected) {
            $contentUserSelected .= "<div class='blockContentUserSelected'><span class='iconUserSelected align-self-center'><i class='fas fa-check'></i></span>"
                . "<span class='firstnameUserSelected align-self-center'>" . $selected['prenom'] . "</span>"
                . "<span class='nameUserSelected align-self-center'>" . $selected['nom'] . "</span>"
                . "<span class='emailUserSelected align-self-center'>" . "(" . $selected['email'] . ")" . "</span>"
                . "</div>";
        }
        $mailDestinataire = "";
        foreach ($memberSelect as $destinataire) {
            $mailDestinataire .= "<input type='hidden' name='destinataire[]' value='" . $destinataire['prenom'] . "|" . htmlspecialchars($destinataire['nom'], ENT_QUOTES) . "|" . $destinataire['email'] . "'>";
        }
        $this->page = str_replace('{destinataire}', $mailDestinataire, $this->page);
        $this->page = str_replace('{contentUserSelected}', $contentUserSelected, $this->page);
        $this->page = str_replace('{associationEmail}', $associationDisplay['email'], $this->page);
        $this->page = str_replace('{subjectMail}', '', $this->page);
        $associationAbstract = "";
        $associationAbstract .= "<input type='hidden' name='association' value='" . $associationDisplay['nom'] . "|" . $associationDisplay['prenomSign'] . "|" . $associationDisplay['nomSign'] . "|" . $associationDisplay['fonctionSign'] . "|" . $associationDisplay['logo'] . "'>";
        $this->page = str_replace('{associationDisplay}', $associationAbstract, $this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage des anciens adhérents
     *
     * @param [type] $listOldAdherents
     * @param [type] $associationDisplay
     * @return void
     */
    public function showOldMember($listOldAdherents, $associationDisplay)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListAdherent.html');
        $this->page = str_replace('{titreFormulaire}', 'Liste des anciens adhérents', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
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
        $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
        <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>", $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{fa-plus}', 'pres fas fa-parachute-box', $this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}', $associationDisplay['logo'], $this->page);
        }

        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<h2 class='text-center mt-4 mb-4'>Liste des anciens adhérents</h2>";
        // début formulaire
        $this->page .= "<form action='index.php?controller=adherent&action=prepareToSend' method='POST'>";
        // boutons d'action
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                        . "<div class='p-2 bd-highlight sendMessage'>"
                        . "<button type='submit' class='btn btn-grey'><i class='fas fa-paper-plane'></i> Envoyer un message</button>"
                        . "</div>"
                        . "</div>";
        }
        // tableau
        $this->page .= "<table id='tableOldMembers' class='table table-sm'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // checkbox générale entête tableau
        $this->page .= "<th scope='col' class='align-middle'>";
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<label class='checkbox' for='searchTableCheckbox'>"
                        . "<input id='searchTableCheckbox' type='checkbox' class='custom-checkbox'>"
                        . "<span class='icons'>"
                        . "<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                        . "</span>";
        }
        $this->page .= "<span>Nom</span>"
                    . "</label></th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Adresse</th>";
        $this->page .= "<th scope='col' class='align-middle'>Téléphone</th>";
        $this->page .= "<th scope='col' class='align-middle'>Email</th>";
        $this->page .= "<th scope='col' id='thActif' class='text-center align-middle'>Actif</th>";
        if ($_SESSION['user']['id_roles'] == "1") {
            $this->page .= "<th scope='col' class='text-center align-middle'>Lire</th>";
        }
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='oldMembersTable'>";
        foreach ($listOldAdherents as $oldAdherents) {
            $actif = "<td id='tdActif' class='text-center text-danger align-middle'><i class='fas fa-circle'></i></td>";
            $this->page .= "<tr>"
                        . "<td data-label='Nom' scope='row' class='align-middle'>";
            if ($_SESSION['user']['id_roles'] == "1") {
                $this->page .= "<label class='checkbox' for='searchTable_input_checkbox" . $oldAdherents['id_adherent'] . "' id='searchTable_label" . $oldAdherents['id_adherent'] . "'>"
                            . "<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox" . $oldAdherents['id_adherent'] . "' value='" . $oldAdherents['id_adherent'] . "' class='custom-checkbox'>"
                            . "<span class='icons'>"
                            . "<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                            . "</span>";
            }
            $this->page .= "<span class='font-weight-bold'>" . $oldAdherents['nom'] . "</span>"
                        . "</label>"
                        . "</td>"
                        . "<td data-label='Date' class='align-middle'>" . $oldAdherents['prenom'] . "</td>"
                        . "<td data-label='Date' class='align-middle'>" . $oldAdherents['adresse'] ." " . $oldAdherents['CP'] . " " . $oldAdherents['ville'] . "</td>"
                        . "<td data-label='Date' class='align-middle'>" . $oldAdherents['telephone'] . "</td>"
                        . "<td data-label='Date' class='align-middle'>" . $oldAdherents['email'] . "</td>"
                        . $actif;
                        if ($_SESSION['user']['id_roles'] == "1") {
                            $this->page .= "<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=" . $oldAdherents['id_adherent']
                                . "' class='btn btn-success ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>";
                        }
                        $this->page .= "</tr>";
        }
        // fin corps du tableau
        $this->page .= "</tbody>";
        // fin tableau
        $this->page .= "</table>";
        // fin formulaire
        $this->page .= "</form>";
        // fin container
        $this->page .= "</div>";
        // popup
        $this->page .= "<div id='popup'>"
                    . "<div id='contenuPop'>Veuillez sélectionner au moins une personne.</div>"
                    . "<div class='buttonpane'><div class='buttonSet'>Fermer</div></div>"
                    . "</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Fonction affichage page radiation adhérent
     *
     * @param [type] $adherent
     * @param [type] $associationDisplay
     * @return void
     */
    public function cancelledMemberForm($associationDisplay, $adherent)
    {
        $this->page .= file_get_contents('template/formCancelled.html');
        $this->page = str_replace('{titreFormulaire}', 'Radier un adhérent', $this->page);
        $this->page = str_replace('{action}', 'cancelledMember&id=' . $adherent['id_adherent'] . '', $this->page);
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
        }
        $this->page = str_replace('{fa-plus}', 'pres fas fa-eject', $this->page);
        $this->page = str_replace('{header-css}', 'user-edit', $this->page);
        $this->page = str_replace('{activeAdd}', '', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logo}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logo}', $associationDisplay['logo'], $this->page);
        }
        $this->page = str_replace('{activeAdd}', 'class="active"', $this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=adherent&action=modal&id='.$adherent['id_adherent'].'', $this->page);
        $this->page = str_replace('{dateSortie}', $adherent['date_sortie'], $this->page);
        $this->displayPage();
    }

    /**
     * Fonction affichage page connexion adherent ou user
     *
     * @param [type] $adherent
     * @param [type] $associationDisplay
     * @return void
     */
    public function modalConnexion($adherent, $associationDisplay)
    {
        $this->page .= file_get_contents('template/detailConnexion.html');
        $this->page = str_replace('{header-css}', 'detail', $this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start', $this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles', $this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout', $this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id=' . $_SESSION['user']['id'] . '', $this->page);
        } else {
            $this->page = str_replace('{account}', '#', $this->page);
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
            $this->page = str_replace('{liAddGroup}', "<li><a data-dl-view='true' data-dl-title='Gérer vos groupes' href='{actionAddGroupMember}'>
            <span class='icon-container'><i class='fas fa-hotel'></i></span><span class='text item'> Gérer vos groupes </span></a></li>", $this->page);
        } else {
            $this->page = str_replace('{hiddenSearchField}', "hidden", $this->page);
            $this->page = str_replace('{liParamsAsso}', "", $this->page);
            $this->page = str_replace('{liAddMembers}', "", $this->page);
            $this->page = str_replace('{liAddUsers}', "", $this->page);
            $this->page = str_replace('{liCreateCampaign}', "", $this->page);
            $this->page = str_replace('{liAddDonations}', "", $this->page);
            $this->page = str_replace('{liAddGroup}', "", $this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm', $this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start', $this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start', $this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start', $this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start', $this->page);
        $this->page = str_replace('{actionAddGroupMember}', 'index.php?controller=group&action=listFiles', $this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg', $this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}', $associationDisplay['logo'], $this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-" . $type . " text-center mb-2 font-m'>" . $content . "</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message, $this->page);
        /** Modification new template */
        $backgroundImage = "";
        $background = $adherent['background'];
        if ($background == NULL) {
            $backgroundImage = "img/cover-00.jpg";
        } else {
            $backgroundImage = $background;
        }
        $this->page = str_replace('{backgroundImage}', $backgroundImage, $this->page);
        $this->page = str_replace('{action}', 'addBG', $this->page);
        $this->page = str_replace('{connexion}', 'index.php?controller=adherent&action=connexionForm&id='.$adherent['id_adherent'].'', $this->page);
        $this->page = str_replace('{detailAdherentNew}', 'index.php?controller=adherent&action=modal&id='.$adherent['id_adherent'].'', $this->page);
        $this->page = str_replace('{actionChangePw}', 'changePW&id='.$adherent['id_adherent'].'', $this->page);

        if ($adherent['id_qualite'] == '1') {
            $this->page = str_replace('{labelFunctionAssociation}', 'Fonction :', $this->page);
            $this->page = str_replace('{functionAssociation}', $adherent['fonction'], $this->page);
            $this->page = str_replace('{statutAssociation}', $adherent['statut'], $this->page);
            $this->page = str_replace('{actionBack}', 'index.php?controller=adherent&action=start', $this->page);
            $this->page = str_replace('{labeDeleteUserOrAdherent}', 'Supprimer l\'adhérent', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSendUser}', '', $this->page);
            $this->page = str_replace('{actionSendReceiptMember}', 'index.php?controller=adherent&action=pdfReceipt&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{detailUserOrAdherent}', 'Détails de l\'adhérent', $this->page);
        } else {
            $this->page = str_replace('{labelFunctionAssociation}', 'Groupe :', $this->page);
            $this->page = str_replace('{functionAssociation}', $adherent['nom_groupe'], $this->page);
            $this->page = str_replace('{statutAssociation}', 'Utilisateur', $this->page);
            $this->page = str_replace('{actionBack}', 'index.php?controller=user&action=start', $this->page);
            $this->page = str_replace('{labeDeleteUserOrAdherent}', 'Supprimer l\'utilisateur', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSendUser}', 'hidden', $this->page);
            $this->page = str_replace('{actionSendReceiptMember}', '', $this->page);
            $this->page = str_replace('{detailUserOrAdherent}', 'Détails de l\'utilisateur', $this->page);
        }
        if ($adherent['id_roles'] == '1' && $adherent['id_qualite'] == '1') {
            $this->page = str_replace('{detail-role}', 'Administrateur', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-admin.png" alt="Administrateur" title="Administrateur" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-admin.png" alt="Administrateur" title="Administrateur" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A tous les droits dont celui d\'ajouter les autres administrateurs.<br>Gère l\'enregistrement des adhésions et dons.<br>Il peut ajouter, modifier ou supprimer les éléments dans l\'application.', $this->page);
        } elseif ($adherent['id_roles'] == '2' && $adherent['id_qualite'] == '1') {
            $this->page = str_replace('{detail-role}', 'Adhérent', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-member.png" alt="Adhérent" title="Adhérent" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-member.png" alt="Adhérent" title="Adhérent" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A le droit de voir et de modifier son profil.<br>A le droit de consulter ou télécharger des documents.<br>L\'adhérent de l\'association est membre de celle-ci et a payé une cotisation pour adhérer.', $this->page);
        } else {
            $this->page = str_replace('{detail-role}', 'Utilisateur', $this->page);
            // $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-user.png" alt="Utilisateur" title="Utilisateur" class="mt-5 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{imgBadgeUserOrAdherent}', '<img src="img/rocket-user.png" alt="Utilisateur" title="Utilisateur" class="mt-1 mb-2 icon-rocket">', $this->page);
            $this->page = str_replace('{text-role}', 'A le droit de voir et de modifier son profil.<br>A le droit de consulter ou télécharger des documents.<br>L\'utilisateur ne participe ni au fonctionnement de l\'association ni aux délibérations.', $this->page);
        }
        $this->page = str_replace('{detail-name}', $adherent['prenom'] . ' ' . $adherent['nom'], $this->page);
        $this->page = str_replace('{photo}', $adherent['avatar'], $this->page);
        $this->page = str_replace('{nom}', $adherent['nom'], $this->page);
        $this->page = str_replace('{prenom}', $adherent['prenom'], $this->page);

        if ($_SESSION['user']['id_roles'] == "1" && $adherent['id_qualite'] == "1") {
            $this->page = str_replace('{hiddenButtonSuppMember}', '', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', '', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', '', $this->page);
            // $this->page = str_replace('{cssActionBackAndActionSend}', 'class="d-flex justify-content-start mb-0"', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', '', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier l\'adhérent', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateForm&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=adherent&action=suppDB&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{cancelled}', 'index.php?controller=adherent&action=cancelledMemberForm&id=' . $adherent['id_adherent'] . '', $this->page);
        } elseif ($_SESSION['user']['id_roles'] == "1" && $adherent['id_qualite'] == "2") {
            $this->page = str_replace('{hiddenButtonSuppMember}', '', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', '', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', '', $this->page);
            // $this->page = str_replace('{cssActionBackAndActionSend}', 'class="d-flex justify-content-start mb-0"', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', '', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier l\'utilisateur', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateForm&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', 'index.php?controller=user&action=suppDBUser&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{cancelled}', 'index.php?controller=user&action=cancelledUser&id=' . $adherent['id_adherent'] . '', $this->page);
        } else {
            $this->page = str_replace('{hiddenButtonSuppMember}', 'hidden', $this->page);
            $this->page = str_replace('{hiddenButtonDropdown}', 'hidden', $this->page);
            $this->page = str_replace('{hiddenButtonCancelledMember}', 'hidden', $this->page);
            $this->page = str_replace('{cssActionBackAndActionSend}', 'hidden', $this->page);
            $this->page = str_replace('{titleUpdateMember}', 'Modifier votre profil', $this->page);
            $this->page = str_replace('{update}', 'index.php?controller=adherent&action=updateFormOnlyMember&id=' . $adherent['id_adherent'] . '', $this->page);
            $this->page = str_replace('{delete}', '', $this->page);
        }
        $this->displayPage();
    }
}
