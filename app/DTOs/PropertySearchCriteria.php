<?php

namespace App\DTOs;

class PropertySearchCriteria
{
    private ?int $investmentId;
    private ?int $rooms;
    private ?int $status;
    private ?string $areaRange;
    private ?string $type;
    private ?array $excludePropertyIds;
    private ?int $buildingId;
    public function __construct(
        ?int $investmentId,
        ?int $rooms,
        ?int $status,
        ?int $buildingId,
        ?string $areaRange,
        ?string $type,
        ?array $excludePropertyIds
    ) {
        $this->investmentId = $investmentId;
        $this->rooms = $rooms;
        $this->status = $status;
        $this->areaRange = $areaRange;
        $this->type = $type;
        $this->excludePropertyIds = $excludePropertyIds;
        $this->buildingId = $buildingId;
    }

    public function getInvestmentId(): ?int { return $this->investmentId; }
    public function getRooms(): ?int { return $this->rooms; }
    public function getStatus(): ?int { return $this->status; }
    public function getAreaRange(): ?string { return $this->areaRange; }
    public function getType(): ?string { return $this->type; }
    public function getExcludePropertyIds(): ?array { return $this->excludePropertyIds; }
    public function getBuildingId(): ?int { return $this->buildingId; }

    public function getAreaBounds(): ?array
    {
        if (!$this->areaRange) {
            return null;
        }

        $bounds = explode('-', $this->areaRange);
        return [
            'min' => $bounds[0] ?? null,
            'max' => $bounds[1] ?? null
        ];
    }
} 