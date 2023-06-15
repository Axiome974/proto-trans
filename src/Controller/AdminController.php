<?php

namespace App\Controller;

use App\Entity\CarouselPicture;
use App\Entity\Contact;
use App\Entity\Quotation;
use App\Entity\ServiceCard;
use App\Entity\SiteMetadata;
use App\Form\CarouselPictureType;
use App\Form\ServiceCardType;
use App\Form\SiteMetadataType;
use App\Repository\CarouselPictureRepository;
use App\Repository\ContactRepository;
use App\Repository\QuotationRepository;
use App\Repository\ServiceCardRepository;
use App\Repository\SiteMetadataRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\HttpFoundation\RedirectResponse;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\IsGranted;

#[IsGranted("ROLE_ADMIN")]
#[Route('/admin', name: 'app_admin_')]
class AdminController extends AbstractController
{
    #[Route('/', name: 'home')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig');
    }

    #[Route('/messages/count', name: 'messages_count')]
    public function getMessages(
        ContactRepository $contactRepository,
        QuotationRepository $quotationRepository
    ): JsonResponse
    {
        return new JsonResponse([
            "messages" => $contactRepository->count([
                "isHidden" => false
            ]),
            "quotations"     => $quotationRepository->count([
                "isHidden" => false
            ])
        ]);
    }

    #[Route('/messages', name: 'messages')]
    public function message(
        ContactRepository $contactRepository,
        Request $request
    ): Response
    {
        if( $request->query->get("archive")) {
            $isHidden = true;
        }else{
            $isHidden = false;
        }
        return $this->render('admin/messages.html.twig', [
            "messages"      => $contactRepository->findBy([
                "isHidden"  => $isHidden
            ]),
            "isArchive" => $isHidden
        ]);
    }

    #[Route('/messages/{id}/hide', name: 'messages_hide')]
    public function messageHide(
        ContactRepository $contactRepository,
        Contact $contact
    ): RedirectResponse
    {
        $contact->setIsHidden(true);
        $contactRepository->save($contact, true);
        return $this->redirectToRoute("app_admin_messages");
    }

    #[Route('/messages/{id}/show', name: 'messages_show')]
    public function messageShow(
        ContactRepository $contactRepository,
        Contact $contact
    ): RedirectResponse
    {
        $contact->setIsHidden(false);
        $contactRepository->save($contact, true);
        return $this->redirectToRoute("app_admin_messages");
    }

    #[Route('/quotations', name: 'quotations')]
    public function quotations(
        QuotationRepository $quotationRepository,
        Request $request
    ): Response
    {
        if( $request->query->get("archive")) {
            $isHidden = true;
        }else{
            $isHidden = false;
        }
        return $this->render('admin/quotations.html.twig', [
            "quotations"      => $quotationRepository->findBy([
                "isHidden"  => $isHidden
            ]),
            "isArchive" => $isHidden
        ]);
    }

    #[Route('/quotations/{id}/hide', name: 'quotations_hide')]
    public function quotationsHide(
        QuotationRepository $quotationRepository,
        Quotation $quotation
    ): RedirectResponse
    {
        $quotation->setIsHidden(true);
        $quotationRepository->save($quotation, true);
        return $this->redirectToRoute("app_admin_quotations");
    }

    #[Route('/quotations/{id}/show', name: 'quotations_show')]
    public function quotationsShow(
        QuotationRepository $quotationRepository,
        Quotation $quotation
    ): RedirectResponse
    {
        $quotation->setIsHidden(false);
        $quotationRepository->save($quotation, true);
        return $this->redirectToRoute("app_admin_quotations");
    }



    #[Route('/informations', name: 'informations')]
    public function informations(
        SiteMetadataRepository $metadataRepository,
        Request $request
    ): Response
    {
        $informations = $metadataRepository->findOneBy([], ["id" => "ASC"]);


        if( $informations === null ){
            $informations = new SiteMetadata();
            $metadataRepository->save($informations, true);
        }
        $form = $this->createForm(SiteMetadataType::class, $informations);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid()){
            $metadataRepository->save($informations, true);
        }

        return $this->render('admin/informations.html.twig', [
            "informations" => $metadataRepository->findOneBy([], ["id" => "ASC"]),
            "form" => $form->createView()
        ]);
    }


    #[Route('/caroussel', name: 'caroussel')]
    public function caroussel(
        CarouselPictureRepository $carouselPictureRepository,
        FileUploader $uploader,
        Request $request
    ): Response
    {
        $carouselPicture = new CarouselPicture();
        $form =$this->createForm(CarouselPictureType::class, $carouselPicture);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $file = $form->get("file")->getData();
            $filename = $uploader->upload($file);
            $carouselPicture->setFilename($filename);
            $carouselPictureRepository->save($carouselPicture, true);
        }
        return $this->render('admin/caroussel.html.twig', [
            "images" => $carouselPictureRepository->findAll(),
            "form"   => $form->createView()
        ]);
    }

    #[Route('/caroussel/{id}/delete', name: 'caroussel_delete')]
    public function carousselDelete(
        CarouselPictureRepository $carouselPictureRepository,
        CarouselPicture $carouselPicture,
        FileUploader $uploader,
        Request $request
    ): Response
    {
        $uploader->removeFile($carouselPicture->getFilename());
        $carouselPictureRepository->remove($carouselPicture, true);
        return $this->redirectToRoute('app_admin_caroussel');
    }

    #[Route('/services', name: 'services')]
    public function services(
        ServiceCardRepository $serviceCardRepository,
        Request $request,
        FileUploader $uploader,
    ): Response
    {
        $newServiceCard = new ServiceCard();
        $form =$this->createForm(ServiceCardType::class, $newServiceCard);
        $form->handleRequest($request);

        if( $form->isSubmitted() && $form->isValid() ){
            $file = $form->get("file")->getData();
            $filename = $uploader->upload($file);
            $newServiceCard->setFilename($filename);
            $serviceCardRepository->save($newServiceCard, true);
        }

        return $this->render('admin/services.html.twig', [
            "serviceCards" => $serviceCardRepository->findAll(),
            "form" => $form->createView()
        ]);
    }

    #[Route('/services/{id}/delete', name: 'services_delete')]
    public function servicesDelete(
        ServiceCardRepository $serviceCardRepository,
        ServiceCard $serviceCard
    ): Response
    {
        $serviceCardRepository->remove($serviceCard, true);

        return $this->redirectToRoute('app_admin_services');
    }

}
