@extends('layouts.app')

@section('title', '仪表盘')

@section('content')
    <div id="mdText">
        <textarea style="display:none">
# 我们是？
嘿，这里是 **Light App Engine**，简称 **LAE**。

### 在这个平台可以做什么？
你可以租赁容器，进行测试。或者利用容器组建一个集群，还可以使用单一容器开一个Minecraft服务器之类的。

### 如何充值？
目前的充值流程是这样的：
1. 点击顶栏“剩余积分”
2. 输入金额
3. 扫码充值

### 如何创建我的实例？
要创建实例，首先你需要**确保**：

1. 你已经创建了项目
2. 项目中有足够的积分余额

确保这些条件之后，你可以按照这个流程操作：

1. 点击“容器管理”
2. 点击“新建Linux容器”
3. 填写基本信息，然后“创建”即可
4. 稍等片刻后，您的容器就已经准备完成。

到这里，你就可以使用容器啦~

### 什么是端口转发？我又如何访问我的容器？
端口转发可以将内网端口转发到公网中。不同服务器之间内网无法互相访问。同服务器中，您可以借助一个容器访问另一个容器。

常见转发：SSH，内部端口22，外部端口"自定义"

转发时，请注意：外部端口必须大于`1025`

### 我不可以在容器上做什么？

- 肉鸡，扫描，等危害网络安全等行为。
- 任何违反服务器所在“地区/国家”法律行为。
- 长时间高强度占用资源。
        </textarea>
    </div>


    <script>
        var editor;
        editor = editormd.markdownToHTML("mdText", {
    htmlDecode: "style,script,iframe",
    emoji: true,
    taskList: true,

    codeFold: true,
});
    </script>

@endsection
