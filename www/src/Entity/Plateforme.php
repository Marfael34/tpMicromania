<?php

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table: "plateforme")]
class Plateforme
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 100)]
   public string $label;

   public function getId(): ?int
    {
        return $this->id;
    }

    public function getLabel(): ?string
    {
        return $this->label; 
        // ou return $this->nom; selon comment tu as appelé ta colonne dans la BDD
    }
        
}
