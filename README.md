# Laravel CMS System

Vollständiges CMS-Webseiten-System auf Laravel-Basis mit modernem Dark/Neon Design.

## Features

- **ACP (Admin Control Panel)** - Vollständiges Administrationspanel
- **UCP (User Control Panel)** - Benutzer-Dashboard
- **Newssystem** - Mit Kategorien und Kommentaren
- **Mediasystem** - Datei-Uploads und Galerien
- **Forumsystem** - Kategorien, Threads und Posts
- **Supportsystem** - Ticket-System
- **Chatsystem** - Echtzeit-Kommunikation (mit optionalem Pusher)
- **Feiertagssettings** - Animationen für alle Feiertage (Schnee, fliegender Santa, etc.)
- **Minecraft Shopsystem** - Produkte, Warenkorb und Bestellungen

## Voraussetzungen

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL/MariaDB
- Web-Server (Apache/Nginx)

## Installation

### Option 1: PHP-basiertes Installations-Script (Empfohlen)

Das PHP-Script funktioniert auf allen Systemen mit PHP und ist zuverlässiger:

```bash
# 1. Composer Dependencies installieren
composer install

# 2. Installations-Script ausführen
php install.php
```

**Vorteile:**
- Funktioniert auf Windows, Linux und macOS
- Direkte Integration mit Laravel/Artisan
- Keine Shell-Script-Probleme
- Bessere Fehlerbehandlung
- Keine Root-Rechte erforderlich (außer für Datenbank-Erstellung)

Das Script führt automatisch aus:
- `.env` Datei erstellen
- App Key generieren
- Datenbank konfigurieren
- Datenbankverbindung testen
- Verzeichnisse erstellen und Berechtigungen setzen
- Migrationen ausführen
- Optional: Datenbank seeden
- Storage Link erstellen
- Cache optimieren
- Assets kompilieren

### Option 2: Shell-Script (Debian 12 Server)

Für Debian 12 Server steht auch ein Shell-Script zur Verfügung, das alle System-Abhängigkeiten installiert:

```bash
# Mit Git Clone
git clone https://github.com/Akranis-Games/cms.git /var/www/html/cms
cd /var/www/html/cms
chmod +x install.sh
sudo ./install.sh
```

Das Shell-Script installiert automatisch:
- PHP 8.2 und alle notwendigen Extensions
- Composer
- Node.js und npm
- MariaDB (MySQL-kompatibel)
- Apache2 (Standard-Konfiguration)
- Git
- Konfiguriert das Projekt
- Richtet die Datenbank ein
- Setzt Berechtigungen

### Option 3: Manuelle Installation

1. **Dependencies installieren:**
```bash
composer install
npm install
```

2. **Umgebungsvariablen konfigurieren:**
```bash
cp env.template .env
php artisan key:generate
```

3. **.env Datei anpassen:**
- `DB_DATABASE` - Datenbankname
- `DB_USERNAME` - Datenbank-Benutzer
- `DB_PASSWORD` - Datenbank-Passwort
- `DB_HOST` - Datenbank-Host (Standard: localhost)
- `APP_URL` - URL der Anwendung

4. **Datenbank erstellen:**
```sql
CREATE DATABASE cms CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER 'cms_user'@'localhost' IDENTIFIED BY 'dein_passwort';
GRANT ALL PRIVILEGES ON cms.* TO 'cms_user'@'localhost';
FLUSH PRIVILEGES;
```

5. **Migrationen ausführen:**
```bash
php artisan migrate --seed
```

6. **Storage Link erstellen:**
```bash
php artisan storage:link
```

7. **Assets kompilieren:**
```bash
# Entwicklung
npm run dev

# Produktion
npm run build
```

8. **Web-Server konfigurieren:**
- Document Root auf `public/` setzen
- URL Rewriting aktivieren (mod_rewrite für Apache)

## Web-Server Konfiguration

### Apache2

**Standard-Konfiguration (empfohlen):**

Bearbeite `/etc/apache2/sites-available/000-default.conf`:

```apache
<VirtualHost *:80>
    ServerAdmin webmaster@localhost
    DocumentRoot /var/www/html/cms/public

    <Directory /var/www/html/cms/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/error.log
    CustomLog ${APACHE_LOG_DIR}/access.log combined

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>
</VirtualHost>
```

**Module aktivieren:**
```bash
a2enmod rewrite
a2enmod headers
a2enmod proxy
a2enmod proxy_fcgi
a2enmod setenvif
systemctl reload apache2
```

### Nginx

```nginx
server {
    listen 80;
    server_name deine-domain.de;
    root /var/www/html/cms/public;

    add_header X-Frame-Options "SAMEORIGIN";
    add_header X-Content-Type-Options "nosniff";

    index index.php;

    charset utf-8;

    location / {
        try_files $uri $uri/ /index.php?$query_string;
    }

    location = /favicon.ico { access_log off; log_not_found off; }
    location = /robots.txt  { access_log off; log_not_found off; }

    error_page 404 /index.php;

    location ~ \.php$ {
        fastcgi_pass unix:/var/run/php/php8.2-fpm.sock;
        fastcgi_param SCRIPT_FILENAME $realpath_root$fastcgi_script_name;
        include fastcgi_params;
    }

    location ~ /\.(?!well-known).* {
        deny all;
    }
}
```

## Pusher Setup (für Echtzeit-Chat)

Der Chat funktioniert auch ohne Pusher (verwendet dann Polling), aber für Echtzeit-Updates:

1. Pusher Account erstellen unter https://pusher.com
2. Credentials in `.env` eintragen:
```env
BROADCAST_DRIVER=pusher
PUSHER_APP_ID=your_app_id
PUSHER_APP_KEY=your_app_key
PUSHER_APP_SECRET=your_app_secret
PUSHER_APP_CLUSTER=your_cluster
```

3. Pusher PHP SDK installieren:
```bash
composer require pusher/pusher-php-server
```

## Verzeichnisse und Berechtigungen

Stelle sicher, dass folgende Verzeichnisse existieren und beschreibbar sind:

```bash
mkdir -p bootstrap/cache
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Berechtigungen setzen (Linux/Unix)
chmod -R 775 storage bootstrap/cache
chown -R www-data:www-data storage bootstrap/cache
```

## Standard-Zugangsdaten

Nach dem Seeding (`php artisan migrate --seed`):
- **Admin:** admin@example.com / password
- **User:** test@example.com / password

## Wichtige Befehle

```bash
# Cache leeren
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear

# Cache optimieren (Produktion)
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Migrationen
php artisan migrate
php artisan migrate:rollback
php artisan migrate:fresh --seed

# Logs ansehen
tail -f storage/logs/laravel.log

# Queue Worker starten (falls verwendet)
php artisan queue:work
```

## Feiertags-Animationen

Die Feiertags-Animationen werden automatisch aktiviert, wenn ein aktiver Feiertag in der Datenbank vorhanden ist. Diese können im Admin-Panel verwaltet werden.

Unterstützte Animationen:
- Schnee (Weihnachten)
- Fliegender Santa (Weihnachten)
- Eier (Ostern)
- Kürbisse (Halloween)
- Und viele mehr...

## Design

Modernes Dark/Neon Design mit:
- Tailwind CSS
- Responsive Layout
- Animierte Feiertags-Effekte
- Moderne UI-Komponenten

## Entwicklung

```bash
# Assets im Watch-Modus kompilieren
npm run dev

# Assets für Produktion kompilieren
npm run build

# Tests ausführen
php artisan test
```

## Support

Bei Problemen:
1. Prüfe die Logs: `storage/logs/laravel.log`
2. Prüfe die `.env` Datei
3. Stelle sicher, dass alle Verzeichnisse existieren und beschreibbar sind
4. Prüfe die Datenbankverbindung

## Lizenz

Dieses Projekt ist für den internen Gebrauch bestimmt.
