## 检测网站IPv4/v6的HTTP、HTTPS、HTTP/2服务情况


## 简单安装说明

1.1 虚拟机环境

    我使用的是Ubuntu 16.04 LTS，配置好IPv6/v4环境即可。
   
``` 

#get curl which support http/2
echo "deb http://ppa.launchpad.net/jonathonf/curl/ubuntu xenial main" > /etc/apt/sources.list.d/jonathonf-ubuntu-curl-xenial.list
apt-get install curl

apt-get install mariadb-server php-cli apache2  php php-mysql 
```

1.2 获取代码

```
cd /usr/src
git clone https://github.com/bg6cq/checksite.git
cd checksite
git submodules init
git submodules update

ln -s /usr/src/checksite/web /var/www/html/checksite
```

1.3 创建数据库
```
echo "create database checksite;" | mysql

cat /usr/src/checksite/checksite.sql | mysql checksite
```

1.4 修改要监测的列表，更新数据库

```
cd /usr/checksite/data
vi group.txt
...
php update_info.php
```


1.5 crontab


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
