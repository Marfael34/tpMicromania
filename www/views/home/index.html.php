<header class="sticky top-0 z-50 bg-[#52ae32] shadow-lg">
    <div class="max-w-7xl h-16 flex items-center justify-between">

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
        <div class="hidden lg:block flex-1 max-w-lg mx-8">
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

            <!-- Compte -->
            <a href="/register" aria-label="Mon Compte"
               class="p-2 text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028]">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z"/>
                </svg>
            </a>

            <!-- Panier -->
            <a href="#" aria-label="Mon Panier"
               class="p-2 text-white rounded-md hover:text-[#E60028] focus:outline-none focus:ring-2 focus:ring-[#E60028] relative">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke-width="2" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round"
                          d="M3 3h2l.4 2M7 13h10l4-8H5.4M7 13L5.4 5M7 13l-2.293 2.293c-.63.63-.184 1.707.707 1.707H17m0 0a2 2 0 100 4 2 2 0 000-4zm-8 2a2 2 0 11-4 0 2 2 0 014 0z"/>
                </svg>

            </a>
        </div>

    </div>


</header>

<h1 class="text-4xl text-center font-bold text-gray-800 mb-4 "><?= htmlspecialchars($title ?? 'Welcome') ?></h1>
<p class="text-xl text-gray-800 mb-6 px-4"><?= htmlspecialchars($message ?? 'Hello World!') ?></p>

<div class="mx-auto max-w-7xl px-5 py-4 bg-gray-100 bg-white">
    <h2 class="text-2xl font-bold text-gray-800 mb-6">Sélection de Jeux</h2>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-3 md:gap-8">

        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-4">
                
                <div class="flex items-start justify-between mb-4">
                    
                    <div class="w-1/4 flex-shrink-0 mr-4">
                        <img src="assets/image/r6.jpg" alt="Boîte de jeu Rainbow Six Siège PS5" class="w-full h-auto object-cover rounded">
                    </div>

                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                            Rainbow Six Siège PS5
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Plateforme : PS5</p>
                    </div>

                    <button class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 flex-shrink-0" aria-label="Ajouter aux favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" /><!-- penser a changer les svg par ceux que j'ai choisi -->
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end mb-4">
                    <span class="text-2xl font-bold text-red-600">39,99 €</span>
                </div>

                <div class="flex justify-between items-center gap-2">
                    <button class="w-1/2 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Ajouter Rainbow Six Siège au panier">
                        🛒 Ajouter
                    </button>
                    <button class="w-1/2 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Voir la fiche produit de Rainbow Six Siège">
                        🔍 Voir produit
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-4">
                
                <div class="flex items-start justify-between mb-4">
                    
                    <div class="w-1/4 flex-shrink-0 mr-4">
                        <img src="assets/image/r6.jpg" alt="Boîte de jeu Rainbow Six Siège PS5" class="w-full h-auto object-cover rounded">
                    </div>

                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                            Jeu de la Mort Subite
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Plateforme : XBOX</p>
                    </div>

                    <button class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 flex-shrink-0" aria-label="Ajouter aux favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end mb-4">
                    <span class="text-2xl font-bold text-red-600">59,99 €</span>
                </div>

                <div class="flex justify-between items-center gap-2">
                    <button class="w-1/2 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Ajouter Jeu de la Mort Subite au panier">
                        🛒 Ajouter
                    </button>
                    <button class="w-1/2 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Voir la fiche produit de Jeu de la Mort Subite">
                        🔍 Voir produit
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-4">
                
                <div class="flex items-start justify-between mb-4">
                    
                    <div class="w-1/4 flex-shrink-0 mr-4">
                        <img src="assets/image/r6.jpg" alt="Boîte de jeu Rainbow Six Siège PS5" class="w-full h-auto object-cover rounded">
                    </div>

                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                            Aventure Fantastique
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Plateforme : Switch</p>
                    </div>

                    <button class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 flex-shrink-0" aria-label="Ajouter aux favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end mb-4">
                    <span class="text-2xl font-bold text-red-600">29,99 €</span>
                </div>

                <div class="flex justify-between items-center gap-2">
                    <button class="w-1/2 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Ajouter Aventure Fantastique au panier">
                        🛒 Ajouter
                    </button>
                    <button class="w-1/2 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Voir la fiche produit de Aventure Fantastique">
                        🔍 Voir produit
                    </button>
                </div>
            </div>
        </div>

        <div class="bg-white border border-gray-200 rounded-lg shadow-md hover:shadow-lg transition-shadow duration-300 overflow-hidden">
            <div class="p-4">
                
                <div class="flex items-start justify-between mb-4">
                    
                    <div class="w-1/4 flex-shrink-0 mr-4">
                        <img src="assets/image/r6.jpg" alt="Boîte de jeu Rainbow Six Siège PS5" class="w-full h-auto object-cover rounded">
                    </div>

                    <div class="flex-grow">
                        <h3 class="text-lg font-semibold text-gray-800 leading-tight">
                            Aventure Fantastique
                        </h3>
                        <p class="text-sm text-gray-500 mt-1">Plateforme : Switch</p>
                    </div>

                    <button class="text-gray-400 hover:text-red-500 transition-colors duration-200 p-1 flex-shrink-0" aria-label="Ajouter aux favoris">
                        <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                            <path stroke-linecap="round" stroke-linejoin="round" d="M4.318 6.318a4.5 4.5 0 000 6.364L12 20.364l7.682-7.682a4.5 4.5 0 00-6.364-6.364L12 7.636l-1.318-1.318a4.5 4.5 0 00-6.364 0z" />
                        </svg>
                    </button>
                </div>

                <div class="flex justify-end mb-4">
                    <span class="text-2xl font-bold text-red-600">29,99 €</span>
                </div>

                <div class="flex justify-between items-center gap-2">
                    <button class="w-1/2 py-2 bg-blue-600 text-white font-bold rounded-md hover:bg-blue-700 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Ajouter Aventure Fantastique au panier">
                        🛒 Ajouter
                    </button>
                    <button class="w-1/2 py-2 bg-gray-200 text-gray-800 font-bold rounded-md hover:bg-gray-300 transition-colors duration-200 uppercase text-xs sm:text-sm" aria-label="Voir la fiche produit de Aventure Fantastique">
                        🔍 Voir produit
                    </button>
                </div>
            </div>
        </div>
        
    </div>
</div>

   
