var mainMenu = {
    update: function () {
        let url = window.location.protocol + '//' + window.location.host + window.location.pathname
        if ($("#main-list a[href='" + url + "']").length > 0) {
            $('#main-list .mdui-list-item').removeClass('mdui-list-item-active')
            $("#main-list a[href='" + url + "']").addClass('mdui-list-item-active')
            $("#backMain").attr('href', url)
        }
    }
}

$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
})

$.pjax.defaults.timeout = 1500

window.addEventListener('online', close_offline_tip)
window.addEventListener('offline', showOfflineTip)

$(document).pjax('a', '.pjax-container')

$(document).on('pjax:clicked', function () {
    // $('#main').css('filter', 'blur(1px)')
    $('#turn').css('animation-play-state', 'running')
})
$(document).on("pjax:timeout", function (event) {
    $('#main').css('opacity', 0)
    event.preventDefault()
})

$(document).on("pjax:complete", function (event) {
    mainMenu.update()

    $('#main').css('height', 'auto')
    $('#main').css('overflow', 'unset')
    $('#main').css('opacity', 1)
    // $('#main').css('filter', 'unset')

    $('#turn').css('animation-play-state', 'paused')
    $('#thisLink').attr('href', window.location.href)

    mdui.mutation()
})

if (window.history && window.history.pushState) {
    window.onpopstate = function () {
        mainMenu.update()
    }
}

mainMenu.update()


let main_offset_top = $('#main').offset().top
let bottom_fab_status = 0
$(window).scroll(function () {
    var scrollTop = document.documentElement.scrollTop || document.body.scrollTop;

    if (scrollTop >= main_offset_top) {
        bottom_fab_status = 1
    } else {
        bottom_fab_status = 0
    }

    if (bottom_fab_status) {
        $('#bottom-fab').removeClass('mdui-fab-hide')
    } else {
        $('#bottom-fab').addClass('mdui-fab-hide')

    }

})
