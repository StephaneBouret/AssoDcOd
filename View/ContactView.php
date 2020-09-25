<?php

class ContactView extends View {

    /**
     * Fonction affichage de la page contact
     *
     * @param [type] $associationDisplay
     * @return void
     */
    public function displayHome($associationDisplay)
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/formContact.html');
        $this->page = str_replace('{action}', 'index.php?controller=contact&action=sendMessage',$this->page);
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page .= "</main>";
        $this->displayPage();
    }
}
