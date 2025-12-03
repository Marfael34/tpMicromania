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
    <title><?= htmlspecialchars($title ?? 'Admin - Todos') ?></title>
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
                        <a href="/admin/todos" class="hover:text-gray-300 font-semibold">Todos</a>
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

            <!-- Header -->
            <div class="flex justify-between items-center mb-6">
                <h1 class="text-3xl font-bold text-gray-800">Tous les Todos</h1>
                <a href="/admin" class="text-gray-600 hover:text-gray-800">← Retour au Dashboard</a>
            </div>

            <!-- Todos List -->
            <?php if (empty($todos)): ?>
            <div class="bg-white rounded-lg shadow-lg p-8 text-center">
                <p class="text-gray-600">Aucun todo pour le moment.</p>
            </div>
            <?php else: ?>
            <div class="bg-white rounded-lg shadow-lg overflow-hidden">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">ID</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Titre</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Utilisateur</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Statut</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Médias</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Créé le</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($todos as $todo): ?>
                        <tr class="hover:bg-gray-50">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $todo->id ?></td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="max-w-xs truncate" title="<?= htmlspecialchars($todo->title) ?>">
                                    <?= htmlspecialchars($todo->title) ?>
                                </div>
                                <?php if ($todo->description): ?>
                                    <div class="text-xs text-gray-500 mt-1 max-w-xs truncate">
                                        <?= htmlspecialchars($todo->description) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if ($todo->user): ?>
                                    <div>
                                        <div class="font-medium"><?= htmlspecialchars($todo->user->email) ?></div>
                                        <?php if ($todo->user->firstname || $todo->user->lastname): ?>
                                            <div class="text-xs text-gray-500">
                                                <?= htmlspecialchars(trim(($todo->user->firstname ?? '') . ' ' . ($todo->user->lastname ?? ''))) ?>
                                            </div>
                                        <?php endif; ?>
                                    </div>
                                <?php else: ?>
                                    <span class="text-gray-400">-</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php if ($todo->completed): ?>
                                    <span class="bg-green-100 text-green-800 text-xs font-semibold px-2 py-1 rounded">Complété</span>
                                <?php else: ?>
                                    <span class="bg-yellow-100 text-yellow-800 text-xs font-semibold px-2 py-1 rounded">En attente</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                <?php if (!empty($todo->media) && is_array($todo->media)): ?>
                                    <span class="text-blue-600"><?= count($todo->media) ?> média<?= count($todo->media) > 1 ? 'x' : '' ?></span>
                                <?php else: ?>
                                    <span class="text-gray-400">Aucun</span>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= $todo->created_at ? $todo->created_at->format('d/m/Y H:i') : '-' ?>
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
