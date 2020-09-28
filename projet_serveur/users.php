<?php

if (!isset($index_loaded)) {
    http_response_code(403);
    die('<h2 style="color: red">Acces direct a ce fichier est interdit !!!</h2>');
}
$index_loaded = true;
// require_once 'db_mysqli.php';
require_once 'db_pdo.php';
require_once 'global_defines.php';
require_once 'tools.php';
require_once 'webpage.php';
require_once 'productlines.php';

class users
{
    public function __construct()
    {
    }

    public function List()
    {
        $DB = new DB();
        $users = $DB->table('users');

        $Page = new webPage();
        $Page->content .= '<h1> Table Users </h1>';
        //table
        $Page->content .= '<div>';
        $Page->content .= '<table class="table">';
        $Page->content .= '<thead class="thead-light">';
        $Page->content .= '<tr>';
        $Page->content .= '<tr>';
        $Page->content .= '<th scope="col"> ID </th>';
        $Page->content .= '<th scope="col"> FULLNAME </th>';
        $Page->content .= '<th scope="col">EMAIL </th>';
        $Page->content .= '<th scope="col">LANGUE </th>';
        $Page->content .= '<th scope="col">PAYS </th>';
        $Page->content .= '</tr>';

        foreach ($users as $un_users) {
            // code...
            $Page->content .= '<tr scope="row">';
            $Page->content .= '<td scope="row">'.$un_users['id'].'</td>';
            $Page->content .= '<td scope="row">'.$un_users['fullname'].'</td>';
            $Page->content .= '<td scope="row">'.$un_users['email'].'</td>';
            $Page->content .= '<td scope="row">'.$un_users['langue'].'</td>';
            $Page->content .= '<td scope="row">'.$un_users['pays'].'</td>';
            $Page->content .= '</tr>';
        }
        $Page->content .= '</table>';
        $Page->content .= '<div>';
        // $Page->content .= '<fieldset>';
        // echo '<button type="button" class="btn btn-success">Ajouter</button>';

        $Page->display();
        die();
    }

    public function loginFormAffiche($userInfo, $message = '')
    {
        if (isset($_SESSION['email'])) {
            $HomePage = new webPage();
            $HomePage->title = 'Deja connectez';
            $HomePage->description = 'page de connection ';
            $HomePage->content = '<h1>Vous etes deja connecte! <a href="index.php?op=5"> Deconnectez-vous</a></h1>';
            $HomePage->display();
        }

        $HomePage = new webPage();
        $HomePage->title = 'Connectez-vous';
        $HomePage->description = 'Bienvenue a la page de connection!! ';

        $HomePage->content = '';
        if (isset($_COOKIE['email'])) {
            $HomePage->content .= '<div  ="alert alert-success" role="alert">';
            $HomePage->content .= '<h4 class="alert-heading">Re-Bienvenue : '.$_COOKIE['email'].'</h4>';
            $HomePage->content .= '<div  class="alert alert-dark" role="alert"> Votre dernier connetion : '.date('d-M-Y', $_COOKIE['dernier_connexion']).'</div>';
            $HomePage->content .= '</div>';
        }
        $HomePage->content .= '<h5>'.$message.'</h5>';
        $HomePage->content .= '<fieldset>';
        $HomePage->content .= '<form action="index.php?op=2" method="POST"> ';
        $HomePage->content .= "<input type='hidden' name='op' value='2'>";
        $HomePage->content .= '<div>';
        $HomePage->content .= "<div>  Email <input type='email' name='email' maxlength='126' value='".$userInfo['email']."'></div>";
        $HomePage->content .= "<div>  Mot de passe <input type='password' name='pw' maxlength='8' value='".$userInfo['pw']."'></div>";
        $HomePage->content .= "<div> <input type='submit' value='continuer'></div>";
        $HomePage->content .= '</div>';
        $HomePage->content .= '</form>';
        $HomePage->content .= '</fieldset >';

        $HomePage->display();
        die();
    }

    public function loginFormVerifier()
    {
        $DB = new DB();
        $email_form = $_POST['email'];
        $pw_form = $_POST['pw'];
        $sql = "SELECT * FROM users WHERE email = '$email_form' && pw ='$pw_form'";
        $users = $DB->querySelect($sql);
        // sauvegarder les valeurs dans le formulaire au cas ou il y a erreur et on doit re-afficher le formulaire
        $userInfo = $_POST;
        // var_dump($_POST);

        if (isset($_POST['email']) and $_POST['email'] !== '') {
            //email valido
            $email_form = $_POST['email'];
        } else {
            // afficher msg manquant
            // loginFormAffiche('Invalid email');
            loginFormAffiche($userInfo, '<div class="alert alert-danger" role="alert">
        Invalid email!!!
      </div>');
        }
        if (isset($_POST['pw']) and $_POST['pw'] !== '') {
            //Password valido
            $pw_form = $_POST['pw'];
        } else {
            // afficher msg de erreur et reaficher le formulaire
            // loginFormAffiche('Invalid password');
            loginFormAffiche($userInfo, '<div class="alert alert-danger" role="alert">
        Invalid password!!
      </div>');
        }

        // verification si l'utilisateur est dans la liste
        foreach ($users as $un_user) {
            if ($un_user['email'] === $_POST['email'] && $un_user['pw'] === $_POST['pw']) {
                // OK CONNECT
                $_SESSION['email'] = $_POST['email'];

                // cookie valide pour 2 ans
                setcookie('email', $_POST['email'], time() + (365 * 24 * 60 * 60));
                setcookie('dernier_connexion', time(), time() + (365 * 24 * 60 * 60));

                $Page = new webPage();
                $Page->content = '<div class="alert alert-success" role="alert">
            Bienvenue a notre site web!
          </div>';
                $Page->display();
                die();
            }
        }
        // reaficher le formulaire
        loginFormAffiche($userInfo, 'Utilisateur non reconu!');
    }

    //FORMULAIRE========================
    public function afficherFormInscription($Users_info, $message = '')
    {
        //     $Provinces = ['QC' => 'Québec', 'ON' => 'Ontario', 'NB' => 'Nouveau-Brunswick', 'NS' => 'Nouvelle-Écosse',  'AB' => 'Alberta', 'MN' => 'Manitoba', 'SK' => 'Saskatchewan'];
        //     $Pays = ['CA' => 'Canada', 'US' => 'USA', 'MX' => 'Mexique', 'FR' => 'France', 'AU' => 'Autre'];

        // $DB = new DB();
        // // $sql_prov = 'SELECT * from province';
        // //$Provinces = $_POST['province'];
        // //$Pays = $_POST['pays'];

        // $sql = 'SELECT * FROM province ';

        // // $sql = 'SELECT * FROM pays ';
        // // $users = $DB->querySelect($sql);
        // //$users = $users[0];

        $prov = SelectProvinceAvecDB('province');
        $pays = SelectPaysAvecDB('pays');

        $HomePage = new webPage();
        $HomePage->title = 'Connectez-vous';
        $HomePage->description = 'Bienvenue a la page de connection!! ';

        $HomePage->content = <<<HTML
     <h4>{$message}</h4>
    <form action='index.php?op=4' method="POST">
    
    <input type='hidden' name='op' value='3'>
    <h3>Info Generale </h3>
    <div class="form-group"> Name <input class="form-control" type='text' name="fullname"  maxlength="50" value="{$Users_info['fullname']}"> </div>
    <div class="form-group"> Adresse (non requis)<input class="form-control" type='text' name="adresse" maxlength="255" value="{$Users_info['adresse']}"> </div>
    <div class="form-group"> Ville (non requis)<input class="form-control" type='text' name="ville" maxlength="50" value="{$Users_info['ville']}"> </div>
    <div class="form-group"><label> Province (non requis)</label> {$prov}</div>
    <div class="form-group"><label> Pays (non requis)</label>{$pays}</div>
    <div class="form-group"> Code Postal (non requis)<input class="form-control" type='text' name="code_postal" maxlength="7" value="{$Users_info['code_postal']}"> </div>
    
    <h3>Langue</h3>
      <input type="radio" id="fr" name="langue" value="fr">
      <label for="fr">Francais</label><br>
      <input type="radio" id="an" name="langue" value="an">
      <label for="an">Anglais</label><br>
      <input type="radio" id="autre" name="langue" value="autre">
      <label for="autre">Autre</label>
    <h3>Info Connexion (requis) </h3>
    <div class="form-group"> Email <input class="form-control" type='email' name="email"  maxlength="126" value="{$Users_info['email']}"> </div>
    <div class="form-group">  Mot de passe <input class="form-control" type='password' name='pw' maxlength='8' value="{$Users_info['pw']}"></div>
    <div class="form-group"> Mot de passe <input class="form-control" type='password' name='pw2' maxlength='8' value="{$Users_info['pw2']}"></div>
    
    <div class="form-group"><input type="checkbox" name="spam_ok" value="1">Je desire recevoir periodiquement de l'information sur les noveaux produits</div>
    Vos intérêts (optionel, vous pouvez sélectionner plusieurs)
            <select class="form-control" name="interets[]" multiple size="3">
                <option value="se">scooter électrique</option>
                <option value="sg">scooter à essence</option>
                <option value="velo_el">vélo électrique</option>
                <option value="velo">velo régulier</option>
                <option value="moto">moto</option>
            </select>
    
    <div class="form-group"><input class="btn btn-primary"type="submit" value="Continuez"> </div>
    
    </form>
    
    HTML;

        $HomePage->display();
        die();
    }

    public function validerFormInscription()
    {
        $DB = new DB();
        $fullname_form = $_POST['fullname'];
        $adresse_form = $_POST['adresse'];
        $ville_form = $_POST['ville'];
        $Provinces = $_POST['province'];
        $Pays = $_POST['pays'];
        $code_postal_form = $_POST['code_postal'];
        $langue = $_POST['langue'];
        $email_form = $_POST['email'];
        $pw_form = $_POST['pw'];

        $Users_info = $_POST;

        $err_message = '';
        if (!isset($_POST['email']) or empty($_POST['email'])) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
           Un mail est requis!
          </div>');
        } else {
            foreach ($Users as $userToTest) {
                // code...
                if ($userToTest['email'] === $_POST['email']) {
                    afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
                Ce email est déjà utilisé, s.v.p. choisir un autre email
              </div>');
                }
                if ($_POST['pw'] !== $_POST['pw2']) {
                    afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
                Desole les password sont differents!
              </div>');
                }
            }
        }
        if (!isset($_POST['fullname']) or empty($_POST['fullname'])) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
           Le nom est requis!
          </div>');
        } elseif (strlen($_POST['fullname'] > 50)) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Desole,Votre nom ne peut pas contenir plus de 50 caracteres!!!
          </div>');
        }
        if (strlen($_POST['adresse'] > 255)) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Desole,Votre adresse ne peut pas contenir plus de 50 caracteres!!!
          </div>');
        }
        if (strlen($_POST['ville'] > 50)) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Desole,Votre ville ne peut pas contenir plus de 50 caracteres!!!
          </div>');
        }

        if (strlen($_POST['email'] > 12)) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Desole,Votre email ne peut pas contenir plus de 8 caracteres!!!
          </div>');
        }
        if (strlen($_POST['pw'] < 8)) {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Desole,Votre password ne peut pas contenir plus de 8 caracteres!!!
          </div>');
        }

        if (isset($_POST['email']) and $_POST['email'] !== '') {
            $email_form = $_POST['email'];
        } else {
            // afficher msg manquant
            // loginFormAffiche('Invalid email');
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Invalid email!!!
          </div>');
        }
        if (isset($_POST['pw']) and $_POST['pw'] !== '') {
            $pw_form = $_POST['pw'];
        } else {
            // afficher msg de erreur et reaficher le formulaire
            // loginFormAffiche('Invalid password');

            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
            Invalid password!!
          </div>');
        }
        if (isset($_POST['pw']) and $_POST['pw2'] !== '') {
            $pw2_form = $_POST['pw'];
        } else {
            afficherFormInscription($Users_info, '<div class="alert alert-danger" role="alert">
                Invalid password!!
              </div>');
        }

        // $sql_str = "INSERT INTO users (fullname, adresse, ville,province,pays,code_postal,langue,email,pw)
        // VALUES('$fullname_form', '$adresse_form', '$ville_form','$Provinces','$Pays', '$code_postal_form','$langue','$email_form','$pw_form')";
        // $DB->query($sql_str);

        $this->List();
        // cookie valide pour 2 ans
        setcookie('email', $_POST['email'], time() + (365 * 24 * 60 * 60));
        setcookie('dernier_connexion', time(), time() + (365 * 24 * 60 * 60));
    }
}
