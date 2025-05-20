<?php
use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\Exception;

require '../vendor/autoload.php';

/**
 * Envoie un email à l'utilisateur pour une création ou une mise à jour de ticket
 *
 * @param string $recipientEmail L'adresse email de l'utilisateur
 * @param string $ticketTitle Le titre du ticket
 * @param int $ticketId L'identifiant du ticket
 * @param string $actionType 'creation' ou 'update'
 * @param string|null $message Message additionnel ou nouveau statut
 * @param string|null $priority Priorité du ticket (optionnelle, surtout utile pour update)
 * @return array Résultat de l'envoi de l'email
 */
function sendTicketNotificationEmail(
    string $recipientEmail,
    string $ticketTitle,
    int $ticketId,
    string $actionType,
    string $message = null,
    string $priority = null
): array {
    $mail = new PHPMailer(true);
    $response = [
        'emailSent' => false,
        'error' => ''
    ];

    try {
        // Configuration SMTP
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = 'trackit.helper@gmail.com';
        $mail->Password = 'kjet feqc xezo artp';
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        // Destinataire
        $mail->setFrom('trackit.helper@gmail.com', 'Support Technique');
        $mail->addAddress($recipientEmail);

        // Contenu personnalisé selon l'action
        $mail->isHTML(true);

        if ($actionType === 'creation') {
            $mail->Subject = "Confirmation de creation de votre ticket #$ticketId";
            $mail->Body = "
                <h2>Bonjour,</h2>
                <p>Votre ticket a bien été enregistré avec le titre : <strong>$ticketTitle</strong>.</p>
                <p>Numéro du ticket : <strong>$ticketId</strong></p>
                <p>Message : $message</p>
                <p>Notre équipe vous répondra dans les plus brefs délais.</p>
                <br><p>Merci de votre confiance.</p>
            ";
            $mail->AltBody = "Votre ticket '$ticketTitle' (#$ticketId) a été enregistré.";
        } elseif ($actionType === 'update') {
            $mail->Subject = "Changement de statut de votre ticket #$ticketId";
            $priorityText = $priority ? "<p>Nouvelle priorité : <strong>$priority</strong></p>" : "";
            $mail->Body = "
                <h2>Bonjour,</h2>
                <p>Le statut de votre ticket <strong>$ticketTitle</strong> (#$ticketId) a été mis à jour.</p>
                <p>Nouveau statut : <strong>$message</strong></p>
                $priorityText
                <p>Nous vous tiendrons informé de toute autre évolution.</p>
                <br><p>Cordialement,<br>L'équipe support</p>
            ";
            $mail->AltBody = "Le statut de votre ticket '$ticketTitle' (#$ticketId) est maintenant : $message.";
        } elseif ($actionType === 'response') {
            // Réponse au ticket
            $mail->Subject = "Nouvelle reponse sur votre ticket #$ticketId";
            $mail->Body = "
                <h2>Bonjour,</h2>
                <p>Une nouvelle réponse a été ajoutée à votre ticket intitulé : <strong>$ticketTitle</strong> (#$ticketId).</p>
                <p><strong>Réponse :</strong> $message</p>
                <p>Nous vous encourageons à consulter votre ticket pour plus de détails.</p>
                <br><p>Cordialement,<br>L'équipe support</p>
            ";
            $mail->AltBody = "Une nouvelle réponse a été ajoutée à votre ticket '$ticketTitle' (#$ticketId).\nRéponse : $message";
        } else {
            throw new Exception("Type d'action invalide fourni à la fonction.");
        }

        // Envoi
        if ($mail->send()) {
            $response['emailSent'] = true;
        } else {
            $response['error'] = "Échec de l'envoi de l'email.";
        }

    } catch (Exception $e) {
        $response['error'] = "Erreur lors de l'envoi de l'email : {$mail->ErrorInfo}";
    }

    return $response;
}
?>
