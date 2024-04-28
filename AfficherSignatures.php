<?php
header('Content-Type: text/plain');  // Ensure we're outputting text

try {
    $bdd = new PDO('mysql:host=localhost:3308;dbname=petition;charset=utf8', 'root', '');
    $idp = isset($_GET['IDP']) ? $_GET['IDP'] : 0;  // Default to 0 if not provided

    $requete = $bdd->prepare("SELECT Nom, Prenom, Date, Heure FROM Signature WHERE IDP = ? ORDER BY IDS DESC LIMIT 5");
    $requete->execute([$idp]);
    $signatures = $requete->fetchAll(PDO::FETCH_ASSOC);

    $responseText = "";
    foreach ($signatures as $signature) {
        $responseText .= "Nom: " . $signature['Nom'] . ", Prénom: " . $signature['Prenom'] . ", Date: " . $signature['Date'] . ", Heure: " . $signature['Heure'] . "\n";
    }
    echo $responseText;
} catch (Exception $e) {
    echo 'Erreur : ' . $e->getMessage();
}
?>
