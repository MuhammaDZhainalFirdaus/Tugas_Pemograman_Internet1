document.addEventListener('DOMContentLoaded', function() {
    const tabLogin = document.getElementById('tabLogin');
    const tabRegister = document.getElementById('tabRegister');
    const loginForm = document.getElementById('loginForm');
    const registerForm = document.getElementById('registerForm');
    const notifSuccess = document.getElementById('notifSuccess');
    const notifError = document.getElementById('notifError');

    function showLoginForm() {
        loginForm.style.display = 'block';
        registerForm.style.display = 'none';
        tabLogin.style.background = '#f3f3f3';
        tabRegister.style.background = '#fff';
        hideNotifications();
    }

    function showRegisterForm() {
        loginForm.style.display = 'none';
        registerForm.style.display = 'block';
        tabLogin.style.background = '#fff';
        tabRegister.style.background = '#f3f3f3';
        hideNotifications();
    }

    function hideNotifications() {
        notifSuccess.style.display = 'none';
        notifError.style.display = 'none';
    }

    tabLogin.addEventListener('click', showLoginForm);
    tabRegister.addEventListener('click', showRegisterForm);

    loginForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideNotifications();

        const formData = new FormData(loginForm);

        fetch('login.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                notifSuccess.textContent = data.message;
                notifSuccess.style.display = 'block';
                setTimeout(() => {
                    window.location.href = data.redirect;
                }, 1000);
            } else {
                notifError.textContent = data.message;
                notifError.style.display = 'block';
            }
        })
        .catch(error => {
            notifError.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
            notifError.style.display = 'block';
            console.error('Error:', error);
        });
    });

    // Register form submission
    registerForm.addEventListener('submit', function(e) {
        e.preventDefault();
        hideNotifications();

        const formData = new FormData(registerForm);

        fetch('register.php', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            if (data.status === 'success') {
                notifSuccess.textContent = data.message;
                notifSuccess.style.display = 'block';
                registerForm.reset(); // Mengosongkan form setelah berhasil
            } else {
                notifError.textContent = data.message;
                notifError.style.display = 'block';
            }
        })
        .catch(error => {
            notifError.textContent = 'Terjadi kesalahan jaringan. Silakan coba lagi.';
            notifError.style.display = 'block';
            console.error('Error:', error);
        });
    });

    // Initial state
    showLoginForm();
}); 