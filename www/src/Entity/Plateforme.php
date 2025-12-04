<?php

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table: "plateforme")]
class Plateforme
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 100)]
   public string $label;
}
