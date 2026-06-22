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
    console.log(flashMessage);

    if (flashMessage) {
        setTimeout(() => {
            closeToast();
        }, 4000);
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