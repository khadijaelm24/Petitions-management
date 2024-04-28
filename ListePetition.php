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

    if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) == 'xmlhttprequest') {
        $response = [
            'petitionList' => getPetitionsTableHTML($pdo),
            'mostSignedPetition' => getMostSignedPetitionHTML($pdo)
        ];
        echo json_encode($response);
        exit(); 
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Liste des Pétitions</title>
    <!-- Bootstrap CSS -->
    <link href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css" rel="stylesheet">
    <style>
        .modal-content {
            background-color: #fefefe;
            padding: 20px;
            border: 1px solid #888;
        }
        .close:hover,
        .close:focus {
            color: black;
            cursor: pointer;
        }
        .btn-primary {
            background-color: blue; 
            border-color: #9dc8e2;
        }
        .btn-primary:hover {
            background-color: blue; 
            border-color: #82b2c1;
        }
    </style>
</head>
<body class="bg-light">
    <div class="container mt-4">

    <div class="d-flex justify-content-between align-items-center mb-4">
        <h4>Liste des Pétitions</h4>
        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#myModal">
            Ajouter Pétition
        </button>
    </div>

        <div class="modal fade" id="myModal">
            <div class="modal-dialog">
                <div class="modal-content">
                    <div class="modal-header">
                        <h4 class="modal-title">Ajouter une nouvelle pétition</h4>
                        <button type="button" class="close" data-dismiss="modal">&times;</button>
                    </div>
                    <div class="modal-body">
                        <form id="addPetitionForm" onsubmit="return submitForm();">
                            <div class="form-group">
                                <label for="titre">Titre:</label>
                                <input type="text" id="titre" name="titre" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="theme">Thème:</label>
                                <select id="theme" name="theme" class="form-control">
                                    <option value="Environnement">Environnement</option>
                                    <option value="Éducation">Éducation</option>
                                    <option value="Technologie">Technologie</option>
                                </select>
                            </div>
                            <div class="form-group">
                                <label for="description">Description:</label>
                                <textarea id="description" name="description" class="form-control" required></textarea>
                            </div>
                            <div class="form-group">
                                <label for="datePublic">Date de Publication:</label>
                                <input type="date" id="datePublic" name="datePublic" class="form-control" required>
                            </div>
                            <div class="form-group">
                                <label for="dateFin">Date de Fin:</label>
                                <input type="date" id="dateFin" name="dateFin" class="form-control" required>
                            </div>
                            <button type="submit" class="btn btn-primary">Ajouter</button>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        
        <table class="table table-bordered mt-5" id='petitionList'>
            <?php if (!$_POST) { echo getPetitionsTableHTML($pdo); } ?>
        </table>

        <div class="d-flex justify-content-between align-items-center mb-4">
            <h4 class="mt-5">La Pétition ayant le plus grand nombre de signatures par Thème</h4>            
        </div>

        <table class="table table-bordered" id='mostSignedPetition'>
            <?php if (!$_POST) { echo getMostSignedPetitionHTML($pdo); } ?>
        </table>
    </div>
    <!-- Bootstrap JS and dependencies -->
    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>

    <script>
        function showModal() {
            $('#myModal').modal('show');
        }
        function closeModal() {
            $('#myModal').modal('hide');
        }
        function submitForm() {
            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById("addPetitionForm"));
            xhr.open("POST", "ListePetition.php", true);
            xhr.setRequestHeader('X-Requested-With', 'XMLHttpRequest');
            xhr.onload = function () {
                if (xhr.status === 200) {
                    var response = JSON.parse(xhr.responseText);
                    document.getElementById("petitionList").innerHTML = response.petitionList;
                    document.getElementById("mostSignedPetition").innerHTML = response.mostSignedPetition;
                    $('#myModal').modal('hide');
                } else {
                    alert('Error adding petition.');
                }
            };
            xhr.send(formData);
            return false;
        }
    </script>

</body>
</html>