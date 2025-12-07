<?php
// views/admin/edit.html.php

use JulienLinard\Core\Session\Session;

// Récupération des messages flash (si nécessaire, ou géré par le layout parent)
$errors = $errors ?? [];
// On s'assure que l'objet catalogue existe
if (!isset($catalogue) || !$catalogue) {
    echo "Jeu introuvable.";
    exit;
}
?>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-xl shadow-lg overflow-hidden">
        <div class="bg-gray-50 px-8 py-6 border-b border-gray-100 flex justify-between items-center">
            <div>
                <h1 class="text-2xl font-bold text-gray-800">Modifier le jeu</h1>
                <p class="text-sm text-gray-500 mt-1">Édition de : <span class="font-medium"><?= htmlspecialchars($catalogue->getTitle()) ?></span></p>
            </div>
            <span class="bg-indigo-100 text-indigo-800 text-xs font-semibold px-3 py-1 rounded-full">
                ID #<?= $catalogue->getId() ?>
            </span>
        </div>

        <div class="p-8">
            <?php if (isset($errors['general']) || Session::get('error')): ?>
                <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded-r-lg flex items-center">
                    <svg class="w-5 h-5 text-red-500 mr-2" fill="currentColor" viewBox="0 0 20 20">
                        <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7 4a1 1 0 11-2 0 1 1 0 012 0zm-1-9a1 1 0 00-1 1v4a1 1 0 102 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                    <div>
                        <p class="text-sm text-red-700"><?= htmlspecialchars($errors['general'] ?? Session::get('error')) ?></p>
                    </div>
                </div>
                <?php Session::remove('error'); ?>
            <?php endif; ?>

            <form method="POST" action="/admin/catalogue/update" enctype="multipart/form-data" class="space-y-6">
                
                <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                
                <input type="hidden" name="id" value="<?= $catalogue->getId() ?>">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                    <div class="md:col-span-2">
                        <label for="title" class="block text-sm font-semibold text-gray-700 mb-2">
                            Titre du jeu <span class="text-red-500">*</span>
                        </label>
                        <input 
                            type="text" 
                            id="title" 
                            name="title" 
                            value="<?= htmlspecialchars($catalogue->getTitle()) ?>"
                            class="w-full px-4 py-2 border rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors <?= isset($errors['title']) ? 'border-red-500 bg-red-50' : 'border-gray-300' ?>"
                            required
                            maxlength="255"
                        >
                        <?php if (isset($errors['title'])): ?>
                            <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['title']) ?></p>
                        <?php endif; ?>
                    </div>

                    <div>
                        <label for="price" class="block text-sm font-semibold text-gray-700 mb-2">
                            Prix (€)
                        </label>
                        <input 
                            type="number" 
                            step="0.01"
                            id="price" 
                            name="price" 
                            value="<?= htmlspecialchars($catalogue->price ?? '0') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>

                    <div>
                        <label for="stock" class="block text-sm font-semibold text-gray-700 mb-2">
                            Stock
                        </label>
                        <input 
                            type="number" 
                            id="stock" 
                            name="stock" 
                            value="<?= htmlspecialchars($catalogue->stock ?? '0') ?>"
                            class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500"
                        >
                    </div>
                </div>

                <div>
                    <label for="description" class="block text-sm font-semibold text-gray-700 mb-2">
                        Description
                    </label>
                    <textarea 
                        id="description" 
                        name="description" 
                        rows="5"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500 transition-colors"
                    ><?= htmlspecialchars($catalogue->description ?? '') ?></textarea>
                </div>

                <hr class="border-gray-200">

                <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Plateformes compatibles</label>
                        <div class="border border-gray-200 rounded-lg p-4 max-h-48 overflow-y-auto bg-gray-50">
                            <div class="grid grid-cols-1 gap-2">
                                <?php 
                                // Extraction des IDs des plateformes actuelles du jeu
                                $currentPlateformeIds = [];
                                if (!empty($catalogue->plateforme)) {
                                    $currentPlateformeIds = array_map(fn($p) => $p->getId(), $catalogue->plateforme);
                                }
                                ?>
                                <?php if (!empty($plateformes)): ?>
                                    <?php foreach ($plateformes as $p): ?>
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                            <input 
                                                type="checkbox" 
                                                name="plateformes[]" 
                                                value="<?= $p->getId() ?>"
                                                <?= in_array($p->getId(), $currentPlateformeIds) ? 'checked' : '' ?>
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            >
                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($p->getLabel()) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500 italic">Aucune plateforme disponible</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-3">Genres</label>
                        <div class="border border-gray-200 rounded-lg p-4 max-h-48 overflow-y-auto bg-gray-50">
                            <div class="grid grid-cols-1 gap-2">
                                <?php 
                                // Extraction des IDs des genres actuels du jeu
                                $currentGenreIds = [];
                                if (!empty($catalogue->genre)) {
                                    $currentGenreIds = array_map(fn($g) => $g->getId(), $catalogue->genre);
                                }
                                ?>
                                <?php if (!empty($genres)): ?>
                                    <?php foreach ($genres as $g): ?>
                                        <label class="flex items-center space-x-3 cursor-pointer hover:bg-gray-100 p-1 rounded">
                                            <input 
                                                type="checkbox" 
                                                name="genres[]" 
                                                value="<?= $g->getId() ?>"
                                                <?= in_array($g->getId(), $currentGenreIds) ? 'checked' : '' ?>
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                            >
                                            <span class="text-sm text-gray-700"><?= htmlspecialchars($g->getLabel()) ?></span>
                                        </label>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500 italic">Aucun genre disponible</p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>

                <hr class="border-gray-200">

                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2">Image du jeu</label>
                    
                    <div class="flex flex-col md:flex-row gap-6 items-start">
                        <div class="w-full md:w-1/3">
                            <div class="border rounded-lg p-2 bg-white shadow-sm">
                                <p class="text-xs text-gray-500 mb-2 font-medium uppercase tracking-wide">Image actuelle :</p>
                                <?php if ($catalogue->media_path): ?>
                                    <div class="relative group">
                                        <img src="<?= htmlspecialchars($catalogue->media_path) ?>" alt="Cover actuelle" class="w-full h-48 object-cover rounded bg-gray-100" id="currentImage">
                                        <div class="absolute bottom-0 left-0 right-0 bg-black bg-opacity-60 text-white text-xs p-2 rounded-b">
                                            <?= basename($catalogue->media_path) ?>
                                        </div>
                                    </div>
                                <?php else: ?>
                                    <div class="w-full h-48 bg-gray-100 rounded flex items-center justify-center border-2 border-dashed border-gray-300">
                                        <span class="text-gray-400 text-sm">Aucune image</span>
                                    </div>
                                <?php endif; ?>
                            </div>
                        </div>

                        <div class="w-full md:w-2/3">
                            <label class="block mb-2 text-sm text-gray-600">Pour modifier, choisissez une nouvelle image :</label>
                            
                            <label for="media" class="flex flex-col items-center justify-center w-full h-48 border-2 border-gray-300 border-dashed rounded-lg cursor-pointer bg-gray-50 hover:bg-gray-100 hover:border-indigo-400 transition-all group">
                                <div class="flex flex-col items-center justify-center pt-5 pb-6" id="dropZoneContent">
                                    <svg class="w-10 h-10 mb-3 text-gray-400 group-hover:text-indigo-500 transition-colors" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                    </svg>
                                    <p class="mb-2 text-sm text-gray-500"><span class="font-semibold text-indigo-600">Cliquez pour remplacer</span></p>
                                    <p class="text-xs text-gray-500">PNG, JPG, WebP (Max 10Mo)</p>
                                </div>
                                <div id="newImagePreview" class="hidden w-full h-full relative p-2">
                                    <img src="" class="w-full h-full object-contain rounded" />
                                    <button type="button" id="removeUpload" class="absolute top-4 right-4 bg-red-500 text-white p-1 rounded-full shadow hover:bg-red-600 transition">
                                        <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                                    </button>
                                </div>
                                <input 
                                    type="file" 
                                    id="media" 
                                    name="media" 
                                    accept="image/*"
                                    class="hidden"
                                    onchange="previewFile(this)"
                                >
                            </label>
                        </div>
                    </div>
                </div>
                
                <div class="flex items-center gap-4 pt-6 mt-6 border-t border-gray-100">
                    <button 
                        type="submit" 
                        class="flex-1 bg-indigo-600 hover:bg-indigo-700 text-white font-semibold py-3 px-6 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition-all transform active:scale-[0.99]"
                    >
                        Enregistrer les modifications
                    </button>
                    <a 
                        href="/admin/catalogue" 
                        class="px-6 py-3 border border-gray-300 text-gray-700 font-medium rounded-lg hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-gray-500 focus:ring-offset-2 transition-colors"
                    >
                        Annuler
                    </a>
                </div>
            </form>
        </div>
    </div>
</div>
