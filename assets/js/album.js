let photos = document.querySelectorAll('.photo')
let modal = document.querySelector('.modal')

photos.forEach(photo => {
    photo.addEventListener('click', function () {
        let id = photo.dataset.id
        fetch('/gallery/album/photo/' + id)
            .then(response => console.log(response))

    })
})