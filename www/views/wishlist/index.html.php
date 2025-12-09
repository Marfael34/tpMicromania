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
        <h1 class="text-4xl text-center font-bold text-gray-800 mb-4 "><?= htmlspecialchars($title ?? 'Welcome') ?></h1>
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
            <div class="bg-white rounded-lg shadow-lg p-6 hover:shadow-xl transition-shadow">
                <div class="flex items-start justify-between">
                    <div class="flex items-start justify-between mb-4-1">
                        <div class="grid grid-cols-1  gap-6  md:gap-8">
                            <?php foreach ($catalogue as $game): ?>
                                
                                <div>
                                    <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                                    
                                        <div class="p-4">
                                            
                                            <?php
                                                // Supporte à la fois le format Array ($room['...']) et Objet ($room->...)
                                                $mediaPath = $game['media_path'] ?? null;
                                            ?>
                                            
                                            <div class="flex items-start justify-between mb-4">
                                                <?php if (!empty($game['media_path'])): ?>
                                                    <img 
                                                        src="<?= htmlspecialchars($game['media_path']) ?>" 
                                                        alt="<?= htmlspecialchars($game['title']) ?>"
                                                        class="w-1/4 flex-shrink-0 mr-4"
                                                        title="<?= htmlspecialchars($game['title']) ?>"
                                                    >
                                                <?php else: ?>
                                                    <div class="flex items-center justify-center h-full text-gray-400">
                                                        <svg class="w-12 h-12" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                            </path>
                                                        </svg>
                                                    </div>
                                                <?php endif; ?>

                                                <div class="flex-grow">
                                                    <h3 class=" text-lg font-semibold text-gray-800 leading-tight">
                                                        <?= htmlspecialchars($game['title']) ?>
                                                    </h3>
                                                    <div class="flex flex-nowrap gap-2 ">
                                                    <p class="text-lg text-gray-500 mt-1"> Genre: <?= htmlspecialchars($game['genres']);?></p>
                                                               
                                                    </div>
                                                    <div class="flex flex-nowrap gap-2">
                                                        <p class="text-lg text-gray-500 mt-1">Plateformes: <?= htmlspecialchars($game['plateformes']);?></p>
                                
                                                    </div>
                                                    <div class="text-xs m-5  md:text-xl md:mt-20 text-gray-800 leading-tight">
                                                        <?= htmlspecialchars($game['description']); ?>
                                                    </div>
                                                    <?php if ($game['stock'] > 0): ?>
                                                        <span class="text-xs  md:text-sm bg-green-600 px-3 py-1 rounded-full">
                                                            En stock (<?= $game['stock']; ?>)
                                                        </span>
                                                    <?php else: ?>
                                                        <span class="text-sm bg-red-600 px-3 py-1 rounded-full">
                                                            Rupture
                                                        </span>
                                                    <?php endif; ?>
                                                </div>

                                              
                                            </div>
                                            <div class="flex justify-end mb-4">
                                                <span class="text-2xl font-bold text-red-600"><?= number_format($game['price'], 2); ?> €</span>
                                            </div>
                                
                                            <div class="flex justify-between items-center gap-2">

                                                <form action="/wishlist/<?= htmlspecialchars($game['id']) ?>/delete" method="POST" class="w-full">
                                                    <?= ViewHelper::csrfField() ?>
                                                     <button class="w-full py-2 bg-red-600  text-white  font-bold rounded-md hover:bg-gray-300 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Voir la fiche produit de Rainbow Six Siège">
                                                     ❌​ Retirer 
                                                    </button>
                                                </form>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <div>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>
<?php if (!empty($catalogue)): ?>
    <div class="rounded-lg shadow-lg p-8 text-center">
            <a href="/" class="inline-block bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">Continuer mes achats</a>
    </div>
<?php endif; ?> 

