<?php

/**
 * ============================================
 * USER REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité User
 * Ajoute des méthodes spécifiques pour la recherche d'utilisateurs
 */

declare(strict_types=1);

namespace App\Repository;

use JulienLinard\Doctrine\Repository\EntityRepository;
use App\Entity\User;

class UserRepository extends EntityRepository
{
    /**
     * Trouve un utilisateur par son email
     * 
     * @param string $email Email de l'utilisateur
     * @return User|null Utilisateur trouvé ou null
     */
    public function findByEmail(string $email): ?User
    {
        return $this->findOneBy(['email' => $email]);
    }
    
    /**
     * Vérifie si un email existe déjà
     * 
     * @param string $email Email à vérifier
     * @return bool True si l'email existe
     */
    public function emailExists(string $email): bool
    {
        $user = $this->findByEmail($email);
        return $user !== null;
    }
    
    /**
     * Trouve tous les utilisateurs actifs
     * 
     * @return array Liste des utilisateurs actifs
     */
    public function findActiveUsers(): array
    {
        // Si vous ajoutez un champ is_active plus tard
        // return $this->findBy(['is_active' => true]);
        return $this->findAll();
    }
}
