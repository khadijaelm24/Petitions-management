<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature de Pétition</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.2/css/bootstrap.min.css">
    <style>
        .btn-light-blue {
            background-color: blue; 
            color: white; 
            width: 172.5vh;
        }
        .btn-light-blue:hover {
            background-color: blue; 
            color: white;
            width: 172.5vh;
        }
    </style>
    <script>
        function fetchSignatures() {
            var xhr = new XMLHttpRequest();
            xhr.open("GET", "AfficherSignatures.php?IDP=" + document.getElementById("IDP").value, true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById("signatureArea").value = xhr.responseText;
                } else {
                    document.getElementById("signatureArea").value = "Erreur de chargement";
                }
            };
            xhr.send();
        }

        function submitSignature() {
            var xhr = new XMLHttpRequest();
            var formData = new FormData(document.getElementById("signatureForm"));
            xhr.open("POST", "AjouterSignature.php", true);
            xhr.onload = function () {
                if (xhr.status === 200) {
                    document.getElementById("response").innerText = xhr.responseText;
                    clearFormFields(); 
                    fetchSignatures(); 
                } else {
                    document.getElementById("response").innerText = "Erreur de traitement";
                }
            };
            xhr.send(formData);
            event.preventDefault(); 
        }

        function clearFormFields() {
            document.getElementById("nom").value = "";
            document.getElementById("prenom").value = "";
            document.getElementById("email").value = "";
            document.getElementById("pays").value = "";
        }

        window.onload = fetchSignatures;
    </script>
</head>
<body class="bg-light">
    <div class="container mt-5">
        <h1 class="mb-4">Signature de Pétition</h1>
        
        <form id="signatureForm" onsubmit="submitSignature(); return false;" class="mb-4">
            <div class="form-group">
                <label for="IDP">IDP:</label>
                <input type="text" id="IDP" name="idp" class="form-control" value="<?php echo isset($_GET['IDP']) ? htmlspecialchars($_GET['IDP']) : 'Unknown'; ?>" required>
            </div>

            <div class="form-group">
                <label for="nom">Nom:</label>
                <input type="text" id="nom" name="nom" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="prenom">Prénom:</label>
                <input type="text" id="prenom" name="prenom" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="email">Email:</label>
                <input type="email" id="email" name="email" class="form-control" required>
            </div>

            <div class="form-group">
                <label for="pays">Pays:</label>
                <input type="text" id="pays" name="pays" class="form-control" required>
            </div>

            <center><button type="submit" class="btn btn-light-blue">Envoyer</button></center>
        </form>

        <div id="response" class="alert alert-info" role="alert" style="display: none;"></div> 
        
        <label for="signatureArea">Signatures récentes:</label>
        <textarea id="signatureArea" class="form-control" rows="10" readonly></textarea>
    </div>

    <br><br>

    <script src="https://code.jquery.com/jquery-3.5.1.slim.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.5.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
