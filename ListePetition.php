<?php
$host = 'localhost:3308';
$dbname = 'petition';
$username = 'root';
$password = '';

$pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
$pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

function getPetitionsTableHTML($pdo) {
    ob_start();
    echo "<tr><th>IDP</th><th>Titre</th><th>Thème</th><th>Description</th><th>Date de Publication</th><th>Date de Fin</th><th>Signature</th></tr>";
    $sql = "SELECT IDP, Titre, Theme, Description, DatePublic, DateFin FROM Petition";
    $stmt = $pdo->query($sql);
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['IDP']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Titre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Theme']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DatePublic']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DateFin']) . "</td>";
        echo "<td><a href='Signature.php?IDP=" . $row['IDP'] . "'>Signer</a></td>";
        echo "</tr>";
    }
    return ob_get_clean();
}

function getMostSignedPetitionHTML($pdo) {
    ob_start();
    $sql = "SELECT p.IDP, p.Titre, p.Theme, p.Description, p.DatePublic, p.DateFin, COUNT(s.IDP) AS SignatureCount
            FROM Petition p
            LEFT JOIN Signature s ON p.IDP = s.IDP
            GROUP BY p.Theme
            ORDER BY SignatureCount DESC LIMIT 1";
    $stmt = $pdo->query($sql);
    echo "<tr><th>IDP</th><th>Titre</th><th>Thème</th><th>Description</th><th>Date de Publication</th><th>Date de Fin</th><th>Signatures</th></tr>";
    if ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
        echo "<tr>";
        echo "<td>" . htmlspecialchars($row['IDP']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Titre']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Theme']) . "</td>";
        echo "<td>" . htmlspecialchars($row['Description']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DatePublic']) . "</td>";
        echo "<td>" . htmlspecialchars($row['DateFin']) . "</td>";
        echo "<td>" . $row['SignatureCount'] . "</td>";
        echo "</tr>";
    }
    return ob_get_clean();
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $titre = $_POST['titre'];
    $theme = $_POST['theme'];
    $description = $_POST['description'];
    $datePublic = $_POST['datePublic'];
    $dateFin = $_POST['dateFin'];

    $stmt = $pdo->prepare("INSERT INTO Petition (Titre, Theme, Description, DatePublic, DateFin) VALUES (?, ?, ?, ?, ?)");
    $stmt->execute([$titre, $theme, $description, $datePublic, $dateFin]);

    // Only return JSON data if it's an AJAX request
    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $response = [
            'petitionList' => getPetitionsTableHTML($pdo),
            'mostSignedPetition' => getMostSignedPetitionHTML($pdo)
        ];
        echo json_encode($response);
        exit(); // Stop further script execution
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Pétitions</title>
    <style>
        table, th, td {
            border: 1px solid black;
            border-collapse: collapse;
            padding: 8px;
            text-align: left;
        }
        th {
            background-color: #f2f2f2;
        }
        .modal {
            display: none;
            position: fixed;
            z-index: 1;
            left: 0;
            top: 0;
            width: 100%;
            height: 100%;
            overflow: auto;
            background-color: rgba(0,0,0,0.4);
        }
        .modal-content {
            background-color: #fefefe;
            margin: 15% auto;
            padding: 20px;
            border: 1px solid #888;
            width: 80%;
        }
        .close {
            color: #aaa;
            float: right;
            font-size: 28px;
            font-weight: bold;
        }
        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }
    </style>
    <script>
        function showModal() {
            document.getElementById('myModal').style.display = "block";
        }

        function closeModal() {
            document.getElementById('myModal').style.display = "none";
        }

        function submitForm() {
            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById("addPetitionForm"));
            xhr.open("POST", "ListePetition.php", true);  // Posting to the same URL.
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest'); // Header to identify AJAX request
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById("petitionList").innerHTML = response.petitionList;
                    document.getElementById("mostSignedPetition").innerHTML = response.mostSignedPetition;
                    closeModal();
                } else {
                    alert('Error adding petition.');
                }
            };
            xhr.send(formData);
            return false; // Prevent traditional form submission
        }
    </script>
</head>
<body>
    <h1>Liste des Pétitions</h1>
    <button onclick="showModal()" style="float:right;">Ajouter Petition</button>
    <div id="myModal" class="modal">
        <div class="modal-content">
            <span class="close" onclick="closeModal()">&times;</span>
            <form id="addPetitionForm" onsubmit="return submitForm();">
                <label for="titre">Titre:</label>
                <input type="text" id="titre" name="titre" required><br><br>
                <label for="theme">Thème:</label>
                <select id="theme" name="theme">
                    <option value="Environnement">Environnement</option>
                    <option value="Éducation">Éducation</option>
                    <option value="Technologie">Technologie</option>
                </select><br><br>
                <label for="description">Description:</label>
                <textarea id="description" name="description" required></textarea><br><br>
                <label for="datePublic">Date de Publication:</label>
                <input type="date" id="datePublic" name="datePublic" required><br><br>
                <label for="dateFin">Date de Fin:</label>
                <input type="date" id="dateFin" name="dateFin" required><br><br>
                <input type="submit" value="Ajouter">
            </form>
        </div>
    </div>
    
    <table id='petitionList'><?php if (!$_POST) { echo getPetitionsTableHTML($pdo); } ?></table>
    <br><br>
    
    <h1>La Pétition ayant le plus grand nombre de signatures par Thème</h1>

    <table id='mostSignedPetition'><?php if (!$_POST) { echo getMostSignedPetitionHTML($pdo); } ?></table>
    <br><br>

</body>
</html>
