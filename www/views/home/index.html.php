<header class=" bg-[#52ae32] shadow-md mb-8 ">
    <div class=" container  mx-auto flex items-center justify-between gap-4 md:gap-10">
        <div class="bg-blue-800  p-2">
            <a href="#">
                <img src="assets/image/logo-micromania.svg" alt="" >
            </a>
        </div>
        <div class="flex space-x-2 items-center">
            <div class=" ">
                <button class="text-gray-900 bg-stone-200 hover:bg-gray-100 p-2 text-sm md:text-xl rounded transition duration-300" type="submit" > Connexion</button>
            </div>
            <div>
                <button class="text-gray-900 bg-stone-200 hover:bg-gray-100 p-2 text-sm md:text-xl rounded transition duration-300 " type="submit">
                    Inscription
                </button>
            </div>  
        </div>
    </div>
</header>

<div class="container">
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