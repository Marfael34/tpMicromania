<?php

namespace App\Entity;

use App\Entity\Genre;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToMany;

#[Entity(table: "catalogue_genre")]
class CatalogueGenre
{
   #[ManyToMany(targetEntity: Genre::class)]
   public $genre = [];
}