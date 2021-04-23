<?php

    // die('bonjour');
    ////////////////////////////////////////////////////////////////////// I N T R O D U C T I O N
    define('ROOT', dirname(__FILE__)); // Constante qui contient nom du repertoire parent
    require ROOT.'/App/App.php'; // Inclusion du fichier App.php [notre fichier principal]
    App::load(); // Appel de la fonction load() qui se trouve dans la classe statique App

    ///////////////////////////////////////////////////////////////////// P E T I T   S Y S T E M E   D E   R O U T I N G
    $p = isset($_GET['p']) ? stripcslashes(htmlentities($_GET['p'])) : 'en.Page.Home.Index'; // récupération de la page à charger
    $p = explode('.', $p); // division de la valeur de $p en un tableau de plusieurs éléments correspondants aux noms des fichiers à charger
    
    $controller = '\QueDuSal\Controller\\'.ucfirst($p[1]).'\\'.ucfirst($p[2]).'Controller'; // Appel du controleur correspondant

    //////////////////////////////////////////////////////////////////// C O N T R O L L E U R  E T  V U E
    $pages = ['', 'index', 'about_us', 'services', 'portfolio', 'pricing', 'blog', 'faq', 'contact_us', 'fake_european_union_eu_national_id_cards_legit_or_not', 'we_produce_to_quality_real_and_fake_drivers_license', 'hard_to_find_good_option_of_fake_passport'];

    if(!in_array(strtolower($p[3]), $pages)) {
        
        $action = 'not_found'; // Chargement de la méthode correspondante
    }else{
        
        $action = $p[3]; // Chargement de la méthode correspondante
    }

    // $action = $p[3]; // Chargement de la méthode correspondante
    // var_dump($action); die();

    $controller = new $controller(); // Appel de la page correspondante
    $controller->$action(); // Appel de la page correspondante

?>