(()=>{"use strict";const e=(e,t)=>{e.classList.toggle("is-hidden"),t.classList.toggle("is-hidden")};(()=>{const t=document.querySelector(".js-imgContent"),n=document.querySelector(".js-description"),c=document.querySelector(".js-modalImgContent"),d=document.querySelector(".js-modalDescription"),o=document.querySelector(".js-btn-open-modal"),s=document.querySelector(".js-btn-close-modal"),l=document.querySelector(".js-btn-cancel"),r=document.querySelector(".js-btn-delete"),i=document.querySelector(".js-modal"),u=document.querySelector(".js-overlay");o.addEventListener("click",(o=>{o.preventDefault(),c.src=t.src,d.textContent=n.textContent,c.addEventListener("load",e.bind(null,i,u))})),s.addEventListener("click",e.bind(null,i,u)),l.addEventListener("click",e.bind(null,i,u)),u.addEventListener("click",e.bind(null,i,u)),document.addEventListener("keydown",(t=>{"Escape"!==t.key||i.classList.contains("is-hidden")||e(i,u)})),r.addEventListener("click",(()=>{document.querySelector(".js-delete-form").submit()}))})()})();