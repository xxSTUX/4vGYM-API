<?php

namespace App\Controller;

use App\Repository\ActivityTypeRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;

class ActivityTypeController extends AbstractController
{
    #[Route('/activity-types', methods: ['GET'])]
    public function listActivityTypes(ActivityTypeRepository $repository): JsonResponse
    {
        $activityTypes = $repository->findAll();
        $activityTypesArray = [];

        foreach ($activityTypes as $activityType) {
            $activityTypesArray[] = [
                'id' => $activityType->getId(),
                'name' => $activityType->getName(),
                'number_of_monitors' => $activityType->getNumberOfMonitors(),
            ];
        }

        return $this->json($activityTypesArray);
    }
}
