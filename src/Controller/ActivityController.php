<?php

namespace App\Controller;

use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use App\Entity\ActivityMonitor;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use Symfony\Component\HttpFoundation\Request;
use App\Repository\ActivityRepository;
use Doctrine\ORM\EntityManagerInterface;
use Symfony\Component\HttpFoundation\Response;
use App\Entity\ActivityType;
use App\Entity\Activity;
use App\Entity\Monitor;

class ActivityController extends AbstractController
{
    #[Route('/activities', methods: ['GET'])]
    public function listActivities(Request $request, ActivityRepository $activityRepository): JsonResponse
    {
        $dateParam = $request->query->get('startTime');
        $activities = [];

        if ($dateParam) {
            $date = \DateTime::createFromFormat('Y-m-d', $dateParam);
            if (!$date) {
                return $this->json(['error' => "Invalid date format. Use 'Y-m-d'."], JsonResponse::HTTP_BAD_REQUEST);
            }
            $date->setTime(0, 0, 0);

            $activities = $activityRepository->createQueryBuilder('a')
                ->where('a.startTime >= :startOfDay')
                ->andWhere('a.startTime < :startOfNextDay')
                ->setParameter('startOfDay', $date)
                ->setParameter('startOfNextDay', (clone $date)->modify('+1 day'))
                ->getQuery()
                ->getResult();

            if (empty($activities)) {
                return $this->json(['error' => "No activities found on this date."], JsonResponse::HTTP_NOT_FOUND);
            }
        } else {
            $activities = $activityRepository->findAll();
            if (empty($activities)) {
                return $this->json(['error' => "No activities found."], JsonResponse::HTTP_NOT_FOUND);
            }
        }

        $activitiesArray = array_map(function ($activity) {
            $activityType = $activity->getActivityType();
            $monitors = $activity->getActivityMonitors();

            $monitorsArray = array_map(function ($activityMonitor) {
                $monitor = $activityMonitor->getMonitor();
                return [
                    'id' => $monitor->getId(),
                    'name' => $monitor->getName(),
                    'email' => $monitor->getEmail(),
                    'phone' => $monitor->getPhone(),
                    'photo' => $monitor->getPhoto(),
                ];
            }, $monitors->toArray());

            return [
                'id' => $activity->getId(),
                'activityType' => [
                    'id' => $activityType->getId(),
                    'name' => $activityType->getName(),
                    'number_of_monitors' => $activityType->getNumberOfMonitors(),
                ],
                'monitors' => $monitorsArray,
                'startTime' => $activity->getStartTime()->format('Y-m-d H:i:s'),
                'duration' => $activity->getDuration(),
            ];
        }, $activities);

        return $this->json($activitiesArray);
    }

    #[Route('/activities', methods: ['POST'])]
    public function createActivity(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        // Comprobar si hay datos JSON válidos
        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $startTime = \DateTime::createFromFormat('d-m-Y H:i', $data['startTime'] ?? '');
        $duration = $data['duration'] ?? null;
        $activityTypeId = $data['activityTypeId'] ?? null;
        $monitorIds = $data['monitorIds'] ?? [];

        // Comprobar si la fecha y la hora son válidas
        if (!$startTime || !in_array($startTime->format('H:i'), ['09:00', '13:30', '17:30'])) {
            return $this->json(['error' => 'Invalid start time.'], Response::HTTP_BAD_REQUEST);
        }

        // Comprobar si la duración es válida
        if ($duration !== 90) {
            return $this->json(['error' => 'Invalid duration.'], Response::HTTP_BAD_REQUEST);
        }

        // Buscar el tipo de actividad
        $activityType = $em->getRepository(ActivityType::class)->find($activityTypeId);
        if (!$activityType) {
            return $this->json(['error' => 'Activity type not found.'], Response::HTTP_NOT_FOUND);
        }

        // Crear la nueva actividad
        $activity = new Activity();
        $activity->setStartTime($startTime);
        $activity->setDuration($duration);
        $activity->setActivityType($activityType);

        // Asignar monitores a la actividad
        foreach ($monitorIds as $monitorId) {
            $monitor = $em->getRepository(Monitor::class)->find($monitorId);
            if (!$monitor) {
                return $this->json(['error' => "Monitor with ID $monitorId not found."], Response::HTTP_NOT_FOUND);
            }

            $activityMonitor = new ActivityMonitor();
            $activityMonitor->setMonitor($monitor);
            $activityMonitor->setActivity($activity);
            $em->persist($activityMonitor);

            $activity->addActivityMonitor($activityMonitor);
        }

        // Comprobar si se asignaron suficientes monitores
        if (count($activity->getActivityMonitors()) < $activityType->getNumberOfMonitors()) {
            return $this->json(['error' => 'Not enough monitors assigned.'], Response::HTTP_BAD_REQUEST);
        }

        // Guardar la nueva actividad
        $em->persist($activity);
        $em->flush();

        // Construir la respuesta
        $monitorsIds = $activity->getActivityMonitors()->map(fn ($activityMonitor) => $activityMonitor->getMonitor()->getId())->getValues();

        return $this->json([
            'id' => $activity->getId(),
            'activityTypeId' => $activityType->getId(),
            'startTime' => $activity->getStartTime()->format('Y-m-d H:i:s'),
            'duration' => $activity->getDuration(),
            'monitorIds' => $monitorsIds,
        ], Response::HTTP_CREATED);
    }

    #[Route('/activities/{id}', methods: ['PUT'])]
    public function updateActivity(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $activity = $em->getRepository(Activity::class)->find($id);

        if (!$activity) {
            return $this->json(['error' => 'Activity not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['startTime'])) {
            $startTime = \DateTime::createFromFormat('d-m-Y H:i', $data['startTime']);
            if (!$startTime) {
                return $this->json(['error' => 'Invalid start time.'], Response::HTTP_BAD_REQUEST);
            }
            $activity->setStartTime($startTime);
        }

        if (isset($data['duration']) && $data['duration'] !== 90) {
            return $this->json(['error' => 'Invalid duration.'], Response::HTTP_BAD_REQUEST);
        }

        if (isset($data['activityTypeId'])) {
            $activityType = $em->getRepository(ActivityType::class)->find($data['activityTypeId']);
            if (!$activityType) {
                return $this->json(['error' => 'Activity type not found.'], Response::HTTP_NOT_FOUND);
            }
            $activity->setActivityType($activityType);
        }

        if (isset($data['monitorIds'])) {
            foreach ($activity->getActivityMonitors() as $activityMonitor) {
                $em->remove($activityMonitor);
            }

            foreach ($data['monitorIds'] as $monitorId) {
                $monitor = $em->getRepository(Monitor::class)->find($monitorId);
                if (!$monitor) {
                    return $this->json(['error' => "Monitor with ID $monitorId not found."], Response::HTTP_NOT_FOUND);
                }
                $activityMonitor = new ActivityMonitor();
                $activityMonitor->setMonitor($monitor);
                $activityMonitor->setActivity($activity);
                $em->persist($activityMonitor);
            }
        }

        $em->flush();

        return $this->json(['message' => 'Activity updated successfully']);
    }

    #[Route('/activities/{id}', methods: ['DELETE'])]
    public function deleteActivity(EntityManagerInterface $em, $id): JsonResponse
    {
        $activity = $em->getRepository(Activity::class)->find($id);

        if (!$activity) {
            return $this->json(['error' => 'Activity not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($activity);
        $em->flush();

        return $this->json(['message' => 'Activity deleted successfully']);
    }
}
