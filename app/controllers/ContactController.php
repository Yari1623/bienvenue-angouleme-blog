<?php
// app/controllers/ContactController.php

namespace App\Controllers;

use App\Core\Controller;
use App\Core\Csrf;
use App\Core\Flash;

class ContactController extends Controller
{
    public function index(): void
    {
        $this->view('contact/index');
    }

    public function send(): void
    {
        $token = $_POST['_csrf'] ?? null;
        if (!Csrf::validate($token)) {
            Flash::set('error', 'Token CSRF invalide. Veuillez réessayer.');
            header('Location: ' . BASE_URL . '/contact');
            exit;
        }

        $firstName = trim($_POST['first_name'] ?? '');
        $lastName  = trim($_POST['last_name']  ?? '');
        $email     = trim($_POST['email']      ?? '');
        $phone     = trim($_POST['phone']      ?? '');
        $company   = trim($_POST['company']    ?? '');
        $subject   = trim($_POST['subject']    ?? '');
        $message   = trim($_POST['message']    ?? '');
        $consent   = isset($_POST['consent']);

        $errors = [];
        if (empty($firstName)) $errors[] = 'Le prénom est obligatoire.';
        if (empty($lastName))  $errors[] = 'Le nom est obligatoire.';
        if (empty($email) || !filter_var($email, FILTER_VALIDATE_EMAIL)) $errors[] = 'Email invalide.';
        if (empty($subject))   $errors[] = 'Veuillez choisir un sujet.';
        if (mb_strlen($message) < 20) $errors[] = 'Le message doit contenir au moins 20 caractères.';
        if (!$consent) $errors[] = 'Vous devez accepter la politique de confidentialité.';

        if (!empty($errors)) {
            foreach ($errors as $err) {
                Flash::set('error', $err);
            }
            header('Location: ' . BASE_URL . '/contact');
            exit;
        }

        $subjectLabels = [
            'question'    => 'Question générale',
            'signalement' => 'Signalement de contenu',
            'suggestion'  => "Suggestion d'article",
            'evenement'   => 'Proposer un événement',
            'partenariat' => 'Partenariat',
            'rgpd'        => 'Demande RGPD',
            'autre'       => 'Autre',
        ];
        $subjectLabel = $subjectLabels[$subject] ?? $subject;
        $to           = 'contact@bienvenue-angouleme.fr';

        $mailSubject = '[Blog Angoulême] ' . $subjectLabel . ' — ' . $firstName . ' ' . $lastName;
        $mailBody    = "Nouveau message depuis le formulaire de contact\n\n"
                     . "Prénom    : {$firstName}\n"
                     . "Nom       : {$lastName}\n"
                     . "Email     : {$email}\n"
                     . "Téléphone : " . ($phone ?: '—') . "\n"
                     . "Entreprise: " . ($company ?: '—') . "\n"
                     . "Sujet     : {$subjectLabel}\n\n"
                     . "Message :\n{$message}";

        $headers  = "From: noreply@bienvenue-angouleme.fr\r\n";
        $headers .= "Reply-To: {$email}\r\n";
        $headers .= "Content-Type: text/plain; charset=UTF-8\r\n";

        @mail($to, $mailSubject, $mailBody, $headers);

        Flash::set('success', 'Votre message a bien été envoyé ! Nous vous répondrons dans les 48h.');
        header('Location: ' . BASE_URL . '/contact');
        exit;
    }
}