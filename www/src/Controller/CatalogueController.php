<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Catalogue;
use JulienLinard\Router\Response;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use App\Repository\CatalogueRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;


class CatalogueController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private EntityManager $em,
        private FileUploadService $fileUpload
    ) {}

    #[Route(path: '/', methods: ['GET'], name: 'home')]
    public function index(): Response
    {

        $user = $this->auth->user();
        

        // Repository Catalogue
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);

        // Récupération catalogue + genres + plateformes
        $catalogues = $catalogueRepo->findAllWithRelations();

        // Chargement des médias (images liés)
        foreach ($catalogues as $catalogue) {
            $entity = $this->em->find(Catalogue::class, $catalogue['id']);
            $this->em->loadMediaRelations($entity);
            $catalogue['media_path'] = $entity->getMedia(); // Ajout des médias dans le tableau final
        }
        

        return $this->view('home/index', [
            'catalogues' => $catalogues,
            'test' => "bonjours",
            'title' => 'Bienvenue sur Micromania',
            'message' => 'Tous les jeux, sur toute les consoles pour vivre votre passion !',
            'user' => $user,
            'isAuthenticated' => $this->auth->check()
            
        ]);
    }
}
