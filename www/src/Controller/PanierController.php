<?php
declare(strict_types=1);

namespace App\Controller;

use Exception;
use App\Entity\User;
use App\Entity\Catalogue;
use App\Service\UploadResult;
use JulienLinard\Router\Request;
use JulienLinard\Router\Response;
use App\Repository\UserRepository;
use App\Service\FileUploadService;
use JulienLinard\Auth\AuthManager;
use App\Repository\PanierRepository;
use JulienLinard\Core\Form\Validator;
use App\Repository\WishlistRepository;
use JulienLinard\Core\Session\Session;
use App\Repository\CatalogueRepository;
use JulienLinard\Doctrine\EntityManager;
use JulienLinard\Router\Attributes\Route;
use JulienLinard\Core\Controller\Controller;
use JulienLinard\Auth\Middleware\AuthMiddleware;

class PanierController extends Controller
{
    public function __construct(
        private AuthManager $auth,
        private EntityManager $em,
        private Validator $validator,
        private FileUploadService $fileUpload
    ) {}
    
   

    /**
     * Liste (Correction de la méthode index existante pour correspondre à la vue)
     */
    #[Route(path: '/panier/index', methods: ['GET'], name: 'panier.index', middleware: [new AuthMiddleware()])]
    public function index(): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }
        
      
        //  récupère les infos complètes (JOIN panier, etat, etc.)
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        
        // ATTENTION : Assurez-vous d'avoir la méthode findPanierByUser dans CatalogueRepository (voir conversation précédente)
        // Sinon, utilisez celle du PanierRepository si vous l'avez déplacée.
        // Ici je suppose qu'on utilise celle définie précédemment :
        $panierItems = $catalogueRepo->findPanierByUser($user->getId()); 
        
        
        return $this->view('panier/index', [
            'title' => 'Mon Panier',
            'catalogue' => $panierItems, // J'ai renommé 'jeux' en 'catalogue' pour matcher votre vue
            'user'  => $this->auth->user(),
            'isAuthenticated' => $this->auth->check()
        ]);
    }

     #[Route(path: '/panier/add/{id}', methods: ['POST'], name: 'panier.add', middleware: [new AuthMiddleware()])]
    public function add(int $id): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        // Vérification que le jeu existe
        $catalogueRepo = $this->em->createRepository(CatalogueRepository::class, Catalogue::class);
        $game = $catalogueRepo->findById($id);

        if (!$game) {
            Session::flash('error', 'Ce jeu n\'existe pas.');
            return $this->redirect('/');
        }

        // Initialisation du repository Panier
        $panierRepo = $this->em->createRepository(PanierRepository::class, Catalogue::class);
        
        try {
            // Ajout au panier (état 1 = dans le panier)
            $panierRepo->addToPanier($user->getId(), $id, 1);
            
            $wishlistRepo = $this->em->createRepository(WishlistRepository::class, Catalogue::class);
            $wishlistRepo->removeFromWishlist($user->getId(), $id);
            Session::flash('success', 'Jeu ajouté au panier avec succès !');

            
        } catch (Exception $e) {
            Session::flash('error', 'Erreur lors de l\'ajout au panier : ' . $e->getMessage());
        }

        // Redirection vers le panier pour voir l'ajout
        return $this->redirect('/panier/index');
    }
    
    /**
     * Supprime un jeux du panier
     */
    #[Route(path: '/catalogue/{id}/delete', methods: ['POST'], name: 'jeux.delete', middleware: [new AuthMiddleware()])]
    public function delete(int $id): Response
    {
        $user = $this->auth->user();
        if (!$user) {
            return $this->redirect('/login');
        }

        // Initialisation du repository Panier
        // On garde votre logique actuelle (passage de Catalogue::class)
        $panierRepo = $this->em->createRepository(PanierRepository::class, Catalogue::class);
        
        try {
            // Appel de la méthode de suppression créée à l'étape 1
            $panierRepo->removeFromPanier($user->getId(), $id);
            Session::flash('success', 'Jeu supprimé du panier avec succès !');
        } catch (Exception $e) {
            Session::flash('error', 'Erreur lors de la suppression : ' . $e->getMessage());
        }

        // Redirection vers le panier
        return $this->redirect('/panier/index');
    }
    
    /**
     * Gère l'upload de médias depuis la requête
     * 
     * @param Request $request
     * @return \App\Service\UploadResult Résultat de l'upload
     */
    private function handleMediaUpload(Request $request): UploadResult
    {
        // Vérifier si des fichiers ont été uploadés
        if (!isset($_FILES['media']) || empty($_FILES['media'])) {
            return \App\Service\UploadResult::success([]);
        }
        
        $files = $_FILES['media'];
        
        // Vérifier que 'name' existe
        if (!isset($files['name'])) {
            return \App\Service\UploadResult::success([]);
        }
        
        // Si c'est un seul fichier (pas de tableau)
        if (!is_array($files['name'])) {
            // Vérifier si le fichier est vide
            if (!isset($files['error']) || $files['error'] === UPLOAD_ERR_NO_FILE || empty($files['tmp_name'])) {
                return \App\Service\UploadResult::success([]);
            }
        } else {
            // Si c'est un tableau de fichiers, vérifier qu'au moins un fichier est valide
            $hasValidFile = false;
            if (isset($files['error']) && is_array($files['error'])) {
                foreach ($files['error'] as $error) {
                    if ($error !== UPLOAD_ERR_NO_FILE) {
                        $hasValidFile = true;
                        break;
                    }
                }
            }
            if (!$hasValidFile) {
                return \App\Service\UploadResult::success([]);
            }
        }
        
        // Utiliser la méthode uploadMultiple du service qui gère les deux cas
        return $this->fileUpload->uploadMultiple($files);
    }
    
    /**
     * Sauvegarde les relations ManyToMany dans la table de jointure
     * 
     * @param object $entity Entité source
     * @param string $relationName Nom de la propriété de relation
     * @param string $joinTable Nom de la table de jointure
     */
    private function saveManyToManyRelations(object $entity, string $relationName, string $joinTable): void
    {
        $className = get_class($entity);
        $metadataReader = $this->em->getMetadataReader();
        $metadata = $metadataReader->getMetadata($className);
        
        // Récupérer l'ID de l'entité
        $entityReflection = new \ReflectionClass($entity);
        $idProperty = $entityReflection->getProperty($metadata['id']);
        $idProperty->setAccessible(true);
        $entityId = $idProperty->getValue($entity);
        
        if ($entityId === null) {
            return;
        }
        
        // Récupérer les médias
        $mediaProperty = $entityReflection->getProperty($relationName);
        $mediaProperty->setAccessible(true);
        $mediaArray = $mediaProperty->getValue($entity);
        
        if (!is_array($mediaArray) || empty($mediaArray)) {
            return;
        }
        
        // Accéder à la Connection
        $connection = $this->em->getConnection();
        
        // Supprimer les anciennes relations pour cette entité
        // Convention : todo_media -> todo_id et media_id
        $todoIdColumn = 'todo_id';
        $connection->execute(
            "DELETE FROM `{$joinTable}` WHERE `{$todoIdColumn}` = :todo_id",
            ['todo_id' => $entityId]
        );
        
        // Insérer les nouvelles relations
        $mediaIdColumn = 'media_id';
        
        foreach ($mediaArray as $media) {
            if (!is_object($media)) {
                continue;
            }
            
            $mediaReflection = new \ReflectionClass($media);
            $mediaIdProperty = $mediaReflection->getProperty('id');
            $mediaIdProperty->setAccessible(true);
            $mediaId = $mediaIdProperty->getValue($media);
            
            if ($mediaId === null) {
                continue;
            }
            
            $connection->execute(
                "INSERT INTO `{$joinTable}` (`{$todoIdColumn}`, `{$mediaIdColumn}`) VALUES (:todo_id, :media_id)",
                [
                    'todo_id' => $entityId,
                    'media_id' => $mediaId
                ]
            );
        }
    }
}
