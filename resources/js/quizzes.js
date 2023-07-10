// Quizzes JS
const form_check = document.getElementById('quiz_form');

if ( form_check != undefined ) {

    const radios =  form_check.querySelectorAll('[type="radio"]');
    for (let ri = 0; ri< radios.length; ri++) {
        radios[ri].addEventListener('click', checkAnswers, false);
    }
    function changeCSS(e) {
        const eName = e.name;
        const inputs = document.querySelectorAll("[name=" + CSS.escape(eName) + "]");

        if ( !e.hasAttribute('readonly') ) {
            for( let j= 0; j<inputs.length; j++) {
                inputs[j].parentNode.classList.remove('selected');
                inputs[j].removeAttribute('selected');
            }
            e.parentNode.classList.add('selected');
            e.setAttribute('selected', 'selected');
        }
    }

    const btn_submit = document.getElementById('quiz_submit');

    //btn_submit.addEventListener('click', ()=> {
    function checkAnswers() {
        changeCSS(this);
        let cc;
        const all_answers = form_check.querySelectorAll('[type="radio"]');
        const selected = form_check.querySelector('[selected="selected"]');
        const ac = getAnswerCookie();

        if ( selected == null ) {
            const quiz_msg = document.getElementById('quiz_msg');
            quiz_msg.innerText = "Please select an answer.";
            quiz_msg.classList.remove('d-none');
            return;
        } else {
            quiz_msg.classList.add('d-none');
            quiz_msg.innerText = '';
        }

        if ( selected.value == ac ) {
            selected.parentNode.classList.add('correct');
            selected.setAttribute('readonly', 'readonly');
        } else {
            selected.parentNode.classList.add('error');
            selected.setAttribute('readonly','readonly');
            for ( cc = 0; cc < all_answers.length; cc++ ) {
                if ( all_answers[cc].value == ac ) {
                    all_answers[cc].parentNode.classList.add('correct-outline');
                    all_answers[cc].setAttribute('readonly','readonly');
                    break;
                }
            }
        }

        for ( cc = 0; cc < all_answers.length; cc++ ) {
            if ( !all_answers[cc].parentNode.classList.contains('error') && !all_answers[cc].parentNode.classList.contains('correct') ) {
                all_answers[cc].parentNode.classList.add('bg-white');
                all_answers[cc].setAttribute('disabled', 'disabled');
            }
        }

        document.querySelector('[type="submit"]').classList.remove('d-none');
    }

    function getAnswerCookie() {
        const cookies = document.cookie.split(';');
        for( let ci = 0; ci < cookies.length; ci++ ) {
            let cookieCheck = cookies[ci].split('=');
            if (cookieCheck[0] == 'leqca') { // 'Lion Energy Quiz Correct Answer'
                let ac = cookieCheck[1];
                return ac;
            }
        }
    }
}
