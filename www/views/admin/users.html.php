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
    <title><?= htmlspecialchars($title ?? 'Admin - Utilisateurs') ?></title>
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
                        <a href="/admin/users" class="hover:text-gray-300 font-semibold">Utilisateurs</a>
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

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Tous les Utilisateurs</h1>
                <a href="/admin" class="text-gray-600 hover:text-gray-800">← Retour au Dashboard</a>
            </div>

            <!-- Users List -->
            <?php if (empty($users)): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <p class="text-gray-600">Aucun utilisateur pour le moment.</p>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Nom</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rôle</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Todos</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($users as $userData): 
                            $user = $userData['user'];
                            $todosCount = $userData['todos_count'];
                        ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $user->id ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <div class="font-medium"><?= htmlspecialchars($user->email) ?></div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if ($user->firstname || $user->lastname): ?>
                                    <?= htmlspecialchars(trim(($user->firstname ?? '') . ' ' . ($user->lastname ?? ''))) ?>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $roleColors = [
                                    'admin' => 'bg-purple-100 text-purple-800',
                                    'moderator' => 'bg-blue-100 text-blue-800',
                                    'user' => 'bg-gray-100 text-gray-800'
                                ];
                                $roleColor = $roleColors[$user->role ?? 'user'] ?? $roleColors['user'];
                                ?>
                                <span class="<?= $roleColor ?> text-xs font-semibold px-2 py-1 rounded">
                                    <?= htmlspecialchars(ucfirst($user->role ?? 'user')) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <span class="text-blue-600"><?= $todosCount ?></span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $user->created_at ? $user->created_at->format('d/m/Y H:i') : '-' ?>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
            <?php endif; ?>
        </div>
    </div>
</body>
</html>
