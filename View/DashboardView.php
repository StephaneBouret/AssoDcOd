<?php
class DashboardView extends View
{
    /**
     * Fonction affichage du tableau de bord de l'association
     *
     * @return void
     */
    public function displayHome($associationDisplay)
    {
        // var_dump($_SESSION['user']);
        // var_dump($_SESSION);
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page .= file_get_contents('template/dashboard.html');
        } else {
            $this->page .= file_get_contents('template/dashboardOnlyMember.html');
        }
        if (empty($associationDisplay['logo'])) {
            $this->page = str_replace('{photo}', 'img/undefined.jpg',$this->page);
        } else {
            $this->page = str_replace('{photo}',$associationDisplay['logo'],$this->page);
        }
        $this->page = str_replace('{actionAddMembers}', 'index.php?controller=adherent&action=addForm',$this->page);
        $this->page = str_replace('{actionParamAssoc}', 'index.php?controller=association&action=start',$this->page);
        $this->page = str_replace('{actionListMembers}', 'index.php?controller=adherent&action=start',$this->page);
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
        if (!empty($_SESSION)) {
            $this->page = str_replace('{adminFirstname}', $_SESSION['user']['prenom'],$this->page);
        } else {
            $this->page = str_replace('{adminFirstname}', '',$this->page);
        }
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        if (!empty($_SESSION['user']) && $_SESSION['user']['id_roles'] == "1") {
            $this->page = str_replace('{descListMembers}', "Voir la liste des adhérents. Voir chaque profil. Envoyer un ou plusieurs mails.",$this->page);
            $this->page = str_replace('{descDocs}', "Consultez ou modifiez les documents de l'association.",$this->page);
            $this->page = str_replace('{titleNews}', "Gérer<br>les news",$this->page);
            $this->page = str_replace('{descNews}', "Créez, modifier vos news.",$this->page);
        } else {
            $this->page = str_replace('{descListMembers}', "Voir la liste des adhérents.",$this->page);
            $this->page = str_replace('{descDocs}', "Consultez les documents de l'association.",$this->page);
            $this->page = str_replace('{titleNews}', "Consulter<br>les news",$this->page);
            $this->page = str_replace('{descNews}', "Consultez les nouvelles de l'association.",$this->page);
        }
        $this->displayPage();
    }

}
