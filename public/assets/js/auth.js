document.addEventListener("DOMContentLoaded", () => {
    const togglePassword = document.querySelector("#togglePassword");
    const password = document.querySelector("#password");

    if (togglePassword) {
        togglePassword.addEventListener("click", function () {
            const type = password.getAttribute("type") === "password" ? "text" : "password";
            password.setAttribute("type", type);

            this.classList.toggle("bi-eye");
            this.classList.toggle("bi-eye-slash");
        });
    }

    const toggleConfirm = document.querySelector("#toggleConfirmPassword");
    const confirmPassword = document.querySelector("#confirm_password");

    if (toggleConfirm) {
        toggleConfirm.addEventListener("click", function () {
            const type = confirmPassword.getAttribute("type") === "password" ? "text" : "password";
            confirmPassword.setAttribute("type", type);

            this.classList.toggle("bi-eye");
            this.classList.toggle("bi-eye-slash");
        });
    }
});