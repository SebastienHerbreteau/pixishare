import 'justifiedGallery/dist/css/justifiedGallery.css';
import 'justifiedGallery/dist/js/jquery.justifiedGallery.js';

let photos = document.querySelectorAll('.photo')
let modal = document.querySelector('.modal')


$('.content').justifiedGallery({
    rowHeight: 200,
    lastRow: 'left',
    margins: 3,
    border: 50,
});
document.addEventListener('DOMContentLoaded', function () {
    const flashMessages = document.querySelectorAll('.flash-message');
    flashMessages.forEach(function (message) {
        setTimeout(function () {
            message.style.transition = 'opacity 0.5s ease';
            message.style.opacity = '0';
            setTimeout(function () {
                message.remove();
            }, 500);
        }, 3000);
    });
});
photos.forEach(photo => {
    photo.addEventListener('click', function () {
        let id = photo.dataset.id
        fetch('/gallery/album/photo/' + id)
            .then(response => response.text().then(function (content) {
                modal.innerHTML = content
                modal.style.display = 'flex'
                let mainPhoto = modal.querySelector('#photo')
                mainPhoto.addEventListener('click', () => modal.style.display = 'none')
                document.addEventListener('keydown', function (event) {
                    if (event.key === 'Escape') {
                        modal.style.display = 'none'
                    }
                })
            }))
    })
})

