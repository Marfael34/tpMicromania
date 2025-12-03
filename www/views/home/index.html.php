<header class="bg-blue-700 shadow-md mb-8">
    <div class="bg-[url(../../public/assets/image/mm-icon.woff)]">
        <div>
            <div>
                <div>
                    <a href="#">
                        <img  src="" alt="">
                    </a>
                </div>
                <div class="mb-6 flex justify-end gap-4">
                    <?php if ($isAuthenticated ?? false): ?>
                        <div class="flex items-center gap-4">
                            <span class="text-gray-700">
                                Bonjour, <strong><?= htmlspecialchars($user->firstname ?? $user->email) ?></strong>
                            </span>
                            <a
                                href="/todos"
                                class="bg-purple-600 text-white px-4 py-2 rounded-lg hover:bg-purple-700 focus:outline-none focus:ring-2 focus:ring-purple-500 focus:ring-offset-2 transition duration-150">
                                Mes Todos
                            </a>
                            <?php if (($user->role ?? 'user') === 'admin'): ?>
                                <a
                                    href="/admin"
                                    class="bg-indigo-600 text-white px-4 py-2 rounded-lg hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:ring-offset-2 transition duration-150">
                                    Back Office
                                </a>
                            <?php endif; ?>
                            <form method="POST" action="/logout" class="inline">
                                <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
                                <button
                                    type="submit"
                                    class="bg-red-600 text-white px-4 py-2 rounded-lg hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-red-500 focus:ring-offset-2 transition duration-150">
                                    Déconnexion
                                </button>
                            </form>
                        </div>
                    <?php else: ?>
                        <div class="flex items-center gap-4">
                            <a
                                href="/login"
                                class="bg-blue-600 text-white px-4 py-2 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150">
                                Connexion
                            </a>
                            <a
                                href="/register"
                                class="bg-green-600 text-white px-4 py-2 rounded-lg hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-offset-2 transition duration-150">
                                Inscription
                            </a>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
    </div>

</header>

<div class="container mx-auto px-4 py-8">
    <div class="max-w-4xl mx-auto bg-white rounded-lg shadow-lg p-8">

        <h1 class="text-4xl font-bold text-gray-800 mb-4"><?= htmlspecialchars($title ?? 'Welcome') ?></h1>
        <p class="text-xl text-gray-600 mb-6"><?= htmlspecialchars($message ?? 'Hello World!') ?></p>

        <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6">
            <p class="text-blue-700">
                <strong>🎉 Congratulations!</strong> Your PHP application is running successfully.
            </p>
        </div>

        <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
            <div class="bg-gray-50 p-4 rounded">
                <h2 class="font-semibold text-gray-800 mb-2">📦 Installed Packages</h2>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>✅ Core PHP Framework</li>
                    <li>✅ PHP Router</li>
                    <li>✅ Doctrine PHP</li>
                    <li>✅ Auth PHP</li>
                </ul>
            </div>
            <div class="bg-gray-50 p-4 rounded">
                <h2 class="font-semibold text-gray-800 mb-2">🚀 Next Steps</h2>
                <ul class="text-sm text-gray-600 space-y-1">
                    <li>Create your controllers</li>
                    <li>Add your views</li>
                    <li>Configure your database</li>
                    <li>Test authentication</li>
                </ul>
            </div>
        </div>
    </div>
</div>