(()=>{"use strict";(()=>{const e=document.querySelector(".js-suggestionInput"),t=document.querySelector(".js-suggestionList"),n=e.dataset.suggestUrl;e.addEventListener("input",(e=>{return s=void 0,r=void 0,i=function*(){if(t.innerHTML="",!(e.target instanceof HTMLInputElement))return;if(""===e.target.value)return;const s=new URLSearchParams({search:e.target.value}),r=`${n}?${String(s)}`,c=yield fetch(r);(yield c.json()).forEach((e=>{const n=document.createElement("li");n.classList.add("js-suggestLi"),n.textContent=e.name,t.appendChild(n)}))},new((c=void 0)||(c=Promise))((function(e,t){function n(e){try{a(i.next(e))}catch(e){t(e)}}function o(e){try{a(i.throw(e))}catch(e){t(e)}}function a(t){var s;t.done?e(t.value):(s=t.value,s instanceof c?s:new c((function(e){e(s)}))).then(n,o)}a((i=i.apply(s,r||[])).next())}));var s,r,c,i})),t.addEventListener("click",(n=>{const s=n.target.closest(".js-suggestLi");s&&(e.value=s.textContent,t.innerHTML="")}))})(),document.querySelector(".js-inputImg").addEventListener("change",(e=>{const t=e.target.files;if(!t)return;const n=window.URL.createObjectURL(t[0]),s=document.querySelector(".js-displayImg");s.src=n,s.addEventListener("load",(()=>s.classList.remove("is-hidden")))}))})();