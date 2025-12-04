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
use JulienLinard\Doctrine\QueryBuilder;

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
            
            ->orderBY('c.title');
            

        $rows = $qb->execute();
        fetchAll($rows);

        // Transformation en structure exploitable
        foreach ($rows as &$row) {
            $row["genres"] = $row["genres"] ? explode(",", $row["genres"]) : [];
            $row["plateformes"] = $row["plateformes"] ? explode(",", $row["plateformes"]) : [];
        }

        return $rows;
    }

    public function findById(int $id): ?Catalogue
    {
        return $this->findOneBy(['id' => $id]);
    }
}
