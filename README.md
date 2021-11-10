# Light App Engine

贡献者名单：[`resources/views/contributes.blade.php`](https://lightart.top/contributes)

### 安装

1. 复制`.env.example`，然后按需编辑字段。

2. 运行`composer update`

3. 运行`php artisan key:generate`

4. 运行`php artisan migrate`

5. 配置 Laravel 伪静态

6. 配置 cron，执行`php /path/to/openappengine/artisan schedule:run`，每 1 分钟执行一次

7. 运行队列,推荐使用 Supervisor
    1. 扣费队列
    
       ```conf
       [program:lae-cost]
       process_name=%(program_name)s_%(process_num)02d
       command=php /path/to/openappengine/artisan queue:work --queue=cost
       autostart=true
       autorestart=true
       user=www
       numprocs=1
       redirect_stderr=true
       stdout_logfile=/tmp/cost.log
       stopwaitsecs=3600
       ```
    
    2. LXD队列

       ```conf
       [program:lae-lxd]
       process_name=%(program_name)s_%(process_num)02d
       command=php /path/to/openappengine/artisan queue:work
       autostart=true
       autorestart=true
       user=www
       numprocs=1
       redirect_stderr=true
       stdout_logfile=/tmp/lae-lxd.log
       stopwaitsecs=3600
       ```
    
    3. 邮件队列
    
       ```conf
       [program:lae-mail]
       process_name=%(program_name)s_%(process_num)02d
       command=php /path/to/openappengine/artisan queue:work --queue=mail
       autostart=true
       autorestart=true
       user=www
       numprocs=5
       redirect_stderr=true
       stdout_logfile=/tmp/cost.log
       stopwaitsecs=3600
       ```
    
    4. RDP 队列
    
       ```conf
       [program:lae-rdp]
       process_name=%(program_name)s_%(process_num)02d
       command=php /path/to/openappengine/artisan queue:work --queue=remote_desktop
       autostart=true
       autorestart=true
       user=www
       numprocs=1
       redirect_stderr=true
       stdout_logfile=/tmp/lae-rdp.log
       stopwaitsecs=3600
       ```
    
       

### 注意事项

1. 本项目没有完成，还有许多要改进的地方。
2. 目前没有控制面板，因为`懒`，所以一些操作得直接动数据库。
3. 我们不推荐将该项目用于正式生产环境，玩玩就好，因为我们在开发项目时还不具备专业的安全知识。

