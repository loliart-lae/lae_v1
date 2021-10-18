$.ajaxSetup({
    headers: {
        'X-CSRF-TOKEN': $('input[name="_token"]').val()
    }
})

$.pjax.defaults.timeout = 1500

function close_offline_tip() {
    $('#offline_tip').fadeOut()
    $('body').css('overflow', 'auto')
}

function showOfflineTip() {
    mdui.snackbar({
        message: '无法连接到 LAE',
        position: 'right-bottom',
        buttonText: '显示',
        onButtonClick: function() {
            $('#offline_tip').fadeIn()
            $('body').css('overflow', 'hidden')
        }
    })
}

window.addEventListener('online', close_offline_tip)
window.addEventListener('offline', showOfflineTip)

$(document).pjax('a', '.pjax-container')

// $("#pre_btn").hide()
$(document).on('pjax:clicked', function() {
    $('#load-spinner').css('top', '12vh')
    $('#main').css('opacity', 0)
    $('#main').css('overflow', 'hidden')
})
$(document).on("pjax:timeout", function(event) {
    $('#load-spinner-text').html('仍在加载...')
    $('#load-spinner-text').animate({
        opacity: 1
    })
    $('#load-spinner').css('top', '14vh')

    event.preventDefault()
})

$(document).on("pjax:complete", function(event) {
    $('#load-spinner-text').css('opacity', 0)
    $('#load-spinner').css('top', 0)

    $('#main').css('overflow', 'auto')
    $('#main').css('opacity', 1)
})
