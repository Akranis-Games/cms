# XAMPP Installation Guide

Diese Anleitung zeigt dir, wie du das Laravel CMS mit XAMPP lokal einrichtest.

## Voraussetzungen

- XAMPP installiert (mit PHP >= 8.1)
- Composer installiert
- Node.js & npm installiert

## Schritt 1: XAMPP vorbereiten

1. **XAMPP starten**
   - Starte XAMPP Control Panel
   - Starte Apache und MySQL

2. **PHP-Version prüfen**
   ```bash
   php -v
   ```
   Stelle sicher, dass PHP >= 8.1 installiert ist. Falls nicht, aktualisiere XAMPP.

## Schritt 2: Projekt einrichten

1. **Projekt in htdocs kopieren**
   
   Option A: Direkt in htdocs klonen/entpacken
   ```
   C:\xampp\htdocs\cms\
   ```
   
   Option B: Symlink erstellen (empfohlen)
   ```bash
   # Erstelle einen Symlink von deinem Projekt-Verzeichnis nach htdocs
   mklink /D C:\xampp\htdocs\cms C:\Users\drago\Documents\Work\www\cms
   ```

2. **In das Projekt-Verzeichnis wechseln**
   ```bash
   cd C:\xampp\htdocs\cms
   # oder
   cd C:\Users\drago\Documents\Work\www\cms
   ```

## Schritt 3: Dependencies installieren

1. **Composer Dependencies**
   ```bash
   composer install
   ```

2. **Node.js Dependencies**
   ```bash
   npm install
   ```

## Schritt 4: Datenbank einrichten

1. **phpMyAdmin öffnen**
   - Öffne http://localhost/phpmyadmin im Browser

2. **Neue Datenbank erstellen**
   - Klicke auf "Neu" oder "New"
   - Datenbankname: `cms`
   - Kollation: `utf8mb4_unicode_ci`
   - Klicke auf "Erstellen"

3. **Benutzer erstellen (optional)**
   - Gehe zu "Benutzerkonten" → "Benutzerkonto hinzufügen"
   - Benutzername: `cms_user`
   - Passwort: `cms_password` (oder dein eigenes)
   - Hostname: `localhost`
   - Rechte: Alle Rechte für die Datenbank `cms` geben

## Schritt 5: Umgebungsvariablen konfigurieren

1. **.env Datei erstellen**
   ```bash
   copy .env.example .env
   ```
   
   Falls `.env.example` nicht existiert, erstelle eine `.env` Datei mit folgendem Inhalt:

2. **.env Datei bearbeiten**
   
   Öffne die `.env` Datei und passe folgende Werte an:

   ```env
   APP_NAME=LaravelCMS
   APP_ENV=local
   APP_KEY=
   APP_DEBUG=true
   APP_URL=http://localhost/cms/public

   DB_CONNECTION=mysql
   DB_HOST=127.0.0.1
   DB_PORT=3306
   DB_DATABASE=cms
   DB_USERNAME=root
   DB_PASSWORD=

   BROADCAST_DRIVER=log
   CACHE_STORE=file
   SESSION_DRIVER=file
   QUEUE_CONNECTION=sync
   ```

   **Wichtig:**
   - `DB_PASSWORD` leer lassen (Standard XAMPP)
   - `APP_URL` auf `http://localhost/cms/public` setzen (oder deinen Pfad)
   - `DB_USERNAME=root` (Standard XAMPP)

3. **App Key generieren**
   ```bash
   php artisan key:generate
   ```

## Schritt 6: Datenbank migrieren

```bash
php artisan migrate --seed
```

Dies erstellt alle Tabellen und fügt Testdaten ein.

## Schritt 7: Storage-Link erstellen

```bash
php artisan storage:link
```

## Schritt 8: Assets kompilieren

```bash
npm run dev
```

Für Produktion:
```bash
npm run build
```

## Schritt 9: Apache Virtual Host einrichten (Optional, aber empfohlen)

Für eine saubere URL ohne `/public` im Pfad:

1. **httpd-vhosts.conf bearbeiten**
   
   Öffne: `C:\xampp\apache\conf\extra\httpd-vhosts.conf`
   
   Füge am Ende hinzu:
   ```apache
   <VirtualHost *:80>
       ServerName cms.local
       DocumentRoot "C:/xampp/htdocs/cms/public"
       <Directory "C:/xampp/htdocs/cms/public">
           Options Indexes FollowSymLinks
           AllowOverride All
           Require all granted
       </Directory>
   </VirtualHost>
   ```

2. **hosts Datei bearbeiten**
   
   Öffne als Administrator: `C:\Windows\System32\drivers\etc\hosts`
   
   Füge hinzu:
   ```
   127.0.0.1    cms.local
   ```

3. **Apache neu starten**
   - Im XAMPP Control Panel: Apache stoppen und starten

4. **Zugriff**
   - Jetzt erreichbar unter: http://cms.local

## Schritt 10: Zugriff auf die Anwendung

**Ohne Virtual Host:**
```
http://localhost/cms/public
```

**Mit Virtual Host:**
```
http://cms.local
```

## Standard-Zugangsdaten

Nach dem Seeding:
- **Admin:** admin@example.com / password
- **User:** test@example.com / password

## Häufige Probleme

### Problem: "Class 'PDO' not found"

**Lösung:**
1. Öffne `C:\xampp\php\php.ini`
2. Suche nach `;extension=pdo_mysql`
3. Entferne das `;` am Anfang: `extension=pdo_mysql`
4. Apache neu starten

### Problem: "500 Internal Server Error"

**Lösung:**
1. Prüfe die Berechtigungen für `storage/` und `bootstrap/cache/`
2. Prüfe die Logs: `storage/logs/laravel.log`
3. Stelle sicher, dass `mod_rewrite` aktiviert ist

### Problem: "mod_rewrite nicht aktiviert"

**Lösung:**
1. Öffne `C:\xampp\apache\conf\httpd.conf`
2. Suche nach `#LoadModule rewrite_module modules/mod_rewrite.so`
3. Entferne das `#`: `LoadModule rewrite_module modules/mod_rewrite.so`
4. Apache neu starten

### Problem: "Composer nicht gefunden"

**Lösung:**
1. Lade Composer herunter: https://getcomposer.org/download/
2. Installiere Composer global
3. Oder verwende den Composer-Phar direkt:
   ```bash
   php composer.phar install
   ```

### Problem: "Node/npm nicht gefunden"

**Lösung:**
1. Lade Node.js herunter: https://nodejs.org/
2. Installiere Node.js (npm wird automatisch mitinstalliert)
3. Terminal neu starten

### Problem: "Datenbankverbindung fehlgeschlagen"

**Lösung:**
1. Prüfe, ob MySQL in XAMPP läuft
2. Prüfe die `.env` Datei:
   - `DB_HOST=127.0.0.1` (nicht localhost)
   - `DB_USERNAME=root`
   - `DB_PASSWORD=` (leer für Standard XAMPP)
3. Teste die Verbindung in phpMyAdmin

## XAMPP-spezifische Tipps

1. **PHP-Version prüfen:**
   ```bash
   C:\xampp\php\php.exe -v
   ```

2. **Composer mit XAMPP PHP verwenden:**
   ```bash
   C:\xampp\php\php.exe C:\path\to\composer.phar install
   ```

3. **Artisan-Befehle mit XAMPP PHP:**
   ```bash
   C:\xampp\php\php.exe artisan migrate
   ```

4. **Logs ansehen:**
   - Laravel Logs: `storage/logs/laravel.log`
   - Apache Logs: `C:\xampp\apache\logs\error.log`
   - PHP Logs: `C:\xampp\php\logs\php_error_log`

## Entwicklung mit XAMPP

### Assets im Development-Modus kompilieren

```bash
npm run dev
```

Dies startet Vite im Watch-Modus und kompiliert Assets automatisch bei Änderungen.

### Cache leeren

```bash
php artisan cache:clear
php artisan config:clear
php artisan route:clear
php artisan view:clear
```

### Datenbank zurücksetzen

```bash
php artisan migrate:fresh --seed
```

**Vorsicht:** Löscht alle Daten!

## Nächste Schritte

1. Teste alle Funktionen der Anwendung
2. Erstelle deine eigenen News, Forum-Threads, etc.
3. Passe das Design nach deinen Wünschen an
4. Konfiguriere Pusher für Echtzeit-Chat (optional)

## Hilfe

Bei Problemen:
1. Prüfe die Logs: `storage/logs/laravel.log`
2. Aktiviere Debug-Modus: `APP_DEBUG=true` in `.env`
3. Prüfe die Apache/PHP-Logs in XAMPP

