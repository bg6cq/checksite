## 检测网站IPv4/v6的HTTP、HTTPS、HTTP/2服务情况


## 简单安装说明

1.1 虚拟机环境

使用Ubuntu 18.04 LTS，配置好IPv6/v4环境即可。
   
``` 
sudo su -
apt-get install mariadb-server php-cli apache2  php php-mysql 
```

1.2 获取代码

```
sudo su -
cd /usr/src
git clone https://github.com/bg6cq/checksite.git
cd checksite
git submodules init
git submodules update

ln -s /usr/src/checksite/web /var/www/html/checksite
```

1.3 创建数据库
```

sudo su -
echo "create database checksite;" | mysql
echo "UPDATE mysql.user SET authentication_string=PASSWORD(''), plugin='mysql_native_password' WHERE User='root' AND Host='localhost';" | mysql mysql
echo "FLUSH PRIVILEGES;" | mysql
cat /usr/src/checksite/sql/checksite.sql | mysql checksite
```

1.4 修改要监测的列表，更新数据库

```
cd /usr/checksite/data
vi group.txt
根据需要修改

php update_info.php
```

使用浏览器访问  http://x.x.x.x/checksite 能看到信息（空结果）

1.5 crontab

创建如下crontab(最后数字是group.txt配置的组编号)

```
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 1
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 2
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 3
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 4
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 5
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 6
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 100
*/5 * * * * cd /usr/src/checksite; php checkgroup.php 101
*/30 * * * * cd /usr/src/checksite; php checkgroup.php 102
0 7 * * * cd /usr/src/checksite; php checkgroup.php 99
25,55 * * * * cd /usr/src/checksite; php update_avg_score.php
```
