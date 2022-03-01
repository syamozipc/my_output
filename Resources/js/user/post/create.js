// scssのimport
import '@scss/user/post/create.scss';
// async/await用
// @todo eslintエラー出るので修正
import { async } from 'regenerator-runtime';
// jsのimport
import * as $form from '@js/_share/form';

$form.getMatchedCountries();
$form.displayInputImg();
