<?php
use JulienLinard\Core\Session\Session;
$successMessage = Session::get('success');
$errorMessage = Session::get('error');
Session::remove('success');
Session::remove('error');
?>

<header class="sticky top-0 z-50 bg-[#52ae32] shadow-lg">
    <div class="max-w-8xl h-16 flex items-center justify-between">

        <!-- Logo -->
        <div class="bg-blue-800 h-16 p-2">
            <a href="/" aria-label="Accueil Micromania">
                <img 
                    src="../assets/image/logo-micromania.svg" 
                    alt="Logo Micromania" 
                    class="h-8 w-auto pt-2 filter brightness-0 invert"
                >
            </a>
        </div>

        <!-- Barre de recherche (desktop) -->
        <div class="hidden lg:block flex-1 max-w-lg mx-6">
            <label for="search-input" class="sr-only">Rechercher des produits</label>
            <div class="relative">
                <input 
                    type="search" 
                    id="search-input" 
                    placeholder="Rechercher un jeu, une console..." 
                    aria-label="Champ de recherche"
                    class="w-full py-2 pl-4 pr-10 rounded-full bg-gray-700 text-white placeholder-gray-400 
                           focus:outline-none focus:ring-2 focus:ring-[#E60028]"
                >
                <button 
                    type="submit" 
                    aria-label="Lancer la recherche"
                    class="absolute inset-y-0 right-0 flex items-center pr-3"
                >
                    <svg class="h-5 w-5 text-gray-400 hover:text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                        <path stroke-linecap="round" stroke-linejoin="round"
                              d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                    </svg>
                </button>
            </div>
        </div>

        <!-- Icônes (compte, panier, menu mobile) -->
        <div class="flex flex-row gap-4 mr-8">

            <!-- Compte -->
            <div class="basis-2xs">
                <a href="/login" aria-label="Mon Compte"
                class="text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028]">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                    </svg>
                </a>
            </div>

            
                    
            <!-- Panier -->
            <div class="basis-2xs">      
                <a href="/panier/index"
                class=" text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028] relative">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                    </svg>
                </a>
            </div>
            <?php if (($user->role ?? 'user') === 'admin'): ?>
                <!-- interface admin -->
                <a 
                    href="/admin" 
                    class=" text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028] relative"
                    >   
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="1em" height="1em" viewBox="0 0 24 24"><g fill="none" stroke="currentColor" stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" ><path d="M20 22v-5c0-1.885 0-2.828-.586-3.414S17.886 13 16 13l-4 9l-4-9c-1.886 0-2.828 0-3.414.586S4 15.115 4 17v5"/><path d="m12 15l-.5 4l.5 1.5l.5-1.5zm0 0l-1-2h2zm3.5-8.5v-1a3.5 3.5 0 0 0-7 0v1a3.5 3.5 0 1 0 7 0"/></g></svg>
                    </a>
            <?php endif; ?>
           <?php use JulienLinard\Core\View\ViewHelper; ?>
            <!-- Déconexion -->
             <?php if ($isAuthenticated ?? false): ?>
                <div class="basis-2xs ">
                    <form action="/logout" method="POST" onsubmit="return confirm('Etes vous sur de vouloir vous déconnecter ?')">
                       <?= ViewHelper::csrfField() ?>          
                       <button type="submit" class="text-sm text-white  hover:text-gray-900 transition-colors">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24">
                                <path fill="currentColor" d="M5 21q-.825 0-1.412-.587T3 19V5q0-.825.588-1.412T5 3h7v2H5v14h7v2zm11-4l-1.375-1.45l2.55-2.55H9v-2h8.175l-2.55-2.55L16 7l5 5z"/></svg>
                        </button>
                    </form>
                </div>
            <?php endif?>
            
         </div>
            
    </div>
    </div>


</header>
<div class="container mx-auto px-4 py-8">
    <div class="max-w-6xl mx-auto">
        <!-- Flash Messages -->
        <?php if ($successMessage): ?>
            <div class="mb-4 p-4 bg-green-50 border border-green-200 rounded-lg">
                <p class="text-sm text-green-600"><?= htmlspecialchars($successMessage) ?></p>
            </div>
        <?php endif; ?>
        
        <?php if ($errorMessage): ?>
            <div class="mb-4 p-4 bg-red-50 border border-red-200 rounded-lg">
                <p class="text-sm text-red-600"><?= htmlspecialchars($errorMessage) ?></p>
            </div>
        <?php endif; ?>
        
        
        <!-- Stats 
        <?php if (isset($stats)): ?>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
            <div class="bg-white rounded-lg shadow p-4">
                <div class="text-sm text-gray-600">Total</div>
                <div class="text-2xl font-bold text-gray-800"><?= $stats['total'] ?></div>
            </div>
            <div class="bg-green-50 rounded-lg shadow p-4">
                <div class="text-sm text-green-600">Complétés</div>
                <div class="text-2xl font-bold text-green-800"><?= $stats['completed'] ?></div>
            </div>
            <div class="bg-yellow-50 rounded-lg shadow p-4">
                <div class="text-sm text-yellow-600">En attente</div>
                <div class="text-2xl font-bold text-yellow-800"><?= $stats['pending'] ?></div>
            </div>
        </div>
        <?php endif; ?>
        -->
        
        <!-- Game List -->
        <?php if (empty($catalogue)): ?>
        <div class="bg-white rounded-lg shadow-lg p-8 text-center">
            <p class="text-gray-600 mb-4">Aucun jeux pour le moment.</p>
            <a 
                href="/" 
                class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700"
            >
                Ajouter votre premier jeux
            </a>
        </div>
        <?php else: ?>
        <div class="space-y-4">
            <?php foreach ($catalogue as $game): ?>
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex-1">
                        <div class="flex items-center gap-3 mb-2">
                            <h3 class="text-xl font-semibold text-gray-800 <?= $game->completed ? 'line-through text-gray-500' : '' ?>">
                                <?= htmlspecialchars($game->title) ?>
                            </h3>
                            <?php if ($game->completed): ?>
                                <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">En attente de payment</span>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($game->description): ?>
                            <p class="text-gray-600 mb-3"><?= nl2br(htmlspecialchars($game->description)) ?></p>
                        <?php endif; ?>
                        
                        <!-- Images du todo -->
                        <?php if (!empty($game->media) && is_array($game->media)): ?>
                        <div class="mb-3">
                            <div class="flex gap-2 flex-wrap">
                                <?php foreach (array_slice($game->media, 0, 4) as $media): ?>
                                    <?php if ($media->isImage()): ?>
                                        <img 
                                            src="<?= htmlspecialchars($media->path) ?>" 
                                            alt="<?= htmlspecialchars($media->original_filename) ?>"
                                            class="w-16 h-16 object-cover rounded border border-gray-200"
                                            title="<?= htmlspecialchars($media->original_filename) ?>"
                                        >
                                    <?php endif; ?>
                                <?php endforeach; ?>
                                <?php if (count($game->media) > 4): ?>
                                    <div class="w-16 h-16 bg-gray-100 rounded border border-gray-200 flex items-center justify-center text-xs text-gray-500">
                                        +<?= count($game->media) - 4 ?>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                        
                        <div class="flex items-center gap-4 text-sm text-gray-500">
                            <span>Créé le <?= $game->created_at ? $game->created_at->format('d/m/Y H:i') : '-' ?></span>
                            <?php if ($game->updated_at && $game->updated_at != $game->created_at): ?>
                                <span>• Modifié le <?= $game->updated_at->format('d/m/Y H:i') ?></span>
                            <?php endif; ?>
                        </div>
                    </div>
                    
                    <div class="flex items-center gap-2 ml-4">
                        <form method="POST" action="/todos/<?game->id ?>/toggle" class="inline">
                            <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                            <button 
                                type="submit" 
                                class="px-3 py-1 text-sm rounded <?= $game->completed ? 'bg-yellow-100 text-yellow-800 hover:bg-yellow-200' : 'bg-green-100 text-green-800 hover:bg-green-200' ?>"
                            >
                                <?= $game->completed ? 'Marquer non complété' : 'Marquer complété' ?>
                            </button>
                        </form>
                        
                        <form method="POST" action="/panier/<?= $game->id ?>/delete" class="inline" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer ce todo ?');">
                            <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                            <button 
                                type="submit" 
                                class="px-3 py-1 text-sm bg-red-100 text-red-800 rounded hover:bg-red-200"
                            >
                                Supprimer
                            </button>
                        </form>
                    </div>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>
</div>
