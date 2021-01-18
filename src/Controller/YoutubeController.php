<?php

namespace App\Controller;

use App\Entity\Youtube;
use App\Form\YoutubeType;
use App\Repository\YoutubeRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class YoutubeController extends AbstractController
{
    /**
     * @Route("/", name="app_index")
     */
    public function index(Request $request, EntityManagerInterface $entityManager,
                          YoutubeRepository $repository): Response
    {

        $youtube = new Youtube();
        $form = $this->createForm(YoutubeType::class,$youtube);

        // tester si le formulaire a été soumis
        $form->handleRequest($request);
        if ($form->isSubmitted() && $form->isValid()) {
            // enregistrer la video Youtube dans la base de données
            $entityManager->persist($youtube); // Créer l'ordre INSERT (SQL)
            $entityManager->flush();   // Envoyer l'ordre INSERT

            return $this->redirectToRoute('app_index');
            }

        return $this->render('youtube/index.html.twig', [
            'formYoutube' => $form->createView(),
            'youtubes' => $repository->findAll()
        ]);
    }

    /**
     * @Route("/{id}", name="app_video")
     */
    public function video($id, YoutubeRepository $repository): Response
    {
        $video = $repository->find($id);
        return $this->render('youtube/video.html.twig', [
            "video" => $video
        ]);
    }
}
