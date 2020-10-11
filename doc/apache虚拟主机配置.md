## apache 虚拟主机配置示例

```
<VirtualHost *:80>
	ServerAdmin webmaster@localhost
	ServerName www.venuscmf.com

	DocumentRoot /var/www/venuscmf_tp5/public
	<Directory />
		Options FollowSymLinks
		AllowOverride All
	</Directory>
	<Directory /var/www/venuscmf_tp5/public>
		Options FollowSymLinks MultiViews
		AllowOverride All
		Order allow,deny
		allow from all
	</Directory>
</VirtualHost>
```