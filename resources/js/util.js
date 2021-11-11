window.util = {
    time: {
        formatSeconds: function (value) {
            let secondTime = parseInt(value); // 秒
            let minuteTime = 0; // 分
            let hourTime = 0; // 小时
            if (secondTime > 60) { //如果秒数大于60，将秒数转换成整数
                //获取分钟，除以60取整数，得到整数分钟
                minuteTime = parseInt(secondTime / 60);
                //获取秒数，秒数取佘，得到整数秒数
                secondTime = parseInt(secondTime % 60);
                //如果分钟大于60，将分钟转换成小时
                if (minuteTime > 60) {
                    //获取小时，获取分钟除以60，得到整数小时
                    hourTime = parseInt(minuteTime / 60);
                    //获取小时后取佘的分，获取分钟除以60取佘的分
                    minuteTime = parseInt(minuteTime % 60);
                }
            }
            let result = "" + parseInt(secondTime) + "秒";

            if (minuteTime > 0) {
                result = "" + parseInt(minuteTime) + "分" + result;
            }
            if (hourTime > 0) {
                result = "" + parseInt(hourTime) + "小时" + result;
            }

            return result;
        }
    },
    dialog: {
        confirm: function (url) {
            mdui.confirm('你正在进入一个安全的页面，请确保你现在没有录制或者进行公开的流式媒体，否则您可能会泄漏重要信息（如用户名，密码等）', function () {
                window.open(url);
            })
        }
    },
    text: {
        putLyric: function (func) {
            $.ajax({
                url: '/api/v1/_lyric',
                method: 'GET',
                success: function (data) {
                    func(data)
                },
                error: function () {
                    let data = { status: 0, content: null, from: null, created_at: null }
                    func(data)
                }
            })
        }
    }
}
