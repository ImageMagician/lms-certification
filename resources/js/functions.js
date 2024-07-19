function startFlashing() {
    console.log('clicked');
    document.getElementById('overlay_bg').classList.add('show');
    document.getElementById('overlay_content').classList.add('show');
    let i = 0;

    setInterval( ()=> {
        const bullets = document.querySelectorAll('.flashing-bullet');
        for(let j = 0; j < bullets.length; j++) {
            if ( j === i ) {
                bullets[j].classList.add('focus');
            } else {
                bullets[j].classList.remove('focus');
            }
        }

        i++;
        if (i === bullets.length ) i = 0;
    }, 250);
}

function showPassword(e) {

    const obj = e.previousElementSibling;
    const eye = e.firstElementChild;
    if (obj.getAttribute('type') === 'password') {
        obj.setAttribute('type', 'text');
        eye.classList.remove('fa-eye');
        eye.classList.add('fa-eye-slash');
    } else {
        obj.setAttribute('type', 'password');
        eye.classList.add('fa-eye');
        eye.classList.remove('fa-eye-slash');
    }
}

window.startFlashing = startFlashing;
window.showPassword = showPassword;
