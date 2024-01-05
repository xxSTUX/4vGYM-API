<?php

namespace App\Controller;

use App\Repository\MonitorRepository;
use Symfony\Bundle\FrameworkBundle\Controller\AbstractController;
use Symfony\Component\HttpFoundation\Request;
use Symfony\Component\HttpFoundation\Response;
use Symfony\Component\HttpFoundation\JsonResponse;
use Symfony\Component\Routing\Annotation\Route;
use App\Entity\Monitor;
use Doctrine\ORM\EntityManagerInterface;

class MonitorController extends AbstractController
{
    #[Route('/monitors', methods: ['GET'])]
    public function listMonitors(MonitorRepository $repository): JsonResponse
    {
        $monitors = $repository->findAll();
        $monitorsArray = [];

        foreach ($monitors as $monitor) {
            $monitorsArray[] = [
                'id' => $monitor->getId(),
                'name' => $monitor->getName(),
                'email' => $monitor->getEmail(),
                'phone' => $monitor->getPhone(),
                'photo' => $monitor->getPhoto(),
            ];
        }

        return $this->json($monitorsArray);
    }

    #[Route('/monitors', methods: ['POST'])]
    public function createMonitor(Request $request, EntityManagerInterface $em): JsonResponse
    {
        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        $monitor = new Monitor();
        $monitor->setName($data['name'] ?? null);
        $monitor->setEmail($data['email'] ?? null);
        $monitor->setPhone($data['phone'] ?? null);
        $monitor->setPhoto($data['photo'] ?? null);

        $em->persist($monitor);
        $em->flush();

        return $this->json(
            [
                'id' => $monitor->getId(),
                'name' => $monitor->getName(),
                'email' => $monitor->getEmail(),
                'phone' => $monitor->getPhone(),
                'photo' => $monitor->getPhoto(),
            ],
        );
    }

    #[Route('/monitors/{id}', methods: ['PUT'])]
    public function updateMonitor(Request $request, EntityManagerInterface $em, $id): JsonResponse
    {
        $monitor = $em->getRepository(Monitor::class)->find($id);

        if (!$monitor) {
            return $this->json(['error' => 'Monitor not found'], Response::HTTP_NOT_FOUND);
        }

        $data = json_decode($request->getContent(), true);

        if ($data === null) {
            return $this->json(['error' => 'Invalid JSON'], Response::HTTP_BAD_REQUEST);
        }

        // Asignar nuevos valores a las propiedades del monitor
        $monitor->setName($data['name'] ?? $monitor->getName());
        $monitor->setEmail($data['email'] ?? $monitor->getEmail());
        $monitor->setPhone($data['phone'] ?? $monitor->getPhone());
        $monitor->setPhoto($data['photo'] ?? $monitor->getPhoto());

        // Persistir los cambios en la base de datos
        $em->flush();

        // Devolver la entidad monitor actualizada
        return $this->json([
            'id' => $monitor->getId(),
            'name' => $monitor->getName(),
            'email' => $monitor->getEmail(),
            'phone' => $monitor->getPhone(),
            'photo' => $monitor->getPhoto(),
        ]);
    }

    #[Route('/monitors/{id}', methods: ['DELETE'])]
    public function deleteMonitor(EntityManagerInterface $em, $id): JsonResponse
    {
        $monitor = $em->getRepository(Monitor::class)->find($id);

        if (!$monitor) {
            return $this->json(['error' => 'Monitor not found'], Response::HTTP_NOT_FOUND);
        }

        $em->remove($monitor);
        $em->flush();

        return $this->json(['message' => 'Monitor deleted successfully'], Response::HTTP_OK);
    }
}
