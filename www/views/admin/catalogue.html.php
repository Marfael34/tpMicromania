<?php
use JulienLinard\Core\Session\Session;
use JulienLinard\Core\View\ViewHelper;

// Récupération des messages flash pour confirmer les actions (ajout/suppression)
$successMessage = Session::get('success');
$errorMessage = Session::get('error');
Session::remove('success');
Session::remove('error');
?>
<div class="min-h-screen flex flex-col">
    <nav class="bg-gray-900 text-white shadow-lg">
    <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <div class="flex items-center gap-2">
                        <span class="text-xl font-bold tracking-wider">BACK OFFICE</span>
                    </div>
                    
                    <div class="hidden md:flex gap-6 items-center text-sm font-medium">
                        <a href="/admin" class="hover:text-blue-400 transition-colors">Dashboard</a>
                        <a href="/admin/catalogue" class="text-blue-400 font-bold">Catalogue</a>
                        <a href="/admin/users" class="hover:text-blue-400 transition-colors">Utilisateurs</a>
                        <div class="h-4 w-px bg-gray-700"></div>
                        <a href="/" class="hover:text-green-400 transition-colors" target="_blank">Voir le site</a>
                        <a href="/logout" class="bg-red-600 hover:bg-red-700 text-white px-4 py-2 rounded transition-colors">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container mx-auto px-4 py-8 flex-grow">
            
            <?php if ($successMessage): ?>
                <div class="mb-6 p-4 bg-green-100 border-l-4 border-green-500 text-green-700 rounded shadow-sm">
                    <?= htmlspecialchars($successMessage) ?>
                </div>
            <?php endif; ?>

            <div class="flex flex-col md:flex-row justify-between items-center mb-8 gap-4">
                <div>
                    <h1 class="text-3xl font-bold text-gray-800">Catalogue</h1>
                    <p class="text-gray-600 mt-1">Gérez les articles visibles sur votre site.</p>
                </div>
                <a href="/admin/catalogue/create" class="bg-blue-600 hover:bg-blue-700 text-white px-6 py-2 rounded-lg shadow flex items-center gap-2 transition-all">
                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                    Ajouter un article
                </a>
            </div>

            <div class="bg-white rounded-xl shadow overflow-hidden">
                <div class="overflow-x-auto">
                    <table class="min-w-full leading-normal">
                        <thead>
                            <tr class="bg-gray-50 border-b border-gray-200 text-left text-xs font-semibold text-gray-600 uppercase tracking-wider">
                                <th class="px-6 py-4">Aperçu</th>
                                <th class="px-6 py-4">Informations</th>
                                <th class="px-6 py-4">Auteur</th>
                                <th class="px-6 py-4 text-center">Actions</th>
                            </tr>
                        </thead>
                        <tbody class="divide-y divide-gray-200">
                            
                            <?php if (empty($items)): ?>
                                <tr>
                                    <td colspan="4" class="px-6 py-10 text-center text-gray-500">
                                        <div class="flex flex-col items-center justify-center">
                                            <svg class="w-12 h-12 text-gray-300 mb-3" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M20 13V6a2 2 0 00-2-2H6a2 2 0 00-2 2v7m16 0v5a2 2 0 01-2 2H6a2 2 0 01-2-2v-5m16 0h-2.586a1 1 0 00-.707.293l-2.414 2.414a1 1 0 01-.707.293h-3.172a1 1 0 01-.707-.293l-2.414-2.414A1 1 0 006.586 13H4"></path></svg>
                                            <p class="text-lg font-medium">Aucun article trouvé.</p>
                                            <p class="text-sm">Commencez par en ajouter un nouveau.</p>
                                        </div>
                                    </td>
                                </tr>
                            <?php else: ?>
                                
                                <?php foreach ($items as $item): ?>
                                    
                                <tr class="hover:bg-gray-50 transition-colors">
                                    <td class="px-6 py-4 w-32">
                                        <div class="flex-shrink-0 w-20 h-20 bg-gray-200 rounded-lg overflow-hidden border border-gray-200">
                                            <?php 
                                                // Logique d'image : on cherche une propriété 'image' ou 'media_url'
                                                // Adapte 'image' selon le nom exact dans ton entité Catalogue
                                                $imageUrl = $item->media_path ?? null; 
                                            ?>
                                            
                                            <?php if ($imageUrl): ?>
                                                <img class="w-full h-full object-cover" src="<?= htmlspecialchars($imageUrl) ?>" alt="Image produit">
                                            <?php else: ?>
                                                <div class="w-full h-full flex items-center justify-center text-gray-400">
                                                    <svg class="w-8 h-8" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path></svg>
                                                </div>
                                            <?php endif; ?>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4">
                                        <div class="text-sm font-bold text-gray-900 text-lg mb-1">
                                            <?= htmlspecialchars($item->title ?? 'Sans titre') ?>
                                        </div>
                                        <div class="text-sm text-gray-500">
                                            <?php 
                                                $desc = $item->description ?? '';
                                                echo htmlspecialchars(strlen($desc) > 80 ? substr($desc, 0, 100) . '...' : $desc);
                                            ?>
                                        </div>
                                        <?php if(isset($item->price)): ?>
                                            <span class="inline-block mt-2 px-2 py-1 text-xs font-semibold text-green-800 bg-green-100 rounded-full">
                                                <?= htmlspecialchars($item->price) ?> €
                                            </span>
                                        <?php endif; ?>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <div class="flex items-center">
                                            <div class="text-sm font-medium text-gray-900">
                                                <?php if (isset($item->user)): ?>
                                                    <?= htmlspecialchars($item->user->firstname . ' ' . $item->user->lastname) ?>
                                                <?php else: ?>
                                                    <span class="text-gray-400 italic">Inconnu</span>
                                                <?php endif; ?>
                                            </div>
                                        </div>
                                    </td>

                                    <td class="px-6 py-4 whitespace-nowrap text-center text-sm font-medium">
                                        <div class="flex justify-center gap-3">
                                            <a href="/admin/catalogue/edit?id=<?= $item->id ?>" class="text-blue-600 hover:text-blue-900 p-2 hover:bg-blue-50 rounded-full transition-colors" title="Modifier">
                                                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5H6a2 2 0 00-2 2v11a2 2 0 002 2h11a2 2 0 002-2v-5m-1.414-9.414a2 2 0 112.828 2.828L11.828 15H9v-2.828l8.586-8.586z"></path></svg>
                                            </a>

                                            <form action="/admin/catalogue/<?= $item->id ?>/delete" method="POST" onsubmit="return confirm('Êtes-vous sûr de vouloir supprimer cet article ? Cette action est irréversible.');">
                                                <?= ViewHelper::csrfField() ?>
                                                <button type="submit" class="text-red-600 hover:text-red-900 p-2 hover:bg-red-50 rounded-full transition-colors" title="Supprimer">
                                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16"></path></svg>
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>

                        </tbody>
                    </table>
                </div>
            </div>
            
            <div class="mt-4 flex justify-end text-sm text-gray-500">
                Affichage de <?= count($items ?? []) ?> élément(s)
            </div>

    </div>
</div>