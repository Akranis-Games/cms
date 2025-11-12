# Laravel CMS System

Vollständiges CMS-Webseiten-System auf Laravel-Basis mit modernem Dark/Neon Design.

## Features

- **ACP (Admin Control Panel)** - Vollständiges Administrationspanel
- **UCP (User Control Panel)** - Benutzer-Dashboard
- **Newssystem** - Mit Kategorien und Kommentaren
- **Mediasystem** - Datei-Uploads und Galerien
- **Forumsystem** - Kategorien, Threads und Posts
- **Supportsystem** - Ticket-System
- **Chatsystem** - Echtzeit-Kommunikation
- **Feiertagssettings** - Animationen für alle Feiertage
- **Minecraft Shopsystem** - Produkte und Warenkorb

## Installation

### Lokale Entwicklung mit XAMPP

Siehe [INSTALLATION_XAMPP.md](INSTALLATION_XAMPP.md) für eine detaillierte XAMPP-Anleitung.

**Schnellstart:**
1. Projekt nach `C:\xampp\htdocs\cms` kopieren
2. XAMPP starten (Apache + MySQL)
3. Datenbank `cms` in phpMyAdmin erstellen
4. `composer install`
5. `npm install`
6. `.env` Datei konfigurieren (siehe INSTALLATION_XAMPP.md)
7. `php artisan key:generate`
8. `php artisan migrate --seed`
9. `npm run dev`

### Standard-Installation

Siehe [INSTALLATION.md](INSTALLATION.md) für die Standard-Installation.

### Server-Deployment

Siehe [DEPLOYMENT.md](DEPLOYMENT.md) für die Deployment-Anleitung.

### Automatische Installation auf Debian 12

Für eine vollständige automatische Installation auf Debian 12:

```bash
chmod +x install.sh
sudo ./install.sh
```

Das Script installiert automatisch:
- PHP 8.2 und alle notwendigen Extensions
- Composer
- Node.js und npm
- MariaDB (MySQL-kompatibel)
- Apache2
- Git (kann Projekt aus Git-Repository klonen)
- Konfiguriert das Projekt
- Richtet die Datenbank ein
- Setzt Berechtigungen
- Erstellt Apache2 Virtual Host Konfiguration

**Git Repository klonen:**
Das Script fragt automatisch, ob das Projekt aus einem Git-Repository geklont werden soll, falls das Verzeichnis leer oder nicht vorhanden ist.

Standard-Repository: `https://github.com/Akranis-Games/cms.git`

Du kannst auch direkt klonen:
```bash
git clone https://github.com/Akranis-Games/cms.git /var/www/html/cms
cd /var/www/html/cms
sudo ./install.sh
```

## Design

Modernes Dark/Neon Design mit animierten Feiertags-Effekten.

## Standard-Zugangsdaten

Nach dem Seeding:
- **Admin:** admin@example.com / password
- **User:** test@example.com / password

