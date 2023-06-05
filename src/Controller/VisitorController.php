<?php

namespace App\Controller;

use App\Entity\Contact;
use App\Form\ContactType;
use App\Repository\CarouselPictureRepository;
use App\Repository\ContactRepository;
use App\Repository\ServiceCardRepository;
use App\Repository\SiteMetadataRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

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
        $form = $this->createForm(ContactType::class, $newContact, [
            "action" => $this->generateUrl("app_visitor_contact")
        ]);

        return $this->render('visitor/index.html.twig', [
            'carousselPictures' => $carouselPictureRepository->findAll(),
            'serviceCards'      => $serviceCardRepository->findAll(),
            'siteMetadata'      => $siteMetadataRepository->findOneBy([]),
            'contactForm'       => $form->createView()
        ]);
    }

    #[Route('/contact', name: 'app_visitor_contact', methods: ["POST"])]
    public function contact(
        ContactRepository $contactRepository,
        Request $request
    ): JsonResponse
    {
        $newContact = new Contact();
        $form = $this->createForm(ContactType::class, $newContact);
        $form->handleRequest($request);
        if( $form->isSubmitted() && $form->isValid() ){
            $newContact->setCreatedAt(new \DateTimeImmutable("now"));
            $newContact->setIsHidden(false);
            $contactRepository->save($newContact, true);
            return new JsonResponse([
                "message" => "Votre message a été transmis, nous reviendrons vers vous prochainement!"
            ]);
        }
        return new JsonResponse([
            "message" => "Votre message n'a pas pu être envoyé."
        ], Response::HTTP_INTERNAL_SERVER_ERROR);
    }


    #[Route('/mentions-legales', name: 'app_visitor_legals')]
    public function legalMentions(
    ): Response
    {
        return $this->render('visitor/legals.html.twig', [

            ]);
    }
}
