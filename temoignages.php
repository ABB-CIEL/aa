<?php
// Enregistrement du témoignage s'il y a une soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $message = htmlspecialchars($_POST['message']);
    $date = date("d/m/Y à H:i");

    if (!empty($nom) && !empty($message)) {
        $temoignage = "$nom|$message|$date\n";
        file_put_contents("temoignages.txt", $temoignage, FILE_APPEND);
    }
}

// Chargement des témoignages en s'assurant qu'ils sont bien sous forme de tableau
$temoignages = file_exists("temoignages.txt") ? file("temoignages.txt", FILE_IGNORE_NEW_LINES) : [];
if (!is_array($temoignages)) {
    $temoignages = [];
}
?>

<!DOCTYPE html>
<html lang="fr">
<head>
  <meta charset="UTF-8">
  <title>Témoignages</title>
  <link rel="stylesheet" href="style.css">
  <style>
    #temoignages { padding: 40px; background: #f9f9f9; text-align: center; }
    .testimonials { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-top: 30px; }
    .testimonial { background: white; padding: 20px; border-radius: 10px; box-shadow: 0 2px 8px rgba(0,0,0,0.1); max-width: 300px; font-style: italic; }
    .testimonial h4 { margin-top: 10px; font-weight: bold; color: #0056b3; }
    .form-container { background: white; padding: 30px; max-width: 600px; margin: 40px auto; border-radius: 10px; box-shadow: 0 2px 10px rgba(0,0,0,0.1); }
    .form-container input, .form-container textarea { width: 100%; padding: 10px; margin-top: 10px; border: 1px solid #ccc; border-radius: 6px; }
    .form-container button { background: #0055a5; color: white; padding: 10px 20px; border: none; border-radius: 5px; margin-top: 15px; cursor: pointer; }
    .form-container button:hover { background: #003f7f; }
  </style>
</head>
<body>

  <header>
    <a href="index.html" style="text-decoration: none; color: white;">
      <h1>Nom du Site</h1>
    </a>
  </header>

 <nav>
  <ul>
    <li><a href="index2.html">Accueil</a></li>
    <li><a href="events.html">Événements</a></li>
    <li><a href="contact.html">Contact</a></li>
    <li><a href="temoignages.php">Témoignages</a></li>
  </ul>
</nav>

  <section id="temoignages">
    <h2>Vos témoignages</h2>

    <div class="testimonials">
      <?php if (empty($temoignages)) : ?>
        <p>Aucun témoignage pour le moment. Soyez le premier à laisser un message !</p>
      <?php else : ?>
        <?php foreach (array_reverse($temoignages) as $ligne) {
            $donnees = explode('|', $ligne);
            if (count($donnees) === 3) {
                list($nom, $message, $date) = $donnees;
        ?>
          <div class="testimonial">
            <p>"<?= nl2br($message) ?>"</p>
            <h4>- <?= $nom ?></h4>
            <small><?= $date ?></small>
          </div>
        <?php 
            }
        } ?>
      <?php endif; ?>
    </div>
  </section>

  <section class="form-container">
    <h2>Laisser un témoignage</h2>
    <form method="post">
      <label for="nom">Nom :</label>
      <input type="text" name="nom" id="nom" required>

      <label for="message">Message :</label>
      <textarea name="message" id="message" rows="5" required></textarea>

      <button type="submit">Envoyer</button>
    </form>
  </section>

  <footer>
    &copy; 2025 Nom du Site – Tous droits réservés
  </footer>

</body>
</html>
