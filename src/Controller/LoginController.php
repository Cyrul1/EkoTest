<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\Security\Http\Authentication\AuthenticationUtils;

class LoginController extends AbstractController
{
    #[Route('/login', name: 'app_login')]
    public function login(
        AuthenticationUtils $untils
    ): Response
    {
        $lastUsername = $untils->getLastUsername();
        $error = $untils->getLastAuthenticationError();

        return $this->render('login/index.html.twig', [
         'lastUsername'=> $lastUsername,
         'error'=> $error
        ]);
    }

    #[Route('/logout', name: 'app_logout')]
    public function logout()  
    {
    }
}