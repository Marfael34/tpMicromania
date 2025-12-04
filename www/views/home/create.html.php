<div class="min-h-screen bg-gradient-to-br from-emerald-500 to-teal-900">
    <header class="bg-white shado-sm border-b border-gray-200 sticky top-0 z-10">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-4">
            <div class="flex items-center justify-between">
                <div class="flex items-center space-x-4">
                    <a href="/dashboard" class="text-gray-600 hover:text-gray-900 transition-colors">
                        <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" width="1em" height="1em" viewBox="0 0 24 24"><path fill="currentColor" d="M4 19v-9q0-.475.213-.9t.587-.7l6-4.5q.525-.4 1.2-.4t1.2.4l6 4.5q.375.275.588.7T20 10v9q0 .825-.588 1.413T18 21h-3q-.425 0-.712-.288T14 20v-5q0-.425-.288-.712T13 14h-2q-.425 0-.712.288T10 15v5q0 .425-.288.713T9 21H6q-.825 0-1.412-.587T4 19"/></svg>
                    </a>
                    <h1 class="text-3xl font-bold bg-gradient-to-r from-indigo-600 to-purple-600 bg-clip-text text-transparent"><?= htmlspecialchars($title) ?></h1>
                </div>
                <div class="flex items-center space-x-6">
                    <p class="text-sm text-gray-600">
                        Bonjour, <span class="font-semibold text-gray-900"> <?= htmlspecialchars($user->firstname ?? $user->email) ?></span> !
                    </p>
                    <form action="/logout" method="POST" onsubmit="return confirm('Etes vous sur de vouloir vous déconnecter ?')">
                            <?php use JulienLinard\Core\View\ViewHelper; ?>
                            <input type="hidden" name="_token" value="<?= htmlspecialchars(ViewHelper::csrfToken()) ?>">
                            <button type="submit" class="text-sm text-gray-600 hover:text-gray-900 transition-colors">
                                Déconnexion
                            </button>
                    </form>
                </div>
            </div>
        </div>
    </header>

    <main class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 py-12">
        <div class="bg-white rounded-2xl shadow-xl overflow-hidden">
            <div class="bg-gradient-to-br from-emerald-400 to-teal-800 px-6 py-8">
                <h2 class="text-2xl font-bold text-white">Créer une nouvelle tâche</h2>
                <p class="text-teal-900 mt-2">Organiser vos idées et vos projets</p>
            </div>
            <form action="/todo/create" method="post" enctype="multipart/form-data" class="p-6 space-y-6">
                <input type="hidden" name="_token" value="<?= htmlspecialchars($csrf_token ?? '')?>">
                <?php if(isset($error)): ?>
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <p class="text-sm text-red-500"><?=  $error ?></p>
                    </div>
                <?php endif ?>
                <!-- Input pour le title -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="title">Titre</label>
                    <input class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 outline-none text-gray-900 placeholder-gray-400" id="title" type="text" name="title" placeholder="Ex: Faire les courses" require value="<?= htmlspecialchars($title_value ?? '') ?>">
                </div>
                 <!-- Input pour la description -->
                <div>
                    <label class="block text-sm font-semibold text-gray-700 mb-2" for="description">Description</label>
                    <textarea class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 outline-none text-gray-900 placeholder-gray-400 resize-none" id="description" rows=6 name="description" placeholder="Ex: Ajouter des détail sur cette tâche"><?= htmlspecialchars($description_value ?? '') ?></textarea>
                </div>
                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div>
                        <label for="deadline" class="block text-sm font-semibold text-gray-700 mb-2">
                            Date limite
                        </label>
                        <input type="datetime-local" 
                               id="deadline" 
                               name="deadline"
                               value="<?= htmlspecialchars($deadline_value ?? '') ?>"
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-indigo-500 focus:border-transparent transition-all duration-200 outline-none text-gray-900">
                        <p class="mt-2 text-sm text-gray-500">Optionnel : définissez une date limite</p>
                    </div>

                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            Priorité
                        </label>
                        <div class="mt-2">
                            <label class="inline-flex items-center cursor-pointer">
                                <input type="checkbox" 
                                       name="is_urgent" 
                                       value="1"
                                       <?= isset($is_urgent_value) && $is_urgent_value ? 'checked' : '' ?>
                                       class="sr-only peer">
                                <div class="relative w-14 h-7 bg-gray-200 peer-focus:outline-none peer-focus:ring-4 peer-focus:ring-indigo-300 rounded-full peer peer-checked:after:translate-x-full peer-checked:after:border-white after:content-[''] after:absolute after:top-0.5 after:left-[4px] after:bg-white after:border-gray-300 after:border after:rounded-full after:h-6 after:w-6 after:transition-all peer-checked:bg-red-600"></div>
                                <span class="ml-3 text-sm font-medium text-gray-700">
                                    <span class="text-red-600 font-bold">Urgent</span>
                                </span>
                            </label>
                        </div>
                        <p class="mt-2 text-sm text-gray-500">Marquer cette tâche comme urgente</p>
                    </div>
                </div>
                <!-- input pour les image-->
                <div>
                    <label for="media" class="block text-sm font-semibold text-gray-700 mb-2">
                        Fichier joint
                    </label>
                    <div class="mt-1 flex justify-center px-6 pt-5 pb-6 border-2 border-gray-300 border-dashed rounded-lg hover:border-indigo-400 transition-colors">
                        <div class="space-y-1 text-center">
                            <svg class="mx-auto h-12 w-12 text-gray-400" stroke="currentColor" fill="none" viewBox="0 0 48 48">
                                <path d="M28 8H12a4 4 0 00-4 4v20m32-12v8m0 0v8a4 4 0 01-4 4H12a4 4 0 01-4-4v-4m32-4l-3.172-3.172a4 4 0 00-5.656 0L28 28M8 32l9.172-9.172a4 4 0 015.656 0L28 28m0 0l4 4m4-24h8m-4-4v8m-12 4h.02" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"></path>
                            </svg>
                            <div class="flex text-sm text-gray-600">
                                <label for="media" class="relative cursor-pointer bg-white rounded-md font-medium text-indigo-600 hover:text-indigo-500 focus-within:outline-none focus-within:ring-2 focus-within:ring-offset-2 focus-within:ring-indigo-500">
                                    <span>Choisir une image</span>
                                    <input id="media" name="media" type="file" class="sr-only" accept="image/jpeg,image/jpg,image/png,image/avif,image/webp">
                                </label>
                                <p class="pl-1">ou glisser-déposer</p>
                            </div>
                            <p class="text-xs text-gray-500">JPEG, JPG, PNG, AVIF, WEBP jusqu'à 10MB</p>
                        </div>
                    </div>
                </div>

                <!-- bouton de soumission ou annulation -->
                 <div class="flex items-center justify-center space-x-4 pt-4 border-t border-gray-200">
                    <a class="px-6 py-3 text-gray-700 bg-gray-100 hover:bg-gray-200 rounded-lg font-medium transition-colors duration-200" href="/dashboard">Annuler</a>
                    <button class="px-6 py-3 bg-indigo-600 hover:bg-indigo-700 text[#ffffff] rounded-lg font-medium shadow-lg 
                    hover:shadow-xl duration-200 transition-all transform hover:-translate-y-0.5 text-white" type="submit">Enregister</button>
                 </div>
             </form>
        </div>
    </main>
</div>