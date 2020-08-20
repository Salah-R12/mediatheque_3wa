<?php

namespace App\Service;

class SendMails
{
    protected $mailer;
    protected $templating;

    public function __construct(\Swift_Mailer $mailer,\Twig\Environment $templating)
    {
        $this->mailer = $mailer;
        $this->templating = $templating;
    }

    public function sendMailToNewStaff(string $staffMail, string $staffToken) : bool
    {   
        $message = (new \Swift_Message('Nouveau contact'))
            // On attribue l'expéditeur
            ->setFrom('radi.salah12@gmail.com')
            // On attribue le destinataire
            ->setTo($staffMail)
            // On crée le texte avec la vue
            ->setBody(
                $this->templating->render(
                    'emails/activation.html.twig', ['token' => $staffToken]
                ),
                'text/html'
            )
        ;
        return $this->mailer->send($message);
    }
}