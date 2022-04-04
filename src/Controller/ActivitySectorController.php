<?php

declare(strict_types=1);

namespace App\Controller;

use App\Repository\ActivitySectorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;

final class ActivitySectorController extends AbstractController
{
    /**
     * @Route("/sectors", name="app_activity_sector_list", methods={"GET"})
     */
    public function index(ActivitySectorRepository $repository): Response
    {
        return $this->render('activity_sector/index.html.twig', [
            'activity_sectors' => $repository->findAll(),
        ]);
    }
}
