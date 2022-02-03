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

/***/ "./resources/js/user/post/edit.js":
/*!****************************************!*\
  !*** ./resources/js/user/post/edit.js ***!
  \****************************************/
/***/ (() => {

eval("reflectInput();\n\n/**\n * 1.inputに文字を入力すると発火\n * 2.入力した文字をvalueに持つoptionがdatalist内にあるかcheck\n * 3.あった場合、そのdata-place-idを取得\n * 4.select tag内からdata-place-idと同じ値をvalueに持つoptionにselectedを付与\n */\nfunction reflectInput() {\n\tconst suggestionInput = document.querySelector('.js-suggestionInput');\n\tconst placeSelect = document.querySelector('.js-countriesSelect');\n\n\tsuggestionInput.addEventListener('change', ev => {\n\t\t// inputに紐づくdatalist tagは、.listでアクセスできる\n\t\tconst placeId = suggestionInput.list.querySelector(\n\t\t\t`[value=${ev.target.value}]`\n\t\t)?.dataset.countryId;\n\n\t\t// 入力と一致する値が無ければ、undefinedになる\n\t\tif (placeId === undefined) return;\n\n\t\t// select tagのoptionが順に入る\n\t\tfor (const option of placeSelect) {\n\t\t\tif (option.value === placeId) {\n\t\t\t\toption.selected = true;\n\t\t\t\tbreak;\n\t\t\t}\n\t\t}\n\t});\n}\n//# sourceURL=[module]\n//# sourceMappingURL=data:application/json;charset=utf-8;base64,eyJ2ZXJzaW9uIjozLCJmaWxlIjoiLi9yZXNvdXJjZXMvanMvdXNlci9wb3N0L2VkaXQuanMuanMiLCJzb3VyY2VzIjpbIndlYnBhY2s6Ly9teV9vdXRwdXQvLi9yZXNvdXJjZXMvanMvdXNlci9wb3N0L2VkaXQuanM/ZWU3NSJdLCJzb3VyY2VzQ29udGVudCI6WyJyZWZsZWN0SW5wdXQoKTtcblxuLyoqXG4gKiAxLmlucHV044Gr5paH5a2X44KS5YWl5Yqb44GZ44KL44Go55m654GrXG4gKiAyLuWFpeWKm+OBl+OBn+aWh+Wtl+OCknZhbHVl44Gr5oyB44Gkb3B0aW9u44GMZGF0YWxpc3TlhoXjgavjgYLjgovjgYtjaGVja1xuICogMy7jgYLjgaPjgZ/loLTlkIjjgIHjgZ3jga5kYXRhLXBsYWNlLWlk44KS5Y+W5b6XXG4gKiA0LnNlbGVjdCB0YWflhoXjgYvjgolkYXRhLXBsYWNlLWlk44Go5ZCM44GY5YCk44KSdmFsdWXjgavmjIHjgaRvcHRpb27jgatzZWxlY3RlZOOCkuS7mOS4jlxuICovXG5mdW5jdGlvbiByZWZsZWN0SW5wdXQoKSB7XG5cdGNvbnN0IHN1Z2dlc3Rpb25JbnB1dCA9IGRvY3VtZW50LnF1ZXJ5U2VsZWN0b3IoJy5qcy1zdWdnZXN0aW9uSW5wdXQnKTtcblx0Y29uc3QgcGxhY2VTZWxlY3QgPSBkb2N1bWVudC5xdWVyeVNlbGVjdG9yKCcuanMtY291bnRyaWVzU2VsZWN0Jyk7XG5cblx0c3VnZ2VzdGlvbklucHV0LmFkZEV2ZW50TGlzdGVuZXIoJ2NoYW5nZScsIGV2ID0+IHtcblx0XHQvLyBpbnB1dOOBq+e0kOOBpeOBj2RhdGFsaXN0IHRhZ+OBr+OAgS5saXN044Gn44Ki44Kv44K744K544Gn44GN44KLXG5cdFx0Y29uc3QgcGxhY2VJZCA9IHN1Z2dlc3Rpb25JbnB1dC5saXN0LnF1ZXJ5U2VsZWN0b3IoXG5cdFx0XHRgW3ZhbHVlPSR7ZXYudGFyZ2V0LnZhbHVlfV1gXG5cdFx0KT8uZGF0YXNldC5jb3VudHJ5SWQ7XG5cblx0XHQvLyDlhaXlipvjgajkuIDoh7TjgZnjgovlgKTjgYznhKHjgZHjgozjgbDjgIF1bmRlZmluZWTjgavjgarjgotcblx0XHRpZiAocGxhY2VJZCA9PT0gdW5kZWZpbmVkKSByZXR1cm47XG5cblx0XHQvLyBzZWxlY3QgdGFn44Gub3B0aW9u44GM6aCG44Gr5YWl44KLXG5cdFx0Zm9yIChjb25zdCBvcHRpb24gb2YgcGxhY2VTZWxlY3QpIHtcblx0XHRcdGlmIChvcHRpb24udmFsdWUgPT09IHBsYWNlSWQpIHtcblx0XHRcdFx0b3B0aW9uLnNlbGVjdGVkID0gdHJ1ZTtcblx0XHRcdFx0YnJlYWs7XG5cdFx0XHR9XG5cdFx0fVxuXHR9KTtcbn1cbiJdLCJtYXBwaW5ncyI6IkFBQUE7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBO0FBQ0E7QUFDQTtBQUNBOyIsInNvdXJjZVJvb3QiOiIifQ==\n//# sourceURL=webpack-internal:///./resources/js/user/post/edit.js\n");

/***/ })

/******/ 	});
/************************************************************************/
/******/ 	
/******/ 	// startup
/******/ 	// Load entry module and return exports
/******/ 	// This entry module can't be inlined because the eval-source-map devtool is used.
/******/ 	var __webpack_exports__ = {};
/******/ 	__webpack_modules__["./resources/js/user/post/edit.js"]();
/******/ 	
/******/ })()
;