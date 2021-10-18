/******/ (() => { // webpackBootstrap
var __webpack_exports__ = {};
/*!******************************!*\
  !*** ./resources/js/pjax.js ***!
  \******************************/
$.ajaxSetup({
  headers: {
    'X-CSRF-TOKEN': $('input[name="_token"]').val()
  }
});
$.pjax.defaults.timeout = 1500;
window.addEventListener('online', close_offline_tip);
window.addEventListener('offline', showOfflineTip);
$(document).pjax('a', '.pjax-container'); // $("#pre_btn").hide()

$(document).on('pjax:clicked', function () {
  $('#load-spinner').css('top', '12vh');
  $('#main').css('opacity', 0);
  $('.load-hidden').fadeOut(100);
});
$(document).on("pjax:timeout", function (event) {
  $('#main').css('overflow', 'hidden');
  $('#load-spinner-text').html('仍在加载...');
  $('#load-spinner-text').animate({
    opacity: 1
  });
  $('#load-spinner').css('top', '14vh');
  event.preventDefault();
});
$(document).on("pjax:complete", function (event) {
  $('#load-spinner-text').css('opacity', 0);
  $('#load-spinner').css('top', '-10vh');
  $('#main').css('height', 'auto');
  $('#main').css('overflow', 'unset');
  $('#main').css('opacity', 1);
  $('.load-hidden').fadeIn(100);
});
/******/ })()
;