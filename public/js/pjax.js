(()=>{var n=function(){var n=window.location.protocol+"//"+window.location.host+window.location.pathname;$("#main-list a[href='"+n+"']").length>0&&($("#main-list .mdui-list-item").removeClass("mdui-list-item-active"),$("#main-list a[href='"+n+"']").addClass("mdui-list-item-active"))};$.ajaxSetup({headers:{"X-CSRF-TOKEN":$('input[name="_token"]').val()}}),$.pjax.defaults.timeout=1500,window.addEventListener("online",close_offline_tip),window.addEventListener("offline",showOfflineTip),$(document).pjax("a",".pjax-container"),$(document).on("pjax:clicked",(function(){$("#load-spinner").css("top","12vh"),$("#main").css("opacity",0),$(".load-hidden").fadeOut(100)})),$(document).on("pjax:timeout",(function(n){$("#main").css("overflow","hidden"),$("#load-spinner-text").html("仍在加载..."),$("#load-spinner-text").animate({opacity:1}),$("#load-spinner").css("top","14vh"),n.preventDefault()})),$(document).on("pjax:complete",(function(t){n(),$("#load-spinner-text").css("opacity",0),$("#load-spinner").css("top","-10vh"),$("#main").css("height","auto"),$("#main").css("overflow","unset"),$("#main").css("opacity",1),$(".load-hidden").fadeIn(100)})),window.history&&window.history.pushState&&(window.onpopstate=function(){n()}),n()})();