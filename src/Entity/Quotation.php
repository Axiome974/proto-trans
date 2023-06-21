<?php

namespace App\Entity;

use App\Repository\QuotationRepository;
use Doctrine\ORM\Mapping as ORM;
use Symfony\Component\Validator\Constraints as Assert;


#[ORM\Entity(repositoryClass: QuotationRepository::class)]
class Quotation
{
    #[ORM\Id]
    #[ORM\GeneratedValue]
    #[ORM\Column]
    private ?int $id = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Length(max:64, maxMessage: "Ne doit pas dépasser 64 caractères.")]
    private ?string $firstname = null;

    #[ORM\Column(length: 64)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Length(max:64, maxMessage: "Ne doit pas dépasser 64 caractères.")]
    private ?string $lastname = null;

    #[ORM\Column(length: 255)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Email(message: "Vous devez saisir un email valide.")]

    private ?string $email = null;

    #[ORM\Column(length: 20, nullable: true)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Length(max:20, maxMessage: "Ne doit pas dépasser 20 chiffres", min: 9, minMessage: "Doit comporter au moins 9 chiffres.")]
    private ?string $phone = null;

    #[ORM\Column(length: 15, nullable: true)]
    private ?string $fax = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    private ?\DateTimeImmutable $departureAt = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    private ?\DateTimeImmutable $arrivalAt = null;

    #[ORM\Column(length: 96)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Length(max:96, maxMessage: "Ne doit pas dépasser 96 caractères.")]
    private ?string $departureCity = null;

    #[ORM\Column(length: 96)]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    #[Assert\Length(max:96, maxMessage: "Ne doit pas dépasser 96 caractères.")]
    private ?string $arrivalCity = null;

    #[ORM\Column]
    #[Assert\NotBlank(message: "Vous devez saisir une valeur.")]
    private ?int $passengers = null;

    #[ORM\Column]
    private ?bool $isUsingBus = false;

    #[ORM\Column]
    private ?\DateTimeImmutable $createdAt = null;

    #[ORM\Column]
    private ?bool $isHidden = null;

    public function getId(): ?int
    {
        return $this->id;
    }

    public function getFirstname(): ?string
    {
        return $this->firstname;
    }

    public function setFirstname(string $firstname): self
    {
        $this->firstname = $firstname;

        return $this;
    }

    public function getLastname(): ?string
    {
        return $this->lastname;
    }

    public function setLastname(string $lastname): self
    {
        $this->lastname = $lastname;

        return $this;
    }

    public function getEmail(): ?string
    {
        return $this->email;
    }

    public function setEmail(string $email): self
    {
        $this->email = $email;

        return $this;
    }

    public function getPhone(): ?string
    {
        return $this->phone;
    }

    public function setPhone(?string $phone): self
    {
        $this->phone = $phone;

        return $this;
    }

    public function getFax(): ?string
    {
        return $this->fax;
    }

    public function setFax(?string $fax): self
    {
        $this->fax = $fax;

        return $this;
    }

    public function getDepartureAt(): ?\DateTimeImmutable
    {
        return $this->departureAt;
    }

    public function setDepartureAt(\DateTimeImmutable $departureAt): self
    {
        $this->departureAt = $departureAt;

        return $this;
    }

    public function getArrivalAt(): ?\DateTimeImmutable
    {
        return $this->arrivalAt;
    }

    public function setArrivalAt(\DateTimeImmutable $arrivalAt): self
    {
        $this->arrivalAt = $arrivalAt;

        return $this;
    }

    public function getDepartureCity(): ?string
    {
        return $this->departureCity;
    }

    public function setDepartureCity(string $departureCity): self
    {
        $this->departureCity = $departureCity;

        return $this;
    }

    public function getArrivalCity(): ?string
    {
        return $this->arrivalCity;
    }

    public function setArrivalCity(string $arrivalCity): self
    {
        $this->arrivalCity = $arrivalCity;

        return $this;
    }

    public function getPassengers(): ?int
    {
        return $this->passengers;
    }

    public function setPassengers(int $passengers): self
    {
        $this->passengers = $passengers;

        return $this;
    }

    public function isIsUsingBus(): ?bool
    {
        return $this->isUsingBus;
    }

    public function setIsUsingBus(bool $isUsingBus): self
    {
        $this->isUsingBus = $isUsingBus;

        return $this;
    }

    public function getCreatedAt(): ?\DateTimeImmutable
    {
        return $this->createdAt;
    }

    public function setCreatedAt(\DateTimeImmutable $createdAt): self
    {
        $this->createdAt = $createdAt;

        return $this;
    }

    public function isIsHidden(): ?bool
    {
        return $this->isHidden;
    }

    public function getFullName(): string
    {
        return $this->getFirstname()." ".$this->getLastname();
    }

    public function setIsHidden(bool $isHidden): self
    {
        $this->isHidden = $isHidden;

        return $this;
    }
}
