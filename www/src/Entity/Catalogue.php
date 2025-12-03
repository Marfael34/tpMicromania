<?php



namespace App\Entity;

use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\ManyToMany;

/**
 * Entité Catalogue représentant la table "catalogue" en base de données
 * 
 * CONCEPT : Entité métier
 * Cette entité représente le concept métier principal de l'application
 */
#[Entity(table: "catalogue")]
class Catalogue
{
    /**
     * ID du jeux créer (clé primaire)
     * 
     * CONCEPT : Clé primaire auto-incrémentée
     * L'ID est généré automatiquement par la base de données
     */
    #[Id]
    #[Column(type: "integer", autoIncrement: true)]
    public ?int $id = null;

    /**
     * Titre 
     * 
     * CONCEPT : Contrainte de longueur
     * VARCHAR(255) → maximum 255 caractères
     */
    #[Column(type: "string", length: 255)]
    public string $titre;

      /**
     * Chemin vers un fichier média associé (image, etc.)
     * 
     * CONCEPT PÉDAGOGIQUE : Stockage de fichiers
     * 
     * Le chemin est relatif au dossier public/
     * Exemple : "/uploads/todos/todo_123456.webp"
     * 
     * POURQUOI stocker le chemin et pas le fichier en BDD ?
     * - Les fichiers sont trop volumineux pour la base de données
     * - La BDD stocke les métadonnées, le système de fichiers stocke les fichiers
     * - Meilleure performance
     */
    #[Column(type: "string", nullable: true, length: 255)]
    public ?string $media_path = null;

    /**
     * Description détaillée 
     * 
     * CONCEPT : Type TEXT
     * TEXT permet de stocker de grandes quantités de texte
     * Contrairement à VARCHAR, TEXT n'a pas de limite de longueur fixe
     */
    #[Column(type: "text")]
    public string $description;

    #[Column(type: "decimal", default: 0.00, precision: 10, scale: 2)]
    public ?float $prix = null;

    #[Column(type: "integer", default: 0)]
    public ?int $stock;

}

#[Entity(table: "catalogue_genre")]
class CatalogueGenre
{
   #[ManyToMany(targetEntity: Genre::class)]
   public $genre = [];
}

#[Entity(table: "genre")]
class Genre
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 100)]
   public string $label;
}

#[Entity(table: "catalogue_plateforme")]
class CataloguePlateforme
{
   #[ManyToMany(targetEntity: Plateforme::class)]
   public $plateforme = [];
}

#[Entity(table: "plateforme")]
class Plateforme
{
   #[Id]
   #[Column(type: "integer", autoIncrement: true)]
   public ?int $id = null;

   #[Column(type: "string", length: 100)]
   public string $label;
}
