<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

class HomepageController extends AbstractController
{
    /**
     * @Route(
     *   path="/hello/{name<%name_pattern%>}",
     *   name="app_hello",
     *   methods={"GET"},
     *   condition="request.headers.get('user-agent') matches '/firefox/i'"
     * )
     */
    public function index(string $name = 'World'): Response
    {
        if ($name === 'lucky-winner') {
            return $this->redirectToRoute('app_hello', ['name' => 'cresus']);
        }

        return $this->render('homepage/index.html.twig', ['name' => $name]);
    }
}
