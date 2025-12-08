<?php

/**
 * ============================================
 * ENTITÉ USER
 * ============================================
 * 
 * Entité utilisateur pour l'authentification
 * Utilise les attributs Doctrine pour le mapping ORM
 */

declare(strict_types=1);

namespace App\Entity;

use App\Entity\Role;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Index;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Auth\Models\UserInterface;
use JulienLinard\Doctrine\Mapping\ManyToOne;
use JulienLinard\Auth\Models\Authenticatable;

#[Entity(table: 'user')]
class User implements UserInterface
{
    use Authenticatable;
    
    #[Id]
    #[Column(type: 'integer', autoIncrement: true)]
    public ?int $id = null;
    
    #[Column(type: 'string', length: 255)]
    #[Index(unique: true)]
    public string $email;
    
    #[Column(type: 'string', length: 255)]
    public string $password;
    
    #[Column(type: 'string', length: 100, nullable: true)]
    public ?string $firstname = null;
    
    #[Column(type: 'string', length: 100, nullable: true)]
    public ?string $lastname = null;
    
    #[Column(type: 'string', length:10)]
    public ?string $role = null;

    public function getId(): ?int
{
    return $this->id;
}
}
