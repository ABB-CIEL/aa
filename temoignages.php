<?php
// Enregistrement du témoignage s'il y a une soumission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $nom = htmlspecialchars($_POST['nom']);
    $message = htmlspecialchars($_POST['message']);
    $date = date("d/m/Y à H:i");

    // Gestion de la note (étoiles)
    $note = isset($_POST['note']) ? intval($_POST['note']) : 0;
    if ($note < 1 || $note > 5) $note = 5;

    // Gestion de la photo (upload simple)
    $photo = '';
    if (isset($_FILES['photo']) && $_FILES['photo']['error'] === UPLOAD_ERR_OK) {
        $ext = strtolower(pathinfo($_FILES['photo']['name'], PATHINFO_EXTENSION));
        $allowed = ['jpg','jpeg','png','gif'];
        if (in_array($ext, $allowed)) {
            $filename = uniqid('temoin_').'.'.$ext;
            $dest = 'img/'.$filename;
            if (move_uploaded_file($_FILES['photo']['tmp_name'], $dest)) {
                $photo = $filename;
            }
        }
    }

    if (!empty($nom) && !empty($message)) {
        $temoignage = "$nom|$message|$date|$note|$photo\n";
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
  <style>
    .header-text h1 {
      font-size: 2.3em;
      font-weight: bold;
      color: #0056b3;
      letter-spacing: 1.5px;
      text-shadow: 0 2px 12px #FFD70033, 0 1px 0 #fff;
      margin-bottom: 0.15em;
      margin-top: 0.1em;
    }
    .header-text p {
      font-size: 1.18em;
      color: #FFD700;
      font-style: italic;
      text-shadow: 0 2px 8px #0056b322, 0 1px 0 #fffbe6;
      margin-top: 0;
      margin-bottom: 0.2em;
      letter-spacing: 0.5px;
    }
    @media (max-width: 600px) {
      .header-text h1 { font-size: 1.3em; }
      .header-text p { font-size: 1em; }
    }
  </style>
  <meta charset="UTF-8">
  <title>Témoignages</title>
  <link rel="stylesheet" href="style.css">
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
  <style>
    body.page-fade {
      opacity: 0;
      transition: opacity 0.7s cubic-bezier(.4,0,.2,1);
    }
    body.page-fade.in {
      opacity: 1;
    }
  </style>
  <style>
    #temoignages { padding: 40px; background: #f9f9f9; text-align: center; }
    .testimonials { display: flex; flex-wrap: wrap; justify-content: center; gap: 20px; margin-top: 30px; }
    .testimonial {
      background: linear-gradient(120deg, #fff 70%, #eaf2fb 100%);
      padding: 28px 22px 22px 22px;
      border-radius: 18px 18px 32px 32px;
      box-shadow: 0 2px 12px rgba(0,0,0,0.10), 0 0px 0px #FFD70000;
      max-width: 320px;
      font-style: italic;
      border-bottom: 4px solid #FFD700;
      border-top: 2px solid #0056b3;
      position: relative;
      overflow: hidden;
      transition: box-shadow 0.35s cubic-bezier(.4,0,.2,1), transform 0.35s cubic-bezier(.4,0,.2,1), background 0.35s cubic-bezier(.4,0,.2,1), border 0.35s cubic-bezier(.4,0,.2,1);
    }
    .testimonial::before {
      content: "\f10d";
      font-family: 'Font Awesome 6 Free';
      font-weight: 900;
      color: #FFD700;
      font-size: 2.2em;
      position: absolute;
      top: 12px;
      left: 18px;
      opacity: 0.18;
      pointer-events: none;
    }
    .testimonial:hover {
      box-shadow: 0 12px 36px 0 #0056b355, 0 2px 24px 0 #FFD70055, 0 0 0 6px #FFD70022;
      background: linear-gradient(120deg, #fffbe6 60%, #eaf2fb 100%);
      transform: scale(1.045) rotate(-2deg);
      border-bottom: 6px solid #FFD700;
      border-top: 2.5px solid #0056b3;
      z-index: 2;
    }
    .testimonial p {
      font-size: 1.13em;
      color: #222;
      margin-bottom: 18px;
      margin-top: 0;
      text-shadow: 0 1px 0 #fff, 0 2px 8px #FFD70011;
    }
    .testimonial h4 {
      margin-top: 10px;
      font-weight: bold;
      color: #0056b3;
      font-size: 1.08em;
      letter-spacing: 0.5px;
      margin-bottom: 2px;
    }
    .testimonial small {
      color: #888;
      font-size: 0.98em;
      letter-spacing: 0.2px;
    }
    .testimonial h4 { margin-top: 10px; font-weight: bold; color: #0056b3; }
    .form-container {
      background: linear-gradient(120deg, #fffbe6 60%, #eaf2fb 100%);
      padding: 34px 32px 32px 32px;
      max-width: 600px;
      margin: 40px auto;
      border-radius: 18px 18px 32px 32px;
      box-shadow: 0 6px 32px 0 #0056b322, 0 2px 24px 0 #FFD70022, 0 0 0 6px #FFD70011;
      border-bottom: 5px solid #FFD700;
      border-top: 2.5px solid #0056b3;
      position: relative;
      transition: box-shadow 0.35s cubic-bezier(.4,0,.2,1), transform 0.35s cubic-bezier(.4,0,.2,1), background 0.35s cubic-bezier(.4,0,.2,1), border 0.35s cubic-bezier(.4,0,.2,1);
    }
    .form-container:hover {
      box-shadow: 0 16px 48px 0 #0056b355, 0 4px 32px 0 #FFD70055, 0 0 0 10px #FFD70022;
      background: linear-gradient(120deg, #fff 60%, #eaf2fb 100%);
      transform: scale(1.025) rotate(-1deg);
      border-bottom: 7px solid #FFD700;
      border-top: 3px solid #0056b3;
      z-index: 2;
    }
    .form-container input, .form-container textarea {
      width: 100%;
      padding: 12px;
      margin-top: 12px;
      border: 1.5px solid #ccc;
      border-radius: 8px;
      font-size: 1.08em;
      transition: border 0.25s, box-shadow 0.25s;
    }
    .form-container input:focus, .form-container textarea:focus {
      border: 1.5px solid #FFD700;
      box-shadow: 0 0 0 2px #FFD70033;
      outline: none;
    }
    .form-container button {
      background: linear-gradient(90deg, #0056b3 60%, #FFD700 100%);
      color: white;
      padding: 12px 24px;
      border: none;
      border-radius: 8px 8px 18px 18px;
      margin-top: 18px;
      cursor: pointer;
      font-size: 18px;
      font-weight: bold;
      letter-spacing: 0.5px;
      box-shadow: 0 2px 10px rgba(0,0,0,0.10);
      position: relative;
      overflow: hidden;
      transition: background 0.35s cubic-bezier(.4,0,.2,1), color 0.35s, box-shadow 0.35s, transform 0.35s;
    }
    .form-container button:hover {
      background: linear-gradient(90deg, #FFD700 60%, #0056b3 100%);
      color: #003366;
      transform: scale(1.06) rotate(-2deg);
      box-shadow: 0 8px 24px #FFD70033, 0 0 0 4px #FFD70022;
      z-index: 1;
    }
  </style>
</head>
<body>

  <script>
    // Animation d'apparition de la page
    document.addEventListener('DOMContentLoaded', function() {
      document.body.classList.add('page-fade');
      setTimeout(function(){
        document.body.classList.add('in');
      }, 10);
    });
    // Animation de transition lors des changements de page
    document.querySelectorAll('a').forEach(function(link) {
      if(link.target==='_blank' || link.href.startsWith('javascript:')) return;
      link.addEventListener('click', function(e) {
        // Lien interne uniquement
        if(link.hostname === window.location.hostname && link.pathname !== window.location.pathname) {
          e.preventDefault();
          document.body.classList.remove('in');
          setTimeout(function(){
            window.location = link.href;
          }, 400);
        }
      });
    });
  </script>
  <header>
     <a href="index2.html">
      <img src="img/b.png" alt="Logo du site" class="logo">
    </a>
   <h1>Association de Football</h1>
      <p>"Le football, plus qu'un jeu, une famille."</p>
    </a>
  </header>

  <nav class="main-nav">
    <button class="menu-toggle" aria-label="Ouvrir le menu" onclick="document.querySelector('.main-nav ul').classList.toggle('show')">
      <span class="sr-only">Ouvrir le menu</span>
      <i class="fas fa-bars"></i>
    </button>
    <ul class="nav-links" style="display: flex; justify-content: center; align-items: center; gap: 18px; padding: 0; margin: 0 auto; list-style: none; max-width: 700px; width: 100%;">
      <li><a href="index2.html"><i class="fas fa-home" title="Accueil"></i> Accueil</a></li>
      <li><a href="events.html"><i class="fas fa-calendar-alt" title="Événements"></i> Événements</a></li>
      <li><a href="about.html"><i class="fas fa-users" title="À propos"></i> À propos</a></li>
      <li><a href="contact.html"><i class="fas fa-envelope" title="Contact"></i> Contact</a></li>
      <li><a href="temoignages.php" class="active"><i class="fas fa-star" title="Témoignages"></i> Témoignages</a></li>
    </ul>
  </nav>


  <!-- Motif watermark ballon premium : SVG décoratif global -->
  <svg class="hero-watermark" style="position:fixed;left:50%;top:50%;transform:translate(-50%,-50%);z-index:0;opacity:0.10;pointer-events:none;" width="820" height="820" viewBox="0 0 820 820" fill="none" xmlns="http://www.w3.org/2000/svg">
    <defs>
      <linearGradient id="circleSplit" x1="0" y1="0" x2="820" y2="0" gradientUnits="userSpaceOnUse">
        <stop offset="0%" stop-color="#111"/>
        <stop offset="50%" stop-color="#111"/>
        <stop offset="50.1%" stop-color="#0056b3"/>
        <stop offset="100%" stop-color="#0056b3"/>
      </linearGradient>
    </defs>
    <circle cx="410" cy="410" r="340" stroke="url(#circleSplit)" stroke-width="14" fill="#fffbe6" fill-opacity="0.10"/>
    <polygon points="410,250 500,310 500,420 410,480 320,420 320,310" fill="#FFD700" fill-opacity="0.18" stroke="#fff" stroke-width="7"/>
    <polygon points="500,310 590,250 590,360 500,420 500,310" fill="#FFD700" fill-opacity="0.10" stroke="#0056b3" stroke-width="5"/>
    <polygon points="320,310 230,250 230,360 320,420 320,310" fill="#FFD700" fill-opacity="0.10" stroke="#111" stroke-width="5"/>
    <polygon points="500,420 590,360 590,470 500,530 500,420" fill="#FFD700" fill-opacity="0.08" stroke="#0056b3" stroke-width="5"/>
    <polygon points="320,420 230,360 230,470 320,530 320,420" fill="#FFD700" fill-opacity="0.08" stroke="#111" stroke-width="5"/>
    <polygon points="410,480 500,530 500,640 410,700 320,640 320,530" fill="#FFD700" fill-opacity="0.07" stroke="#111" stroke-width="5"/>
    <path d="M270 320 Q410 180 550 320" stroke="#fff" stroke-width="18" stroke-linecap="round" opacity="0.18"/>
    <path d="M320 640 Q410 760 500 640" stroke="#fff" stroke-width="14" stroke-linecap="round" opacity="0.10"/>
    <circle cx="410" cy="410" r="340" stroke="url(#circleSplit)" stroke-width="4" fill="none"/>
    <circle cx="410" cy="410" r="400" stroke="#0056b3" stroke-width="2" fill="none"/>
  </svg>
  <section id="temoignages">
    <h2>Vos témoignages</h2>

    <div class="testimonials">
      <?php if (empty($temoignages)) : ?>
        <p>Aucun témoignage pour le moment. Soyez le premier à laisser un message !</p>
      <?php else : ?>
        <?php foreach (array_reverse($temoignages) as $ligne) {
            $donnees = explode('|', $ligne);
            // Ancien format : 3 champs, nouveau : 5
            if (count($donnees) >= 3) {
                $nom = $donnees[0];
                $message = $donnees[1];
                $date = $donnees[2];
                $note = isset($donnees[3]) ? intval($donnees[3]) : 5;
                $photo = isset($donnees[4]) ? $donnees[4] : '';
        ?>
          <div class="testimonial">
            <?php if ($photo) : ?>
              <img src="img/<?= htmlspecialchars($photo) ?>" alt="Photo de <?= htmlspecialchars($nom) ?>" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-bottom:10px;border:2px solid #FFD700;box-shadow:0 2px 8px #FFD70033;">
            <?php endif; ?>
            <div style="margin-bottom:6px;">
              <?php for($i=1;$i<=5;$i++): ?>
                <i class="fa-star<?= $i<=$note?' fas':' far' ?>" style="color:#FFD700;"></i>
              <?php endfor; ?>
            </div>
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
    <form method="post" enctype="multipart/form-data">
      <label for="nom">Nom :</label>
      <input type="text" name="nom" id="nom" required>

      <label for="note">Note :</label>
      <div style="margin-bottom:10px;direction:rtl;text-align:left;">
        <?php for($i=5;$i>=1;$i--): ?>
          <input type="radio" name="note" id="star<?= $i ?>" value="<?= $i ?>" style="display:none;">
          <label for="star<?= $i ?>" style="font-size:1.5em;cursor:pointer;color:#FFD700;" onclick="setStars(<?= $i ?>)"><i class="fa-star far"></i></label>
        <?php endfor; ?>
      </div>
      <script>
      // Affichage dynamique des étoiles sélectionnées
      function setStars(val) {
        for(let i=1;i<=5;i++) {
          document.getElementById('star'+i).checked = (i===val);
        }
        updateStars();
      }
      function updateStars() {
        for(let i=1;i<=5;i++) {
          let star = document.querySelector('label[for=star'+i+'] i');
          if(document.getElementById('star'+i).checked) {
            for(let j=1;j<=5;j++) {
              let s = document.querySelector('label[for=star'+j+'] i');
              if(j<=i) s.className = 'fa-star fas';
              else s.className = 'fa-star far';
            }
            break;
          }
        }
      }
      // Initialisation au chargement
      document.addEventListener('DOMContentLoaded', function() {
        // Par défaut, 5 étoiles cochées
        document.getElementById('star5').checked = true;
        updateStars();
        // Ajout du clic sur les labels pour la sélection
        for(let i=1;i<=5;i++) {
          document.getElementById('star'+i).addEventListener('change', updateStars);
        }
      });
      </script>

      <label for="photo">Photo (optionnel) :</label>
      <input type="file" name="photo" id="photo" accept="image/*" style="margin-bottom:10px;">

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
