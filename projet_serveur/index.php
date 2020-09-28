<?php

session_start();

/**
 * Projet serveur- ceci le point d'entree.
 */
$index_loaded = true;

//fichier requis
require_once 'global_defines.php';
require_once 'tools.php';
require_once 'webpage.php';
//mon project du tp
require_once 'productlines.php';
//examen pratique
require_once 'users.php';

//controller
if (isset($_GET['op'])) {
    $op = $_GET['op'];
} else {
    $op = 0; //affiche page d'acceuil
}
//si pas connecte code entre o et 5
if (!isset($_SESSION['email']) and $op >= 5) {
    crash(401, 'vous devez etre connecte pour effectuer cette operation');
}

switch ($op) {
    case 0:
        HomePage();
        break;

    case 1:
        $userInfo = [
            'email' => 'lavoie@gmail.com',
            'pw' => '12345678',
        ];
        $users = new users();
        $users->loginFormAffiche($userInfo);
        break;

    case 2:
        $users = new users();
        $users->loginFormVerifier();
        break;

    case 3:
        $Users_info = [
            'fullname' => '',
            'adresse' => '',
            'ville' => '',
           'code_postal' => '',
            'email' => '',
            'pw' => '',
            'pw2' => '',
        ];
        $users = new users();
        $users->afficherFormInscription($Users_info);
        break;

    case 4:
        $users = new users();
        $users->validerFormInscription();
        break;
    case 5:
        //deconnection
        $_SESSION['email'] = null;
        HomePage();
        break;

    case 98:
        $Page = new webPage();
        $Page->title = 'Log du serveur';
        $Page->content = logAffiche();
        $Page->display();
        logAffiche();
        break;

    case 99:
        tableauAffiche($_SERVER);
        break;
    //EXAMEN PRATIQUE=============================================================
    //users
    case 10:
        $users = new users();
        $users->List();
    break;
//PRODUCTLINE
    case 200:
        $productlines = new productlines();

        if (isset($_POST['produitFiltre'])) {
            $productlines->List($_POST['produitFiltre']);
        } else {
            $productlines->List();
        }

        break;

    case 201:
        // edit
        $product = new productlines();
        $DB = new DB();
        $id = $_GET['id'];
        $sql = "SELECT * FROM productlines WHERE productLine ='".$id."'";

        $productlines = $DB->querySelect($sql);
        $productline = $productlines[0];
        $product->Edit($productline);

        if (isset($_POST['productline'])) {
            break;
        } else {
            $productlines->List();
        }
        break;

    case 202:
        // Delete
        $productlines = new productlines();
        $id = $_GET['id'];
        $productlines->Delete($id);
        break;

    case 203:
        // Display
       $productlines = new productlines();
        $id = $_GET['id'];
        $productlines->Display($id);
        break;

    case 204:
        // Save
        $productlines = new productlines();
        $id = $_POST['productLine'];
        $productlines->Save($id);
        break;

    case 205:
        // Add
        $productlines_info = [
            'productLine' => '',
            'textDescription' => '',
            'htmlDescription' => '',
            'image' => '',
        ];
        $productlines = new productlines();
        $productlines->Add();
        break;
    case 206:
        //save edit
        $productlines = new productlines();
        $id = $_POST['productLine'];
        $productlines->SaveEdit($id);
        break;

    case 210:
        //service web API returne la liste de productlines
        $productlines = new productlines();
        $productlines->ListJson();
        break;

    default:
        crash(400, 'operation invalide');
}

function HomePage()
{
    $HomePage = new webPage();

    $HomePage->title = 'Page Acceuil';
    $HomePage->description = 'Bienvenue!! ';

    $HomePage->content = <<<HTML
<h1>Bonjour le monde</h1>
<p>ceci est la page acceuil </p>

HTML;

    $HomePage->display();
    die();
}
