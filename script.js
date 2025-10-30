function toggleNav() {
    var navbar = document.querySelector('.navbar');
    navbar.classList.toggle('active');
}

function closeMenu() {
    var navbar = document.querySelector('.navbar');
    navbar.classList.remove('active');
}


function openLoginModal() {
    document.getElementById('loginModal').style.display = 'block';
}

function closeLoginModal() {
    document.getElementById('loginModal').style.display = 'none';
}

function openRegisterModal() {
    document.getElementById('registerModal').style.display = 'block';
}

function closeRegisterModal() {
    document.getElementById('registerModal').style.display = 'none';
}

// Close modals if the user clicks outside the modal
window.onclick = function(event) {
    if (event.target === document.getElementById('loginModal')) {
        closeLoginModal();
    }
    if (event.target === document.getElementById('registerModal')) {
        closeRegisterModal();
    }
};