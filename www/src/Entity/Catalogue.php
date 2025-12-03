<?php

namespace App\Entity;

use DateTime;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\ManyToOne;

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
     * ID de la tâche (clé primaire)
     * 
     * CONCEPT : Clé primaire auto-incrémentée
     * L'ID est généré automatiquement par la base de données
     */
    #[Id]
    #[Column(type: "integer", autoIncrement: true)]
    public ?int $id = null;

    /**
     * Titre du jeux
     * 
     * CONCEPT : Contrainte de longueur
     * VARCHAR(255) → maximum 255 caractères
     */
    #[Column(type: "string", length: 255)]
    public string $title;

    /**
     * Description détaillée de la tâche
     * 
     * CONCEPT : Type TEXT
     * TEXT permet de stocker de grandes quantités de texte
     * Contrairement à VARCHAR, TEXT n'a pas de limite de longueur fixe
     */
    #[Column(type: "text")]
    public string $description;

    /**
     * État de complétion de la tâche
     * 
     * CONCEPT : Booléen avec valeur par défaut
     * default: false → une nouvelle tâche n'est pas complétée par défaut
     */
    #[Column(type: "boolean", default: false)]
    public bool $completed;

    /**
     * Marqueur d'urgence
     * 
     * CONCEPT : Flag booléen
     * Permet de marquer certaines tâches comme urgentes
     * Utile pour le tri et l'affichage
     */
    #[Column(type: "boolean", default: false)]
    public bool $is_urgent;

    /**
     * Date limite pour accomplir la tâche
     * 
     * CONCEPT : Date optionnelle
     * nullable: true → la tâche peut ne pas avoir de date limite
     * Utile pour les tâches sans échéance
     */
    #[Column(type: "datetime", nullable: true)]
    public ?DateTime $deadline = null;

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
     * Date de création de la tâche
     * 
     * CONCEPT : Timestamp de création
     * Enregistre automatiquement quand la tâche a été créée
     * Utile pour le tri chronologique
     */
    #[Column(type: "datetime", nullable: true)]
    public ?DateTime $created_at = null;

    /**
     * Date de dernière modification
     * 
     * CONCEPT : Timestamp de modification
     * Mise à jour à chaque modification de la tâche
     * Utile pour savoir quand la tâche a été modifiée pour la dernière fois
     */
    #[Column(type: "datetime", nullable: true)]
    public ?DateTime $updated_at = null;

    /**
     * ID de l'utilisateur propriétaire
     * 
     * CONCEPT : Clé étrangère
     * Cette colonne fait référence à la table "user"
     * Permet de savoir à quel utilisateur appartient la tâche
     * 
     * NOTE : On a aussi la relation $user ci-dessous
     * La colonne user_id est nécessaire pour la base de données
     * La relation $user est utile pour accéder à l'objet User directement
     */
    #[Column(type: "integer", nullable: true)]
    public ?int $user_id = null;

    /**
     * Relation Doctrine vers l'entité User
     * 
     * CONCEPT PÉDAGOGIQUE : Relation ManyToOne
     * 
     * ManyToOne signifie : "Plusieurs todos appartiennent à un utilisateur"
     * 
     * AVANTAGES :
     * 1. Accès facile : $todo->user->email (au lieu de chercher l'utilisateur manuellement)
     * 2. Doctrine charge automatiquement l'utilisateur si nécessaire (lazy loading)
     * 3. Type safety : $user est un objet User, pas juste un ID
     * 4. Navigation bidirectionnelle : $user->todos retourne tous les todos
     * 
     * inversedBy: 'todos' → côté User, la propriété $todos contient tous les todos
     * 
     * EXEMPLE D'UTILISATION :
     * $todo = $repository->find(1);
     * echo $todo->user->email; // Affiche l'email de l'utilisateur propriétaire
     */
    #[ManyToOne(targetEntity: User::class, inversedBy: 'todos')]
    public ?User $user = null;
}
