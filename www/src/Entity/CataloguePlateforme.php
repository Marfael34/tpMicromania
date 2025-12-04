<?php
namespace App\Entity;

use Plateforme;
use JulienLinard\Doctrine\Mapping\Entity;


#[Entity(table: "catalogue_plateforme")]
class CataloguePlateforme
{
   #[ManyToMany(targetEntity: Plateforme::class)]
   public $plateforme = [];
}