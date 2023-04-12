<?php

namespace App\Controller;

use App\Entity\User;
use App\Entity\Magazyny;
use App\Form\ArtykulType;
use App\Form\MagazynType;
use App\Repository\MagazynyRepository;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\Persistence\ManagerRegistry;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;

class AdminController extends AbstractController
{
    #[Route('/admin', name: 'app_admin')]
    public function index(): Response
    {
        return $this->render('admin/index.html.twig', [
            'controller_name' => 'AdminController',
        ]);
    }

    #[Route('/addmagazyn', name: 'app_addMagazyn')]
    public function nowyMagazyn(Request $request, ManagerRegistry $doctrine)
{
    $form = $this->createForm(MagazynType::class);
    $form->handleRequest($request);

    if ($form->isSubmitted() && $form->isValid()) {
        $magazyn = $form->getData();

        $entityManager = $doctrine->getManager();
        $entityManager->persist($magazyn);
        $entityManager->flush();

        return $this->redirectToRoute('app_user');
    }

    return $this->render('admin/addMagazyn.html.twig', [
        'form' => $form->createView(),
    ]);
}
#[Route('/list', name: 'app_list')]
public function magazyny(MagazynyRepository $magazynyRepository): Response
{
    $magazyny = $magazynyRepository->findAll();

    return $this->render('admin/list.html.twig', [
        'magazyny' => $magazyny,
      
    ]);
}

#[Route('/addArtykul', name: 'app_addArtykul')]
public function nowyArtykul(Request $request, ManagerRegistry $doctrine)
{
$form = $this->createForm(ArtykulType::class);
$form->handleRequest($request);

if ($form->isSubmitted() && $form->isValid()) {
    $artykul = $form->getData();

    $entityManager = $doctrine->getManager();
    $entityManager->persist($artykul);
    $entityManager->flush();

    return $this->redirectToRoute('app_list');
}

return $this->render('admin/nowyArtykul.html.twig', [
    'form' => $form->createView(),
]);

}
}