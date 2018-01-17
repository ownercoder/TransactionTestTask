Для проверки решения необходим хостинг с поддержкой ssl и php >= 7.1

```
Пользователь/Пароль: admin/mysuperpassword
```

Необходимо переименовать config/config.yaml.example в config/config.yaml и заполнить актуальными данными.
Для установки зависимостей нужно запустить
```
composer install
```
Веб сервер должен быть направлен в папку public.

MySQL dump находится в ```users.sql``` и необходим для работы приложения.

Конфиг сервера nginx:
```
server {
    listen 80;
    listen 443 ssl http2;
    server_name .transactiontest.app;
    root "/home/vagrant/transactionTest/public";

    index index.html index.htm index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    access_log off;
    error_log  /var/log/nginx/project.app-error.log error;

    sendfile off;

    client_max_body_size 100m;

    location ~ \.php$ {
        fastcgi_split_path_info ^(.+\.php)(/.+)$;
        fastcgi_pass unix:/var/run/php/php7.1-fpm.sock;
        fastcgi_index index.php;
        include fastcgi_params;
        fastcgi_param SCRIPT_FILENAME $document_root$fastcgi_script_name;


        fastcgi_intercept_errors off;
        fastcgi_buffer_size 16k;
        fastcgi_buffers 4 16k;
        fastcgi_connect_timeout 300;
        fastcgi_send_timeout 300;
        fastcgi_read_timeout 300;
    }

    location ~ /\.ht {
        deny all;
    }

    ssl_certificate     /etc/nginx/ssl/cert.app.crt;
    ssl_certificate_key /etc/nginx/ssl/cert.app.key;
}
```
