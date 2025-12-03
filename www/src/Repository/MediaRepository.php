<?php

/**
 * ============================================
 * MEDIA REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité Media
 * Ajoute des méthodes spécifiques pour la recherche de médias
 */

declare(strict_types=1);

namespace App\Repository;

use JulienLinard\Doctrine\Repository\EntityRepository;
use App\Entity\Media;

class MediaRepository extends EntityRepository
{
    /**
     * Trouve un média par son nom de fichier
     * 
     * @param string $filename Nom du fichier
     * @return Media|null Média trouvé ou null
     */
    public function findByFilename(string $filename): ?Media
    {
        return $this->findOneBy(['filename' => $filename]);
    }
    
    /**
     * Trouve tous les médias d'un type spécifique
     * 
     * @param string $type Type de média (image, document, video, etc.)
     * @return array Liste des médias
     */
    public function findByType(string $type): array
    {
        return $this->findBy(['type' => $type], ['created_at' => 'DESC']);
    }
    
    /**
     * Trouve tous les médias images
     * 
     * @return array Liste des médias images
     */
    public function findImages(): array
    {
        return $this->findByType('image');
    }
    
    /**
     * Trouve un média par son chemin
     * 
     * @param string $path Chemin du média
     * @return Media|null Média trouvé ou null
     */
    public function findByPath(string $path): ?Media
    {
        return $this->findOneBy(['path' => $path]);
    }
    
    /**
     * Trouve les médias récents
     * 
     * @param int $limit Nombre de résultats à retourner
     * @return array Liste des médias récents
     */
    public function findRecent(int $limit = 10): array
    {
        return $this->findBy([], ['created_at' => 'DESC'], $limit);
    }
    
    /**
     * Compte le nombre de médias d'un type spécifique
     * 
     * @param string $type Type de média
     * @return int Nombre de médias
     */
    public function countByType(string $type): int
    {
        return count($this->findByType($type));
    }
}
