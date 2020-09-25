<?php
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();
include 'Model/Model.php';
include 'View/View.php';

include 'Controller/Controller.php';
include 'Controller/AdherentController.php';
include 'Controller/AssociationController.php';
include 'Controller/DashboardController.php';
include 'Controller/DonsController.php';
include 'Controller/CampaignController.php';
include 'Controller/ContactController.php';
include 'Controller/DocumentController.php';
include 'Controller/UserController.php';
include 'Controller/SecurityController.php';
include 'Controller/GroupController.php';

// if (isset($_GET['controller'])) {
//     $controllerStart = ucfirst($_GET["controller"]) . "Controller";
// } else {
//     // $controllerStart = 'AssoController';
//     $controllerStart = 'DashboardController';
// }

// $controller = new $controllerStart();

// if (isset($_GET['action'])) {
//     $action = $_GET["action"];
// } else {
//     // $action = 'list';
//     $action = 'start';
// }

// $controller->$action();

$paramGet = extractParameters();
$controller = $paramGet['controller'];
$action = $paramGet['action'];

$controller = new $controller();

$controller->$action();

function extractParameters(){

    $controllerNotAuth = ['SecurityController', 'ContactController'];
    $actionNotAuth = ['start', 'sendMessage', 'formRegister', 'registerMember', 'formLogin', 'login', 'confirm', 'formForget', 'forget', 'confirmForget', 'formReset', 'reset'];
    $controllerNotAdmin = ['SecurityController', 'DocumentController', 'AdherentController', 'ContactController', 'GroupController'];
    $actionNotAdmin = ['listFiles', 'show', 'start', 'detailForm', 'detailDoc', 'modal', 'sendMessage', 'ajaxAutocompletionJobs', 'ajaxAutocompletionDocs', 'updateFormOnlyMember', 'updateDBOnlyMember', 'formRegister', 'registerMember', 'formLogin', 'login', 'logout', 'confirm', 'formForget', 'forget', 'confirmForget', 'formReset', 'reset'];

    /**
     * récupération des données de l'url
     */
    if (isset($_GET['controller'])) {
        $controller = ucfirst($_GET['controller']) . "Controller";
    } else {
        $controller = 'DashboardController';
    }

    if (isset($_GET['action'])) {
        $action = $_GET['action'];
    } else {
        $action = 'start';
    }

    /**
     * validation selon les droits établis si l'utilisateur n'est pas authentifié
     */
    if (!isset($_SESSION['user'])) {
        if (!in_array($controller, $controllerNotAuth) || !in_array($action, $actionNotAuth)) {
            $controller = 'SecurityController';
            $action = "formLogin";
        }
    }
    /**
     * validation selon les droits établis si l'utilisateur n'est pas administrateur
     */
    if (isset($_SESSION['user']) && ($_SESSION['user']['id_roles'] != "1")) {
        if (!in_array($controller, $controllerNotAdmin) || !in_array($action, $actionNotAdmin)) {
            $controller = 'DashboardController';
            $action = "start";
        }
    }

    return (['controller' => $controller, 'action' => $action]);
}