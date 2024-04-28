<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Signature de Pétition</title>
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
                    clearFormFields(); // Call to clear fields after successful form submission
                    fetchSignatures(); // Update the signatures list
                } else {
                    document.getElementById("response").innerText = "Erreur de traitement";
                }
            };
            xhr.send(formData);
            event.preventDefault(); // Prevent the form from submitting in the traditional way
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
<body>
    <h1>Signature de Pétition</h1>
    
    <form id="signatureForm" onsubmit="submitSignature(); return false;">
        <label for="IDP">IDP:</label>
        <input type="text" id="IDP" name="idp" value="<?php echo isset($_GET['IDP']) ? htmlspecialchars($_GET['IDP']) : 'Unknown'; ?>" required>
        <br><br>

        <label for="nom">Nom:</label>
        <input type="text" id="nom" name="nom" required>
        <br><br>

        <label for="prenom">Prénom:</label>
        <input type="text" id="prenom" name="prenom" required>
        <br><br>

        <label for="email">Email:</label>
        <input type="email" id="email" name="email" required>
        <br><br>

        <label for="pays">Pays:</label>
        <input type="text" id="pays" name="pays" required>
        <br><br>

        <input type="submit" value="Envoyer">
    </form>

    <div id="response"></div> <!-- This will display "OK" or "NotOK" -->

    <br>
    <textarea id="signatureArea" rows="10" cols="50" readonly></textarea>
</body>
</html>
