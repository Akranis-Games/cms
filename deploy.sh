#!/bin/bash

# Laravel CMS Deployment Script
# Dieses Script erstellt alle notwendigen Verzeichnisse und konfiguriert die Umgebung

# Farben für Output
GREEN='\033[0;32m'
RED='\033[0;31m'
YELLOW='\033[1;33m'
NC='\033[0m' # No Color

echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Laravel CMS Deployment Script${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""

# 1. Verzeichnisse erstellen
echo -e "${YELLOW}[1/7] Erstelle notwendige Verzeichnisse...${NC}"
mkdir -p bootstrap/cache
mkdir -p storage/app
mkdir -p storage/framework/cache
mkdir -p storage/framework/sessions
mkdir -p storage/framework/views
mkdir -p storage/logs

if [ $? -eq 0 ]; then
    echo -e "${GREEN}✓ Verzeichnisse erstellt${NC}"
else
    echo -e "${RED}✗ Fehler beim Erstellen der Verzeichnisse${NC}"
    exit 1
fi

# 2. Berechtigungen setzen
echo -e "${YELLOW}[2/7] Setze Berechtigungen...${NC}"
chmod -R 775 storage 2>/dev/null
chmod -R 775 bootstrap/cache 2>/dev/null

# Versuche www-data als Besitzer zu setzen (falls verfügbar)
if id "www-data" &>/dev/null; then
    chown -R www-data:www-data storage 2>/dev/null
    chown -R www-data:www-data bootstrap/cache 2>/dev/null
fi

echo -e "${GREEN}✓ Berechtigungen gesetzt${NC}"

# 3. .env Datei prüfen
echo -e "${YELLOW}[3/7] Prüfe .env Datei...${NC}"
if [ ! -f .env ]; then
    if [ -f env.txt ]; then
        cp env.txt .env
        echo -e "${GREEN}✓ .env aus env.txt erstellt${NC}"
    else
        echo -e "${RED}✗ Keine .env Datei gefunden. Bitte manuell erstellen.${NC}"
    fi
else
    echo -e "${GREEN}✓ .env Datei vorhanden${NC}"
fi

# 4. Composer Dependencies
echo -e "${YELLOW}[4/7] Installiere Composer Dependencies...${NC}"
if command -v composer &> /dev/null; then
    composer install --no-dev --optimize-autoloader --no-interaction
    if [ $? -eq 0 ]; then
        echo -e "${GREEN}✓ Dependencies installiert${NC}"
    else
        echo -e "${RED}✗ Fehler bei Composer Installation${NC}"
        exit 1
    fi
else
    echo -e "${RED}✗ Composer nicht gefunden${NC}"
    exit 1
fi

# 5. Laravel Key generieren (falls nicht vorhanden)
echo -e "${YELLOW}[5/7] Prüfe App Key...${NC}"
if grep -q "APP_KEY=$" .env 2>/dev/null || ! grep -q "APP_KEY=" .env 2>/dev/null; then
    php artisan key:generate --force
    echo -e "${GREEN}✓ App Key generiert${NC}"
else
    echo -e "${GREEN}✓ App Key bereits vorhanden${NC}"
fi

# 6. Cache optimieren
echo -e "${YELLOW}[6/7] Optimiere Cache...${NC}"
php artisan config:cache --quiet
php artisan route:cache --quiet
php artisan view:cache --quiet
echo -e "${GREEN}✓ Cache optimiert${NC}"

# 7. Storage-Link erstellen
echo -e "${YELLOW}[7/7] Erstelle Storage-Link...${NC}"
php artisan storage:link --quiet
echo -e "${GREEN}✓ Storage-Link erstellt${NC}"

echo ""
echo -e "${GREEN}========================================${NC}"
echo -e "${GREEN}Deployment erfolgreich abgeschlossen!${NC}"
echo -e "${GREEN}========================================${NC}"
echo ""
echo -e "${YELLOW}Nächste Schritte:${NC}"
echo "1. Bearbeite die .env Datei mit deinen Einstellungen"
echo "2. Führe 'php artisan migrate --seed' aus"
echo "3. Kompiliere Assets mit 'npm install && npm run build'"
echo "4. Teste die Anwendung"

