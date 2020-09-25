<?php

abstract class View
{
    protected $page;

    /**
     * Ajout de l'entête de la page
     */
    public function __construct()
    {
        $this->page = file_get_contents('template/header.html');
        // $this->page .= file_get_contents('template/nav.html');

        // if(isset($_SESSION['user'])) {
        //     $optionConnect = "<a class='nav-link' href='index.php?controller=security&action=logout'>Se déconnecter</a>";
        //     $optionCot = "<a class='nav-link {activeCot}' href='index.php?controller=cotis'>Cotisations</a>";
        //     $optionDon = "<a class='nav-link {activeDon}' href='index.php?controller=dons'>Dons</a>";
        //     $optionUser = "<a class='nav-link {activeUser}' href='index.php?controller=security&action=formRegister'>S'enregistrer</a>";
        // } else {
        //     $optionConnect = "<a class='nav-link' href='index.php?controller=security&action=formLogin'>Se connecter</a>";
        //     // $optionCot = "";
        //     // $optionDons = "";
        //     $optionCot = "<a class='nav-link {activeCot}' href='index.php?controller=cotis'>Cotisations</a>";
        //     $optionDon = "<a class='nav-link {activeDon}' href='index.php?controller=dons'>Dons</a>";
        //     $optionUser = "<a class='nav-link {activeUser}' href='index.php?controller=security&action=formRegister'>S'enregistrer</a>";
        // }
        // $this->page = str_replace('{optionConnect}', $optionConnect,$this->page);
        // $this->page = str_replace('{listCot}', $optionCot,$this->page);
        // $this->page = str_replace('{listDon}', $optionDon,$this->page);
        // $this->page = str_replace('{listUser}', $optionUser,$this->page);
    }
    //######################################################
    /**
     * Affichage de la page
     *
     * @return void
     ******************************************************/
    protected function displayPage()
    {
        $this->page .= file_get_contents('template/footer.html');
        echo $this->page;
    }
}
