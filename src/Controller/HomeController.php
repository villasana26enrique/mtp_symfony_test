<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\Routing\Annotation\Route;
use Doctrine\ORM\EntityManagerInterface;

class HomeController extends AbstractController
{
    /** @var \Doctrine\Persistence\ObjectRepository */
    private $activityRepo;

    /**
     * @param EntityManagerInterface $entityManager
     */
    public function __construct(EntityManagerInterface $entityManager)
    {
        $this->entityManager = $entityManager;
        $this->activityRepo = $entityManager->getRepository('App:RecentActivity');
    }

    /**
     * @Route("/home", name="home")
     */
    public function index(): Response
    {
        $activities = $this->activityRepo->findAll();
        $data = array();
        foreach ($activities as $activity) {
            $data[] = [
                "id" => $activity->getId(),
                "email" => $activity->getEmail(),
                "lastActivity" => $activity->getLastConnection()->format('Y-m-d H:i:s'),
            ];
        }
        return $this->render('home/index.html.twig', [
            'controller_name' => 'HomeController',
            'activities' => $data
        ]);
    }
}
