<?php

include 'Model/DashboardModel.php';
include 'View/DashboardView.php';

class DashboardController extends Controller
{


    public function __construct()
    {
        $this->view = new DashboardView();
        $this->model = new DashboardModel();
    }

    /**
     * Construction de la page tableau de bord
     * Liste des informations
     * @return void
     */
    public function start()
    {
        $associationDisplay = $this->model->getFullAssociation();
        $this->view->displayHome($associationDisplay);
    }

}