(()=>{"use strict";function e(e,t){(null==t||t>e.length)&&(t=e.length);for(var r=0,n=new Array(t);r<t;r++)n[r]=e[r];return n}var t,r;t=document.querySelector(".js-suggestionInput"),r=document.querySelector(".js-countriesSelect"),t.addEventListener("change",(function(n){var o=t.list.querySelector("[value=".concat(n.target.value,"]")).dataset.countryId;if(void 0!==o){var a,i=function(t,r){var n="undefined"!=typeof Symbol&&t[Symbol.iterator]||t["@@iterator"];if(!n){if(Array.isArray(t)||(n=function(t,r){if(t){if("string"==typeof t)return e(t,r);var n=Object.prototype.toString.call(t).slice(8,-1);return"Object"===n&&t.constructor&&(n=t.constructor.name),"Map"===n||"Set"===n?Array.from(t):"Arguments"===n||/^(?:Ui|I)nt(?:8|16|32)(?:Clamped)?Array$/.test(n)?e(t,r):void 0}}(t))||r&&t&&"number"==typeof t.length){n&&(t=n);var o=0,a=function(){};return{s:a,n:function(){return o>=t.length?{done:!0}:{done:!1,value:t[o++]}},e:function(e){throw e},f:a}}throw new TypeError("Invalid attempt to iterate non-iterable instance.\nIn order to be iterable, non-array objects must have a [Symbol.iterator]() method.")}var i,u=!0,c=!1;return{s:function(){n=n.call(t)},n:function(){var e=n.next();return u=e.done,e},e:function(e){c=!0,i=e},f:function(){try{u||null==n.return||n.return()}finally{if(c)throw i}}}}(r);try{for(i.s();!(a=i.n()).done;){var u=a.value;if(u.value===o){u.selected=!0;break}}}catch(e){i.e(e)}finally{i.f()}}})),document.querySelector(".js-inputImg").addEventListener("change",(function(e){var t=e.target.files[0];if(t){var r=window.URL.createObjectURL(t),n=document.querySelector(".js-displayImg");n.src=r,n.addEventListener("load",(function(){return n.classList.remove("is-hidden")}))}}))})();