# pabalsts.bitkoinos.lv

## nginx konfigurācija
```
server {
	listen 80;
	server_name pabalsts.bitkoinos.lv;
	access_log /var/www/pabalsts.bitkoinos.lv/log/access_log main;
	error_log /var/www/pabalsts.bitkoinos.lv/log/error_log info;
	include /etc/nginx/letsencrypt-acme-challenge.conf;
	location / {
		return 301 https://$host$request_uri;
	}
}
server {
	listen 443 ssl;
	server_name pabalsts.bitkoinos.lv;
	add_header Referrer-Policy "origin" always;
	add_header Strict-Transport-Security "max-age=31536000" always;
	add_header X-Frame-Options "SAMEORIGIN" always;
	ssl_certificate /etc/letsencrypt/live/pabalsts.bitkoinos.lv/fullchain.pem;
	ssl_certificate_key /etc/letsencrypt/live/pabalsts.bitkoinos.lv/privkey.pem;
	access_log /var/www/pabalsts.bitkoinos.lv/log/access_log main;
	error_log /var/www/pabalsts.bitkoinos.lv/log/error_log info;
	root /var/www/pabalsts.bitkoinos.lv/htdocs;
	location / {
		try_files /index.php =404;
		include /etc/nginx/fastcgi.conf;
		fastcgi_pass unix:/run/php-fpm.socket;
	}
	location /css/ {
	}
	location /favicon.ico {
	}
	location /js/ {
	}
}
```
