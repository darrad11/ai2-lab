<?php

namespace App\Entity;

use App\Repository\MeteoDataRepository;
use Doctrine\DBAL\Types\Types;
use Doctrine\ORM\Mapping as ORM;

#[ORM\Entity(repositoryClass: MeteoDataRepository::class)]
class MeteoData
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\ManyToOne(inversedBy: 'meteoData')]
    #[ORM\JoinColumn(nullable: false)]
    private ?Location $location = null;

    #[ORM\Column(type: Types::DATE_MUTABLE)]
    private ?\DateTimeInterface $date = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0)]
    private ?string $celsius_temperature = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 3, scale: 0, nullable: true)]
    private ?string $flcelsius_temperature = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $pressure = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $humidity = null;

    #[ORM\Column(type: Types::DECIMAL, precision: 4, scale: 1, nullable: true)]
    private ?float $wind_speed = null;

    #[ORM\Column(type: Types::STRING, length: 10, nullable: true)]
    private ?string $wind_direction = null;

    #[ORM\Column(type: Types::INTEGER, nullable: true)]
    private ?int $cloudiness = null;

    #[ORM\Column(type: Types::STRING, length: 255, nullable: true)]
    private ?string $icon = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getLocation(): ?Location
    {
        return $this->location;
    }

    public function setLocation(?Location $location): static
    {
        $this->location = $location;

        return $this;
    }

    public function getDate(): ?\DateTimeInterface
    {
        return $this->date;
    }

    public function setDate(\DateTimeInterface $date): static
    {
        $this->date = $date;

        return $this;
    }

    public function getCelsiusTemperature(): ?string
    {
        return $this->celsius_temperature;
    }

    public function setCelsiusTemperature(string $celsius_temperature): static
    {
        $this->celsius_temperature = $celsius_temperature;

        return $this;
    }

    public function getFlCelsiusTemperature(): ?string
    {
        return $this->flcelsius_temperature;
    }

    public function setFlCelsiusTemperature(?string $flcelsius_temperature): self
    {
        $this->flcelsius_temperature = $flcelsius_temperature;

        return $this;
    }

    public function getPressure(): ?int
    {
        return $this->pressure;
    }

    public function setPressure(?int $pressure): self
    {
        $this->pressure = $pressure;

        return $this;
    }

    public function getHumidity(): ?int
    {
        return $this->humidity;
    }

    public function setHumidity(?int $humidity): self
    {
        $this->humidity = $humidity;

        return $this;
    }

    public function getWindSpeed(): ?float
    {
        return $this->wind_speed;
    }

    public function setWindSpeed(?float $wind_speed): self
    {
        $this->wind_speed = $wind_speed;

        return $this;
    }

    public function getWindDirection(): ?string
    {
        return $this->wind_direction;
    }

    public function setWindDirection(?string $wind_direction): self
    {
        $this->wind_direction = $wind_direction;

        return $this;
    }

    public function getCloudiness(): ?int
    {
        return $this->cloudiness;
    }

    public function setCloudiness(?int $cloudiness): self
    {
        $this->cloudiness = $cloudiness;

        return $this;
    }

    public function getIcon(): ?string
    {
        return $this->icon;
    }

    public function setIcon(?string $icon): self
    {
        $this->icon = $icon;

        return $this;
    }
    
}
