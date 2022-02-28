// scssのimport
import '@scss/user/post/create.scss';
// jsのimport
import * as $form from '@js/_share/form';

$form.reflectInput();
$form.displayInputImg();

const getSuggestion = () => {
    const url = document.querySelector('.js-apiSuggest').dataset.suggestUrl;
    console.log(url);
    fetch(url);
};
getSuggestion();
