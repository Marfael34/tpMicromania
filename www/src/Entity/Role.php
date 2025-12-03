<?php



namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table: "role")]
class Role
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 15)]
   public string $label;
}
