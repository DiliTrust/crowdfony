<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\ActivitySector;
use App\Repository\ActivitySectorRepository;
use Sensio\Bundle\FrameworkExtraBundle\Configuration\Entity;
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
            'activity_sectors' => $repository->findActiveSectors(),
        ]);
    }

    /**
     * @Route("/sectors/{id<\d+>}", name="app_activity_sector_show", methods={"GET"})
     * @Entity("activitySector", expr="repository.findActiveSector(id)")
     */
    public function show(ActivitySector $activitySector): Response
    {
        return $this->render('activity_sector/show.html.twig', ['activity_sector' => $activitySector]);
    }
}
