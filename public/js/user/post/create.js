/*
 * ATTENTION: An "eval-source-map" devtool has been used.
 * This devtool is neither made for production nor for readable output files.
 * It uses "eval()" calls to create a separate source file with attached SourceMaps in the browser devtools.
 * If you are trying to read the output file, select a different devtool (https://webpack.js.org/configuration/devtool/)
 * or disable the default devtool with "devtool: false".
 * If you are looking for production-ready output files, see mode: "production" (https://webpack.js.org/configuration/mode/).
 */
/******/ (() => { // webpackBootstrap
/******/ 	var __webpack_modules__ = ({

/***/ "./resources/js/user/post/create.js":
/*!******************************************!*\
  !*** ./resources/js/user/post/create.js ***!
  \******************************************/
/***/ (() => {

eval("reflectInput();\n\ndisplayInputImg();\n\n/**\n * 1.inputに文字を入力すると発火\n * 2.入力した文字をvalueに持つoptionがdatalist内にあるかcheck\n * 3.あった場合、そのdata-place-idを取得\n * 4.select tag内からdata-place-idと同じ値をvalueに持つoptionにselectedを付与\n */\nfunction reflectInput() {\n\tconst suggestionInput = document.querySelector('.js-suggestionInput');\n\tconst placeSelect = document.querySelector('.js-countriesSelect');\n\n\tsuggestionInput.addEventListener('change', ev => {\n\t\t// inputに紐づくdatalist tagは、.listでアクセスできる\n\t\tconst placeId = suggestionInput.list.querySelector(\n\t\t\t`[value=${ev.target.value}]`\n\t\t)?.dataset.countryId;\n\n\t\t// 入力と一致する値が無ければ、undefinedになる\n\t\tif (placeId === undefined) return;\n\n\t\t// select tagのoptionが順に入る\n\t\tfor (const option of placeSelect) {\n\t\t\tif (option.value === placeId) {\n\t\t\t\toption.selected = true;\n\t\t\t\tbreak;\n\t\t\t}\n\t\t}\n\t});\n}\n\n/**\n * 1.画像を選択時に発火\n * 2.input[file]から該当のfile情報を取得してblobUrlを生成\n * それをimgタグのsrcに設定し、is-hiddenクラスを取り除いて画面に表示する\n */\nfunction displayInputImg() {\n\tdocument\n\t\t.querySelector('.js-inputImg')\n\t\t.addEventListener('change', function (e) {\n\t\t\tconst file = e.target.files[0];\n\n\t\t\tif (!file) return;\n\n\t\t\tblobUrl = window.URL.createObjectURL(file);\n\n\t\t\tconst img = document.querySelector('.js-displayImg');\n\t\t\timg.src = blobUrl;\n\n\t\t\timg.addEventListener('load', () => img.classList.remove('is-hidden'));\n\t\t});\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvdXNlci9wb3N0L2NyZWF0ZS5qcy5qcyIsInNvdXJjZXMiOlsid2VicGFjazovL215X291dHB1dC8uL3Jlc291cmNlcy9qcy91c2VyL3Bvc3QvY3JlYXRlLmpzP2ZhM2YiXSwic291cmNlc0NvbnRlbnQiOlsicmVmbGVjdElucHV0KCk7XG5cbmRpc3BsYXlJbnB1dEltZygpO1xuXG4vKipcbiAqIDEuaW5wdXTjgavmloflrZfjgpLlhaXlipvjgZnjgovjgajnmbrngatcbiAqIDIu5YWl5Yqb44GX44Gf5paH5a2X44KSdmFsdWXjgavmjIHjgaRvcHRpb27jgYxkYXRhbGlzdOWGheOBq+OBguOCi+OBi2NoZWNrXG4gKiAzLuOBguOBo+OBn+WgtOWQiOOAgeOBneOBrmRhdGEtcGxhY2UtaWTjgpLlj5blvpdcbiAqIDQuc2VsZWN0IHRhZ+WGheOBi+OCiWRhdGEtcGxhY2UtaWTjgajlkIzjgZjlgKTjgpJ2YWx1ZeOBq+aMgeOBpG9wdGlvbuOBq3NlbGVjdGVk44KS5LuY5LiOXG4gKi9cbmZ1bmN0aW9uIHJlZmxlY3RJbnB1dCgpIHtcblx0Y29uc3Qgc3VnZ2VzdGlvbklucHV0ID0gZG9jdW1lbnQucXVlcnlTZWxlY3RvcignLmpzLXN1Z2dlc3Rpb25JbnB1dCcpO1xuXHRjb25zdCBwbGFjZVNlbGVjdCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1jb3VudHJpZXNTZWxlY3QnKTtcblxuXHRzdWdnZXN0aW9uSW5wdXQuYWRkRXZlbnRMaXN0ZW5lcignY2hhbmdlJywgZXYgPT4ge1xuXHRcdC8vIGlucHV044Gr57SQ44Gl44GPZGF0YWxpc3QgdGFn44Gv44CBLmxpc3TjgafjgqLjgq/jgrvjgrnjgafjgY3jgotcblx0XHRjb25zdCBwbGFjZUlkID0gc3VnZ2VzdGlvbklucHV0Lmxpc3QucXVlcnlTZWxlY3Rvcihcblx0XHRcdGBbdmFsdWU9JHtldi50YXJnZXQudmFsdWV9XWBcblx0XHQpPy5kYXRhc2V0LmNvdW50cnlJZDtcblxuXHRcdC8vIOWFpeWKm+OBqOS4gOiHtOOBmeOCi+WApOOBjOeEoeOBkeOCjOOBsOOAgXVuZGVmaW5lZOOBq+OBquOCi1xuXHRcdGlmIChwbGFjZUlkID09PSB1bmRlZmluZWQpIHJldHVybjtcblxuXHRcdC8vIHNlbGVjdCB0YWfjga5vcHRpb27jgYzpoIbjgavlhaXjgotcblx0XHRmb3IgKGNvbnN0IG9wdGlvbiBvZiBwbGFjZVNlbGVjdCkge1xuXHRcdFx0aWYgKG9wdGlvbi52YWx1ZSA9PT0gcGxhY2VJZCkge1xuXHRcdFx0XHRvcHRpb24uc2VsZWN0ZWQgPSB0cnVlO1xuXHRcdFx0XHRicmVhaztcblx0XHRcdH1cblx0XHR9XG5cdH0pO1xufVxuXG4vKipcbiAqIDEu55S75YOP44KS6YG45oqe5pmC44Gr55m654GrXG4gKiAyLmlucHV0W2ZpbGVd44GL44KJ6Kmy5b2T44GuZmlsZeaDheWgseOCkuWPluW+l+OBl+OBpmJsb2JVcmzjgpLnlJ/miJBcbiAqIOOBneOCjOOCkmltZ+OCv+OCsOOBrnNyY+OBq+ioreWumuOBl+OAgWlzLWhpZGRlbuOCr+ODqeOCueOCkuWPluOCiumZpOOBhOOBpueUu+mdouOBq+ihqOekuuOBmeOCi1xuICovXG5mdW5jdGlvbiBkaXNwbGF5SW5wdXRJbWcoKSB7XG5cdGRvY3VtZW50XG5cdFx0LnF1ZXJ5U2VsZWN0b3IoJy5qcy1pbnB1dEltZycpXG5cdFx0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGZ1bmN0aW9uIChlKSB7XG5cdFx0XHRjb25zdCBmaWxlID0gZS50YXJnZXQuZmlsZXNbMF07XG5cblx0XHRcdGlmICghZmlsZSkgcmV0dXJuO1xuXG5cdFx0XHRibG9iVXJsID0gd2luZG93LlVSTC5jcmVhdGVPYmplY3RVUkwoZmlsZSk7XG5cblx0XHRcdGNvbnN0IGltZyA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1kaXNwbGF5SW1nJyk7XG5cdFx0XHRpbWcuc3JjID0gYmxvYlVybDtcblxuXHRcdFx0aW1nLmFkZEV2ZW50TGlzdGVuZXIoJ2xvYWQnLCAoKSA9PiBpbWcuY2xhc3NMaXN0LnJlbW92ZSgnaXMtaGlkZGVuJykpO1xuXHRcdH0pO1xufVxuIl0sIm1hcHBpbmdzIjoiQUFBQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7Iiwic291cmNlUm9vdCI6IiJ9\n//# sourceURL=webpack-internal:///./resources/js/user/post/create.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/user/post/create.js"]();
/******/ 	
/******/ })()
;