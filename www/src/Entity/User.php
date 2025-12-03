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

use JulienLinard\Doctrine\Mapping\Entity;
use JulienLinard\Doctrine\Mapping\Column;
use JulienLinard\Doctrine\Mapping\Id;
use JulienLinard\Doctrine\Mapping\Index;
use JulienLinard\Auth\Models\UserInterface;
use JulienLinard\Auth\Models\Authenticatable;

#[Entity(table: 'users')]
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
    
    #[Column(type: 'string', length: 50, nullable: true, default: 'user')]
    public ?string $role = 'user';
    
    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTime $created_at = null;
    
    #[Column(type: 'datetime', nullable: true)]
    public ?\DateTime $updated_at = null;
    
    /**
     * Retourne les rôles de l'utilisateur
     * 
     * @return array|string
     */
    public function getAuthRoles(): array|string
    {
        return $this->role ?? 'user';
    }
    
    /**
     * Retourne les permissions de l'utilisateur
     * 
     * @return array
     */
    public function getAuthPermissions(): array
    {
        // Permissions basées sur le rôle
        return match($this->role) {
            'admin' => ['manage-users', 'edit-posts', 'delete-posts'],
            'moderator' => ['edit-posts', 'delete-posts'],
            'user' => ['view-posts'],
            default => []
        };
    }
}
