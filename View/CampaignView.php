<?php

class CampaignView extends View {

    /**
     * Affichage du formulaire de création de campagne
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($associationDisplay)
    {
        $this->page .= file_get_contents('template/formCampaign.html');
        $this->page = str_replace('{titreFormulaire}','Campagne d\'adhésion',$this->page);
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
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-globe-europe',$this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers',$this->page);
        $this->page = str_replace('{action}','addDB',$this->page);
        $this->page = str_replace('{actionListCampaigns}', 'index.php?controller=campaign&action=listCampaigns',$this->page);
        $this->page = str_replace('{actionAlertMembers}', 'index.php?controller=campaign&action=listMembersForAlert',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $rate = "";
        $unit = [
            'percent' => '%',
            'currency' => '€',
        ];
        foreach ($unit as $cle => $unit) {
            $rate .= "<option value='" . $cle . "'>" . $unit ."</option>";
        }
        $this->page = str_replace('{rate}', $rate,$this->page);
        $this->displayPage();
    }

    public function displayCampaign($associationDisplay,$listCotisations)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListCampaign.html');
        $this->page = str_replace('{titreFormulaire}','Liste des campagnes d\'adhésion',$this->page);
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionAddUsers}', 'index.php?controller=user&action=start',$this->page);
        $this->page = str_replace('{actionAddDonations}', 'index.php?controller=dons&action=start',$this->page);
        $this->page = str_replace('{actionCreateCampaign}', 'index.php?controller=campaign&action=start',$this->page);
        $this->page = str_replace('{actionReadDocuments}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionCreateNews}', 'index.php?controller=document&action=listFiles',$this->page);
        $this->page = str_replace('{actionAddGroupMembers}', 'index.php?controller=group&action=listFiles',$this->page);
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }        $this->page = str_replace('{fa-gifts}', 'pres fas fa-globe-europe',$this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page .= "<div class='container'>";
        $this->page .= "<div id='templatePageHeader'>"
                    ."<div id='templateTitleCampaign'>Liste des campagnes d'adhésion</div>"
                    ."</div>";
        // nav tabulations haut
        $this->page .= "<div id='templateTabulationWrapper'>"
                    ."<div id='templateTabulationCampaign' class='d-flex flex-wrap'>"
                    ."<a id='tabFormulaire' href='index.php?controller=campaign&action=start'>Créer une campagne</a>"
                    ."<a id='tabDonateurs' href='#' class='active'>Lister les campagnes</a>"
                    ."<a id='tabDonateurs' href='index.php?controller=campaign&action=listMembersForAlert'>Liste de diffusion</a>"
                    ."</div>"
                    ."<div id='separateTopDonation'></div>"
                    ."</div>";
        // bouton envoi CSV
        $this->page .= "<div class='hide-on-csv bd-highlight'>"
                    ."<div class='revealCsv-on-hover'><button type='button' class='btn buttonXls'><i class='fas fa-file-excel'></i><img src='img/arrow-select.png' class='icoXls align-middle'></button></div>"
                    ."<div class='revealCsv-on-click'>"
                    ."<h2>Exporter vers Excel</h2>"
                    ."<div class='xlsContent'>"
                    ."<p>Vous allez exporter le tableau en fichier CSV, lisible sous Excel</p>"
                    ."<p>Une fois ouvert sous Excel, vous pouvez l'enregistrer sous format xls ou xlsx</p>"
                    ."<a href='index.php?controller=campaign&action=export_data_to_csv'><button ttpe='button' class='btn btn-primary align-middle text-center exportBtn'><i class='fad fa-file-export'></i> Exporter</button></a>"
                    ."</div>"
                    ."</div>"
                    ."</div>";
        // tableau
        $this->page .= "<table id='tableListCampaigns' class='table table-respons'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // $this->page .= "<th scope='col' class='align-middle'>Libellé<a href='#'><i class='fa fa-sort ml-1'></i></a></th>";
        $this->page .= "<th scope='col' class='align-middle'>Libellé</th>";
        $this->page .= "<th scope='col' class='align-middle'>Montant Net</th>";
        $this->page .= "<th scope='col' class='align-middle'>Début</th>";
        $this->page .= "<th scope='col' class='align-middle'>Fin</th>";
        $this->page .= "<th scope='col' class='align-middle'>Nb Mois</th>";
        $this->page .= "<th scope='col' class='align-middle'>Remise</th>";
        $this->page .= "<th scope='col' class='align-middle'>Détail</th>";
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='ListCampaignsTable'>";
        foreach ($listCotisations as $cotisations) {
            $dateDebutCotisation = $cotisations['debutDateCotisation'];
            if ($dateDebutCotisation == '0000-00-00') {
                $dateDebutCotisation = "Aucune";
            } else {
                $dateDebutCotisation = date("d-m-Y", strtotime($dateDebutCotisation));
            }
            $dateFinCotisation = $cotisations['finDateCotisation'];
            if ($dateFinCotisation == '0000-00-00') {
                $dateFinCotisation = "Aucune";
            } else {
                $dateFinCotisation = date("d-m-Y", strtotime($dateFinCotisation));
            }
            $montantReduction = $cotisations['montantReduction'];
            if ($montantReduction == '') {
                $montantReduction = "Aucune";
            } else {
                $montantReduction = $cotisations['montantReduction'];
            }
            $this->page .= "<tr>"
                        ."<td data-label='Libelle' scope='row' class='align-middle'>".$cotisations['libelle']."</td>"
                        ."<td data-label='Montant' class='align-middle'>".$cotisations['montant_cotisation']." €</td>"
                        ."<td data-label='DateDebut' class='align-middle'>".$dateDebutCotisation."</td>"
                        ."<td data-label='DateFin' class='align-middle'>".$dateFinCotisation."</td>"
                        ."<td data-label='Mois' class='align-middle'>".$cotisations['dureeMois']."</td>"
                        ."<td data-label='Remise' class='align-middle'>".$montantReduction."</td>"
                        ."<td data-label='Detail' class='align-middle'><a href='index.php?controller=campaign&action=updateForm&id=".$cotisations['id']
                        ."' class='btn btn-warning ml-auto mr-3'><i class='fad fa-eye'></i></a></td>"
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

    /**
     * Affichage du formulaire contenant l'information à modifier
     *
     * @param [type] $cotisation, $associationDisplay
     * @return void
     */
    public function updateForm($cotisation,$associationDisplay){
        $this->page .= file_get_contents('template/formUpdateCampaign.html');
        $this->page = str_replace('{titreFormulaire}','Modifier une campagne',$this->page);
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
        $this->page = str_replace('{header-css}', 'params-listUsers',$this->page);
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-globe-europe',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{action}','updateDB',$this->page);
        $this->page = str_replace('{actionBack}', 'index.php?controller=campaign&action=listCampaigns',$this->page);
        $this->page = str_replace('{actionDelCampaign}', 'index.php?controller=campaign&action=suppDB&id='.$cotisation['id_cotisation'].'',$this->page);
        $this->page = str_replace('{libelleCotisation}',$cotisation['libelle'],$this->page);
        $this->page = str_replace('{montant_cotisation}',$cotisation['montant_cotisation'],$this->page);
        $this->page = str_replace('{montantBrut}',$cotisation['montantBrut'],$this->page);
        $this->page = str_replace('{readonlyTarifCotisation}', 'readonly',$this->page);
        $this->page = str_replace('{id_cotisation}',$cotisation['id_cotisation'],$this->page);
        $this->page = str_replace('{hiddenId}', 'hidden',$this->page);
        if ($cotisation['dureeMois'] == 0) {
            $this->page = str_replace('{checkedDateCampaign}', 'checked',$this->page);
            $this->page = str_replace('{checkedMounthCampaign}', '',$this->page);
            $this->page = str_replace('{campaignStartDate}',$cotisation['debutDateCotisation'],$this->page);
            $this->page = str_replace('{campaignEndDate}',$cotisation['finDateCotisation'],$this->page);
            $this->page = str_replace('{campaignLength}', '',$this->page);
        } else {
            $this->page = str_replace('{checkedDateCampaign}', '',$this->page);
            $this->page = str_replace('{checkedMounthCampaign}', 'checked',$this->page);
            $this->page = str_replace('{campaignStartDate}', '',$this->page);
            $this->page = str_replace('{campaignEndDate}', '',$this->page);
            $this->page = str_replace('{campaignLength}',$cotisation['dureeMois'],$this->page);
        }
        $montantReduction = "";
        $partReduction = "";
        $partLibelle = "";
        $rate = "";
        if (isset($cotisation['montantReduction']) && !empty($cotisation['montantReduction'])) {
            $libelle = $cotisation['libelle'];
            $partLibelle = explode(" - ", $libelle);
            $montantReduction = $cotisation['montantReduction'];
            $partReduction = explode(" ", $montantReduction);
            $unit = [
                'percent' => '%',
                'currency' => '€',
            ];
            foreach ($unit as $cle => $unit) {
                $selected = "";
                if ($partReduction[1] == $unit) {
                    $selected = "selected";
                }
                $rate .= "<option $selected value='" . $cle . "'>" . $unit ."</option>";
            }
            $this->page = str_replace('{displayIfnoReduc}', 'style="display: none;"',$this->page);
            $this->page = str_replace('{displayIfREduc}', 'style="display: block;"',$this->page);
            $this->page = str_replace('{readIfReduction}', $partLibelle[1],$this->page);
            $this->page = str_replace('{valueReduction}', $partReduction[0],$this->page);
            $this->page = str_replace('{rate}', $rate,$this->page);
        } else {
            $unit = [
                'percent' => '%',
                'currency' => '€',
            ];
            foreach ($unit as $cle => $unit) {
                $rate .= "<option value='" . $cle . "'>" . $unit ."</option>";
            }
            $this->page = str_replace('{rate}', $rate,$this->page);
            $this->page = str_replace('{displayIfnoReduc}', 'style="display: block;"',$this->page);
            $this->page = str_replace('{displayIfREduc}', 'style="display: none;"',$this->page);
            $this->page = str_replace('{readIfReduction}', '',$this->page);
            $this->page = str_replace('{valueReduction}', '',$this->page);
            $this->page = str_replace('{rate}', $rate,$this->page);
        }
        $this->page = str_replace('{valueButtonSubmitCampaign}', 'Modifier',$this->page);
        $this->displayPage();
    }

    /**
     * Affichage du tableau contenant les adhérents arrivant à échéance
     *
     * @param [type] $listCotisations
     * @param [type] $associationDisplay
     * @param [type] $listAdherents
     * @return void
     */
    public function listMembersForAlert($listCotisations,$associationDisplay,$listAdherents)
    {

        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/headerListCampaign.html');
        $this->page = str_replace('{titreFormulaire}','Membres arrivant à échéance',$this->page);
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
        $this->page = str_replace('{fa-gifts}', 'pres fas fa-globe-europe',$this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
    //     $regulation = "";
    //     foreach ($listRegulations as $regulations) {
    //         $regulation .= "<option value='" . $regulations['id'] . "'>" . $regulations['mode_reglement'] ."</option>";
    //     }
        // div container
        $this->page .= "<div class='container'>";
        $this->page .= "<div id='templatePageHeader'>"
                    ."<div id='templateTitleCampaign'>Membres arrivant à échéance</div>"
                    ."</div>";
        // nav tabulations haut
        $this->page .= "<div id='templateTabulationWrapper'>"
                    ."<div id='templateTabulationCampaign' class='d-flex flex-wrap'>"
                    ."<a id='tabFormulaire' href='index.php?controller=campaign&action=start'>Créer une campagne</a>"
                    ."<a id='tabDonateurs' href='index.php?controller=campaign&action=listCampaigns'>Lister les campagnes</a>"
                    ."<a id='tabDonateurs' href='index.php?controller=campaign&action=listMembersForAlert' class='active'>Liste de diffusion</a>"
                    ."</div>"
                    ."<div id='separateTopDonation'></div>"
                    ."</div>";
        // bouton envoi CSV
        $this->page .= "<div class='hide-on-csv bd-highlight'>"
                    ."<div class='revealCsv-on-hover'><button type='button' class='btn buttonXls'><i class='fas fa-file-excel'></i><img src='img/arrow-select.png' class='icoXls align-middle'></button></div>"
                    ."<div class='revealCsv-on-click'>"
                    ."<h2>Exporter vers Excel</h2>"
                    ."<div class='xlsContent'>"
                    ."<p>Vous allez exporter le tableau en fichier CSV, lisible sous Excel</p>"
                    ."<p>Une fois ouvert sous Excel, vous pouvez l'enregistrer sous format xls ou xlsx</p>"
                    ."<a href='index.php?controller=campaign&action=export_dataMaturedContribution_to_csv'><button ttpe='button' class='btn btn-primary align-middle text-center exportBtn'><i class='fad fa-file-export'></i> Exporter</button></a>"
                    ."</div>"
                    ."</div>"
                    ."</div>";
        // début formulaire
        $this->page .= "<form action='index.php?controller=campaign&action=prepareToSend' method='POST'>";
        // boutons d'action
        $this->page .= "<div class='d-flex flex-wrap bd-highlight mb-3'>"
                    ."<span class='tableLinesNb p-2 bd-highlight'>3 ligne(s).</span>"
                    ."<div class='p-2 bd-highlight sendMessage'>"
                    ."<button type='submit' class='btn btn-grey' type='submit'><i class='fas fa-paper-plane'></i> Envoyer une relance</button>"
                    ."</div>"
                    ."</div>";
        // tableau
        $this->page .= "<table id='tableListRelaunch' class='table table-sm'>";
        // entête tableau
        $this->page .= "<thead class='thead-dark'><tr>";
        // checkbox générale entête tableau
        $this->page .= "<th scope='col' class='align-middle'>"
                    ."<label class='checkbox' for='searchTableCheckbox'>"
                    ."<input id='searchTableCheckbox' type='checkbox' class='custom-checkbox'>"
                    ."<span class='icons'>"
                    ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                    ."</span>"
                    ."<span>Nom</span>"
                    ."</label></th>";
        $this->page .= "<th scope='col' class='align-middle'>Prénom</th>";
        $this->page .= "<th scope='col' id='thActif' class='text-center align-middle'>Echéance</th>";
        $this->page .= "<th scope='col' class='text-center align-middle'>Lire</th>";
        $this->page .= "</tr></thead>";
        // corps tableau
        $this->page .= "<tbody id='RelaunchTable'>";
        foreach ($listAdherents as $adherents) {
            $firstrelance = "<td id='tdActif' class='text-center text-warning align-middle'><i class='fas fa-circle'></i></td>";
            $secondrelance = "<td id='tdActif' class='text-center text-danger align-middle'><i class='fas fa-circle'></i></td>";
            // Calcul date du jour
            $today = date("Y-m-d");
            $dateOfJoining = new DateTime($adherents['date_renouvellement']);
            $campaignEndDate = new DateTime($adherents['finDateCotisation']);
            $campaignStartDate = new DateTime($adherents['debutDateCotisation']);
            $dateToday = new DateTime($today);
            // Calcul de la date de relance à j-30 de la date de fin de cotisation
            $preDunningDate = new DateTime($adherents['finDateCotisation']);
            $preDunningDate->modify('-30 days');
            $dunningDate = $preDunningDate->format('Y-m-d');
            $dunningDateForInterval = new DateTime($dunningDate);
            // Calcul de l'intervalle entre la date de relance et la date du jour en nombre de jour style orienté objet
            $interval = $dunningDateForInterval->diff($dateToday);
            $intervalBetweenDunningAndTodayDate = floatval($interval->format('%R%a jours'));
            // Calcul de l'intervalle entre le début et la fin de la campagne d'adhésion
            $interval2 = $campaignStartDate->diff($campaignEndDate);
            $intervalCampaignDuration = floatval($interval2->format('%R%a jours'));
            // Calcul de l'intervalle entre le début de la campagne et la date de relance
            $interval3 = $campaignStartDate->diff($dunningDateForInterval);
            $intervalBetweenStartAndDunning = floatval($interval3->format('%R%a jours'));
            // Calcul de l'intervalle entre le début de la campagne et le début de l'adhésion
            $interval4 = $campaignStartDate->diff($dateOfJoining);
            $intervalBetweenCampaignAndFirstJoining = floatval($interval4->format('%R%a jours'));
            // Calcul de l'intervalle entre le début de l'adhésion et la date de relance
            $interval4 = $dateOfJoining->diff($dunningDateForInterval);
            $intervalBetweenDateOfJoiningAndDunning = floatval($interval4->format('%R%a jours'));
            // Cas d'une durée de x mois à partir de la date d'adhésion - Calcul de la date de relance à j-30 de la date de fin de cotisation
            $dureeMois = $adherents['dureeMois'];
            $preDateOfJoiningPerMounth = new DateTime($adherents['date_renouvellement']);
            $preDateOfJoiningPerMounth->modify('+'.$dureeMois.' month');
            $dateOfJoiningPerMounth = $preDateOfJoiningPerMounth->format('Y-m-d'); // Date de fin d'adhésion tenant compte du nombre de mois
            $preDunningDatePerMounth = new DateTime($dateOfJoiningPerMounth);
            $preDunningDatePerMounth->modify('-30 days');
            $dunningDatePerMounth = $preDunningDatePerMounth->format('Y-m-d');
            $dunningDateForIntervalPerMounth = new DateTime($dunningDatePerMounth);
            // Cas d'une durée de x mois à partir de la date d'adhésion - Calcul de la date de relance et la date du jour
            $intervalPerMounth = $dunningDateForIntervalPerMounth->diff($dateToday);
            $intervalBetweenDunningAndTodayDatePerMounth = floatval($intervalPerMounth->format('%R%a jours'));

            if (($adherents['dureeMois'] == 0) && (($intervalBetweenCampaignAndFirstJoining + $intervalBetweenDateOfJoiningAndDunning) > $intervalBetweenStartAndDunning) 
            && (($intervalBetweenCampaignAndFirstJoining + $intervalBetweenDateOfJoiningAndDunning) <= $intervalCampaignDuration) 
            || ($intervalBetweenDunningAndTodayDate > 0 && $intervalBetweenDunningAndTodayDate <= 30)) {
                $this->page .= "<tr>"
                            ."<td data-label='Nom' scope='row' class='align-middle'>"
                            ."<label class='checkbox' for='searchTable_input_checkbox".$adherents['id_adherent']."' id='searchTable_label".$adherents['id_adherent']."'>"
                            ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$adherents['id_adherent']."' value='".$adherents['id_adherent']."' class='custom-checkbox'>"
                            ."<span class='icons'>"
                            ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                            ."</span>"
                            ."<span class='font-weight-bold text-warning'>".$adherents['nom']."</span>"
                            ."</label>"
                            ."</td>"
                            ."<td data-label='Date' class='align-middle text-warning'>".$adherents['prenom']."</td>"
                            .$firstrelance
                            ."<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=".$adherents['id_adherent']
                            ."' class='btn btn-warning ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>"
                            ."</tr>";
            } 
            if (($adherents['dureeMois'] == 0) && (($intervalBetweenCampaignAndFirstJoining + $intervalBetweenDateOfJoiningAndDunning) > $intervalCampaignDuration) 
            || ($intervalBetweenDunningAndTodayDate > 30 && $intervalBetweenDunningAndTodayDate < 366)) {
                $this->page .= "<tr>"
                            ."<td data-label='Nom' scope='row' class='align-middle'>"
                            ."<label class='checkbox' for='searchTable_input_checkbox".$adherents['id_adherent']."' id='searchTable_label".$adherents['id_adherent']."'>"
                            ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$adherents['id_adherent']."' value='".$adherents['id_adherent']."' class='custom-checkbox'>"
                            ."<span class='icons'>"
                            ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                            ."</span>"
                            ."<span class='font-weight-bold text-danger'>".$adherents['nom']."</span>"
                            ."</label>"
                            ."</td>"
                            ."<td data-label='Date' class='align-middle text-danger'>".$adherents['prenom']."</td>"
                            .$secondrelance
                            ."<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=".$adherents['id_adherent']
                            ."' class='btn btn-danger ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>"
                            ."</tr>";
            }
            if (($adherents['dureeMois'] != 0) && ($intervalBetweenDunningAndTodayDatePerMounth > 0 && $intervalBetweenDunningAndTodayDatePerMounth <= 30)) {
                $this->page .= "<tr>"
                            ."<td data-label='Nom' scope='row' class='align-middle'>"
                            ."<label class='checkbox' for='searchTable_input_checkbox".$adherents['id_adherent']."' id='searchTable_label".$adherents['id_adherent']."'>"
                            ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$adherents['id_adherent']."' value='".$adherents['id_adherent']."' class='custom-checkbox'>"
                            ."<span class='icons'>"
                            ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                            ."</span>"
                            ."<span class='font-weight-bold text-warning'>".$adherents['nom']."</span>"
                            ."</label>"
                            ."</td>"
                            ."<td data-label='Date' class='align-middle text-warning'>".$adherents['prenom']."</td>"
                            .$firstrelance
                            ."<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=".$adherents['id_adherent']
                            ."' class='btn btn-warning ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>"
                            ."</tr>";
            }
            if (($adherents['dureeMois'] != 0) && ($intervalBetweenDunningAndTodayDatePerMounth > 30 && $intervalBetweenDunningAndTodayDatePerMounth < 366)) {
                $this->page .= "<tr>"
                            ."<td data-label='Nom' scope='row' class='align-middle'>"
                            ."<label class='checkbox' for='searchTable_input_checkbox".$adherents['id_adherent']."' id='searchTable_label".$adherents['id_adherent']."'>"
                            ."<input type='checkbox' name='searchTable[]' id='searchTable_input_checkbox".$adherents['id_adherent']."' value='".$adherents['id_adherent']."' class='custom-checkbox'>"
                            ."<span class='icons'>"
                            ."<span class='icon-unchecked'></span><span class='icon-checked'></span>"
                            ."</span>"
                            ."<span class='font-weight-bold text-danger'>".$adherents['nom']."</span>"
                            ."</label>"
                            ."</td>"
                            ."<td data-label='Date' class='align-middle text-danger'>".$adherents['prenom']."</td>"
                            .$secondrelance
                            ."<td class='text-center align-middle'><a href='index.php?controller=adherent&action=modal&id=".$adherents['id_adherent']
                            ."' class='btn btn-danger ml-auto mr-auto'><i class='fas fa-eye'></i></a></td>"
                            ."</tr>";
            }
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
     * Fonction affichage des membres sélectionnés pour la relance des adhésions avant envoi du mail
     *
     * @param [type] $memberSelect
     * @param [type] $associationDisplay
     * @param [type] $listCotisations
     * @return void
     */
    public function prepareToSend($memberSelect,$associationDisplay,$listCotisations)
    {
        $this->page .= file_get_contents('template/formSendMailForRelaunch.html');
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
        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);        $this->page = str_replace('{logout}', 'index.php?controller=security&action=logout',$this->page);
        if (isset($_SESSION)) {
            $this->page = str_replace('{account}', 'index.php?controller=adherent&action=modal&id='.$_SESSION['user']['id'].'',$this->page);
        } else {
            $this->page = str_replace('{account}', '#',$this->page);
        }        $this->page = str_replace('{fa-paper-plane}', 'pres fas fa-paper-plane pink',$this->page);
        $this->page = str_replace('{header-css}', 'params-listUsers',$this->page);
        $this->page = str_replace('{action}', 'index.php?controller=campaign&action=sendMail',$this->page);
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{logoPrincipal}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{logoPrincipal}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{memberOrDonatorSelected}', 'Membre(s) à relancer',$this->page);
        $contentUserSelected = "";
        foreach ($memberSelect as $selected) {
            $contentUserSelected .= "<div class='blockContentUserSelected'><span class='iconUserSelected align-self-center'><i class='fas fa-check'></i></span>"
                                 ."<span class='firstnameUserSelected align-self-center'>".$selected['prenom']."</span>"
                                 ."<span class='nameUserSelected align-self-center'>".$selected['nom']."</span>"
                                 ."<span class='emailUserSelected align-self-center'>"."(".$selected['email'].")"."</span>"
                                 ."</div>";
        }
        $mailDestinataire = "";
        foreach($memberSelect as $destinataire){
            $mailDestinataire .= "<input type='hidden' name='destinataire[]' value='".$destinataire['prenom']."|".htmlspecialchars($destinataire['nom'], ENT_QUOTES)."|".$destinataire['email']."|".$destinataire['date_entree']."'>";
        }
        $cotisations = "";
        $campaignTypeSelected = "";
        foreach ($listCotisations as $cotis) {
            $tarifEtLibelleCotisation = $cotis['montant_cotisation']."€ (".$cotis['libelle'].")";
            $cotisations .= "<option value='" . $cotis['id'] . "'>" . $tarifEtLibelleCotisation ."</option>";
            $campaignTypeSelected .= "<input type='hidden' name='campaignTypeSelected[]' value='".$cotis['id']."|".$cotis['montant_cotisation']."|".htmlspecialchars($cotis['libelle'], ENT_QUOTES)."|".$cotis['debutDateCotisation']."|".$cotis['finDateCotisation']."|".$cotis['dureeMois']."'>";
        }
        $this->page = str_replace('{campaignType}', $cotisations,$this->page);
        $this->page = str_replace('{campaignTypeSelected}', $campaignTypeSelected,$this->page);
        $this->page = str_replace('{destinataire}',$mailDestinataire,$this->page);
        $this->page = str_replace('{contentUserSelected}',$contentUserSelected,$this->page);
        $this->page = str_replace('{associationEmail}',$associationDisplay['email'],$this->page);
        $this->page = str_replace('{subjectMail}', '',$this->page);
        $associationAbstract = "";
        $associationAbstract .= "<input type='hidden' name='association' value='".$associationDisplay['nom']."|".$associationDisplay['prenomSign']."|".$associationDisplay['nomSign']."|".$associationDisplay['fonctionSign']."|".$associationDisplay['logo']."'>";
        $this->page = str_replace('{associationDisplay}',$associationAbstract,$this->page);
        $this->displayPage();
    }
}
