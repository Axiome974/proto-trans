<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Entity\Quotation;
use App\Form\ContactType;
use App\Form\QuotationType;
use App\Repository\CarouselPictureRepository;
use App\Repository\ContactRepository;
use App\Repository\QuotationRepository;
use App\Repository\ServiceCardRepository;
use App\Repository\SiteMetadataRepository;
use App\Service\ContactMailer;
use Symfony\Bridge\Twig\Mime\TemplatedEmail;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\Form\FormError;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Mailer\Exception\TransportExceptionInterface;
use Symfony\Component\Mailer\MailerInterface;
use Symfony\Component\Mime\Email;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Routing\Generator\UrlGenerator;
use Symfony\Component\Routing\Generator\UrlGeneratorInterface;

class VisitorController extends AbstractController
{
    #[Route('/', name: 'app_visitor')]
    public function index(
        CarouselPictureRepository $carouselPictureRepository,
        ServiceCardRepository $serviceCardRepository,
        SiteMetadataRepository $siteMetadataRepository
    ): Response
    {
        $newContact = new Contact();
        $contactForm = $this->createForm(ContactType::class, $newContact, [
            "action" => $this->generateUrl("app_visitor_contact")
        ]);



        return $this->render('visitor/index.html.twig', [
            'carousselPictures' => $carouselPictureRepository->findAll(),
            'serviceCards'      => $serviceCardRepository->findAll(),
            'siteMetadata'      => $siteMetadataRepository->findOneBy([]),
            'contactForm'       => $contactForm->createView(),
        ]);
    }

    #[Route('/contact', name: 'app_visitor_contact', methods: ["POST"])]
    public function contact(
        ContactRepository $contactRepository,
        Request $request,
        ContactMailer $contactMailer
    ): JsonResponse
    {
        $newContact = new Contact();
        $form = $this->createForm(ContactType::class, $newContact);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $newContact->setCreatedAt(new \DateTimeImmutable("now"));
            $newContact->setIsHidden(false);
            $contactRepository->save($newContact, true);
            $contactMailer->sendContactEmail($newContact);

            return new JsonResponse([
                "message" => "Votre message a été transmis, nous reviendrons vers vous prochainement!"
            ]);
        }
        return new JsonResponse([
            "message" => "Votre message n'a pas pu être envoyé."
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }

    #[Route('/quotation', name: 'app_visitor_quotation', methods: ["GET", "POST"])]
    public function quoation(
        QuotationRepository $quotationRepository,
        Request $request,
        ContactMailer $contactMailer
    ): Response
    {
        // Quotation form
        $now = new \DateTimeImmutable("now");
        $next = $now->modify("+7days");
        $quotation = new Quotation();
        $quotation->setDepartureAt($next->setTime(9,0,0));
        $quotation->setArrivalAt($next->setTime(18,0,0));
        $form = $this->createForm(QuotationType::class, $quotation, [
            "action" => $this->generateUrl("app_visitor_quotation")
        ]);
        $form->handleRequest($request);
        $success = false;

        if( $form->isSubmitted() && $form->isValid() ) {
            $quotation->setCreatedAt(new \DateTimeImmutable("now"));
            $quotation->setIsHidden(false);
            $quotationRepository->save($quotation, true);
            $contactMailer->sendQuotationEmail($quotation);
            $success = true;
        }
        return $this->render("visitor/quotation_form.html.twig",[
            'quotationForm'     => $form->createView(),
            'success'           => $success
        ]);
    }


    #[Route('/mentions-legales', name: 'app_visitor_legals')]
    public function legalMentions(
        SiteMetadataRepository $siteMetadataRepository
    ): Response
    {
        return $this->render('visitor/legals.html.twig', [
            'siteMetadata'      => $siteMetadataRepository->findOneBy([]),
        ]);
    }

    #[Route('/conditions-utilisation', name: 'app_visitor_conditions')]
    public function conditions(
    ): Response
    {
        return $this->render('visitor/conditions.html.twig', [
        ]);
    }
}
