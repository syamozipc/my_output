(()=>{"use strict";var e,t,n;e=document.querySelector(".js-suggestionInput"),t=document.querySelector(".js-countriesSelect"),e.addEventListener("change",(function(n){var r,o=null===(r=e.list.querySelector("[value=".concat(n.target.value,"]")))||void 0===r?void 0:r.dataset.countryId;void 0!==o&&(Array.from(t.options).find((function(e){return e.value===o})).selected=!0)})),document.querySelector(".js-inputImg").addEventListener("change",(function(e){var t=e.target.files[0];if(t){var n=window.URL.createObjectURL(t),r=document.querySelector(".js-displayImg");r.src=n,r.addEventListener("load",(function(){return r.classList.remove("is-hidden")}))}})),n=document.querySelector(".js-apiSuggest").dataset.suggestUrl,console.log(n),fetch(n)})();