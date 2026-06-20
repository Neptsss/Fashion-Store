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
})