<?php

namespace App\Entity;

use Genre;
use JulienLinard\Doctrine\Mapping\Entity;

#[Entity(table: "catalogue_genre")]
class CatalogueGenre
{
   #[ManyToMany(targetEntity: Genre::class)]
   public $genre = [];
}