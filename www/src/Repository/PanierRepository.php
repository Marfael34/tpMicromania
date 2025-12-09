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

class PanierRepository extends EntityRepository
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
    
    /**
     * Trouve tous les todos non complétés d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return array Liste des todos non complétés
     */
    public function findPendingByUser(int $userId): array
    {
        return $this->findBy(
            ['user_id' => $userId, 'completed' => false],
            ['created_at' => 'DESC']
        );
    }
    
    /**
     * Trouve un todo par son ID et l'ID de l'utilisateur (sécurité)
     * 
     * @param int $id ID du todo
     * @param int $userId ID de l'utilisateur
     * @return Catalogue|null Catalogue trouvé ou null
     */
    public function findByIdAndUser(int $id, int $userId): ?Catalogue
    {
        return $this->findOneBy(['id' => $id, 'user_id' => $userId]);
    }
    
    /**
     * Compte le nombre de todos d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de todos
     */
    public function countByUser(int $userId): int
    {
        return count($this->findByUser($userId));
    }
    
    /**
     * Compte le nombre de todos complétés d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de todos complétés
     */
    public function countCompletedByUser(int $userId): int
    {
        return count($this->findCompletedByUser($userId));
    }
    
    /**
     * Compte le nombre de todos non complétés d'un utilisateur
     * 
     * @param int $userId ID de l'utilisateur
     * @return int Nombre de todos non complétés
     */
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
     * Ajoute un article au panier via une requête SQL directe
     */
    public function addToPanier(int $userId, int $catalogueId, int $etatId = 1): void
    {
        // 1. Vérifier si le jeu est déjà dans le panier pour cet utilisateur
        // On utilise des noms de paramètres clairs (:userId, :catalogueId)
        $sqlCheck = "SELECT COUNT(*) as cnt FROM panier WHERE user_id = :userId AND catalogue_id = :catalogueId";
        
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
        $sqlInsert = "INSERT INTO panier (user_id, catalogue_id, etat_id) VALUES (:userId, :catalogueId, :etatId)";
        
        $this->connection->execute($sqlInsert, [
            'userId'      => $userId,      // Correction : on map bien l'ID utilisateur
            'catalogueId' => $catalogueId, // Correction : on map bien l'ID du jeu
            'etatId'      => $etatId       // Correction : on map l'état
        ]);
    }
    
    public function getTotalPrice(int $userId): float
    {
        // On joint la table panier et catalogue pour récupérer les prix
        // On filtre par l'ID utilisateur
        $sql = "
            SELECT SUM(c.price) as total
            FROM panier p
            JOIN catalogue c ON p.catalogue_id = c.id
            WHERE p.user_id = :userId
        ";
        
        // Exécution de la requête
        $result = $this->connection->fetchAll($sql, [
            'userId' => $userId
        ]);
        
        // Retourne le total ou 0.00 si le panier est vide
        return (float) ($result[0]['total'] ?? 0.00);
    }

    public function removeFromPanier(int $userId, int $catalogueId): void
{
    $sql = "DELETE FROM panier WHERE user_id = :userId AND catalogue_id = :catalogueId";
    
    $this->connection->execute($sql, [
        'userId'      => $userId,
        'catalogueId' => $catalogueId
    ]);
}
}
