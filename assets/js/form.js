/*
 * Welcome to your app's main JavaScript file!
 *
 * We recommend including the built version of this JavaScript file
 * (and its CSS file) in your base layout (base.html.twig).
 */

// any CSS you import will output into a single css file (app.css in this case)
import '../css/form.css';

// Need jQuery? Install it with "yarn add jquery", then uncomment to import it.
// import $ from 'jquery';

console.log('Hello Webpack Encore! Edit me in assets/js/form.js');


const questions = document.getElementById('questions');

if (questions){
    questions.addEventListener('click', e => {
        if (e.target.className === 'deleteButton') {
            if (confirm('Вы уверены, что хотите удалить?')){
                const id = e.target.getAttribute('data-id');

                fetch(`/question_delete/${id}`, {
                    method: 'DELETE'
                }).then(res => window.location.reload());
            }
        }
    });
}