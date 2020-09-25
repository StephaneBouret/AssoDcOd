<?php

class SecurityView extends View {

    /**
     * Affichage du formulaire de connexion
     *
     * @return void
     */
    public function addFormLogin()
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/formLogin.html');
        $this->page = str_replace('{action}', 'login',$this->page);
        $message = "";
        if (isset($_SESSION['flash'])){
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page = str_replace('{forgotPassword}', 'index.php?controller=security&action=formForget',$this->page);
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Affichage du formulaire d'enregistrement
     *
     * @return void
     */
    public function registerForm()
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/formRegister.html');
        $this->page = str_replace('{action}', 'registerMember',$this->page);
        $message = "";
        if(isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        } else {
            $message = "";
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Affichage du formulaire oubli du mot de passe
     *
     * @return void
     */
    public function addForget()
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/formForget.html');
        $this->page = str_replace('{action}', 'forget',$this->page);
        $message = "";
        if(isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        } else {
            $message = "";
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page .= "</main>";
        $this->displayPage();
    }

    /**
     * Affichage du formulaire de changement du mot de passe
     *
     * @return void
     */
    public function addReset()
    {
        $this->page .= "<main id='main'>";
        $this->page .= file_get_contents('template/formReset.html');
        $this->page = str_replace('{action}', 'index.php?controller=security&action=reset',$this->page);
        $message = "";
        if(isset($_SESSION['flash'])) {
            foreach ($_SESSION['flash'] as $type => $content) {
                $message = "<div class='alert alert-".$type." text-center mb-2 font-m'>".$content."</div>";
            }
            unset($_SESSION['flash']);
        } else {
            $message = "";
        }
        $this->page = str_replace('{message}', $message,$this->page);
        $this->page .= "</main>";
        $this->displayPage();
    }

}
