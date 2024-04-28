<?php
try {
    // Connexion à la base de données
    $bdd = new PDO('mysql:host=localhost:3308;dbname=petition;charset=utf8', 'root', '');

    // Réception des données du formulaire
    $idp = $_POST['idp'];
    $nom = $_POST['nom'];
    $prenom = $_POST['prenom'];
    $email = $_POST['email']; // Cette variable est reçue mais non utilisée dans la table Signature selon votre structure actuelle.
    $pays = $_POST['pays'];
    $date = date('Y-m-d'); // La date d'aujourd'hui
    $heure = date('H:i:s'); // L'heure actuelle

    // Préparation de la requête d'insertion
    $requete = $bdd->prepare('INSERT INTO Signature (IDP, Nom, Prenom, Pays, Date, Heure) VALUES (?, ?, ?, ?, ?, ?)');

    // Exécution de la requête
    if ($requete->execute(array($idp, $nom, $prenom, $pays, $date, $heure))) {
        echo 'OK';
    } else {
        echo 'NotOK';
    }
} catch(Exception $e) {
    // En cas d'erreur, afficher le message d'erreur
    die('Erreur : ' . $e->getMessage());
}
?>
