<?php
namespace App\Entity;

use App\Entity\Plateforme;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToMany;


#[Entity(table: "catalogue_plateforme")]
class CataloguePlateforme
{
   #[ManyToMany(targetEntity: Plateforme::class)]
   public $plateforme = [];
}