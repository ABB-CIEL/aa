<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;
use Dotenv\Dotenv;

require 'vendor/autoload.php';

$dotenv = Dotenv::createImmutable(__DIR__);
$dotenv->load();

$mail = new PHPMailer(true);

try {
    $mail->isSMTP();
    $mail->Host = 'smtp.gmail.com';
    $mail->SMTPAuth = true;
    $mail->Username = $_ENV['SMTP_EMAIL'];
    $mail->Password = $_ENV['SMTP_PASSWORD'];
    $mail->SMTPSecure = 'tls';
    $mail->Port = 587;

  
    $nom = htmlspecialchars($_POST['nom'] ?? '');
    $email = filter_var($_POST['email'] ?? '', FILTER_SANITIZE_EMAIL);
    $message = htmlspecialchars($_POST['message'] ?? '');

    if (empty($nom) || empty($email) || empty($message)) {
        header('Location: contact.html?error=1&msg=Veuillez+remplir+tous+les+champs.');
        exit;
    }
    if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        header('Location: contact.html?error=1&msg=Adresse+email+invalide.');
        exit;
    }

    $mail->setFrom($email, $nom);
    $mail->addAddress('habbey480@gmail.com', 'Association de Football');

    $mail->isHTML(true);
    $mail->Subject = 'Message de contact depuis le site Association de Football';
    $mail->Body = "<h2>Nouveau message de contact</h2><p><strong>Nom :</strong> {$nom}</p><p><strong>Email :</strong> {$email}</p><p><strong>Message :</strong><br>" . nl2br($message) . "</p>";

    $mail->send();
    // Redirection vers contact.html avec succès (pour affichage du message dans le formulaire)
    header('Location: contact.html?success=1');
    exit;
} catch (Exception $e) {
    // Redirection vers contact.html avec message d'erreur
    header('Location: contact.html?error=1&msg=' . urlencode($mail->ErrorInfo));
    exit;
}