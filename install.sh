#!/bin/bash

# Laravel CMS Installation Script für Debian 12
# Dieses Script installiert alle notwendigen Abhängigkeiten und richtet das CMS ein

set -e  # Exit bei Fehlern

# Farben für Output
RED='\033[0;31m'
GREEN='\033[0;32m'
YELLOW='\033[1;33m'
BLUE='\033[0;34m'
NC='\033[0m' # No Color

# Funktionen
print_header() {
    echo ""
    echo -e "${BLUE}========================================${NC}"
    echo -e "${BLUE}$1${NC}"
    echo -e "${BLUE}========================================${NC}"
    echo ""
}

print_success() {
    echo -e "${GREEN}✓ $1${NC}"
}

print_error() {
    echo -e "${RED}✗ $1${NC}"
}

print_warning() {
    echo -e "${YELLOW}⚠ $1${NC}"
}

print_info() {
    echo -e "${BLUE}ℹ $1${NC}"
}

# Prüfe ob als root ausgeführt
if [ "$EUID" -ne 0 ]; then 
    print_error "Bitte als root ausführen: sudo ./install.sh"
    exit 1
fi

print_header "Laravel CMS Installation für Debian 12 (Apache2)"

# System aktualisieren
print_header "System aktualisieren"
apt-get update
apt-get install -y lsb-release curl wget
apt-get upgrade -y
print_success "System aktualisiert"

# PHP 8.2 und notwendige Extensions installieren
print_header "PHP 8.2 installieren"

# Prüfe Debian Version
DEBIAN_VERSION=$(lsb_release -rs 2>/dev/null || echo "12")

if [ "$DEBIAN_VERSION" = "12" ]; then
    # Debian 12 - PHP 8.2 ist im Standard-Repository
    apt-get install -y \
        php8.2 \
        php8.2-cli \
        php8.2-fpm \
        php8.2-common \
        php8.2-mysql \
        php8.2-zip \
        php8.2-gd \
        php8.2-mbstring \
        php8.2-curl \
        php8.2-xml \
        php8.2-bcmath \
        php8.2-intl \
        php8.2-readline \
        php8.2-opcache
else
    # Für andere Debian-Versionen oder Ubuntu
    apt-get install -y software-properties-common lsb-release
    add-apt-repository -y ppa:ondrej/php
    apt-get update
    apt-get install -y \
        php8.2 \
        php8.2-cli \
        php8.2-fpm \
        php8.2-common \
        php8.2-mysql \
        php8.2-zip \
        php8.2-gd \
        php8.2-mbstring \
        php8.2-curl \
        php8.2-xml \
        php8.2-bcmath \
        php8.2-intl \
        php8.2-readline \
        php8.2-opcache
fi

print_success "PHP 8.2 installiert"

# Composer installieren
print_header "Composer installieren"
if ! command -v composer &> /dev/null; then
    # Prüfe ob curl installiert ist
    if ! command -v curl &> /dev/null; then
        print_info "curl nicht gefunden, installiere curl..."
        apt-get install -y curl
    fi
    
    print_info "Lade Composer herunter..."
    cd /tmp
    curl -sS https://getcomposer.org/installer | php
    if [ -f "composer.phar" ]; then
        mv composer.phar /usr/local/bin/composer
        chmod +x /usr/local/bin/composer
        cd - > /dev/null
        print_success "Composer installiert"
    else
        cd - > /dev/null
        print_error "Fehler beim Herunterladen von Composer!"
        exit 1
    fi
else
    print_info "Composer bereits installiert"
fi

# Node.js und npm installieren
print_header "Node.js installieren"
if ! command -v node &> /dev/null; then
    # Prüfe ob curl installiert ist
    if ! command -v curl &> /dev/null; then
        print_info "curl nicht gefunden, installiere curl..."
        apt-get install -y curl
    fi
    
    print_info "Lade Node.js Setup Script herunter..."
    curl -fsSL https://deb.nodesource.com/setup_20.x | bash -
    apt-get install -y nodejs
    print_success "Node.js installiert"
else
    print_info "Node.js bereits installiert"
fi

# MariaDB installieren und konfigurieren
print_header "MariaDB installieren"
if ! command -v mariadb &> /dev/null && ! command -v mysql &> /dev/null; then
    apt-get install -y mariadb-server mariadb-client
    systemctl start mariadb
    systemctl enable mariadb
    
    # MariaDB Root-Passwort setzen (falls nicht gesetzt)
    print_warning "MariaDB Root-Passwort wird benötigt"
    read -sp "MariaDB Root-Passwort eingeben (Enter für Standard 'root'): " MYSQL_ROOT_PASS
    echo ""
    
    if [ -z "$MYSQL_ROOT_PASS" ]; then
        MYSQL_ROOT_PASS="root"
    fi
    
    # Setze Root-Passwort
    mysql -e "ALTER USER 'root'@'localhost' IDENTIFIED BY '${MYSQL_ROOT_PASS}';" 2>/dev/null || \
    mysql -e "SET PASSWORD FOR 'root'@'localhost' = PASSWORD('${MYSQL_ROOT_PASS}');" 2>/dev/null || true
    mysql -e "FLUSH PRIVILEGES;" 2>/dev/null || true
    
    print_success "MariaDB installiert"
    print_info "Führe 'mariadb-secure-installation' aus, um die Sicherheitseinstellungen zu konfigurieren (optional)"
else
    print_info "MariaDB/MySQL bereits installiert"
fi

# Apache2 installieren
print_header "Apache2 installieren"
if ! command -v apache2 &> /dev/null; then
    apt-get install -y apache2
    systemctl start apache2
    systemctl enable apache2
    print_success "Apache2 installiert"
else
    print_info "Apache2 bereits installiert"
fi

# Apache2 Module aktivieren
a2enmod rewrite
a2enmod headers
a2enmod proxy
a2enmod proxy_fcgi
a2enmod setenvif
systemctl restart apache2
print_success "Apache2 Module aktiviert"

# Git installieren
print_header "Git installieren"
apt-get install -y git
print_success "Git installiert"

# Projekt-Verzeichnis bestimmen
print_header "Projekt-Verzeichnis konfigurieren"
read -p "Projekt-Verzeichnis [/var/www/html/cms]: " PROJECT_DIR
PROJECT_DIR=${PROJECT_DIR:-/var/www/html/cms}

# Standard Git Repository URL
DEFAULT_GIT_REPO="https://github.com/Akranis-Games/cms.git"

# Prüfe ob Verzeichnis existiert und ob es leer ist
if [ ! -d "$PROJECT_DIR" ]; then
    # Verzeichnis existiert nicht - Frage nach Git Clone
    print_info "Verzeichnis $PROJECT_DIR existiert nicht."
    read -p "Soll das Projekt aus einem Git-Repository geklont werden? (Y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Nn]$ ]]; then
        read -p "Git Repository URL [${DEFAULT_GIT_REPO}]: " GIT_REPO_URL
        GIT_REPO_URL=${GIT_REPO_URL:-$DEFAULT_GIT_REPO}
        if [ -z "$GIT_REPO_URL" ]; then
            print_error "Keine Git Repository URL angegeben!"
            exit 1
        fi
        
        # Parent-Verzeichnis erstellen falls nötig
        PARENT_DIR=$(dirname "$PROJECT_DIR")
        mkdir -p "$PARENT_DIR"
        
        # Repository klonen
        print_info "Klone Repository..."
        git clone "$GIT_REPO_URL" "$PROJECT_DIR"
        if [ $? -eq 0 ]; then
            print_success "Repository geklont"
        else
            print_error "Fehler beim Klonen des Repositories!"
            exit 1
        fi
    else
        # Verzeichnis erstellen
        mkdir -p "$PROJECT_DIR"
        print_success "Verzeichnis erstellt"
    fi
elif [ -d "$PROJECT_DIR" ] && [ -z "$(ls -A $PROJECT_DIR 2>/dev/null)" ]; then
    # Verzeichnis existiert aber ist leer
    print_info "Verzeichnis $PROJECT_DIR existiert, ist aber leer."
    read -p "Soll das Projekt aus einem Git-Repository geklont werden? (Y/n): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Nn]$ ]]; then
        read -p "Git Repository URL [${DEFAULT_GIT_REPO}]: " GIT_REPO_URL
        GIT_REPO_URL=${GIT_REPO_URL:-$DEFAULT_GIT_REPO}
        if [ -z "$GIT_REPO_URL" ]; then
            print_error "Keine Git Repository URL angegeben!"
            exit 1
        fi
        
        # Repository klonen
        print_info "Klone Repository..."
        git clone "$GIT_REPO_URL" "$PROJECT_DIR"
        if [ $? -eq 0 ]; then
            print_success "Repository geklont"
        else
            print_error "Fehler beim Klonen des Repositories!"
            exit 1
        fi
    else
        print_info "Verwende leeres Verzeichnis"
    fi
elif [ -d "$PROJECT_DIR/.git" ]; then
    # Git Repository bereits vorhanden
    print_info "Git Repository bereits vorhanden. Aktualisiere..."
    cd "$PROJECT_DIR"
    git pull
    print_success "Repository aktualisiert"
else
    # Verzeichnis existiert mit Inhalt, aber kein Git Repository
    print_info "Verzeichnis $PROJECT_DIR existiert bereits mit Inhalt."
    print_info "Verwende vorhandenes Verzeichnis."
fi

cd "$PROJECT_DIR"

# Prüfe ob composer.json existiert
if [ ! -f "composer.json" ]; then
    print_error "composer.json nicht gefunden!"
    print_error "Stelle sicher, dass das Projekt-Verzeichnis korrekt ist oder das Repository geklont wurde."
    exit 1
fi

# Composer Dependencies installieren
print_header "Composer Dependencies installieren"
composer install --no-dev --optimize-autoloader --no-interaction
print_success "Composer Dependencies installiert"

# Node Dependencies installieren
print_header "Node Dependencies installieren"
if [ -f "package.json" ]; then
    npm install
    print_success "Node Dependencies installiert"
else
    print_warning "package.json nicht gefunden, überspringe npm install"
fi

# .env Datei erstellen
print_header ".env Datei konfigurieren"
if [ ! -f ".env" ]; then
    if [ -f "env.template" ]; then
        cp env.template .env
        print_success ".env aus env.template erstellt"
    elif [ -f ".env.example" ]; then
        cp .env.example .env
        print_success ".env aus .env.example erstellt"
    else
        print_error "Keine .env Vorlage gefunden!"
        exit 1
    fi
else
    print_warning ".env existiert bereits, überspringe Erstellung"
fi

# App Key generieren
print_header "App Key generieren"
php artisan key:generate --force
print_success "App Key generiert"

# Datenbank konfigurieren
print_header "Datenbank konfigurieren"
read -p "Datenbankname [cms]: " DB_NAME
DB_NAME=${DB_NAME:-cms}

read -p "Datenbank-Benutzer [cms_user]: " DB_USER
DB_USER=${DB_USER:-cms_user}

read -sp "Datenbank-Passwort: " DB_PASS
echo ""

# Datenbank erstellen
print_info "Erstelle Datenbank..."

# Versuche zuerst mit Passwort
if [ -n "$MYSQL_ROOT_PASS" ] && [ "$MYSQL_ROOT_PASS" != "" ]; then
    mysql -u root -p"${MYSQL_ROOT_PASS}" <<EOF 2>/dev/null
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOF
    DB_CREATE_STATUS=$?
else
    DB_CREATE_STATUS=1
fi

# Falls mit Passwort fehlgeschlagen, versuche ohne Passwort
if [ $DB_CREATE_STATUS -ne 0 ]; then
    print_warning "Versuche ohne Passwort..."
    mysql -u root <<EOF 2>/dev/null
CREATE DATABASE IF NOT EXISTS ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;
CREATE USER IF NOT EXISTS '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';
GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';
FLUSH PRIVILEGES;
EOF
    DB_CREATE_STATUS=$?
fi

if [ $DB_CREATE_STATUS -eq 0 ]; then
    print_success "Datenbank erstellt"
else
    print_error "Fehler beim Erstellen der Datenbank!"
    print_info "Bitte erstelle die Datenbank manuell:"
    echo "  mysql -u root -p"
    echo "  CREATE DATABASE ${DB_NAME} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;"
    echo "  CREATE USER '${DB_USER}'@'localhost' IDENTIFIED BY '${DB_PASS}';"
    echo "  GRANT ALL PRIVILEGES ON ${DB_NAME}.* TO '${DB_USER}'@'localhost';"
    echo "  FLUSH PRIVILEGES;"
    read -p "Drücke Enter um fortzufahren (Datenbank muss manuell erstellt werden)..."
fi

# .env Datei aktualisieren
sed -i "s/DB_DATABASE=.*/DB_DATABASE=${DB_NAME}/" .env
sed -i "s/DB_USERNAME=.*/DB_USERNAME=${DB_USER}/" .env
sed -i "s/DB_PASSWORD=.*/DB_PASSWORD=${DB_PASS}/" .env

# APP_URL konfigurieren
read -p "APP_URL [http://localhost]: " APP_URL
APP_URL=${APP_URL:-http://localhost}
sed -i "s|APP_URL=.*|APP_URL=${APP_URL}|" .env

# Verzeichnisse erstellen und Berechtigungen setzen
print_header "Verzeichnisse und Berechtigungen konfigurieren"
mkdir -p bootstrap/cache
mkdir -p storage/app
mkdir -p storage/app/public
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

# Setze Berechtigungen
chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
chmod -R 775 storage bootstrap/cache

# Stelle sicher, dass www-data Schreibrechte hat
if id "www-data" &>/dev/null; then
    chown -R www-data:www-data storage bootstrap/cache 2>/dev/null || true
fi

print_success "Berechtigungen gesetzt"

# Datenbank migrieren
print_header "Datenbank migrieren"
php artisan migrate --force
print_success "Migrationen ausgeführt"

# Datenbank seeden
read -p "Datenbank mit Testdaten seeden? (y/N): " -n 1 -r
echo
if [[ $REPLY =~ ^[Yy]$ ]]; then
    php artisan db:seed --force
    print_success "Datenbank geseedet"
fi

# Storage Link erstellen
print_header "Storage Link erstellen"
php artisan storage:link
print_success "Storage Link erstellt"

# Cache optimieren
print_header "Cache optimieren"

# Erstelle View-Verzeichnis falls nicht vorhanden
mkdir -p storage/framework/views
chmod -R 775 storage/framework/views
chown -R www-data:www-data storage/framework/views 2>/dev/null || true

# Cache leeren bevor neu erstellt
php artisan config:clear 2>/dev/null || true
php artisan route:clear 2>/dev/null || true

# View Cache leeren (mit Fehlerbehandlung)
if [ -d "storage/framework/views" ]; then
    php artisan view:clear 2>/dev/null || true
else
    print_warning "View-Verzeichnis fehlt, überspringe view:clear"
fi

# Cache neu erstellen
php artisan config:cache
if [ $? -eq 0 ]; then
    print_success "Config Cache erstellt"
else
    print_warning "Config Cache konnte nicht erstellt werden"
fi

php artisan route:cache
if [ $? -eq 0 ]; then
    print_success "Route Cache erstellt"
else
    print_warning "Route Cache konnte nicht erstellt werden (möglicherweise Route-Konflikte)"
fi

# View Cache nur erstellen wenn Verzeichnis existiert
if [ -d "storage/framework/views" ]; then
    php artisan view:cache 2>/dev/null
    if [ $? -eq 0 ]; then
        print_success "View Cache erstellt"
    else
        print_warning "View Cache konnte nicht erstellt werden (nicht kritisch)"
    fi
else
    print_warning "View-Verzeichnis fehlt, überspringe view:cache"
fi

# Assets kompilieren
print_header "Assets kompilieren"
if [ -f "package.json" ]; then
    npm run build
    print_success "Assets kompiliert"
fi

# Apache2 Virtual Host erstellen
print_header "Apache2 Virtual Host erstellen"
read -p "Domain/Server-Name [localhost]: " SERVER_NAME
SERVER_NAME=${SERVER_NAME:-localhost}

APACHE_CONFIG="/etc/apache2/sites-available/cms.conf"
cat > "$APACHE_CONFIG" <<EOF
<VirtualHost *:80>
    ServerName ${SERVER_NAME}
    ServerAdmin webmaster@${SERVER_NAME}
    DocumentRoot ${PROJECT_DIR}/public

    <Directory ${PROJECT_DIR}/public>
        Options -Indexes +FollowSymLinks
        AllowOverride All
        Require all granted
    </Directory>

    ErrorLog \${APACHE_LOG_DIR}/cms_error.log
    CustomLog \${APACHE_LOG_DIR}/cms_access.log combined

    <FilesMatch \.php$>
        SetHandler "proxy:unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost"
    </FilesMatch>

    <Proxy "unix:/var/run/php/php8.2-fpm.sock|fcgi://localhost">
        ProxySet connectiontimeout=5 timeout=240
    </Proxy>
</VirtualHost>
EOF

# Apache2 Site aktivieren
a2ensite cms.conf
a2dissite 000-default.conf 2>/dev/null || true

# Apache2 Konfiguration testen
apache2ctl configtest
if [ $? -eq 0 ]; then
    systemctl reload apache2
    print_success "Apache2 konfiguriert und neu geladen"
else
    print_error "Apache2 Konfiguration fehlerhaft!"
fi

# PHP-FPM neu starten
systemctl restart php8.2-fpm
print_success "PHP-FPM neu gestartet"

# Zusammenfassung
print_header "Installation abgeschlossen!"
echo ""
echo -e "${GREEN}Zusammenfassung:${NC}"
echo "  Projekt-Verzeichnis: $PROJECT_DIR"
if [ -d "$PROJECT_DIR/.git" ]; then
    echo "  Git Repository: $(cd $PROJECT_DIR && git remote get-url origin 2>/dev/null || echo 'Lokal')"
fi
echo "  Datenbank: $DB_NAME"
echo "  Datenbank-Benutzer: $DB_USER"
echo "  Server-Name: $SERVER_NAME"
echo ""
echo -e "${YELLOW}Nächste Schritte:${NC}"
echo "  1. Prüfe die .env Datei: ${PROJECT_DIR}/.env"
echo "  2. Teste die Anwendung: http://${SERVER_NAME}"
echo "  3. Standard-Zugangsdaten (falls geseedet):"
echo "     - Admin: admin@example.com / password"
echo "     - User: test@example.com / password"
echo ""
echo -e "${BLUE}Wichtige Befehle:${NC}"
echo "  Logs ansehen: tail -f ${PROJECT_DIR}/storage/logs/laravel.log"
echo "  Cache leeren: cd ${PROJECT_DIR} && php artisan cache:clear"
echo "  Apache2 neu laden: systemctl reload apache2"
echo "  Apache2 Logs: tail -f /var/log/apache2/cms_error.log"
echo ""

