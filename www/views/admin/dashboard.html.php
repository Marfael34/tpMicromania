<?php
use JulienLinard\Core\Session\Session;

// Récupération des messages flash
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
    <div class="container mx-auto px-4 py-8 flex-grow">
        <?php if ($successMessage): ?>
            <div class="mb-6 p-4 bg-green-50 border-l-4 border-green-500 rounded shadow-sm flex items-center justify-between">
                <div class="flex items-center">
                    <svg class="w-6 h-6 text-green-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"><path></svg>
                    <p class="text-green-700 font-medium"><?= htmlspecialchars($successMessage) ?></p>
                </div>
            </div>
        <?php endif; ?>
        <?php if ($errorMessage): ?>
            <div class="mb-6 p-4 bg-red-50 border-l-4 border-red-500 rounded shadow-sm flex items-center">
                <svg class="w-6 h-6 text-red-500 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                <p class="text-red-700 font-medium"><?= htmlspecialchars($errorMessage) ?></p>
            </div>
        <?php endif; ?>
        <div class="mb-8">
            <h2 class="text-3xl font-bold text-gray-800">Vue d'ensemble</h2>
            <p class="text-gray-600 mt-1">Bienvenue, <?= htmlspecialchars($user->firstname ?? 'Admin') ?>.</p>
        </div>
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-10">
            <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-blue-500 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Articles en ligne</p>
                            <h3 class="text-4xl font-bold text-gray-800 mt-2"><?= $stats['total_items'] ?? 0 ?></h3>
                        </div>
                        <div class="p-3 bg-blue-50 rounded-full text-blue-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-emerald-500 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Utilisateurs Inscrits</p>
                            <h3 class="text-4xl font-bold text-gray-800 mt-2"><?= $stats['total_users'] ?? 0 ?></h3>
                        </div>
                        <div class="p-3 bg-emerald-50 rounded-full text-emerald-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z"></path></svg>
                        </div>
                    </div>
                </div>

                <div class="bg-white rounded-xl shadow-sm p-6 border-t-4 border-purple-500 hover:shadow-md transition-shadow">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="text-sm font-semibold text-gray-500 uppercase tracking-wide">Administrateurs</p>
                            <h3 class="text-4xl font-bold text-gray-800 mt-2"><?= $stats['admin_users'] ?? 0 ?></h3>
                        </div>
                        <div class="p-3 bg-purple-50 rounded-full text-purple-600">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m5.618-4.016A11.955 11.955 0 0112 2.944a11.955 11.955 0 01-8.618 3.04A12.02 12.02 0 003 9c0 5.591 3.824 10.29 9 11.622 5.176-1.332 9-6.03 9-11.622 0-1.042-.133-2.052-.382-3.016z"></path></svg>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white rounded-xl shadow-sm p-8">
                <h2 class="text-xl font-bold text-gray-800 mb-6 flex items-center gap-2">
                    <svg class="w-5 h-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 10V3L4 14h7v7l9-11h-7z"></path></svg>
                    Actions rapides
                </h2>
                <div class="flex flex-wrap gap-4">
                    <a href="/admin/catalogue/create" class="inline-flex items-center bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition-colors font-medium shadow-sm hover:shadow">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"></path></svg>
                        Ajouter un jeux
                    </a>
                    <a href="/admin/catalogue" class="inline-flex items-center bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Gérer le catalogue
                    </a>
                    <a href="/admin/users" class="inline-flex items-center bg-white border border-gray-300 text-gray-700 px-6 py-3 rounded-lg hover:bg-gray-50 transition-colors font-medium">
                        Gérer les utilisateurs
                    </a>
                </div>
            </div>
        </div>
    </div>