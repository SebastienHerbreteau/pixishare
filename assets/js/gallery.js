let plusButton = document.querySelector('.plus');
// let modal = document.querySelector('.upload-modal');
// modal.addEventListener('click', e => {
//     modal.style.display = 'none';
// })
plusButton.addEventListener('click', () => {
    fetch('/gallery/upload')
        .then(res => res.text())
        .then(data => {
            let uploadModal = document.querySelector('.upload-modal');
            uploadModal.innerHTML = data;
            uploadModal.style.display = 'block';
            let form = document.querySelector('form');

            form.addEventListener('submit', e => {
                console.log(form);
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
        })
})

