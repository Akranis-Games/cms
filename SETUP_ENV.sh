#!/bin/bash

# Script zum Erstellen der .env Datei

echo "Erstelle .env Datei..."

# Prüfe ob .env bereits existiert
if [ -f .env ]; then
    echo "WARNUNG: .env Datei existiert bereits!"
    read -p "Möchtest du sie überschreiben? (y/N): " -n 1 -r
    echo
    if [[ ! $REPLY =~ ^[Yy]$ ]]; then
        echo "Abgebrochen."
        exit 1
    fi
fi

# Prüfe ob env.template existiert
if [ -f env.template ]; then
    cp env.template .env
    echo "✓ .env aus env.template erstellt"
elif [ -f .env.example ]; then
    cp .env.example .env
    echo "✓ .env aus .env.example erstellt"
else
    echo "FEHLER: Weder env.template noch .env.example gefunden!"
    echo "Bitte erstelle die .env Datei manuell."
    exit 1
fi

# Generiere App Key
echo "Generiere App Key..."
php artisan key:generate

if [ $? -eq 0 ]; then
    echo "✓ App Key erfolgreich generiert"
    echo ""
    echo "Nächste Schritte:"
    echo "1. Bearbeite die .env Datei mit deinen Einstellungen:"
    echo "   - DB_DATABASE, DB_USERNAME, DB_PASSWORD"
    echo "   - APP_URL"
    echo "   - Mail-Einstellungen (falls benötigt)"
    echo ""
    echo "2. Führe die Migrationen aus:"
    echo "   php artisan migrate --seed"
else
    echo "✗ Fehler beim Generieren des App Keys"
    exit 1
fi

