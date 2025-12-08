<?php

declare(strict_types=1);

namespace App\Repository;

use App\Entity\Genre;
use App\Entity\Catalogue;
use App\Entity\Plateforme;
use App\Entity\CatalogueGenre;
use App\Entity\CataloguePlateforme;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Doctrine\Repository\EntityRepository;


/**
 * Repository personnalisé pour l'entité Catalogue
 * Gère la récupération des jeux + genres + plateformes
 *
 * @extends EntityRepository<Catalogue>
 */
class CatalogueRepository extends EntityRepository
{
    protected EntityManager $em;

    public function __construct(EntityManager $em)
    {
        parent::__construct(
            $em->getConnection(),
            $em->getMetadataReader(),
            Catalogue::class
        );

        $this->em = $em;
    }

    /**
     * Récupère tous les jeux + genres + plateformes
     */
    public function findAllWithRelations(): array
    {
        $qb = $this->em->createQueryBuilder();

        $qb->select("
                c.id,
                c.title,
                c.media_path,
                c.description,
                c.price,
                c.stock,

                GROUP_CONCAT(DISTINCT g.label SEPARATOR ',') AS genres,
                GROUP_CONCAT(DISTINCT p.label SEPARATOR ',') AS plateformes
            ")
            ->from(Catalogue::class, "c")

            // catalogue → catalogue_genre → genre
            ->leftJoin(CatalogueGenre::class, "cg", "c.id = cg.catalogue_id")
            ->leftJoin(Genre::class, "g", "g.id = cg.genre_id")

            // catalogue → catalogue_plateforme → plateforme
            ->leftJoin(CataloguePlateforme::class, "cp", "c.id = cp.catalogue_id")
            ->leftJoin(Plateforme::class, "p", "p.id = cp.plateforme_id")
            
            ->groupBy('c.id')
            ->orderBY('c.title');
            

        $rows = $qb->getResult();
        // Transformation en structure exploitable
        foreach ($rows as &$row) {
            $row["genres"] = $row["genres"] ? explode(",", $row["genres"]) : [];
            $row["plateformes"] = $row["plateformes"] ? explode(",", $row["plateformes"]) : [];
        }

        return $rows;
    }

    /**
     * Charge les objets Genre et Plateforme liés à une entité Catalogue
     * Utile pour le formulaire d'édition (edit) afin de pré-cocher les cases
     */
    public function loadRelations(Catalogue $catalogue): void
    {
        $id = $catalogue->getId();
        if (!$id) return;

        // 1. Charger les Genres
        // On fait une requête SQL directe pour récupérer les genres liés
        $sqlGenres = "SELECT g.* FROM genre g 
                      JOIN catalogue_genre cg ON g.id = cg.genre_id 
                      WHERE cg.catalogue_id = :id";
        
        $genreRows = $this->connection->fetchAll($sqlGenres, ['id' => $id]);
        
        $catalogue->genre = [];
        foreach ($genreRows as $row) {
            $g = new Genre();
            // Hydratation manuelle simple (ou via réflexion si propriété privée)
            // On suppose ici que les propriétés sont accessibles ou gérées via Reflection
            $ref = new \ReflectionClass(Genre::class);
            
            if ($ref->hasProperty('id')) {
                $propId = $ref->getProperty('id');
                $propId->setAccessible(true);
                $propId->setValue($g, (int)$row['id']);
            }
            
            if ($ref->hasProperty('label') && isset($row['label'])) {
                $propLabel = $ref->getProperty('label');
                $propLabel->setAccessible(true);
                $propLabel->setValue($g, $row['label']);
            }
            
            $catalogue->genre[] = $g;
        }

        // 2. Charger les Plateformes
        $sqlPlat = "SELECT p.* FROM plateforme p 
                    JOIN catalogue_plateforme cp ON p.id = cp.plateforme_id 
                    WHERE cp.catalogue_id = :id";
        
        $platRows = $this->connection->fetchAll($sqlPlat, ['id' => $id]);

        $catalogue->plateforme = [];
        foreach ($platRows as $row) {
            $p = new Plateforme();
            $ref = new \ReflectionClass(Plateforme::class);
            
            if ($ref->hasProperty('id')) {
                $propId = $ref->getProperty('id');
                $propId->setAccessible(true);
                $propId->setValue($p, (int)$row['id']);
            }
            
            if ($ref->hasProperty('label') && isset($row['label'])) {
                $propLabel = $ref->getProperty('label');
                $propLabel->setAccessible(true);
                $propLabel->setValue($p, $row['label']);
            }
            
            $catalogue->plateforme[] = $p;
        }
    }

    /**
     * Sauvegarde les relations ManyToMany (Genres et Plateformes)
     * À appeler dans le Controller après le persist/flush du Catalogue
     * * @param int $catalogueId L'ID du jeu
     * @param array $genreIds Tableau des IDs de genres (ex: [1, 3])
     * @param array $plateformeIds Tableau des IDs de plateformes (ex: [2, 4])
     */
    public function saveManyToManyRelations(int $catalogueId, array $genreIds, array $plateformeIds): void
    {
        // 1. Nettoyer les relations existantes (pour éviter les doublons ou lors d'un update)
        // On supprime tout pour ce jeu et on recrée
        $this->connection->execute("DELETE FROM catalogue_genre WHERE catalogue_id = :id", ['id' => $catalogueId]);
        $this->connection->execute("DELETE FROM catalogue_plateforme WHERE catalogue_id = :id", ['id' => $catalogueId]);

        // 2. Insérer les nouveaux genres
        if (!empty($genreIds)) {
            // Préparer la requête d'insertion
            $sql = "INSERT INTO catalogue_genre (catalogue_id, genre_id) VALUES (:cid, :gid)";
            foreach ($genreIds as $genreId) {
                $this->connection->execute($sql, ['cid' => $catalogueId, 'gid' => (int)$genreId]);
            }
        }

        // 3. Insérer les nouvelles plateformes
        if (!empty($plateformeIds)) {
            $sql = "INSERT INTO catalogue_plateforme (catalogue_id, plateforme_id) VALUES (:cid, :pid)";
            foreach ($plateformeIds as $platId) {
                $this->connection->execute($sql, ['cid' => $catalogueId, 'pid' => (int)$platId]);
            }
        }
    }

    public function findById(int $id): ?Catalogue
    {
        return $this->findOneBy(['id' => $id]);
    }  

    public function findByIdAndUser(int $id): ?Catalogue
    {
        return $this->findOneBy(['id' => $id]);
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
}
