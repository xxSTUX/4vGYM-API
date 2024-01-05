<?php

namespace App\Entity;

use App\Repository\ActivityRepository;
use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: ActivityRepository::class)]
class Activity
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(type: Types::DATETIME_MUTABLE)]
    private ?\DateTimeInterface $startTime = null;

    #[ORM\Column]
    private ?int $duration = null;

    #[ORM\ManyToOne(inversedBy: 'activities')]
    private ?ActivityType $activityType = null;

    #[ORM\OneToMany(mappedBy: 'activity', targetEntity: ActivityMonitor::class, orphanRemoval: true, cascade: ['remove'])]
    private Collection $activityMonitors;


    public function __construct()
    {
        $this->activityMonitors = new ArrayCollection();
    }

    public function getId(): ?int
    {
        return $this->id;
    }

    public function setId(int $id): static
    {
        $this->id = $id;

        return $this;
    }

    public function getStartTime(): ?\DateTimeInterface
    {
        return $this->startTime;
    }

    public function setStartTime(\DateTimeInterface $startTime): static
    {
        $this->startTime = $startTime;

        return $this;
    }

    public function getDuration(): ?int
    {
        return $this->duration;
    }

    public function setDuration(int $duration): static
    {
        $this->duration = $duration;

        return $this;
    }

    public function getActivityType(): ?ActivityType
    {
        return $this->activityType;
    }

    public function setActivityType(?ActivityType $activityType): static
    {
        $this->activityType = $activityType;

        return $this;
    }

    /**
     * @return Collection<int, ActivityMonitor>
     */
    public function getActivityMonitors(): Collection
    {
        return $this->activityMonitors;
    }

    public function addActivityMonitor(ActivityMonitor $activityMonitor): self
    {
        if (!$this->activityMonitors->contains($activityMonitor)) {
            $this->activityMonitors[] = $activityMonitor;
            $activityMonitor->setActivity($this);
        }

        return $this;
    }

    public function removeActivityMonitor(ActivityMonitor $activityMonitor): self
    {
        if ($this->activityMonitors->removeElement($activityMonitor)) {
            // set the owning side to null (unless already changed)
            if ($activityMonitor->getActivity() === $this) {
                $activityMonitor->setActivity(null);
            }
        }

        return $this;
    }
}
