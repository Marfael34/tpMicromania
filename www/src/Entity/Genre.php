<?php

namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table: "genre")]
class Genre
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 100)]
   public string $label;
}