<?php

namespace App\Service;


use App\Entity\Contact;
use App\Entity\Quotation;
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

    public function sendContactEmail(Contact $contact): void
    {
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

    public function sendQuotationEmail(Quotation $quotation): void
    {
        $email = new TemplatedEmail();
        $email  ->from('nepasrepondre@transportproto.fr')
            ->htmlTemplate("emails/quotation_email.html.twig")
            ->to($quotation->getEmail())
            ->subject($quotation->getFullName().' - demande de devis')
            ->context([
                "quotation" => $quotation,
                "absolutePath" => $this->urlGenerator->generate("app_admin_quotations",[], UrlGenerator::ABSOLUTE_URL)
            ]);
        $this->send($email);
    }

    public function send( TemplatedEmail $email ): void
    {
        try {
            $this->mailer->send($email);
        }catch ( \Exception $e ){
            dd($e);
        }
    }



}