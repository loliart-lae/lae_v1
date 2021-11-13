var nowShow = null;

setInterval(function () {
    let updateCount = 0
    let date = new Date()
    let startTime = Date.parse(date)
    if (localStorage.getItem('startTime') == null) {
        localStorage.setItem('startTime', startTime)
    }
    current = localStorage.getItem('startTime')
    if (startTime - current >= 10000) {
        // 立即更新localStorage，然后获取通知
        localStorage.setItem('startTime', startTime)

        $.ajax({
            type: 'GET',
            url: '/messages/unread',
            dataType: 'json',
            success: function (data) {
                close_offline_tip()
                var currentBalance = parseFloat($('#userBalance').text())
                if (currentBalance != data.balance && updateCount == 0) {
                    mdui.snackbar({
                        message: '账户积分已更新为:' + data.balance,
                        position: 'right-bottom'
                    })
                    $({
                        // 起始值
                        countNum: currentBalance
                    }).animate({
                        // 最终值
                        countNum: data.balance
                    }, {
                        // 动画持续时间
                        duration: 2000,
                        easing: "linear",
                        step: function () {
                            // 设置每步动画计算的数值
                            $('.userBalance').text(Math.floor(this.countNum))
                        },
                        complete: function () {
                            // 设置动画结束的数值
                            $('.userBalance').text(this.countNum)
                        }
                    })
                }
                updateCount++
                $('.userBalance').html(data.balance)
                for (var i = 0; i < data.data.length; i++) {
                    if (data.data.length != 0) {
                        mdui.snackbar({
                            message: data.data[i].content,
                            position: 'right-bottom'
                        })
                    }
                }

                $('.global-time-river').addClass('mdui-text-color-green')
                if (data.streaming != null) {
                    if (data.streaming.id != nowShow) {
                        if (window.location.pathname == '/dashboard/global') {
                            $('#streaming_div').show()
                        }
                        mdui.snackbar({
                            message: '节目 ' + data.streaming.name + ' 已开始。',
                            position: 'right-bottom'
                        })
                        $('#streaming_tool_btn').show()

                        nowShow = data.streaming.id

                    }
                } else {
                    nowShow = null
                    if (window.location.pathname == '/dashboard/global') {
                        $('#streaming_div').hide()
                    }
                    $('.global-time-river').removeClass('mdui-text-color-green')
                    $('#streaming_tool_btn').hide()
                }
            },
            error: function (data) {
                showOfflineTip()
            }
        })
    }
}, 1000)
