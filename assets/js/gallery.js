let plusButton = document.querySelector('.plus');
let containerModal = document.querySelector('.container-modal');
let uploadModal = document.querySelector('.upload-modal');

plusButton.addEventListener('click', () => {
    fetch('/gallery/upload')
        .then(res => res.text())
        .then(data => {
            uploadModal.innerHTML = data;
            uploadModal.style.display = 'block';
            containerModal.style.display = 'block';
            let form = document.querySelector('form');

            form.addEventListener('submit', e => {
                e.preventDefault();
                let progressContainer = document.querySelector('.container-progress');
                progressContainer.style.display = 'flex';
                form.style.display = 'none';
                const formData = new FormData(form);
                fetch('/gallery/upload', {
                    method: 'POST',
                    body: formData
                })
            })
            let closeButton = document.querySelector('.close');
            closeButton.addEventListener('click', e => {
                containerModal.style.display = 'none';
            })
        })
})

