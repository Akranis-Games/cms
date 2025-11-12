# Installation Guide

## Voraussetzungen

- PHP >= 8.1
- Composer
- Node.js & npm
- MySQL/MariaDB
- Web-Server (Apache/Nginx)

## Lokale Installation

1. **Dependencies installieren:**
```bash
composer install
npm install
```

2. **Umgebungsvariablen konfigurieren:**
```bash
cp env.txt .env
php artisan key:generate
```

3. **.env Datei anpassen:**
- Datenbankverbindung konfigurieren
- APP_URL setzen
- Pusher Credentials (für Chat) konfigurieren

4. **Datenbank erstellen:**
```bash
php artisan migrate --seed
```

5. **Storage Link erstellen:**
```bash
php artisan storage:link
```

6. **Assets kompilieren:**
```bash
npm run dev
# oder für Produktion:
npm run build
```

7. **Web-Server konfigurieren:**
- Document Root auf `public/` setzen
- URL Rewriting aktivieren

## Deployment auf Server

Für das Deployment auf einen Server siehe [DEPLOYMENT.md](DEPLOYMENT.md)

**Schnellstart mit deploy.sh:**
```bash
chmod +x deploy.sh
./deploy.sh
```

## Standard-Zugangsdaten

Nach dem Seeding:
- **Admin:** admin@example.com / password
- **User:** test@example.com / password

## Feiertags-Animationen

Die Feiertags-Animationen werden automatisch aktiviert, wenn ein aktiver Feiertag in der Datenbank vorhanden ist. Diese können im Admin-Panel verwaltet werden.

## Pusher Setup (für Chat)

1. Pusher Account erstellen
2. Credentials in `.env` eintragen
3. Frontend konfigurieren (siehe `resources/js/app.js`)

## Wichtige Verzeichnisse

Stelle sicher, dass folgende Verzeichnisse existieren und beschreibbar sind:
- `bootstrap/cache`
- `storage/app`
- `storage/framework/cache`
- `storage/framework/sessions`
- `storage/framework/views`
- `storage/logs`

Diese werden automatisch beim Deployment erstellt (siehe `deploy.sh`).
