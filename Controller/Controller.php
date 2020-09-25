<?php

abstract class Controller
{
    protected $view;
    protected $model;

        /**
     * Gestion de l'affichage du formulaire d'ajout
     * Manage the display of the add form
     *
     * @return void
     */
    public function addForm()
    {
        $this->view->addForm();
    }
    
}
