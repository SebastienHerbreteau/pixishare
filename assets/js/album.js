// script.js

// Sélectionne tous les éléments de type photo
const photos = document.querySelectorAll('.photo');
const lightbox = document.querySelector('.lightbox');
const lightboxContent = document.querySelector('.lightbox-content');
const closeBtn = document.querySelector('.close-btn');
const caption = document.querySelector('.caption');

// Ouvrir la lightbox avec l'image cliquée
photos.forEach(photo => {
    photo.addEventListener('click', function(event) {
        event.preventDefault(); // Empêche le comportement par défaut du lien

        const largeImageUrl = this.getAttribute('href'); // L'URL de l'image en grande taille
        const imageCaption = this.getAttribute('data-caption'); // La légende de l'image

        lightboxContent.src = largeImageUrl; // Charge l'image dans la lightbox
        caption.textContent = imageCaption; // Affiche la légende dans la lightbox
        lightbox.style.display = 'flex'; // Affiche la lightbox
    });
});

// Fermer la lightbox
closeBtn.addEventListener('click', () => {
    lightbox.style.display = 'none';
});

// Fermer la lightbox en cliquant en dehors de l'image
lightbox.addEventListener('click', (event) => {
    if (event.target === lightbox) {
        lightbox.style.display = 'none';
    }
});


/*let photos = document.querySelectorAll('.photo-album')
let modal = document.querySelector('.modal')

photos.forEach(photo => {
    photo.addEventListener('click', function () {
        let id = photo.dataset.id
        fetch('/gallery/album/photo/' + id)
            .then(response => response.text().then(function(content) {
                modal.innerHTML = content
                modal.style.display = 'flex'
            }))

    })
})*/