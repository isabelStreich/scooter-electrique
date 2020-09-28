<?php

if (!isset($index_loaded)) {
    http_response_code(403);
    die('<h2 style="color: red">Acces direct a ce fichier est interdit !!!</h2>');
}

function tableauAffiche($tableauUnidimensionel)
{
    echo '<table style="border:1px solid black">';
    echo '<th style="border:1px solid black"> Cle/ Indice </th>';
    echo '<th style="border:1px solid black"> valeur </th>';
    foreach ($tableauUnidimensionel as $key => $valeur) {
        echo '<tr>';
        echo '<td style="border:1px solid black">'.$key.'</td>';
        echo '<td style="border:1px solid black">'.$valeur.'</td>';
        echo '</tr>';
    }
    echo '</table>';
}
function TableauSelectHTML($nom_du_select, $Tableau)
{
    // $html = '<select  name"'.$nomSelect.'>';
    $html = '<select class="form-control" name="'.$nom_du_select.'">';
    foreach ($Tableau as $keyTableau => $valeurTableau) {
        $html .= '<option value="'.$keyTableau.'">'.$valeurTableau.'</option>';
    }
    $html .= '</select>';

    return $html;
}
function SelectProvinceAvecDB($nom_du_select)
{
    $DB = new DB();
    // $sql_prov = 'SELECT code, nom FROM users WHERE province';
    $sql_prov = 'SELECT * FROM users WHERE province';
    $Provinces = $DB->querySelect($sql_prov);
    $html = '<select class="form-control" name="'.$nom_du_select.'">';
    foreach ($Provinces as $un_province) {
        $html .= '<option value="'.$Provinces.'">'.$un_province.'</option>';
    }
    $html .= '</select>';

    return $html;
}
function SelectPaysAvecDB($nom_du_select)
{
    $DB = new DB();
    $sql_pays = 'SELECT * FROM users WHERE pays';
    $Pays = $DB->querySelect($sql_pays);
    $html = '<select class="form-control" name="'.$nom_du_select.'">';
    foreach ($Pays as $un_pays) {
        $html .= '<option value="'.$un_pays.'">'.$un_pays.'</option>';
    }
    $html .= '</select>';

    return $html;
}

/**
 * crash affiche un erreur et enregistre l'erreur dans un fichier .log.
 */
function crash($codeErreur, $message)
{
    $currentDirectory = getcwd();
    $temps = date(DATE_RFC2822);

    // tableauAffiche($_SERVER);
    $myFile = fopen($currentDirectory.'\log\serveur.log', 'a+');
    fwrite($myFile, $_SERVER['REMOTE_ADDR'].'-'.$temps.'-'.$message.PHP_EOL);
    fclose($myFile);

    //  envoit reponse HTTP
    http_response_code($codeErreur);

    //envoit mail ne fonctionne pas pour l'instant
    // mail('isabelstreich@gmail.com', 'Erreur dans le serveur'.COMPANY_NAME, $message);

    die($message);
}
function logAffiche()
{
    $currentDirectory = getcwd();
    $file = file_get_contents($currentDirectory.'\log\serveur.log');

    if ($file === false) {
        return 'fichier vide';
    } else {
        return $file;
    }

    return $file;
}
function Photo_Uploaded_Is_Valid($Max_Size = 500000)
{
    //il faut dans le HTML <form enctype="multipart/form-data" ..
    //sinon $_FILES n'est pas défini

    // 'un_fichier' est le nom sur le formulaire HTML
    if (!isset($_FILES['un_fichier'])) {
        return 'Aucune image téléversée';
    }

    if ($_FILES['un_fichier']['error'] != UPLOAD_ERR_OK) {
        return 'Erreur téléchargement de la photo: code='.$_FILES['un_fichier']['error'];
    }

    // Vérifier taille de l'image
    if ($_FILES['un_fichier']['size'] > $Max_Size) {
        return 'Fichier trop gros, taille maximum = '.$Max_Size.' Kb';
    }

    // Vérifier si le fichier contient une image
    $check = getimagesize($_FILES['un_fichier']['tmp_name']);
    if ($check === false) {
        return "Ce fichier n'est pas une image";
    }

    // Vérifier si extension est jpg,JPG,gif,png
    $imageFileType = pathinfo(basename($_FILES['un_fichier']['name']), PATHINFO_EXTENSION);
    if ($imageFileType != 'jpg' && $imageFileType != 'JPG' && $imageFileType != 'gif' && $imageFileType != 'png') {
        return "L'extension du fichier est invalide. Doit être parmis: .jpg .JPG .gif .png";
    }

    return 'OK';
}
function Photo_Uploaded_Mime_Type()
{
    // Attention: Utiliser function Photo_Uploaded_Is_Valid() avant !
    // On assume ici que la photo est valide

    $imageFileType = pathinfo(basename($_FILES['un_fichier']['name']), PATHINFO_EXTENSION);
    switch ($imageFileType) {
        case 'jpg':
        case 'JPG':
            return 'image/jpeg';

        case 'gif':
        case 'GIF':
            return 'image/gif';

        case 'png':
        case 'PNG':
            return 'image/png';
    }

    return 'ERROR';
}
