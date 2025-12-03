<div class="container mx-auto px-4 py-8">
    <div class="max-w-md mx-auto bg-white rounded-lg shadow-lg p-8">
        <h1 class="text-3xl font-bold text-gray-800 mb-6 text-center">Inscription</h1>
        
        <form method="POST" action="/register" class="space-y-6">
            <!-- CSRF Token -->
            <?= \JulienLinard\Core\Middleware\CsrfMiddleware::field() ?>
            
            <!-- Firstname -->
            <div>
                <label for="firstname" class="block text-sm font-medium text-gray-700 mb-2">
                    Prénom
                </label>
                <input 
                    type="text" 
                    id="firstname" 
                    name="firstname" 
                    value="<?= htmlspecialchars($old['firstname'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['firstname']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['firstname'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['firstname']) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Lastname -->
            <div>
                <label for="lastname" class="block text-sm font-medium text-gray-700 mb-2">
                    Nom
                </label>
                <input 
                    type="text" 
                    id="lastname" 
                    name="lastname" 
                    value="<?= htmlspecialchars($old['lastname'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['lastname']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['lastname'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['lastname']) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Email -->
            <div>
                <label for="email" class="block text-sm font-medium text-gray-700 mb-2">
                    Email
                </label>
                <input 
                    type="email" 
                    id="email" 
                    name="email" 
                    value="<?= htmlspecialchars($old['email'] ?? '') ?>"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['email']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['email'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['email']) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Password -->
            <div>
                <label for="password" class="block text-sm font-medium text-gray-700 mb-2">
                    Mot de passe
                </label>
                <input 
                    type="password" 
                    id="password" 
                    name="password" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['password']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['password'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['password']) ?></p>
                <?php endif; ?>
                <p class="mt-1 text-xs text-gray-500">Minimum 8 caractères</p>
            </div>
            
            <!-- Password Confirm -->
            <div>
                <label for="password_confirm" class="block text-sm font-medium text-gray-700 mb-2">
                    Confirmer le mot de passe
                </label>
                <input 
                    type="password" 
                    id="password_confirm" 
                    name="password_confirm" 
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-transparent <?= isset($errors['password_confirm']) ? 'border-red-500' : '' ?>"
                    required
                >
                <?php if (isset($errors['password_confirm'])): ?>
                    <p class="mt-1 text-sm text-red-600"><?= htmlspecialchars($errors['password_confirm']) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Submit Button -->
            <button 
                type="submit" 
                class="w-full bg-blue-600 text-white py-2 px-4 rounded-lg hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2 transition duration-150"
            >
                S'inscrire
            </button>
        </form>
        
        <!-- Link to Login -->
        <div class="mt-6 text-center">
            <p class="text-sm text-gray-600">
                Déjà un compte ? 
                <a href="/login" class="text-blue-600 hover:text-blue-800 font-medium">
                    Se connecter
                </a>
            </p>
        </div>
    </div>
</div>
