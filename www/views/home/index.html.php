
<header class="sticky top-0 z-50 bg-[#52ae32] shadow-lg">
    <div class="max-w-8xl h-16 flex items-center justify-between">

        <!-- Logo -->
        <div class="bg-blue-800 h-16 p-2">
            <a href="/" aria-label="Accueil Micromania">
                <img 
                    src="assets/image/logo-micromania.svg" 
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
                <!-- wishlist -->
                <a 
                    href="/wishlist/index" 
                    class=" text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028] relative"
                    >   
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M20 22H4a1 1 0 0 1-1-1V3a1 1 0 0 1 1-1h16a1 1 0 0 1 1 1v18a1 1 0 0 1-1 1m-1-2V4H5v16zM8 7h8v2H8zm0 4h8v2H8zm0 4h8v2H8z"/></svg>
                </a>
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

<h1 class="text-4xl text-center font-bold text-gray-800 mb-4 "><?= htmlspecialchars($title ?? 'Welcome') ?></h1>
<p class="text-xl text-center text-gray-800 mb-6 px-4"><?= htmlspecialchars($message ?? 'Hello World!') ?></p>
<?php if ($isAuthenticated ?? false): ?>
    <p class="text-4xl text-center font-bold text-gray-800 mb-4 mt-3">Bonjour, <?= htmlspecialchars($user->firstname) ?></p>
<?php endif; ?>

<div class="mx-auto max-w-7xl px-5 py-4 bg-gray-100 bg-white">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Sélection de Jeux</h2>
    <?php if (empty($catalogues)): ?>
        <p>Aucun jeu n'est actuellement disponible dans le catalogue.</p>
    <?php else: ?>
        <div class="grid grid-cols-1  md:grid-cols-3 gap-6  md:gap-8">
            <?php foreach ($catalogues as $game): ?>
                <div>
                    <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
                    
                        <div class="p-4">
                            
                            <?php
                                // Supporte à la fois le format Array ($room['...']) et Objet ($room->...)
                                $mediaPath = $game['media_path'] ?? null;
                            ?>
                            
                            <div class="flex items-start justify-between mb-4">
                                <?php if ($mediaPath != null): ?>
                                    <img src="<?= htmlspecialchars($mediaPath) ?>"
                                        alt="<?= htmlspecialchars($title) ?>"
                                        class="w-1/4 flex-shrink-0 mr-4">
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
                                    <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                                        <?= htmlspecialchars($game['title']); ?>
                                    </h3>
                                    
                                    <?php if (!empty($game['description'])): ?>
                                        <p class="text-gray-600 pb-2 mt-2 text-xs">
                                            <?= nl2br(htmlspecialchars(mb_strimwidth($game['description'], 0, 100, "..."))) ?>
                                        </p>
                                    <?php endif; ?>
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
                                <!-- ajout a la wishlist -->
                                <?php 
                                    $isInWishlist = isset($wishlistGameIds) && in_array($game['id'], $wishlistGameIds);
                                    // Détermine l'action : retirer si déjà présent, sinon ajouter
                                    $actionUrl = $isInWishlist ? '/catalogue/wishlist/' . $game['id'] . '/delete': '/wishlist/add/' . $game['id'];
                                ?>
                                 <form action="<?= $actionUrl ?>" method="post">
                                    <?= ViewHelper::csrfField() ?>
                                    <button class="transition-colors duration-200 p-1 flex-shrink-0 <?= $isInWishlist ? 'text-red-600 hover:text-red-800' : 'text-gray-400 hover:text-red-500' ?>" 
                                            aria-label="<?= $isInWishlist ? 'Retirer des favoris' : 'Ajouter aux favoris' ?>">
                                        
                                        <?php if ($isInWishlist): ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" viewBox="0 0 24 24" fill="currentColor">
                                                <path d="M12 21.35l-1.45-1.32C5.4 15.36 2 12.28 2 8.5 2 5.42 4.42 3 7.5 3c1.74 0 3.41.81 4.5 2.09C13.09 3.81 14.76 3 16.5 3 19.58 3 22 5.42 22 8.5c0 3.78-3.4 6.86-8.55 11.54L12 21.35z"/>
                                            </svg>
                                        <?php else: ?>
                                            <svg xmlns="http://www.w3.org/2000/svg" class="w-6 h-6" width="1em" height="1em" viewBox="0 0 24 24">
                                                <path fill="currentColor" d="m12.1 18.55l-.1.1l-.11-.1C7.14 14.24 4 11.39 4 8.5C4 6.5 5.5 5 7.5 5c1.54 0 3.04 1 3.57 2.36h1.86C13.46 6 14.96 5 16.5 5c2 0 3.5 1.5 3.5 3.5c0 2.89-3.14 5.74-7.9 10.05M16.5 3c-1.74 0-3.41.81-4.5 2.08C10.91 3.81 9.24 3 7.5 3C4.42 3 2 5.41 2 8.5c0 3.77 3.4 6.86 8.55 11.53L12 21.35l1.45-1.32C18.6 15.36 22 12.27 22 8.5C22 5.41 19.58 3 16.5 3"/>
                                            </svg>
                                        <?php endif; ?>
                                        
                                    </button>
                                 </form>
                                
                            </div>
                            <div class="flex flex-nowrap gap-2 ">
                                Genre :
                                        <?php foreach ($game['genres'] as $genre): ?>
                                            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($genre);?></p>
                                        <?php endforeach; ?>
                            </div>
                            <div class="flex flex-nowrap gap-2">
                                        Plateformes:
                                        <?php foreach ($game['plateformes'] as $plat): ?>
                                            <p class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($plat);?></p>
                                        <?php endforeach; ?>
                            </div>
                                <div class="flex justify-end mb-4">
                                    <span class="text-2xl font-bold text-red-600"><?= number_format($game['price'], 2); ?> €</span>
                                </div>
                
                        <div class="w-full flex justify-between items-center gap-2">
                            <form action="/panier/add/<?= $game['id'] ?>" method="POST" class="w-full">
                                <?= ViewHelper::csrfField() ?>
                                <button class="w-full py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Ajouter Rainbow Six Siège au panier">
                                    🛒 Ajouter
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
        <div>
    <?php endif; ?>
</div>

    