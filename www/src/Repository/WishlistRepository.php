<?php

/**
 * ============================================
 * TODO REPOSITORY
 * ============================================
 * 
 * Repository personnalisé pour l'entité Todo
 * Ajoute des méthodes spécifiques pour la recherche de todos
 */

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Media;
use App\Entity\Catalogue;
use App\Repository\MediaRepository;
use JulienLinard\Doctrine\Repository\EntityRepository;

class WishlistRepository extends EntityRepository
{
    /**
     * Retourne le nom de colonne pour une propriété
     * 
     * @param string $propertyName Nom de la propriété PHP
     * @return string Nom de la colonne en base de données
     */
    private function getColumnName(string $propertyName): string
    {
        $metadata = $this->metadataReader->getMetadata($this->entityClass);
        
        // Vérifier si c'est une colonne
        if (isset($metadata['columns'][$propertyName])) {
            return $metadata['columns'][$propertyName]['name'] ?? $propertyName;
        }
        
        // Vérifier si c'est une relation ManyToOne (utiliser joinColumn)
        if (isset($metadata['relations'][$propertyName]) && $metadata['relations'][$propertyName]['type'] === 'ManyToOne') {
            return $metadata['relations'][$propertyName]['joinColumn'] ?? $propertyName . '_id';
        }
        
        // Par défaut, retourner le nom de la propriété
        return $propertyName;
    }
    
    /**
     * Mappe un tableau de critères (propriétés PHP) vers les noms de colonnes
     * 
     * @param array $criteria Critères avec noms de propriétés PHP
     * @return array Critères avec noms de colonnes SQL
     */
    private function mapCriteriaToColumns(array $criteria): array
    {
        $mapped = [];
        foreach ($criteria as $property => $value) {
            $mapped[$this->getColumnName($property)] = $value;
        }
        return $mapped;
    }
    
    /**
     * Mappe un tableau d'ordre (propriétés PHP) vers les noms de colonnes
     * 
     * @param array $orderBy Ordre avec noms de propriétés PHP
     * @return array Ordre avec noms de colonnes SQL
     */
    private function mapOrderByToColumns(array $orderBy): array
    {
        $mapped = [];
        foreach ($orderBy as $property => $direction) {
            $mapped[$this->getColumnName($property)] = $direction;
        }
        return $mapped;
    }
    
    /**
     * Trouve tous les todos d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Liste des todos
     */
    public function findByUser(int $userId): array
    {
        // Utiliser directement le nom de colonne 'user_id' car findBy() utilise les noms de colonnes
        return $this->findBy(['user_id' => $userId], ['created_at' => 'DESC']);
    }
    
    /**
     * Trouve tous les todos complétés d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Liste des todos complétés
     */
    public function findCompletedByUser(int $userId): array
    {
        return $this->findBy(
            ['user_id' => $userId, 'completed' => true],
            ['updated_at' => 'DESC']
        );
    }
    
   
    public function findByIdAndUser(int $id, int $userId): ?Catalogue
    {
        return $this->findOneBy(['id' => $id, 'user_id' => $userId]);
    }

    
    public function countByUser(int $userId): int
    {
        return count($this->findByUser($userId));
    }
    
    public function countCompletedByUser(int $userId): int
    {
        return count($this->findCompletedByUser($userId));
    }
    
    public function countPendingByUser(int $userId): int
    {
        return count($this->findPendingByUser($userId));
    }
    
    /**
     * Charge Catalogue les relations ManyToMany pour un todo
     * 
     * @param Catalogue pour lequel charger les médias
     * @return void
     */
    public function loadMediaRelations(Catalogue $catalogue): void
    {
        if ($catalogue->id === null) {
            return;
        }
        
        // Charger les médias depuis la table de jointure
        $joinTable = 'catalogue';
        $sql = "SELECT madia_path FROM `{$joinTable}` WHERE id = :catalogue_id";
        $rows = $this->connection->fetchAll($sql, ['catalogue_id' => $catalogue->id]);
        
        if (empty($rows)) {
            $catalogue->media = [];
            return;
        }
        
        // Récupérer les IDs des médias
        $mediaIds = array_column($rows, 'media_id');
        
        // Charger les médias
        $mediaRepository = new MediaRepository(
            $this->connection,
            $this->metadataReader,
            Media::class,
            $this->queryCache
        );
        
        $mediaArray = [];
        foreach ($mediaIds as $mediaId) {
            $media = $mediaRepository->find($mediaId);
            if ($media !== null) {
                $mediaArray[] = $media;
            }
        }
        
        $todo->media = $mediaArray;
    }

    /**
     * Ajoute un jeux a la wishlist via une requête SQL directe
     */
    public function addToWishlist(int $userId, int $catalogueId, int $etatId = 1): void
    {
        // 1. Vérifier si le jeu est déjà dans le panier pour cet utilisateur
        // On utilise des noms de paramètres clairs (:userId, :catalogueId)
        $sqlCheck = "SELECT COUNT(*) as cnt FROM wishlist WHERE user_id = :userId AND catalogue_id = :catalogueId";
        
        $result = $this->connection->fetchAll($sqlCheck, [
            'userId' => $userId,
            'catalogueId' => $catalogueId
        ]);
        
        $exists = ($result[0]['cnt'] ?? 0) > 0;

        if ($exists) {
            // Le jeu est déjà dans le panier, on ne fait rien pour l'instant
            return;
        }

        // 2. Insérer dans la table panier
        $sqlInsert = "INSERT INTO wishlist (user_id, catalogue_id) VALUES (:userId, :catalogueId)";
        
        $this->connection->execute($sqlInsert, [
            'userId'      => $userId,
            'catalogueId' => $catalogueId,
        ]);
    }

     /**
     * Supprime un jeux a la wishlist via une requête SQL directe
     */
    public function removeFromWishlist(int $userId, int $catalogueId): void
    {
        $sql = "DELETE FROM wishlist WHERE user_id = :userId AND catalogue_id = :catalogueId";
        
        $this->connection->execute($sql, [
            'userId'      => $userId,
            'catalogueId' => $catalogueId
        ]);
    }
    /**
     * Récupère un tableau simple des IDs des jeux dans la wishlist de l'utilisateur
     */
    public function findGameIdsByUser(int $userId): array
    {
        $sql = "SELECT catalogue_id FROM wishlist WHERE user_id = :userId";
        $rows = $this->connection->fetchAll($sql, ['userId' => $userId]);
        
        // Retourne un tableau simple : [1, 5, 12, ...]
        return array_column($rows, 'catalogue_id');
    }
}
