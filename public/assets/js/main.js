document.addEventListener('DOMContentLoaded', function () {
    const profileMenu = document.querySelector('.profile-menu')
    const iconProfile = document.querySelector('#profile')

    profileMenu.style.display = "none";
    iconProfile.addEventListener('click', () => {
        if (profileMenu.style.display === "none") {
            profileMenu.style.display = "block";
        } else {
            profileMenu.style.display = "none";
        }
    })

    const flashMessage = document.querySelector('#flashMessage');

    if (flashMessage) {
        setTimeout(() => {
            closeToast();
        }, 4000);
    }

    const editPhotoButton = document.getElementById('editPhotoButton');
    const photoModal = document.getElementById('photoModal');
    const closePhotoModal = document.getElementById('closePhotoModal');
    const cancelPhotoModal = document.getElementById('cancelPhotoModal');

    if (editPhotoButton && photoModal) {
        editPhotoButton.addEventListener('click', () => {
            photoModal.classList.add('open');
        });
    }

    if (closePhotoModal && photoModal) {
        closePhotoModal.addEventListener('click', () => {
            photoModal.classList.remove('open');
        });
    }

    if (cancelPhotoModal && photoModal) {
        cancelPhotoModal.addEventListener('click', () => {
            photoModal.classList.remove('open');
        });
    }

    if (photoModal) {
        photoModal.addEventListener('click', (event) => {
            if (event.target === photoModal) {
                photoModal.classList.remove('open');
            }
        });
    }
}); 

function closeToast() {
    const flashMessage = document.getElementById('flashMessage');
    if (flashMessage) {
        flashMessage.classList.add('fade-out');

        setTimeout(() => {
            flashMessage.remove();
        }, 500);
    }
}