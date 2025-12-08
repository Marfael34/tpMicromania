<?php
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;

// Récupération des messages flash
$successMessage = Session::get('success');
$errorMessage = Session::get('error');
Session::remove('success');
Session::remove('error');
?>
<div class="min-h-screen bg-gray-50">
    <header class="bg-white shadow-sm border-b border-gray-200 sticky top-0 z-10">
        <nav class="bg-gray-900 text-white shadow-lg">
        <div class="container mx-auto px-4 py-4">
            <div class="flex justify-between items-center">
                <div class="flex items-center gap-2">
                    <span class="text-xl font-bold tracking-wider">BACK OFFICE</span>
                </div>
                    
                <div class="hidden md:flex gap-6 items-center text-sm font-medium">
                    <a href="/admin" class="hover:text-blue-400 transition-colors <?= $_SERVER['REQUEST_URI'] === '/admin' ? 'text-blue-400' : '' ?>">
                            Dashboard
                    </a>
                    <a href="/admin/catalogue" class="hover:text-blue-400 transition-colors">
                        Catalogue
                    </a>
                    <a href="/admin/users" class="hover:text-blue-400 transition-colors">
                        Utilisateurs
                    </a>
                    <div class="h-4 w-px bg-gray-700"></div> <a href="/" class="hover:text-green-400 transition-colors">
                        Voir le site
                    </a>
                    <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors">
                        Déconnexion
                    </a>
                </div>
            </div>
        </div>
    </nav>
    </header>

    <main class="max-w-3xl mx-auto px-4 sm:px-6 lg:px-8 py-10">
        
        <nav class="flex mb-5 text-gray-500 text-sm">
            <a href="/dashboard" class="hover:text-indigo-600">Tableau de bord</a>
            <span class="mx-2">/</span>
            <span class="text-gray-900">Nouveaux Jeux</span>
        </nav>

        <div class="bg-white rounded-2xl shadow-xl overflow-hidden border border-gray-100">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-700 px-8 py-8">
                <h2 class="text-2xl font-bold text-white">Créer une nouveaux jeux</h2>
                <p class="text-indigo-100 mt-2 text-sm">Remplissez les informations ci-dessous pour ajouter nouveaux jeux.</p>
            </div>

            <form action="/admin/catalogue/create" method="post" enctype="multipart/form-data" class="p-8 space-y-6">
                <?= ViewHelper::csrfField() ?>

                <?php if(isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg flex items-start">
                        <svg class="h-5 w-5 text-red-500 mr-2 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                        </svg>
                        <p class="text-sm text-red-700 font-medium"><?= htmlspecialchars($error) ?></p>
                    </div>
                <?php endif ?>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="title">Titre du jeux <span class="text-red-500">*</span></label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none text-gray-900 placeholder-gray-400" 
                           id="title" type="text" name="title" placeholder="Ex: Faire les courses" required 
                           value="<?= htmlspecialchars($title_value ?? '') ?>">
                </div>

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="description">Description</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none text-gray-900 placeholder-gray-400 resize-none" 
                              id="description" rows="5" name="description" placeholder="Détails supplémentaires..."><?= htmlspecialchars($description_value ?? '') ?></textarea>
                </div>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix
                        </label>
                        <input type="number" 
                               id="price" 
                               name="price"
                               value="<?= htmlspecialchars($price ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none text-gray-900">
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                            Stock
                        </label>
                        <input type="number" name="stock" id="stock" class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-all duration-200 outline-none text-gray-900">
                        
                    </div>
                </div>
                <!-- SECTION PLATEFORMES -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Plateformes
                    </label>
                    
                    <!-- Conteneur avec scroll si la liste est longue -->
                    <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white max-h-48 overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            
                            <?php if (!empty($plateformes)): ?>
                                <?php foreach ($plateformes as $p): ?>
                                    <div class="flex items-center">
                                        <!-- Note les crochets [] dans le name -->
                                        <input type="checkbox" 
                                            id="plat_<?= $p->getId() ?>" 
                                            label="plateformes[]" 
                                            value="<?= $p->getId() ?>"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        
                                        <label for="plat_<?= $p->getId() ?>" class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                                            <?= htmlspecialchars($p->getLabel()) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 italic">Aucune plateforme disponible.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Cochez toutes les plateformes compatibles.</p>
                </div>
                <!-- SECTION GENRES -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Genres
                    </label>
                    
                    <!-- Conteneur avec scroll -->
                    <div class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-white max-h-48 overflow-y-auto">
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-2">
                            
                            <?php if (!empty($genres)): ?>
                                <?php foreach ($genres as $g): ?>
                                    <div class="flex items-center">
                                        <input type="checkbox" 
                                            id="genre_<?= $g->getId() ?>" 
                                            label="genres[]" 
                                            value="<?= $g->getId() ?>"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 cursor-pointer">
                                        
                                        <label for="genre_<?= $g->getId() ?>" class="ml-2 text-sm text-gray-700 cursor-pointer select-none">
                                            <?= htmlspecialchars($g->getLabel()) ?>
                                        </label>
                                    </div>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <p class="text-sm text-gray-500 italic">Aucun genre disponible.</p>
                            <?php endif; ?>

                        </div>
                    </div>
                    <p class="text-xs text-gray-500 mt-1">Sélectionnez un ou plusieurs genres.</p>
                </div>
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">
                        Fichier joint
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-500 hover:bg-indigo-50 transition-all duration-200 group">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400 group-hover:text-indigo-500 transition-colors" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600 justify-center">
                                <label for="media" class="relative cursor-pointer rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none">
                                    <span id="file-label">Télécharger un fichier</span>
                                    <input id="media" name="media" type="file" class="sr-only" accept="image/*" onchange="updateFileName(this)">
                                </label>
                            </div>
                            <p class="text-xs text-gray-500" id="file-info">PNG, JPG, WEBP jusqu'à 10MB</p>
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-end space-x-4 pt-6 border-t border-gray-100">
                    <a class="px-6 py-2.5 text-sm text-gray-700 bg-white border border-gray-300 hover:bg-gray-50 rounded-lg font-medium transition-colors duration-200" href="/admin">
                        Annuler
                    </a>
                    <button class="px-6 py-2.5 text-sm bg-indigo-600 hover:bg-indigo-700 text-white rounded-lg font-medium shadow-md hover:shadow-lg duration-200 transition-all transform hover:-translate-y-0.5 flex items-center" type="submit">
                        <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>
                        Enregistrer la tâche
                    </button>
                </div>
            </form>
        </div>
    </main>
</div>