<?php

declare(strict_types=1);

namespace App\Entiter;

use InvalidArgumentException;

class Client
{
    private string $nom;
    private string $prenom;
    private string $email;
    private ?string $telephone; // optionnel

    // Constructeur : passe par les setters (l'email est validé)
    public function __construct(string $nom, string $prenom, string $email, ?string $telephone = null)
    {
        $this->setNom($nom)
             ->setPrenom($prenom)
             ->setEmail($email)
             ->setTelephone($telephone);
    }

    public function getNom(): string
    {
        return $this->nom;
    }

    public function setNom(string $nom): self
    {
        $this->nom = $nom;

        return $this;
    }

    public function getPrenom(): string
    {
        return $this->prenom;
    }

    public function setPrenom(string $prenom): self
    {
        $this->prenom = $prenom;

        return $this;
    }

    public function getEmail(): string
    {
        return $this->email;
    }

    // Lève une InvalidArgumentException si l'email est invalide
    public function setEmail(string $email): self
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new InvalidArgumentException("Email invalide : {$email}");
        }
        $this->email = $email;

        return $this;
    }

    public function getTelephone(): ?string
    {
        return $this->telephone;
    }

    public function setTelephone(?string $telephone): self
    {
        $this->telephone = $telephone;

        return $this;
    }

    // Ex : Jean Dupont
    public function getNomComplet(): string
    {
        return "{$this->prenom} {$this->nom}";
    }

    // Ex : Jean Dupont (jean.dupont@mail.fr)
    public function __toString(): string
    {
        return "{$this->getNomComplet()} ({$this->email})";
    }
}
