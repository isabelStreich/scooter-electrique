<?php

if (!isset($index_loaded)) {
    http_response_code(403);
    die('<h2 style="color: red">Acces direct a ce fichier est interdit !!!</h2>');
}
require_once 'global_defines.php';

class webPage
{
    public $lang = 'fr-CA';
    public $title = 'ScooterElectrique.com';
    public $description = 'Le plus vaste de choix de scooters électrique à Montréal - Vente - Service - Pièces';
    public $author = 'Votre nom ici';
    public $icon = WEB_SITE_ICON;
    public $content;

    public function __construct()
    {
    }

    public function display()
    {
        if (!isset($this->content)) {
            // http_response_code(500);
            // echo 'erreur: le contenu de cette page est vide';
            // die();
            crash(500, 'erreur: le contenu de cette page est vide');
        } ?>
<!DOCTYPE html>
<html lang="<?= $this->lang; ?>">

<head>
    <meta charset="UTF-8">
    <title><?= $this->title; ?>
    </title>
    <meta name="DESCRIPTION" content="<?=$this->description; ?>">
    <meta name="author" content=" <?=$this->author; ?>">
    <!-- web site icon -->
    <LINK REL="icon" href="<?=$this->icon; ?>">
    <!-- CSS -->
    <link rel="steelsheet" href="css/global.css">
    <!--IMPORTANT pour responsive -->
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- Bootstrap -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css"
        integrity="sha384-JcKb8q3iqJ61gNV9KGb8thSsNjpSL0n8PARn9HuZOnIxN0hoP+VmmDGMN5t9UJ0Z" crossorigin="anonymous">

</head>

<body style="background-color:#fbfbf8">

    <!-- PAGE HEADER -->
    <!-- style-color -->
    <!-- https://www.color-hex.com/color-palette/96273 -->
    <header>
        <h2 style="background-color:#989596;color:white;padding:10px">
            <?= $this->title; ?>
        </h2>
    </header>

    <!-- BARRE DE NAVIGATION -->
    <nav class="navbar navbar-light" style="background-color:#caeaf6;">
        <a class="navbar-brand" href='index.php'>Acceuil</a>
        <a class="navbar-brand" href='index.php?op=3'>Inscription</a>
        <a class="navbar-brand" href='index.php?op=98'>Log du serveur</a>
        <a class="navbar-brand" href='index.php?op=99'>$_SERVER</a>
        <a class="navbar-brand" href='index.php?op=200'> Productlines</a>
        <a class="navbar-brand" href='index.php?op=10'> Users</a>

        <?php
        if (isset($_SESSION['email'])) {
            echo '<a class="navbar-brand" href="index.php?op=5">LogOut</a>';
            echo$_SESSION['email'];
        } else {
            echo"<a class='navbar-brand' href='index.php?op=1'>Connection</a>";
        } ?>
    </nav>

    <!-- CONTENT -->
    <?= $this->content; ?>

    <!-- FOOTER -->
    <footer style=" background-color:#989596;color:white;padding:10px">
        Exercice par <?='Isabel Streich'; ?>
        &copy;
    </footer>
    </div>





    <!--bootstrap JS-->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"
        integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous">
    </script>
    <script src="https://cdn.jsdelivr.net/npm/popper.js@1.16.1/dist/umd/popper.min.js"
        integrity="sha384-9/reFTGAW83EW2RDu2S0VKaIzap3H66lZH81PoYlFhbGU+6BZp6G7niu735Sk7lN" crossorigin="anonymous">
    </script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/js/bootstrap.min.js"
        integrity="sha384-B4gt1jrGC7Jh4AgTPSdUtOBvfO8shuf57BaghqFfPlYxofvL8/KUEfYiJOMMV+rV" crossorigin="anonymous">
    </script>
</body>

</html>

<?php
//fin de la function display
die();
    }
}
