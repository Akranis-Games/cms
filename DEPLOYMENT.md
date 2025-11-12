# Deployment-Anleitung

## Schnellstart für Debian 12

Für eine vollständige automatische Installation auf Debian 12:

**Option 1: Mit Git Clone (empfohlen)**
```bash
git clone https://github.com/Akranis-Games/cms.git /var/www/html/cms
cd /var/www/html/cms
chmod +x install.sh
sudo ./install.sh
```

**Option 2: Script klont automatisch**
```bash
# Lade install.sh herunter
wget https://raw.githubusercontent.com/Akranis-Games/cms/main/install.sh
chmod +x install.sh
sudo ./install.sh
# Script fragt nach Git Repository URL (Standard: https://github.com/Akranis-Games/cms.git)
```

Das Script installiert alle Abhängigkeiten und richtet das System automatisch ein.

## Manuelle Installation

## Voraussetzungen für das Deployment

### 1. Verzeichnisse erstellen

Stelle sicher, dass folgende Verzeichnisse existieren und beschreibbar sind:

```bash
mkdir -p bootstrap/cache
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs
```

### 2. Berechtigungen setzen

Setze die korrekten Berechtigungen für Laravel:

```bash
# Für Linux/Unix-Server:
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Falls nötig, ändere den Besitzer:
chown -R www-data:www-data storage
chown -R www-data:www-data bootstrap/cache
```

### 3. Deployment-Schritte

1. **Code hochladen**
   ```bash
   git clone <repository-url>
   cd cms
   ```

2. **Dependencies installieren**
   ```bash
   composer install --no-dev --optimize-autoloader
   ```
   
   **WICHTIG:** Pusher ist optional. Wenn du den Chat mit Echtzeit-Updates verwenden möchtest, installiere Pusher separat:
   ```bash
   composer require pusher/pusher-php-server
   ```

3. **Umgebungsvariablen konfigurieren**
   ```bash
   # Falls env.template existiert:
   cp env.template .env
   
   # Oder erstelle .env manuell und kopiere den Inhalt aus env.template
   # Bearbeite .env mit deinen Einstellungen (Datenbank, APP_URL, etc.)
   
   # Generiere App Key
   php artisan key:generate
   ```
   
   **WICHTIG:** Stelle sicher, dass die `.env` Datei existiert, bevor du `php artisan key:generate` ausführst!

4. **Datenbank migrieren**
   ```bash
   php artisan migrate --force
   php artisan db:seed --force
   ```

5. **Cache optimieren**
   ```bash
   php artisan config:cache
   php artisan route:cache
   php artisan view:cache
   ```

6. **Storage-Link erstellen**
   ```bash
   php artisan storage:link
   ```

7. **Assets kompilieren**
   ```bash
   npm install
   npm run build
   ```

### 4. Pusher Setup (Optional - nur für Echtzeit-Chat)

Wenn du den Chat mit Echtzeit-Updates verwenden möchtest:

1. Pusher Account erstellen unter https://pusher.com
2. Pusher Paket installieren:
   ```bash
   composer require pusher/pusher-php-server
   ```
3. Pusher Credentials in `.env` eintragen:
   ```
   BROADCAST_DRIVER=pusher
   PUSHER_APP_ID=your_app_id
   PUSHER_APP_KEY=your_app_key
   PUSHER_APP_SECRET=your_app_secret
   PUSHER_APP_CLUSTER=your_cluster
   ```

**Ohne Pusher:** Der Chat funktioniert auch ohne Pusher, verwendet dann Polling (Auto-Refresh alle 5 Sekunden).

### 5. Server-Konfiguration

#### Apache (.htaccess sollte bereits vorhanden sein)

Stelle sicher, dass `mod_rewrite` aktiviert ist.

#### Apache2

```apache
<VirtualHost *:80>
    ServerName deine-domain.de
    ServerAdmin webmaster@deine-domain.de
    DocumentRoot /var/www/html/cms/public

    <Directory /var/www/html/cms/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog ${APACHE_LOG_DIR}/cms_error.log
    CustomLog ${APACHE_LOG_DIR}/cms_access.log combined

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>

    <Proxy "unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost">
        ProxySet connectiontimeout=5 timeout=240
    </Proxy>
</VirtualHost>
```

**Apache2 Module aktivieren:**
```bash
a2enmod rewrite
a2enmod headers
a2enmod proxy
a2enmod proxy_fcgi
a2enmod setenvif
a2ensite cms.conf
systemctl reload apache2
```

#### Nginx (Alternative)

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

### 6. Fehlerbehebung

#### Problem: "bootstrap/cache directory must be present and writable"

**Lösung:**
```bash
mkdir -p bootstrap/cache
chmod 775 bootstrap/cache
chown www-data:www-data bootstrap/cache
```

#### Problem: "Class Pusher\Pusher not found"

**Lösung:**
Dieser Fehler tritt auf, wenn Pusher nicht installiert ist, aber als Broadcasting-Driver konfiguriert ist.

**Option 1:** Pusher installieren (für Echtzeit-Chat)
```bash
composer require pusher/pusher-php-server
```

**Option 2:** Broadcasting auf 'log' setzen (Standard, funktioniert ohne Pusher)
```bash
# In .env:
BROADCAST_DRIVER=log
```

Die Standard-Konfiguration verwendet bereits 'log' als Broadcasting-Driver, daher sollte dieser Fehler nicht auftreten.

#### Problem: Storage-Verzeichnisse nicht beschreibbar

**Lösung:**
```bash
chmod -R 775 storage
chown -R www-data:www-data storage
```

#### Problem: Composer-Installation schlägt fehl

**Lösung:**
```bash
# Cache leeren
composer clear-cache

# Neu installieren
composer install --no-dev --optimize-autoloader
```

### 7. Automatisches Deployment-Script

Erstelle eine Datei `deploy.sh`:

```bash
#!/bin/bash

# Farben für Output
GREEN='\033[0;32m'
RED='\033[0;31m'
NC='\033[0m' # No Color

echo -e "${GREEN}Starte Deployment...${NC}"

# Verzeichnisse erstellen
echo -e "${GREEN}Erstelle Verzeichnisse...${NC}"
mkdir -p bootstrap/cache
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Berechtigungen setzen
echo -e "${GREEN}Setze Berechtigungen...${NC}"
chmod -R 775 storage
chmod -R 775 bootstrap/cache

# Composer installieren
echo -e "${GREEN}Installiere Dependencies...${NC}"
composer install --no-dev --optimize-autoloader

# Cache optimieren
echo -e "${GREEN}Optimiere Cache...${NC}"
php artisan config:cache
php artisan route:cache
php artisan view:cache

# Storage-Link
echo -e "${GREEN}Erstelle Storage-Link...${NC}"
php artisan storage:link

echo -e "${GREEN}Deployment abgeschlossen!${NC}"
```

### 8. Wichtige Hinweise

- **Produktions-Umgebung:** Setze `APP_DEBUG=false` in der `.env`
- **Sicherheit:** Stelle sicher, dass `.env` nicht öffentlich zugänglich ist
- **Backups:** Erstelle regelmäßig Backups der Datenbank
- **Updates:** Halte Laravel und Dependencies aktuell
- **Pusher:** Ist optional - das System funktioniert auch ohne Pusher

### 9. Nach dem Deployment

1. Teste alle Funktionen
2. Überprüfe die Logs: `storage/logs/laravel.log`
3. Stelle sicher, dass alle Assets geladen werden
4. Teste die Datenbankverbindung
5. Wenn Pusher verwendet wird, teste den Chat
