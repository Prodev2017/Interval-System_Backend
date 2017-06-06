## Dependencies
- PHP(with curl) >= 5.6.4
- libcurl >= 7.10.5
- OpenSSL PHP Extension
- PDO PHP Extension
- Mbstring PHP Extension
- Tokenizer PHP Extension
- XML PHP Extension
- MySQL
## Settings
Edit in file .env:
- API URL:
```$xslt
APP_URL=http://interval.app
```
- Database parameters:
```$xslt
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=interval
DB_USERNAME=homestead
DB_PASSWORD=secret
```
- Exchange parameters with INTERVALS for the user with maximum permissions:
```$xslt
INTERVALS_ACCESS_TOKEN=access_token
INTERVALS_ACCESS_TOKEN=password
```
- Mail parameters:
```$xslt
MAIL_ADDRESS=report@example.com
MAIL_DRIVER=mail_driver
MAIL_HOST=mail_host
MAIL_PORT=2525
MAIL_USERNAME=username
MAIL_PASSWORD=password
MAIL_ENCRYPTION=null
```
Add to CRON:
```$xslt
* * * * * php /path-to-your-project/artisan schedule:run >> /dev/null 2>&1
```
## Deploying the project
```$xslt
composer install
```
```$xslt
cp .env.example .env
```
```$xslt
php artisan key:generate
```
```$xslt
php artisan migrate
```
## API DOC
```$xslt
/doc/index.html
```
