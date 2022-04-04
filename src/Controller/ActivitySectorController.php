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
            'activity_sectors' => $repository->findBy(['isEnabled' => true], ['name' => 'ASC']),
        ]);
    }

    /**
     * @Route("/sectors/{id<\d+>}", name="app_activity_sector_show", methods={"GET"})
     */
    public function show(ActivitySectorRepository $repository, int $id): Response
    {
        if (! $activitySector = $repository->find($id)) {
            throw $this->createNotFoundException(\sprintf('Unable to find activity sector identified by ID #%s.', $id));
        }

        if (! $activitySector->isEnabled()) {
            throw $this->createNotFoundException(\sprintf('Activity sector identified by ID #%s is not enabled.', $id));
        }

        return $this->render('activity_sector/show.html.twig', ['activity_sector' => $activitySector]);
    }
}
