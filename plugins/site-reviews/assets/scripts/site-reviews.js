!function(i,e,t){"use strict";var h="star-rating",s=function(t,i){this.selects="[object String]"==={}.toString.call(t)?e.querySelectorAll(t):[t],this.destroy=function(){this.widgets.forEach(function(t){t.t()})},this.rebuild=function(){this.widgets.forEach(function(t){t.i()})},this.widgets=[];for(var s=0;s<this.selects.length;s++)if("SELECT"===this.selects[s].tagName&&!this.selects[s][h]){var n=new o(this.selects[s],i);void 0!==n.direction&&this.widgets.push(n)}},o=function(t,i){this.el=t,this.s=this.n({},this.e,i||{},JSON.parse(t.getAttribute("data-options"))),this.h(),this.stars<1||this.stars>this.s.maxStars||this.o()};o.prototype={e:{classname:"gl-star-rating",clearable:!0,initialText:"Select a Rating",maxStars:10,showText:!0},o:function(){this.r(),this.current=this.selected=this.a(),this.u(),this.c(),this.f(),this.d(this.current),this.l("add"),this.el[h]=!0},v:function(){this.s.showText&&(this.textEl=this.m(this.widgetEl,{class:this.s.classname+"-text"},!0))},c:function(){var t=this._(),i=this.m(this.el,{class:this.s.classname+"-stars"},!0);for(var s in t){var n=this.p({"data-value":s,"data-text":t[s]});i.innerHTML+=n.outerHTML}this.widgetEl=i,this.v()},w:function(t){(t<0||isNaN(t))&&(t=0),t>this.stars&&(t=this.stars),this.widgetEl.classList.remove("s"+10*this.current),this.widgetEl.classList.add("s"+10*t),this.s.showText&&(this.textEl.textContent=t<1?this.s.initialText:this.widgetEl.childNodes[t-1].dataset.text),this.current=t},p:function(t){var i=e.createElement("span");for(var s in t=t||{})i.setAttribute(s,t[s]);return i},t:function(){this.l("remove");var t=this.el.parentNode;t.parentNode.replaceChild(this.el,t),delete this.el[h]},g:function(i,s,t){t.forEach(function(t){i[s+"EventListener"](t,this.events[t])}.bind(this))},n:function(){var t=[].slice.call(arguments),s=t[0],n=t.slice(1);return Object.keys(n).forEach(function(t){for(var i in n[t])n[t].hasOwnProperty(i)&&(s[i]=n[t][i])}),s},S:function(t){var i={},s=t.pageX||t.changedTouches[0].pageX,n=this.widgetEl.offsetWidth;return i.ltr=Math.max(s-this.offsetLeft,1),i.rtl=n-i.ltr,Math.min(Math.ceil(i[this.direction]/Math.round(n/this.stars)),this.stars)},_:function(){for(var t=this.el,i={},s={},n=0;n<t.length;n++)this.b(t[n])||(i[t[n].value]=t[n].text);return Object.keys(i).sort().forEach(function(t){s[t]=i[t]}),s},a:function(){return parseInt(this.el.options[Math.max(this.el.selectedIndex,0)].value)||0},l:function(t){var i=this.el.closest("form");i&&"FORM"===i.tagName&&this.g(i,t,["reset"]),this.g(this.el,t,["change","keydown"]),this.g(this.widgetEl,t,["mousedown","mouseleave","mousemove","mouseover","touchend","touchmove","touchstart"])},r:function(){this.events={change:this.L.bind(this),keydown:this.R.bind(this),mousedown:this.x.bind(this),mouseleave:this.G.bind(this),mousemove:this.T.bind(this),mouseover:this.F.bind(this),reset:this.j.bind(this),touchend:this.x.bind(this),touchmove:this.T.bind(this),touchstart:this.F.bind(this)}},m:function(t,i,s){var n=this.p(i);return t.parentNode.insertBefore(n,!0===s?t.nextSibling:t),n},b:function(t){return null===t.getAttribute("value")||""===t.value},L:function(){this.w(this.a())},R:function(t){if(~["ArrowLeft","ArrowRight"].indexOf(t.key)){var i="ArrowLeft"===t.key?-1:1;"rtl"===this.direction&&(i*=-1),this.d(Math.min(Math.max(this.a()+i,0),this.stars)),this.M()}},x:function(t){t.preventDefault();var i=this.S(t);if(0!==this.current&&parseFloat(this.selected)===i&&this.s.clearable)return this.j(),void this.M();this.d(i),this.M()},G:function(t){t.preventDefault(),this.w(this.selected)},T:function(t){t.preventDefault(),this.w(this.S(t))},F:function(t){t.preventDefault();var i=this.widgetEl.getBoundingClientRect();this.offsetLeft=i.left+e.body.scrollLeft},j:function(){var t=this.el.querySelector("[selected]"),i=t?t.value:"";this.el.value=i,this.selected=parseInt(i)||0,this.w(i)},i:function(){this.el.parentNode.classList.contains(this.s.classname)&&this.t(),this.o()},f:function(){var t=this.el.parentNode;this.direction=i.getComputedStyle(t,null).getPropertyValue("direction"),t.classList.add(this.s.classname+"-"+this.direction)},d:function(t){this.el.value=this.selected=t,this.w(t)},h:function(){for(var t=this.el,i=this.stars=0;i<t.length;i++)if(!this.b(t[i])){if(isNaN(parseFloat(t[i].value))||!isFinite(t[i].value))return void(this.stars=0);this.stars++}},M:function(){this.el.dispatchEvent(new Event("change"))},u:function(){this.m(this.el,{class:this.s.classname,"data-star-rating":""}).appendChild(this.el)}},"function"==typeof define&&define.amd?define([],function(){return s}):"object"==typeof module&&module.exports?module.exports=s:i.StarRating=s}(window,document),function(){"use strict";GLSR.Ajax=function(){},GLSR.Ajax.prototype={get:function(t,i,s){this.k(i),this.xhr.open("GET",t,!0),this.xhr.responseType="text",this.y(s),this.xhr.send()},C:function(t){return"json"===this.xhr.responseType?t({message:this.xhr.statusText},!1):"text"===this.xhr.responseType?t(this.xhr.statusText):void console.log(this.xhr)},O:function(t){if(0===this.xhr.status||200<=this.xhr.status&&this.xhr.status<300||304===this.xhr.status){if("json"===this.xhr.responseType)return t(this.xhr.response.data,this.xhr.response.success);if("text"===this.xhr.responseType)return t(this.xhr.responseText);console.log(this.xhr)}else this.C(t)},isFileSupported:function(){var t=document.createElement("INPUT");return t.type="file","files"in t},isFormDataSupported:function(){return!!window.FormData},isUploadSupported:function(){var t=new XMLHttpRequest;return!!(t&&"upload"in t&&"onprogress"in t.upload)},post:function(t,i,s){this.k(i),this.xhr.open("POST",GLSR.ajaxurl,!0),this.xhr.responseType="json",this.y(s),this.xhr.send(this.D(t))},k:function(t){this.xhr=new XMLHttpRequest,this.xhr.onload=this.O.bind(this,t),this.xhr.onerror=this.C.bind(this,t)},I:function(i,s,n){return"object"!=typeof s||s instanceof Date||s instanceof File?i.append(n,s||""):Object.keys(s).forEach(function(t){s.hasOwnProperty(t)&&(i=this.I(i,s[t],n?n[t]:t))}.bind(this)),i},D:function(t){var i=t;return"[object HTMLFormElement]"===Object.prototype.toString.call(t)&&(i=new FormData(t)),"[object FormData]"!==Object.prototype.toString.call(i)&&(i=new FormData),i.append("action",GLSR.action),i.append("_ajax_request",!0),i},y:function(t){for(var i in(t=t||{})["X-Requested-With"]="XMLHttpRequest",t)t.hasOwnProperty(i)&&this.xhr.setRequestHeader(i,t[i])}}}(),function(){"use strict";GLSR.Excerpts=function(t){this.o(t||document)},GLSR.Excerpts.prototype={config:{hiddenClass:"glsr-hidden",hiddenTextSelector:".glsr-hidden-text",readMoreClass:"glsr-read-more",visibleClass:"glsr-visible"},N:function(t){var i=document.createElement("span"),s=document.createElement("a");s.setAttribute("href","#"),s.setAttribute("data-text",t.getAttribute("data-show-less")),s.innerHTML=t.getAttribute("data-show-more"),s.addEventListener("click",this.P.bind(this)),i.setAttribute("class",this.config.readMoreClass),i.appendChild(s),t.parentNode.insertBefore(i,t.nextSibling)},P:function(t){t.preventDefault();var i=t.currentTarget,s=i.parentNode.previousSibling,n=i.getAttribute("data-text");s.classList.toggle(this.config.hiddenClass),s.classList.toggle(this.config.visibleClass),i.setAttribute("data-text",i.innerText),i.innerText=n},o:function(t){for(var i=t.querySelectorAll(this.config.hiddenTextSelector),s=0;s<i.length;s++)this.N(i[s])}}}(),function(){"use strict";var e=function(t,i){this.button=i,this.config=GLSR.validationconfig,this.form=t,this.recaptcha=new GLSR.Recaptcha(this),this.strings=GLSR.validationstrings,this.useAjax=this.q(),this.validation=new GLSR.Validation(t)};e.prototype={V:function(t,i,s){t.classList[s?"add":"remove"](i)},A:function(){this.button.setAttribute("disabled","")},H:function(){this.button.removeAttribute("disabled")},B:function(t,i){var s=!0===i;"unset"!==t.recaptcha?("reset"===t.recaptcha&&this.recaptcha.U(),s&&(this.recaptcha.U(),this.form.reset()),this.W(t.errors),this.X(t.message,s),this.H(),t.form=this.form,document.dispatchEvent(new CustomEvent("site-reviews/after/submission",{detail:t})),s&&""!==t.redirect&&(window.location=t.redirect)):this.recaptcha.Y()},o:function(){this.form.addEventListener("submit",this.z.bind(this)),this.J(),this.recaptcha.K()},J:function(){new StarRating("select.glsr-star-rating",{clearable:!1,showText:!1})},q:function(){var i=new GLSR.Ajax,s=!0;return[].forEach.call(this.form.elements,function(t){"file"===t.type&&(s=i.isFileSupported()&&i.isUploadSupported())}),s&&!this.form.classList.contains("no-ajax")},z:function(t){if(!this.validation.$())return t.preventDefault(),void this.X(this.strings.errors,!1);this.Q(),(this.form["g-recaptcha-response"]&&""===this.form["g-recaptcha-response"].value||this.useAjax)&&(t.preventDefault(),this.Z())},Q:function(){this.X("",!0),this.validation.U()},W:function(t){if(t)for(var i in t)if(t.hasOwnProperty(i)){var s=GLSR.nameprefix?GLSR.nameprefix+"["+i+"]":i,n=this.form.querySelector('[name="'+s+'"]');this.validation.tt(n,t[i]),this.validation.it(n.validation,"add")}},X:function(t,i){var s=this.form.querySelector("."+this.config.message_tag_class);null===s&&((s=document.createElement(this.config.message_tag)).className=this.config.message_tag_class,this.button.parentNode.insertBefore(s,this.button.nextSibling)),this.V(s,this.config.message_error_class,!i),this.V(s,this.config.message_success_class,i),s.classList.remove(this.config.message_initial_class),s.innerHTML=t},Z:function(t){(new GLSR.Ajax).isFormDataSupported()?(this.A(),this.form[GLSR.nameprefix+"[_counter]"].value=t||0,(new GLSR.Ajax).post(this.form,this.B.bind(this))):this.X(this.strings.unsupported,!1)}},GLSR.Forms=function(t){var i,s;this.nodeList=document.querySelectorAll("form.glsr-form"),this.forms=[];for(var n=0;n<this.nodeList.length;n++)(s=this.nodeList[n].querySelector("[type=submit]"))&&(i=new e(this.nodeList[n],s),t&&i.o(),this.forms.push(i))}}(),function(){"use strict";var i=function(t){this.el=t,this.r()};i.prototype={config:{hideClass:"glsr-hide",linkSelector:".glsr-navigation a",scrollTime:468},st:function(t){for(var i=0;t=t.previousSibling;)1===t.nodeType&&i++;return i},nt:function(t){if(t.nodeName)return this.et(this.ht(t))},et:function(t){if(""!==t.id)return"#"+t.id;var i="";return t.parent&&(i=this.et(t.parent)+" > "),i+t.name+":nth-child("+(t.index+1)+")"},ht:function(t){var i={id:t.id,index:this.st(t),name:t.nodeName.toLowerCase(),parent:null};return t.parentElement&&t.parentElement!==document.body&&(i.parent=this.ht(t.parentElement)),i},ot:function(t){return t.className?"."+t.className.trim().replace(/\s+/g,"."):""},rt:function(t){return t.id?"#"+t.id.trim():""},B:function(t,i,s){var n=document.implementation.createHTMLDocument("x");n.documentElement.innerHTML=s;var e=i?n.querySelectorAll(i):"";if(1===e.length)return this.el.innerHTML=e[0].innerHTML,this.at(this.el),this.el.classList.remove(this.config.hideClass),this.r(),window.history.pushState(null,"",t),void new GLSR.Excerpts(this.el);window.location=t},r:function(){for(var t=this.el.querySelectorAll(this.config.linkSelector),i=0;i<t.length;i++)t[i].addEventListener("click",this.P.bind(this))},P:function(t){t.preventDefault();var i=this.nt(this.el);this.el.classList.add(this.config.hideClass),(new GLSR.Ajax).get(t.currentTarget.href,this.B.bind(this,t.currentTarget.href,i))},at:function(t,i){var s;i=i||16;for(var n=0;n<GLSR.ajaxpagination.length;n++)(s=document.querySelector(GLSR.ajaxpagination[n]))&&"fixed"===window.getComputedStyle(s).getPropertyValue("position")&&(i+=s.clientHeight);var e=t.getBoundingClientRect().top-i;0<e||this.ut({endY:e,offset:window.pageYOffset,startTime:window.performance.now(),startY:t.scrollTop})},ut:function(t){var i=(window.performance.now()-t.startTime)/this.config.scrollTime;i=1<i?1:i;var s=.5*(1-Math.cos(Math.PI*i)),n=t.startY+(t.endY-t.startY)*s;window.scroll(0,t.offset+n),n!==t.endY&&window.requestAnimationFrame(this.ut.bind(this,t))}},GLSR.Pagination=function(){this.navs=[];var t=document.querySelectorAll(".glsr-ajax-pagination");t.length&&t.forEach(function(t){this.navs.push(new i(t))}.bind(this))}}(),function(){"use strict";GLSR.Recaptcha=function(t){this.Form=t,this.counter=0,this.id=-1,this.is_submitting=!1,this.observer=new MutationObserver(function(t){var i=t.pop();i.target&&"visible"!==i.target.style.visibility&&(this.observer.disconnect(),setTimeout(function(){this.is_submitting||this.Form.H()}.bind(this),250))}.bind(this))},GLSR.Recaptcha.prototype={Y:function(){if(-1!==this.id)return this.counter=0,this.ct(this.id),void grecaptcha.execute(this.id);setTimeout(function(){this.counter++,this.Z.call(this.Form,this.counter)}.bind(this),1e3)},ct:function(t){var i=window.___grecaptcha_cfg.clients[t];for(var s in i)if(i.hasOwnProperty(s)&&"[object String]"===Object.prototype.toString.call(i[s])){var n=document.querySelector("iframe[name=c-"+i[s]+"]");if(n){this.observer.observe(n.parentElement.parentElement,{attributeFilter:["style"],attributes:!0});break}}},K:function(){this.Form.form.onsubmit=null;var t=this.Form.form.querySelector(".glsr-recaptcha-holder");t&&(t.innerHTML="",this.ft(t))},ft:function(t){setTimeout(function(){if("undefined"==typeof grecaptcha||void 0===grecaptcha.render)return this.ft(t);this.id=grecaptcha.render(t,{callback:this.Z.bind(this.Form,this.counter),"expired-callback":this.U.bind(this),isolated:!0},!0)}.bind(this),250)},U:function(){this.counter=0,this.is_submitting=!1,-1!==this.id&&grecaptcha.reset(this.id)},Z:function(t){if(this.recaptcha.is_submitting=!0,!this.useAjax)return this.A(),void this.form.submit();this.Z(t)}}}(),function(){"use strict";function s(t){var i='input[name="'+t.getAttribute("name")+'"]:checked';return t.validation.form.querySelectorAll(i).length}var h={email:{fn:function(t){return!t||/^[^\s@]+@[^\s@]+\.[^\s@]+$/.test(t)}},max:{fn:function(t,i){return!t||("checkbox"===this.type?s(this)<=parseInt(i):parseFloat(t)<=parseFloat(i))}},maxlength:{fn:function(t,i){return!t||t.length<=parseInt(i)}},min:{fn:function(t,i){return!t||("checkbox"===this.type?s(this)>=parseInt(i):parseFloat(t)>=parseFloat(i))}},minlength:{fn:function(t,i){return!t||t.length>=parseInt(i)}},number:{fn:function(t){return!t||!isNaN(parseFloat(t))},priority:2},required:{fn:function(t){return"radio"===this.type||"checkbox"===this.type?s(this):void 0!==t&&""!==t},priority:99,halt:!0}};GLSR.Validation=function(t){this.config=GLSR.validationconfig,this.form=t,this.form.setAttribute("novalidate",""),this.strings=GLSR.validationstrings,this.o()},GLSR.Validation.prototype={dt:["required","max","maxlength","min","minlength","pattern"],lt:"input:not([type^=hidden]):not([type^=submit]), select, textarea",vt:function(t){var i=~["radio","checkbox"].indexOf(t.getAttribute("type"))||"SELECT"===t.nodeName?"change":"input";t.addEventListener(i,function(t){this.$(t.target)}.bind(this))},mt:function(t,i,s){[].forEach.call(t,function(t){~this.dt.indexOf(t.name)?this._t(i,s,t.name,t.value):"type"===t.name&&this._t(i,s,t.value)}.bind(this))},_t:function(t,i,s,n){if(h[s]&&(h[s].name=s,t.push(h[s]),n)){var e=n.split(",");e.unshift(null),i[s]=e}},U:function(){for(var t in this.fields)this.fields.hasOwnProperty(t)&&(this.fields[t].errorElements=null,this.fields[t].input.classList.remove(this.config.input_error_class));[].map.call(this.form.querySelectorAll("."+this.config.error_tag_class),function(t){t.parentNode.classList.remove(this.config.field_error_class),t.parentNode.removeChild(t)}.bind(this))},n:function(){var t=[].slice.call(arguments),s=t[0],n=t.slice(1);return Object.keys(n).forEach(function(t){for(var i in n[t])n[t].hasOwnProperty(i)&&(s[i]=n[t][i])}),s},pt:function(t){if(t.errorElements)return t.errorElements;var i,s=t.input.closest("."+this.config.field_class);return s&&null===(i=s.closest("."+this.config.error_tag_class))&&((i=document.createElement(this.config.error_tag)).className=this.config.error_tag_class,s.appendChild(i)),t.errorElements=[s,i]},o:function(){this.fields=[].map.call(this.form.querySelectorAll(this.lt),function(t){return this.wt(t)}.bind(this))},wt:function(t){var i={},s=[];return this.mt(t.attributes,s,i),this.gt(s),this.vt(t),t.validation={form:this.form,input:t,params:i,validators:s}},it:function(t,i){var s=this.pt(t),n="add"===i;t.input.classList[i](this.config.input_error_class),s[0]&&s[0].classList[i](this.config.field_error_class),s[1]&&(s[1].innerHTML=n?t.errors.join("<br>"):"",s[1].style.display=n?"":"none")},tt:function(t,i){t.validation||this.wt(t),t.validation.errors=i},gt:function(t){t.sort(function(t,i){return(i.priority||1)-(t.priority||1)})},$:function(t){var i=!0,s=this.fields;for(var n in t instanceof HTMLElement&&(s=[t.validation]),s)if(s.hasOwnProperty(n)){var e=s[n];this.St(e)?this.it(e,"remove"):(i=!1,this.it(e,"add"))}return i},St:function(t){var i=[],s=!0;for(var n in t.validators)if(t.validators.hasOwnProperty(n)){var e=t.validators[n],h=t.params[e.name]?t.params[e.name]:[];if(h[0]=t.input.value,!e.fn.apply(t.input,h)){s=!1;var o=this.strings[e.name];if(i.push(o.replace(/(\%s)/g,h[1])),!0===e.halt)break}}return t.errors=i,s}}}(),document.addEventListener("DOMContentLoaded",function(){for(var t=document.querySelectorAll(".glsr-widget, .glsr-shortcode"),i=0;i<t.length;i++){var s=window.getComputedStyle(t[i],null).getPropertyValue("direction");t[i].classList.add("glsr-"+s)}document.all&&!window.atob||(new GLSR.Forms(!0),new GLSR.Pagination,new GLSR.Excerpts)});