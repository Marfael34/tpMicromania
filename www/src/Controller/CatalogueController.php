<?php

declare(strict_types=1);

namespace App\Controller;

use App\Entity\Catalogue;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Middleware\AuthMiddleware;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use JulienLinard\Core\Session\Session;
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

        $imagePath = null;

        // 3. Gestion de l'image
        if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
            $uploadResult = $this->fileUpload->upload($_FILES['media']);

            if ($uploadResult->isSuccess()) {
                $data = $uploadResult->getData();
                $imagePath = ltrim($data['path'], '/');
            } else {
                $errors['general'] = $uploadResult->getError();
            }
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

   #[Route(path: '/admin/catalogue/create', methods: ['POST'], name: 'admin.store', middleware: [AuthMiddleware::class])]
    public function store(Request $request): Response
    {
        // 1. Vérification auth
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }
        
        // 2. Récupération des données du formulaire
        $title = trim((string) $request->getPost('title', ''));
        $description = trim((string) $request->getPost('description', ''));
        $price = (float) $request->getPost('price', 0);
        $stock = (int) $request->getPost('stock', 0);
        
        $genreIds = $request->getPost('genres', []); 
        $platformIds = $request->getPost('plateformes', []);
        
        // 3. Validation (inchangée)
        $errors = [];
        if (empty($title)) $errors['title'] = 'Le titre est requis';
        if ($price <= 0) $errors['price'] = 'Le prix doit être supérieur à 0';
        if ($stock < 0) $errors['stock'] = 'Le stock ne peut pas être négatif';
        
        if (!empty($errors)) {
            Session::flash('error', 'Veuillez corriger les erreurs du formulaire');
            return $this->view('admin/create_catalogue', [ // Attention: vérifiez le nom de la vue, c'était 'admin/create' dans votre code original mais 'admin/create_catalogue' dans le catch
                'title' => 'Ajouter un jeu',
                'errors' => $errors,
                'old' => compact('title', 'description', 'price', 'stock')
            ]);
        }
        
        try {
            // 4. Instanciation
            $catalogue = new Catalogue();
            $catalogue->setTitle($title);
            $catalogue->setDescription($description ?: null);
            $catalogue->setPrice($price);
            $catalogue->setStock($stock);
            
            $catalogue->genre = $genreIds; 
            $catalogue->plateforme = $platformIds;

            
            // On vérifie d'abord si un fichier a été envoyé pour éviter une erreur
            if (isset($_FILES['media']) && $_FILES['media']['error'] !== UPLOAD_ERR_NO_FILE) {
                
                // Correction 1 : Utilisation du bon nom de propriété ($this->fileUpload)
                $uploadResult = $this->fileUpload->upload($_FILES['media']);
                
                // Correction 2 : Utilisation de isSuccess() au lieu de upload()
                if ($uploadResult->isSuccess()) {
                    // Correction 3 : getData() renvoie directement le tableau du fichier unique
                    $fileData = $uploadResult->getData();
                    
                    if (isset($fileData['path'])) {
                        $catalogue->setMediaPath($fileData['path']); 
                    }
                } elseif ($uploadResult->hasErrors()) {
                    // Si l'upload échoue vraiment (pas juste "aucun fichier"), on lève une exception
                    throw new \Exception("Erreur upload : " . $uploadResult->getErrorsAsString());
                }
            }
            // -------------------------------

            // 6. Sauvegarde
            $this->em->persist($catalogue);
            $this->em->flush(); 
            
            // 7. Relations
            if (!empty($catalogue->genre)) {
                $this->saveManyToManyRelations($catalogue, 'genre', 'catalogue_genre');
            }
            if (!empty($catalogue->plateforme)) {
                $this->saveManyToManyRelations($catalogue, 'plateforme', 'catalogue_plateforme'); 
            }

            // 8. Cache
            if (method_exists($this->em, 'getQueryCache')) {
                $queryCache = $this->em->getQueryCache();
                if ($queryCache) {
                    $queryCache->invalidateEntity(Catalogue::class, $catalogue->getId());
                }
            }
            
            Session::flash('success', 'Jeu ajouté au catalogue avec succès !');
            return $this->redirect('/admin');
            
        } catch (\Exception $e) {
            Session::flash('error', 'Erreur lors de la création : ' . $e->getMessage());
            return $this->view('admin/create_catalogue', [
                'title' => 'Ajouter un jeu',
                'old' => compact('title', 'description', 'price', 'stock'),
                'errors' => ['general' => $e->getMessage()]
            ]);
        }
    } 
        /**
     * Gère l'upload d'une image de manière propre et sécurisée.
     * Retourne soit le chemin final du fichier, soit null.
     */
    private function handleImageUpload(string $fieldName = 'media'): ?string
    {
        // Aucun fichier envoyé ?
        if (!isset($_FILES[$fieldName]) || $_FILES[$fieldName]['error'] === UPLOAD_ERR_NO_FILE) {
            return null;
        }

        $uploadResult = $this->fileUpload->upload($_FILES[$fieldName]);

        // Si échec → on stoppe proprement (sans crash)
        if (!$uploadResult->isSuccess()) {
            Session::flash('error', $uploadResult->getError());
            return null;
        }

        $data = $uploadResult->getData();

        // Sécurité supplémentaire
        if (!is_array($data) || !isset($data['path'])) {
            Session::flash('error', "L'image n'a pas pu être traitée correctement.");
            return null;
        }

        // Retour du chemin final
        return $data['path'];
    }

}
