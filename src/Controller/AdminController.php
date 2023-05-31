<?php

namespace App\Controller;

use App\Entity\CarouselPicture;
use App\Entity\SiteMetadata;
use App\Form\CarouselPictureType;
use App\Form\SiteMetadataType;
use App\Repository\CarouselPictureRepository;
use App\Repository\SiteMetadataRepository;
use App\Service\FileUploader;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
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
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/informations', name: 'informations')]
    public function informations(
        SiteMetadataRepository $metadataRepository
    ): Response
    {
        $informations = $metadataRepository->findOneBy([], ["id" => "ASC"]);


        if( $informations === null ){
            $informations = new SiteMetadata();
            $metadataRepository->save($informations, true);
        }
        $form = $this->createForm(SiteMetadataType::class, $informations);

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

    #[Route('/services', name: 'services')]
    public function services(
        CarouselPictureRepository $carouselPictureRepository
    ): Response
    {
        return $this->render('admin/services.html.twig', [
            "images" => $carouselPictureRepository->findAll()
        ]);
    }

}
