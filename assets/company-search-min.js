!function(){"use strict";jQuery(document).ready((function(){const e=document.querySelector(".skip-link"),t=document.querySelectorAll(".menu-item a"),s=document.querySelector(".site-logo a"),i=document.querySelector(".main-filter-query-wrapper"),a=i.querySelectorAll(".hentry"),n=i.querySelectorAll(".gb-container-link"),r=i.querySelector(".mined-filter-menu"),o=r.querySelectorAll(".filter"),c=r.querySelector(".reset-filters"),l=r.querySelector(".search-reset"),d=r.querySelector("#searchbox"),u=i.querySelector(".sticky");function v(){const e=document.querySelectorAll(".loop");e&&e.forEach((e=>{e.querySelectorAll(".cards .is-visible").length<=0?e.classList.add("is-hidden"):e.classList.remove("is-hidden")}),!1)}function m(){let e=document.getElementById("searchbox").value;for(let t=0;t<a.length;t++)a[t].textContent.toLowerCase().includes(e.toLowerCase())?(a[t].classList.remove("is-hidden"),a[t].classList.add("is-visible"),v()):(a[t].classList.add("is-hidden"),a[t].classList.remove("is-visible"),v())}function h(e){Array.from(r.querySelectorAll(".active")).forEach((e=>e.classList.remove("active")));for(let t=0;t<a.length;t++)d.value="",a[t].classList.contains(e)?(a[t].classList.remove("is-hidden"),a[t].classList.add("is-visible"),v()):a[t].classList.contains(e)||(a[t].classList.add("is-hidden"),a[t].classList.remove("is-visible"),v()),c.addEventListener("click",(()=>{a[t].classList.remove("is-hidden"),a[t].classList.add("is-visible"),o.forEach((e=>{e.classList.remove("active")})),c.classList.add("active"),v()})),c.addEventListener("keyup",(e=>{"Enter"===e.key&&(a[t].classList.remove("is-hidden"),a[t].classList.add("is-visible"),o.forEach((e=>{e.classList.remove("active")})),c.classList.add("active"),v())}))}let f;function y(){window.pageYOffset>window.innerHeight/3&&window.scroll({top:0,left:0,behavior:"smooth"})}a.forEach((e=>e.classList.add("is-visible"))),e.setAttribute("tabindex",1),s.setAttribute("tabindex",1),t.forEach((e=>e.setAttribute("tabindex",1))),n.forEach((e=>e.setAttribute("tabindex",3))),u&&(u.offsetHeight<window.innerHeight&&u.classList.add("sticky-element"),window.addEventListener("resize",(()=>{u.offsetHeight<window.innerHeight?u.classList.add("sticky-element"):u.classList.remove("sticky-element")}))),o.forEach((e=>{e.addEventListener("click",(t=>{t.preventDefault(),y(),h(php_vars.minerals_taxonomy+"-"+e.id),e.classList.toggle("active")}))})),o.forEach((e=>{e.addEventListener("keyup",(t=>{"Enter"===t.key&&(t.preventDefault(),y(),h(php_vars.minerals_taxonomy+"-"+e.id),e.classList.toggle("active"))}))})),php_vars.ajaxurl,php_vars.nonce,d.addEventListener("keyup",(()=>{clearTimeout(f),f=setTimeout(m,500,y())})),l.addEventListener("keyup",(()=>{d.value=""}))}),!1),jQuery((function($){var e={ajaxurl:php_vars.ajaxurl,nonce:php_vars.nonce};$("#company-sort").on("change",(function(){var t=$("#company-sort").val(),s=e.nonce;$.ajax({url:e.ajaxurl,type:"POST",data:{action:"company_search",nonce:s,sort_option:t},beforeSend:function(){},success:function(e){if(e.success){var t=e.data;$("#company-container").html(t)}else console.log(e.data)},error:function(e,t,s){console.log(e.responseText)},complete:function(){}})}))}))}();