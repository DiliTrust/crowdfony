<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\User;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ApiLoginController extends AbstractController
{
    /**
     * @Route("/api/login", name="app_api_login", methods={"POST"})
     */
    public function index(): Response
    {
        $user = $this->getUser();
        if (! $user instanceof User) {
            return $this->json(['message' => 'missing credentials'], Response::HTTP_UNAUTHORIZED);
        }

        // Generate an API token for user
        // Save user

        return $this->json([
            'api_token' => $user->getApiToken(),
        ]);
    }
}
