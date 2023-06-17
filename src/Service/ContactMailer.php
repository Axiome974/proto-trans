<?php

namespace App\Service;


use App\Entity\Contact;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class ContactMailer{


    public function __construct(
        private MailerInterface $mailer,
        private UrlGeneratorInterface $urlGenerator
    )
    {
    }

    public function createTemplatedEmail(){

    }

    public function sendContactEmail(Contact $contact){
        $email = new TemplatedEmail();
        $email  ->from('nepasrepondre@transportproto.fr')
                ->htmlTemplate("emails/contact_email.html.twig")
                ->to($contact->getEmail())
                ->subject($contact->getName().' - '.$contact->getReason())
                ->context([
                    "contact" => $contact,
                    "absolutePath" => $this->urlGenerator->generate("app_admin_messages",[], UrlGenerator::ABSOLUTE_URL)
                ]);
        $this->send($email);
    }

    public function send( TemplatedEmail $email ){
        try {
            $this->mailer->send($email);
        }catch ( \Exception $e ){

        }
    }



}