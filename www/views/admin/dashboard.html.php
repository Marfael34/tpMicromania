<?php
use JulienLinard\Core\Session\Session;
$successMessage = Session::get('success');
$errorMessage = Session::get('error');
Session::remove('success');
Session::remove('error');
?>
<!DOCTYPE html>
<html lang="fr">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($title ?? 'Admin Dashboard') ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-gray-100">
    <div class="min-h-screen">
        <!-- Navigation -->
        <nav class="bg-gray-800 text-white">
            <div class="container mx-auto px-4 py-4">
                <div class="flex justify-between items-center">
                    <h1 class="text-xl font-bold">Back Office Admin</h1>
                    <div class="flex gap-4">
                        <a href="/admin" class="hover:text-gray-300">Dashboard</a>
                        <a href="/admin/todos" class="hover:text-gray-300">Todos</a>
                        <a href="/admin/users" class="hover:text-gray-300">Utilisateurs</a>
                        <a href="/todos" class="hover:text-gray-300">Mes Todos</a>
                        <a href="/logout" class="hover:text-gray-300">Déconnexion</a>
                    </div>
                </div>
            </div>
        </nav>

        <div class="container mx-auto px-4 py-8">
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

            <!-- Stats Cards -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-5 gap-4 mb-8">
                <div class="bg-white rounded-lg shadow p-6">
                    <div class="text-sm text-gray-600 mb-2">Total Todos</div>
                    <div class="text-3xl font-bold text-gray-800"><?= $stats['total_todos'] ?? 0 ?></div>
                </div>
                <div class="bg-green-50 rounded-lg shadow p-6">
                    <div class="text-sm text-green-600 mb-2">Complétés</div>
                    <div class="text-3xl font-bold text-green-800"><?= $stats['completed_todos'] ?? 0 ?></div>
                </div>
                <div class="bg-yellow-50 rounded-lg shadow p-6">
                    <div class="text-sm text-yellow-600 mb-2">En attente</div>
                    <div class="text-3xl font-bold text-yellow-800"><?= $stats['pending_todos'] ?? 0 ?></div>
                </div>
                <div class="bg-blue-50 rounded-lg shadow p-6">
                    <div class="text-sm text-blue-600 mb-2">Total Utilisateurs</div>
                    <div class="text-3xl font-bold text-blue-800"><?= $stats['total_users'] ?? 0 ?></div>
                </div>
                <div class="bg-purple-50 rounded-lg shadow p-6">
                    <div class="text-sm text-purple-600 mb-2">Admins</div>
                    <div class="text-3xl font-bold text-purple-800"><?= $stats['admin_users'] ?? 0 ?></div>
                </div>
            </div>

            <!-- Quick Actions -->
            <div class="bg-white rounded-lg shadow p-6">
                <h2 class="text-xl font-semibold text-gray-800 mb-4">Actions rapides</h2>
                <div class="flex gap-4">
                    <a href="/admin/todos" class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700">
                        Voir tous les Todos
                    </a>
                    <a href="/admin/users" class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700">
                        Voir tous les Utilisateurs
                    </a>
                </div>
            </div>
        </div>
    </div>
</body>
</html>
