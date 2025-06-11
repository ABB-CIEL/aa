<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {
  $nom = htmlspecialchars($_POST["nom"]);
  $email = htmlspecialchars($_POST["email"]);
  $message = htmlspecialchars($_POST["message"]);

  // Pour test : afficher dans le navigateur
  echo "<h2>Message reçu :</h2>";
  echo "<p><strong>Nom :</strong> $nom</p>";
  echo "<p><strong>Email :</strong> $email</p>";
  echo "<p><strong>Message :</strong><br>$message</p>";
} else {
  echo "Aucune donnée reçue.";
}
?>
