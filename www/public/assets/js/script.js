// Script JavaScript pour gérer l'ouverture/fermeture du menu burger
    document.addEventListener('DOMContentLoaded', function() {
        const button = document.getElementById('mobile-menu-button');
        const menu = document.getElementById('mobile-menu');
        const iconMenu = document.getElementById('icon-menu');
        const iconClose = document.getElementById('icon-close');

        if (button && menu) {
            button.addEventListener('click', function() {
                // Bascule la classe 'hidden' du menu
                menu.classList.toggle('hidden');

                // Bascule les icônes (hamburger <-> X)
                iconMenu.classList.toggle('hidden');
                iconClose.classList.toggle('hidden');

                // Ajout d'une classe pour désactiver le scroll du body (optionnel pour les grands menus)
                // document.body.classList.toggle('overflow-hidden');
            });
        }
    });