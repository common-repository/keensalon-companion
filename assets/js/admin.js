!function(e){var n={};function t(o){if(n[o])return n[o].exports;var r=n[o]={i:o,l:!1,exports:{}};return e[o].call(r.exports,r,r.exports,t),r.l=!0,r.exports}t.m=e,t.c=n,t.d=function(e,n,o){t.o(e,n)||Object.defineProperty(e,n,{enumerable:!0,get:o})},t.r=function(e){"undefined"!=typeof Symbol&&Symbol.toStringTag&&Object.defineProperty(e,Symbol.toStringTag,{value:"Module"}),Object.defineProperty(e,"__esModule",{value:!0})},t.t=function(e,n){if(1&n&&(e=t(e)),8&n)return e;if(4&n&&"object"==typeof e&&e&&e.__esModule)return e;var o=Object.create(null);if(t.r(o),Object.defineProperty(o,"default",{enumerable:!0,value:e}),2&n&&"string"!=typeof e)for(var r in e)t.d(o,r,function(n){return e[n]}.bind(null,r));return o},t.n=function(e){var n=e&&e.__esModule?function(){return e.default}:function(){return e};return t.d(n,"a",n),n},t.o=function(e,n){return Object.prototype.hasOwnProperty.call(e,n)},t.p="",t(t.s=2)}([,,function(e,n,t){e.exports=t(3)},function(e,n,t){"use strict";jQuery(document).ready((function(e){e("body").on("click",".keensalon-upload-button",(function(n){n.preventDefault();var t=e(this).closest("div"),o=wp.media({title:"KeenSalon Image Uploader",multiple:!1}).on("select",(function(){var n=o.state().get("selection").first().toJSON(),r=n.url.split(".").pop();-1!=e.inArray(r,["jpg","gif","png","jpeg"])?t.find(".keensalon-screenshot").empty().hide().append('<img src="'+n.url+'"><a class="keensalon-remove-image"></a>').slideDown("fast"):t.find(".keensalon-screenshot").empty().hide().append("<small>"+KEENSALON_COMPANION_uploader.msg+"</small>").slideDown("fast"),t.find(".keensalon-upload").val(n.id).trigger("change"),t.find(".keensalon-upload-button").val(KEENSALON_COMPANION_uploader.change)})).open()})),e("body").on("click",".keensalon-remove-image",(function(n){var t=e(this).parent("div").parent("div");return t.find(".keensalon-upload").val("").trigger("change"),t.find(".keensalon-remove-image").hide(),t.find(".keensalon-screenshot").slideUp(),t.find(".keensalon-upload-button").val(KEENSALON_COMPANION_uploader.upload),!1}))}))}]);