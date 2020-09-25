<?php

class DonsView extends View {

    /**
     * Affichage de la page Dons
     *
     * @param [array] $listDons,$associationDisplay,$listRegulations
     * @return void
     */
    public function displayHome($listDons,$associationDisplay,$listRegulations)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListDonation.html');
        $this->page = str_replace('{titreFormulaire}','Gérer vos dons',$this->page);
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
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-gifts',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $regulation = "";
        foreach ($listRegulations as $regulations) {
            $regulation .= "<option value='" . $regulations['id'] . "'>" . $regulations['mode_reglement'] ."</option>";
        }
        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<div id='templatePageHeader'>"
                    ."<div id='templateTitle'>Bienvenue sur la page des Dons</div>"
                    ."</div>";
        // nav tabulations haut
        $this->page .= "<div id='templateTabulationWrapper'>"
                    ."<div id='templateTabulation'>"
                    ."<a id='tabFormulaire' href='#' class='active'>Donateurs</a>"
                    ."<a id='tabDonateurs' href='index.php?controller=dons&action=taxReceipt'>Reçus Fiscaux</a>"
                    ."</div>"
                    ."<div id='separateTopDonation'></div>"
                    ."</div>";
        // section recherche
        $this->page .= "<div id='searchContainer'>"
                    ."<div class='col-lg-6 col-md-6 col-sm-12'>"
                    ."<div id='searchWrapper'><label id='searchLabel' for='search'>Recherche</label><input type='text' id='searchDonator' name='search' class='form-control' placeholder='Rechercher un donateur'></div>"
                    ."</div>"
                    ."<div class='col-lg-6 col-md-6 col-sm-12'>"
                    ."<div id='filterWrapper'><label id='filterLabel' for='methodFilter'>Filtrer par moyen de paiement</label><select id='methodFilter' name='methodFilter' class='form-control'><option selected='selected'></option>{reglement}</select></div>"
                    ."</div>"
                    ."</div>";
        $this->page = str_replace('{reglement}', $regulation,$this->page);
        // bouton envoi CSV
        $this->page .= "<div class='hide-on-csv bd-highlight'>"
                    ."<div class='revealCsv-on-hover'><button type='button' class='btn buttonXls'><i class='fas fa-file-excel'></i><img src='img/arrow-select.png' class='icoXls align-middle'></button></div>"
                    ."<div class='revealCsv-on-click'>"
                    ."<h2>Exporter vers Excel</h2>"
                    ."<div class='xlsContent'>"
                    ."<p>Vous allez exporter le tableau en fichier CSV, lisible sous Excel</p>"
                    ."<p>Une fois ouvert sous Excel, vous pouvez l'enregistrer sous format xls ou xlsx</p>"
                    ."<a href='index.php?controller=dons&action=export_data_to_csv'><button ttpe='button' class='btn btn-primary align-middle text-center exportBtn'><i class='fad fa-file-export'></i> Exporter</button></a>"
                    ."</div>"
                    ."</div>"
                    ."</div>";
        // début formulaire
        $this->page .= "<form action='index.php?controller=dons&action=prepareToSend' method='POST'>";
        // boutons d'action
        $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                    ."<span class='tableDonatorLinesNb p-2 bd-highlight'>3 ligne(s).</span>"
                    ."<div class='p-2 bd-highlight sendMessage'>"
                    ."<button type='submit' class='btn btn-grey' type='submit'><i class='fas fa-paper-plane'></i> Envoyer un message</button>"
                    ."</div>"
                    // ."<div class='p-2 bd-highlight sendMessage'><button type='button' class='btn btn-grey'><i class='fas fa-paper-plane'></i> Envoyer un message</button></div>"
                    ."<div class='p-2 bd-highlight addDonate'><a href='index.php?controller=dons&action=addForm'><button type='button' class='btn btn-primary'><i class='fas fa-plus-circle'></i> Ajouter un donateur</button></a></div>"
                    ."</div>";
        // tableau
        $this->page .= "<table id='tableDonator' class='table table-respons'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // checkbox générale entête tableau
        $this->page .= "<th scope='col' class='align-middle'>"
                    ."<label class='checkbox' for='searchTableCheckbox'>"
                    ."<input id='searchTableCheckbox' type='checkbox' class='custom-checkbox'>"
                    ."<span class='icons'>"
                    ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                    ."</span>"
                    ."<span>N° don</span>"
                    ."</label></th>";
                    // ."N° de don<a href='#'><i class='fa fa-sort ml-1'></i></a></th>";
        $this->page .= "<th scope='col' class='align-middle'>Date</th>";
        $this->page .= "<th scope='col' class='align-middle'>Montant</th>";
        $this->page .= "<th scope='col' class='align-middle'>Nom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Réglement</th>";
        $this->page .= "<th scope='col' class='align-middle'>Détail</th>";
        // $this->page .= "<th scope='col' class='text-center align-middle'>Supprimer</th>";
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='DonatorsTable'>";
        foreach ($listDons as $dons) {
            $this->page .= "<tr>"
                        ."<td data-label='N° de don' scope='row' class='align-middle'>"
                        ."<label class='checkbox' for='searchTable_input_checkbox".$dons['id_don']."' id='searchTable_label".$dons['id_don']."'>"
                        ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$dons['id_don']."' value='".$dons['id_don']."' class='custom-checkbox'>"
                        ."<span class='icons'>"
                        ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                        ."</span>"
                        ."<span class='font-weight-normal'>".$dons['numDon']."</span>"
                        ."</label>"
                        ."</td>"
                        // ."<td data-label='N° de don' scope='row' class='align-middle'>".$dons['numDon']."</td>"
                        ."<td data-label='Date' class='align-middle'>".date("d-m-Y", strtotime($dons['date_don']))."</td>"
                        ."<td data-label='Montant' class='align-middle'>".number_format($dons['montant_don'], 2, ',', '')." €</td>"
                        ."<td data-label='Nom' class='align-middle'>".$dons['nom']."</td>"
                        ."<td data-label='Prénom' class='align-middle'>".$dons['prenom']."</td>"
                        ."<td data-label='Réglement' class='align-middle'>".$dons['mode_reglement']."</td>"
                        ."<td data-label='Détail' class='align-middle'><a href='index.php?controller=dons&action=updateForm&id=".$dons['id_don']
                        ."' class='btn btn-warning ml-auto mr-3'><i class='fad fa-eye'></i></a></td>"
                        // ."<td class='text-center'><a href='index.php?controller=dons&action=suppDB&id="
                        // .$dons['id_don']
                        // ."' class='btn btn-danger'><i class='fas fa-trash'></i></a></td>"
                        ."</tr>";
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
                    ."<div id='contenuPop'>Veuillez sélectionner au moins une personne.</div>"
                    ."<div class='buttonpane'><div class='buttonSet'>Fermer</div></div>"
                    ."</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Fonction affichage des membres/donateurs sélectionnés avant envoi du mail
     *
     * @param [type] $donatorSelect
     * @param [type] $associationDisplay
     * @return void
     */
    public function prepareToSend($donatorSelect,$associationDisplay)
    {
        $this->page .= file_get_contents('template/formSendMail.html');
        $this->page = str_replace('{titreFormulaire}','Envoyer un mail',$this->page);
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
        $this->page = str_replace('{fa-paper-plane}', 'pres fas fa-paper-plane',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        // $this->page = str_replace('{action}', 'traitement.php',$this->page);
        $this->page = str_replace('{action}', 'index.php?controller=dons&action=sendMail',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{memberOrDonatorSelected}', 'Donateur(s)',$this->page);
        $contentUserSelected = "";
        foreach ($donatorSelect as $selected) {
            $contentUserSelected .= "<div class='blockContentUserSelected'><span class='iconUserSelected align-self-center'><i class='fas fa-check'></i></span>"
                                 ."<span class='firstnameUserSelected align-self-center'>".$selected['prenom']."</span>"
                                 ."<span class='nameUserSelected align-self-center'>".$selected['nom']."</span>"
                                 ."<span class='emailUserSelected align-self-center'>"."(".$selected['email'].")"."</span>"
                                 ."</div>";
        }
        $mailDestinataire = "";
        foreach($donatorSelect as $destinataire){
            $mailDestinataire .= "<input type='hidden' name='destinataire[]' value='".$destinataire['prenom']."|".htmlspecialchars($destinataire['nom'], ENT_QUOTES)."|".$destinataire['email']."'>";
        }
        $this->page = str_replace('{destinataire}',$mailDestinataire,$this->page);
        $this->page = str_replace('{contentUserSelected}',$contentUserSelected,$this->page);
        $this->page = str_replace('{associationEmail}',$associationDisplay['email'],$this->page);
        $this->page = str_replace('{subjectMail}', '',$this->page);
        $associationAbstract = "";
        $associationAbstract .= "<input type='hidden' name='association' value='".$associationDisplay['nom']."|".$associationDisplay['prenomSign']."|".$associationDisplay['nomSign']."|".$associationDisplay['fonctionSign']."|".$associationDisplay['logo']."'>";
        $this->page = str_replace('{associationDisplay}',$associationAbstract,$this->page);
        $this->displayPage();
    }

    /**
     * Affichage du formulaire de saisie d'un nouveau donateur
     *
     * @param [type] $listRegulations
     * @param [type] $associationDisplay
     * @return void
     */
    public function addForm($listRegulations,$associationDisplay)
    {
        $this->page .= file_get_contents('template/formDons.html');
        $this->page = str_replace('{titreFormulaire}','Ajouter un donateur',$this->page);
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
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-gifts',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','addDB',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionAddAdherent}','index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}', '',$this->page);
        $this->page = str_replace('{hiddenSendReceiptDonator}','hidden',$this->page);
        $this->page = str_replace('{hiddenButtonDelDonator}','hidden',$this->page);
        $this->page = str_replace('{hiddenCheckboxNotification}','',$this->page);
        $this->page = str_replace('{searchMemberForDonationHidden}','',$this->page);
        $this->page = str_replace('{hiddenDonatorSelectedForChange}','hidden',$this->page);
        $this->page = str_replace('{hiddenHelperForForm}','',$this->page);
        $this->page = str_replace('{readonlyDonatorSelectedForChange}', 'readonly',$this->page);
        $this->page = str_replace('{dateDons}','',$this->page);
        $this->page = str_replace('{readonlyDateDons}', '',$this->page);
        $this->page = str_replace('{montant}','',$this->page);
        $regulation = "";
        foreach ($listRegulations as $regulations) {
            $regulation .= "<option value='" . $regulations['id'] . "'>" . $regulations['mode_reglement'] ."</option>";
        }
        $this->page = str_replace('{reglement}', $regulation,$this->page);
        $this->page = str_replace('{valueButtonSubmitDonator}', 'Valider',$this->page);
        $this->displayPage();
    }

    /**
     * Affichage du formulaire contenant l'information à modifier
     *
     * @param [type] $don, $listRegulations, $associationDisplay
     * @return void
     */
    public function updateForm($don,$listRegulations,$associationDisplay){
        $this->page .= file_get_contents('template/formDons.html');
        $this->page = str_replace('{titreFormulaire}','Modifier un donateur',$this->page);
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
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-gifts',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','updateDB',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionDelDonator}', 'index.php?controller=dons&action=suppDB&id='.$don['id_don'].'',$this->page);
        $this->page = str_replace('{actionAddAdherent}','index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionSendReceiptDonator}','index.php?controller=dons&action=pdfReceipt&id='.$don['id_don'].'',$this->page);
        $this->page = str_replace('{hiddenInputId}','hidden',$this->page);
        $this->page = str_replace('{id}',$don['id_don'],$this->page);
        $this->page = str_replace('{hiddenSendReceiptDonator}','',$this->page);
        $this->page = str_replace('{hiddenButtonDelDonator}','',$this->page);
        $this->page = str_replace('{hiddenCheckboxNotification}','hidden',$this->page);
        $this->page = str_replace('{searchMemberForDonationHidden}','hidden',$this->page);
        $this->page = str_replace('{hiddenDonatorSelectedForChange}','',$this->page);
        $this->page = str_replace('{hiddenHelperForForm}','hidden',$this->page);
        $DonatorSelectedForChange = $don['prenom']." ".$don['nom']." (".$don['email'].")";
        $this->page = str_replace('{DonatorSelectedForChange}',$DonatorSelectedForChange,$this->page);
        $this->page = str_replace('{readonlyDonatorSelectedForChange}', 'readonly',$this->page);
        $this->page = str_replace('{montant}',$don['montant_don'],$this->page);
        $this->page = str_replace('{dateDons}',$don['date_don'],$this->page);
        $this->page = str_replace('{readonlyDateDons}', 'readonly',$this->page);
        $regulation = "";
        foreach ($listRegulations as $regulations) {
            $selected = "";
            if ($don['id_reglement'] == $regulations['id']){
                $selected = "selected";
            }
            $regulation .= "<option $selected value='" . $regulations['id'] . "'>".$regulations['mode_reglement']."</option>";
        }
        $this->page = str_replace('{reglement}', $regulation,$this->page);
        $this->page = str_replace('{valueButtonSubmitDonator}', 'Modifier',$this->page);
        $this->displayPage();
    }

    /**
     * Fonction permettant l'affichage du tableau des donateurs pour les reçus fiscaux
     *
     * @param [type] $listDons
     * @param [type] $associationDisplay
     * @param [type] $listTaxReceipt
     * @return void
     */
    public function taxReceipt($listDons,$associationDisplay,$listTaxReceipt) {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListDonation.html');
        $this->page = str_replace('{titreFormulaire}','Reçus fiscaux',$this->page);
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
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-gifts',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $taxReceipt = "";
        foreach ($listTaxReceipt as $receipt) {
            $taxReceipt .= "<option value='" . $receipt['id'] . "'>" . $receipt['type'] ."</option>";
        }
        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<div id='templatePageHeader'>"
                    ."<div id='templateTitle'>Bienvenue sur la page des reçus fiscaux</div>"
                    ."</div>";
        // nav tabulations haut
        $this->page .= "<div id='templateTabulationWrapper'>"
        ."<div id='templateTabulation'>"
        ."<a id='tabFormulaire' href='index.php?controller=dons&action=start'>Donateurs</a>"
        ."<a id='tabDonateurs' href='index.php?controller=dons&action=taxReceipt' class='active'>Reçus Fiscaux</a>"
        ."</div>"
        ."<div id='separateTopDonation'></div>"
        ."</div>";
        // section recherche
        $this->page .= "<div id='searchContainer'>"
                    ."<div class='col-lg-6 col-md-6 col-sm-12'>"
                    ."<div id='searchWrapper'><label id='searchLabel' for='search'>Recherche</label><input type='text' id='searchDonator' name='search' class='form-control' placeholder='Rechercher un donateur'></div>"
                    ."</div>"
                    ."<div class='col-lg-6 col-md-6 col-sm-12'>"
                    ."<div id='filterWrapper'><label id='filterLabel' for='methodFilter'>Filtrer par reçus fiscal</label><select id='methodFilter' name='methodFilter' class='form-control'><option selected='selected'></option>{taxReceipt}</select></div>"
                    ."</div>"
                    ."</div>";
        $this->page = str_replace('{taxReceipt}', $taxReceipt,$this->page);
        // début formulaire
        $this->page .= "<form action='index.php?controller=dons&action=prepareToSendMassTaxReceipt' method='POST'>";
        // boutons d'action
        $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                    ."<span class='tableLinesNb p-2 bd-highlight'>3 ligne(s).</span>"
                    ."<div class='p-2 bd-highlight sendMessage'>"
                    ."<button type='submit' class='btn btn-grey' type='submit'><i class='fas fa-paper-plane'></i> Envoyer les reçus en masse</button>"
                    ."</div>"
                    ."</div>";
        // tableau
        $this->page .= "<table id='tableDonator' class='table table-respons'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // checkbox générale entête tableau
        $this->page .= "<th scope='col' class='align-middle'>"
                    ."<label class='checkbox' for='searchTableCheckbox'>"
                    ."<input id='searchTableCheckbox' type='checkbox' class='custom-checkbox'>"
                    ."<span class='icons'>"
                    ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                    ."</span>"
                    ."<span>N° don</span>"
                    ."</label></th>";
        // ."N° de don<a href='#'><i class='fa fa-sort ml-1'></i></a></th>";
        $this->page .= "<th scope='col' class='align-middle'>Nom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' class='align-middle'>Etat</th>";
        $this->page .= "<th scope='col' class='align-middle text-center'>Reçu Fiscal</th>";
        // $this->page .= "<th scope='col' class='text-center align-middle'>Supprimer</th>";
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='DonatorsTable'>";
        foreach ($listDons as $dons) {
            $this->page .= "<tr>"
                        ."<td data-label='N° de don' scope='row' class='align-middle'>"
                        ."<label class='checkbox' for='searchTable_input_checkbox".$dons['id_don']."' id='searchTable_label".$dons['id_don']."'>"
                        ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$dons['id_don']."' value='".$dons['id_don']."' class='custom-checkbox'>"
                        ."<span class='icons'>"
                        ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                        ."</span>"
                        ."<span class='font-weight-normal'>".$dons['numDon']."</span>"
                        ."</label>"
                        ."</td>"
                        ."<td data-label='Nom' class='align-middle'>".$dons['nom']."</td>"
                        ."<td data-label='Prénom' class='align-middle'>".$dons['prenom']."</td>"
                        ."<td data-label='Réglement' class='align-middle'>".$dons['type']."</td>"
                        ."<td data-label='Détail' class='align-middle text-center'><a href='index.php?controller=dons&action=pdfTaxReceipt&id=".$dons['id_don']
                        ."' class='btn btn-info ml-auto mr-3'><i class='fas fa-piggy-bank'></i></a></td>"
                        ."</tr>";
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
                    ."<div id='contenuPop'>Veuillez sélectionner au moins une personne.</div>"
                    ."<div class='buttonpane'><div class='buttonSet'>Fermer</div></div>"
                    ."</div>";
        $this->page .= "</main>";
        $this->displayPage();
    }

    public function prepareToSendMassTaxReceipt($donatorSelect,$associationDisplay)
    {
        $this->page .= file_get_contents('template/formSendMassTaxReceipt.html');
        $this->page = str_replace('{titreFormulaire}','Envoyer en masse les reçus fiscaux',$this->page);
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
        $this->page = str_replace('{fa-paper-plane}', 'pres fas fa-paper-plane',$this->page);
        $this->page = str_replace('{header-css}', 'params-assoc',$this->page);
        $this->page = str_replace('{action}', 'index.php?controller=dons&action=sendMailMassTaxReceipt',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=dons&action=taxReceipt',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{memberOrDonatorSelected}', 'Donateur(s)',$this->page);
        $contentUserSelected = "";
        foreach ($donatorSelect as $selected) {
            $contentUserSelected .= "<div class='blockContentUserSelected'><span class='iconUserSelected align-self-center'><i class='fas fa-check'></i></span>"
                                 ."<span class='firstnameUserSelected align-self-center'>".$selected['prenom']."</span>"
                                 ."<span class='nameUserSelected align-self-center'>".$selected['nom']."</span>"
                                 ."<span class='emailUserSelected align-self-center'>"."(".$selected['email'].")"."</span>"
                                 ."</div>";
        }
        $mailDestinataire = "";
        foreach($donatorSelect as $destinataire){
            $mailDestinataire .= "<input type='hidden' name='destinataire[]' value='".$destinataire['id_don']."|".$destinataire['prenom']."|".htmlspecialchars($destinataire['nom'], ENT_QUOTES)."|".htmlspecialchars($destinataire['adresse'], ENT_QUOTES)."|".$destinataire['CP']."|".htmlspecialchars($destinataire['ville'], ENT_QUOTES)."|".$destinataire['email']."|".$destinataire['numDon']."|".$destinataire['montant_don']."|".$destinataire['date_don']."|".$destinataire['mode_reglement']."'>";
        }
        $this->page = str_replace('{destinataire}',$mailDestinataire,$this->page);
        $this->page = str_replace('{contentUserSelected}',$contentUserSelected,$this->page);
        $associationAbstract = "";
        $associationAbstract .= "<input type='hidden' name='association' value='".$associationDisplay['nom']."|".$associationDisplay['prenomSign']."|".$associationDisplay['nomSign']."|".$associationDisplay['fonctionSign']."|".$associationDisplay['logo']."'>";
        $this->page = str_replace('{associationDisplay}',$associationAbstract,$this->page);
        $this->displayPage();
    }

}
