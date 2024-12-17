let photos = document.querySelectorAll('.photo-album')
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
})