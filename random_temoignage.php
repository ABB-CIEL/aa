<?php
// Affiche un témoignage vedette aléatoire (pour index2.html)
$temoignages = file_exists('temoignages.txt') ? file('temoignages.txt', FILE_IGNORE_NEW_LINES) : [];
if (!empty($temoignages)) {
    $ligne = $temoignages[array_rand($temoignages)];
    $donnees = explode('|', $ligne);
    if (count($donnees) >= 3) {
        $nom = $donnees[0];
        $message = $donnees[1];
        $date = $donnees[2];
        $note = isset($donnees[3]) ? intval($donnees[3]) : 5;
        $photo = isset($donnees[4]) ? $donnees[4] : '';
        echo '<div class="testimonial featured" style="max-width:340px;margin:0 auto 24px auto;padding:24px 18px 18px 18px;background:linear-gradient(120deg,#fffbe6 60%,#eaf2fb 100%);border-radius:18px 18px 32px 32px;box-shadow:0 8px 32px #FFD70033,0 0 0 4px #FFD70022;border-bottom:5px solid #FFD700;border-top:2.5px solid #0056b3;position:relative;">';
        if ($photo) {
            echo '<img src="img/'.htmlspecialchars($photo).'" alt="Photo de '.htmlspecialchars($nom).'" style="width:60px;height:60px;object-fit:cover;border-radius:50%;margin-bottom:10px;border:2px solid #FFD700;box-shadow:0 2px 8px #FFD70033;">';
        }
        echo '<div style="margin-bottom:6px;">';
        for($i=1;$i<=5;$i++) {
            echo '<i class="fa-star'.($i<=$note?' fas':' far').'" style="color:#FFD700;"></i>';
        }
        echo '</div>';
        echo '<p style="font-size:1.1em;color:#222;margin-bottom:12px;font-style:italic;">"'.nl2br(htmlspecialchars($message)).'"</p>';
        echo '<h4 style="margin:0;font-weight:bold;color:#0056b3;font-size:1.08em;">- '.htmlspecialchars($nom).'</h4>';
        echo '<small style="color:#888;font-size:0.98em;">'.$date.'</small>';
        echo '</div>';
    }
}
?>
