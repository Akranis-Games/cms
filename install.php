<?php
/**
 * Laravel CMS Installation Script
 * PHP-basierte Installation für bessere Kompatibilität
 */

// Farben für Terminal-Output
class Colors {
    const RESET = "\033[0m";
    const RED = "\033[31m";
    const GREEN = "\033[32m";
    const YELLOW = "\033[33m";
    const BLUE = "\033[34m";
    const CYAN = "\033[36m";
    const BOLD = "\033[1m";
}

function print_header($text) {
    echo "\n" . Colors::CYAN . "========================================" . Colors::RESET . "\n";
    echo Colors::BOLD . Colors::CYAN . $text . Colors::RESET . "\n";
    echo Colors::CYAN . "========================================" . Colors::RESET . "\n\n";
}

function print_success($text) {
    echo Colors::GREEN . "✓ " . $text . Colors::RESET . "\n";
}

function print_error($text) {
    echo Colors::RED . "✗ " . $text . Colors::RESET . "\n";
}

function print_warning($text) {
    echo Colors::YELLOW . "⚠ " . $text . Colors::RESET . "\n";
}

function print_info($text) {
    echo Colors::BLUE . "ℹ " . $text . Colors::RESET . "\n";
}

function read_input($prompt, $default = null) {
    $defaultText = $default ? " [{$default}]" : "";
    echo Colors::CYAN . $prompt . $defaultText . ": " . Colors::RESET;
    $input = trim(fgets(STDIN));
    return $input ?: $default;
}

function read_password($prompt) {
    echo Colors::CYAN . $prompt . ": " . Colors::RESET;
    system('stty -echo');
    $password = trim(fgets(STDIN));
    system('stty echo');
    echo "\n";
    return $password;
}

// Prüfe PHP Version
if (version_compare(PHP_VERSION, '8.1.0') < 0) {
    print_error("PHP 8.1 oder höher erforderlich! Aktuelle Version: " . PHP_VERSION);
    exit(1);
}

print_header("Laravel CMS Installation");

// Prüfe ob wir im richtigen Verzeichnis sind
if (!file_exists('artisan')) {
    print_error("artisan Datei nicht gefunden! Bitte führe dieses Script im Laravel Root-Verzeichnis aus.");
    exit(1);
}

// Prüfe ob Composer installiert wurde
if (!file_exists('vendor/autoload.php')) {
    print_error("Composer Dependencies nicht installiert!");
    print_info("Bitte führe zuerst aus: composer install");
    exit(1);
}

// Lade Laravel Bootstrap
require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

// Helper-Funktion für Artisan Commands
function artisan_call($command, $parameters = []) {
    global $kernel;
    return $kernel->call($command, $parameters);
}

// .env Datei erstellen
print_header(".env Datei konfigurieren");

if (!file_exists('.env')) {
    if (file_exists('env.template')) {
        copy('env.template', '.env');
        print_success(".env aus env.template erstellt");
    } elseif (file_exists('.env.example')) {
        copy('.env.example', '.env');
        print_success(".env aus .env.example erstellt");
    } else {
        print_error("Keine .env Vorlage gefunden!");
        exit(1);
    }
} else {
    print_warning(".env existiert bereits");
}

// App Key generieren
print_header("App Key generieren");
try {
    artisan_call('key:generate', ['--force' => true]);
    print_success("App Key generiert");
} catch (Exception $e) {
    print_error("Fehler beim Generieren des App Keys: " . $e->getMessage());
}

// Datenbank konfigurieren
print_header("Datenbank konfigurieren");

$dbName = read_input("Datenbankname", "cms");
$dbUser = read_input("Datenbank-Benutzer", "cms_user");
$dbPass = read_password("Datenbank-Passwort");
$dbHost = read_input("Datenbank-Host", "localhost");
$dbPort = read_input("Datenbank-Port", "3306");

// Aktualisiere .env Datei
$envFile = file_get_contents('.env');
$envFile = preg_replace('/^DB_DATABASE=.*/m', "DB_DATABASE={$dbName}", $envFile);
$envFile = preg_replace('/^DB_USERNAME=.*/m', "DB_USERNAME={$dbUser}", $envFile);
$envFile = preg_replace('/^DB_PASSWORD=.*/m', "DB_PASSWORD={$dbPass}", $envFile);
$envFile = preg_replace('/^DB_HOST=.*/m', "DB_HOST={$dbHost}", $envFile);
$envFile = preg_replace('/^DB_PORT=.*/m', "DB_PORT={$dbPort}", $envFile);

// Füge hinzu falls nicht vorhanden
if (!preg_match('/^DB_DATABASE=/m', $envFile)) {
    $envFile .= "\nDB_DATABASE={$dbName}";
}
if (!preg_match('/^DB_USERNAME=/m', $envFile)) {
    $envFile .= "\nDB_USERNAME={$dbUser}";
}
if (!preg_match('/^DB_PASSWORD=/m', $envFile)) {
    $envFile .= "\nDB_PASSWORD={$dbPass}";
}
if (!preg_match('/^DB_HOST=/m', $envFile)) {
    $envFile .= "\nDB_HOST={$dbHost}";
}
if (!preg_match('/^DB_PORT=/m', $envFile)) {
    $envFile .= "\nDB_PORT={$dbPort}";
}

file_put_contents('.env', $envFile);
print_success(".env Datei aktualisiert");

// APP_URL konfigurieren
$appUrl = read_input("APP_URL", "http://localhost");
$envFile = file_get_contents('.env');
$envFile = preg_replace('/^APP_URL=.*/m', "APP_URL={$appUrl}", $envFile);
if (!preg_match('/^APP_URL=/m', $envFile)) {
    $envFile .= "\nAPP_URL={$appUrl}";
}
file_put_contents('.env', $envFile);
print_success("APP_URL gesetzt: {$appUrl}");

// Teste Datenbankverbindung
print_header("Datenbankverbindung testen");
print_info("Teste Verbindung zur Datenbank...");

try {
    // Lade Config neu
    artisan_call('config:clear');
    
    // Teste Verbindung
    $pdo = DB::connection()->getPdo();
    print_success("Datenbankverbindung erfolgreich!");
} catch (Exception $e) {
    print_error("Datenbankverbindung fehlgeschlagen: " . $e->getMessage());
    print_warning("Bitte erstelle die Datenbank manuell:");
    echo "\n";
    echo "  CREATE DATABASE IF NOT EXISTS {$dbName} CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci;\n";
    echo "  CREATE USER IF NOT EXISTS '{$dbUser}'@'{$dbHost}' IDENTIFIED BY '{$dbPass}';\n";
    echo "  GRANT ALL PRIVILEGES ON {$dbName}.* TO '{$dbUser}'@'{$dbHost}';\n";
    echo "  FLUSH PRIVILEGES;\n";
    echo "\n";
    
    $continue = read_input("Trotzdem fortfahren? (y/N)", "N");
    if (strtolower($continue) !== 'y') {
        exit(1);
    }
}

// Verzeichnisse erstellen und Berechtigungen setzen
print_header("Verzeichnisse und Berechtigungen konfigurieren");

$directories = [
    'bootstrap/cache',
    'storage/app',
    'storage/app/public',
    'storage/framework/cache',
    'storage/framework/sessions',
    'storage/framework/views',
    'storage/logs',
];

foreach ($directories as $dir) {
    if (!is_dir($dir)) {
        mkdir($dir, 0755, true);
        print_success("Verzeichnis erstellt: {$dir}");
    }
}

// Setze Berechtigungen (nur auf Unix-Systemen)
if (PHP_OS_FAMILY !== 'Windows') {
    foreach ($directories as $dir) {
        chmod($dir, 0775);
    }
    print_success("Berechtigungen gesetzt");
} else {
    print_info("Windows erkannt - Berechtigungen werden übersprungen");
}

// Datenbank migrieren
print_header("Datenbank migrieren");

try {
    artisan_call('migrate', ['--force' => true]);
    print_success("Migrationen erfolgreich ausgeführt");
} catch (Exception $e) {
    print_error("Fehler bei Migrationen: " . $e->getMessage());
    print_info("Du kannst die Migrationen später manuell ausführen:");
    echo "  php artisan migrate --force\n";
    
    $continue = read_input("Trotzdem fortfahren? (y/N)", "N");
    if (strtolower($continue) !== 'y') {
        exit(1);
    }
}

// Datenbank seeden
$seed = read_input("Datenbank mit Testdaten seeden? (y/N)", "N");
if (strtolower($seed) === 'y') {
    try {
        artisan_call('db:seed', ['--force' => true]);
        print_success("Datenbank geseedet");
    } catch (Exception $e) {
        print_warning("Fehler beim Seeding: " . $e->getMessage());
    }
}

// Storage Link erstellen
print_header("Storage Link erstellen");
try {
    if (file_exists('public/storage')) {
        print_info("Storage Link existiert bereits");
    } else {
        artisan_call('storage:link');
        print_success("Storage Link erstellt");
    }
} catch (Exception $e) {
    print_warning("Storage Link konnte nicht erstellt werden: " . $e->getMessage());
}

// Cache optimieren
print_header("Cache optimieren");

try {
    artisan_call('config:cache');
    print_success("Config Cache erstellt");
} catch (Exception $e) {
    print_warning("Config Cache konnte nicht erstellt werden: " . $e->getMessage());
}

try {
    artisan_call('route:cache');
    print_success("Route Cache erstellt");
} catch (Exception $e) {
    print_warning("Route Cache konnte nicht erstellt werden: " . $e->getMessage());
}

try {
    if (is_dir('storage/framework/views')) {
        artisan_call('view:cache');
        print_success("View Cache erstellt");
    }
} catch (Exception $e) {
    print_warning("View Cache konnte nicht erstellt werden (nicht kritisch)");
}

// Assets kompilieren
print_header("Assets kompilieren");
if (file_exists('package.json')) {
    print_info("Führe npm run build aus...");
    $output = [];
    $returnVar = 0;
    exec('npm run build 2>&1', $output, $returnVar);
    if ($returnVar === 0) {
        print_success("Assets kompiliert");
    } else {
        print_warning("Assets konnten nicht kompiliert werden");
        print_info("Du kannst später manuell ausführen: npm run build");
    }
} else {
    print_info("package.json nicht gefunden, überspringe Asset-Kompilierung");
}

// Zusammenfassung
print_header("Installation abgeschlossen!");

echo "\n";
echo Colors::GREEN . "Zusammenfassung:" . Colors::RESET . "\n";
echo "  Datenbank: {$dbName}\n";
echo "  Datenbank-Benutzer: {$dbUser}\n";
echo "  APP_URL: {$appUrl}\n";
echo "\n";

echo Colors::YELLOW . "Nächste Schritte:" . Colors::RESET . "\n";
echo "  1. Prüfe die .env Datei\n";
echo "  2. Teste die Anwendung: {$appUrl}\n";
if (strtolower($seed) === 'y') {
    echo "  3. Standard-Zugangsdaten:\n";
    echo "     - Admin: admin@example.com / password\n";
    echo "     - User: test@example.com / password\n";
}
echo "\n";

echo Colors::BLUE . "Wichtige Befehle:" . Colors::RESET . "\n";
echo "  Logs ansehen: tail -f storage/logs/laravel.log\n";
echo "  Cache leeren: php artisan cache:clear\n";
echo "  Config Cache leeren: php artisan config:clear\n";
echo "  Route Cache leeren: php artisan route:clear\n";
echo "\n";

print_success("Installation erfolgreich abgeschlossen!");

