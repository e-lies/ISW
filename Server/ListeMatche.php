<?php
    header('Access-Control-Allow-Origin: *');
    header('Access-Control-Allow-Headers: X-Requested-With, Access-Control-Allow-Headers,Access-Control-Allow-Methods, Content-Type, Origin');
    header('Content-Type: Application/json');
    header('Access-Control-Allow-Methods: GET, POST');    
    session_start();
    require_once('../Connexion.inc.php'); //permet d'intégrer l'objet $conx à ce fichier

    //Meesage personnalisé grace aux sessions
    //echo " <p> Bienvenu  <strong>".$_SESSION['userName']."</strong>, voici les matches de votre équipe";
    //Récupération de la liste personnalisée des matches
    try{
        $matches = $conx->query(
            "select matches.id, matches.score_guest, matches.score_visitor,
            (select nom from clubs where id = matches.id_guest) AS 'guest_name',
            (select logo from clubs where id = matches.id_guest) AS 'guest_logo',
            (select nom from clubs where id = matches.id_visitor) AS 'visitor_name',
            (select logo from clubs where id = matches.id_visitor) AS 'visitor_logo'
            from matches 
            join clubs AS guest on (matches.id_guest=guest.id) 
            join clubs as visitor on (matches.id_visitor=visitor.id) 
            join pays on (guest.id_pays=pays.id) 
            where pays.nom = '".$_GET['pays']."'")->fetchall(PDO::FETCH_ASSOC);
        sleep(3);
        if(sizeof($matches) > 0){
            http_response_code(200);
            //echo "<pre>"; print_r($matches);
            echo json_encode($matches,JSON_UNESCAPED_UNICODE|JSON_NUMERIC_CHECK);
        }
        else{
            http_response_code(400);
            echo "Pas de matches trouvés";
        }
    }
    catch(Exception $e) {
		http_response_code(500);
		echo "Une erreur s'est produite sur le serveur ! \n".$e;
	}
        
?>