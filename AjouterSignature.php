<?php
try {
    
    $bdd = new PDO('mysql:host=localhost:3308;dbname=petition;charset=utf8', 'root', '');

    
    $idp = $_POST['idp'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email']; 
    $pays = $_POST['pays'];
    $date = date('Y-m-d'); 
    $heure = date('H:i:s'); 

    $requete = $bdd->prepare('INSERT INTO Signature (IDP, Nom, Prenom, Pays, Date, Heure) VALUES (?, ?, ?, ?, ?, ?)');

    if ($requete->execute(array($idp, $nom, $prenom, $pays, $date, $heure))) {
        echo 'OK';
    } else {
        echo 'NotOK';
    }
} catch(Exception $e) {
    die('Erreur : ' . $e->getMessage());
}
?>
