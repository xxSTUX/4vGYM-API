<?php

namespace App\Entity;

use App\Repository\ActivityMonitorRepository;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityMonitorRepository::class)]
class ActivityMonitor
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'activityMonitors')]
    private ?Activity $activity = null;

    #[ORM\ManyToOne]
    #[ORM\JoinColumn(nullable: false)]
    private ?Monitor $monitor = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getActivity(): ?Activity
    {
        return $this->activity;
    }

    public function setActivity(?Activity $activity): static
    {
        $this->activity = $activity;

        return $this;
    }

    public function getMonitor(): ?Monitor
    {
        return $this->monitor;
    }

    public function setMonitor(?Monitor $monitor): static
    {
        $this->monitor = $monitor;

        return $this;
    }
}
