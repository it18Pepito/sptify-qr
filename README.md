# Pepito Loyalty Redirect App (PHP)

This is a PHP port of the Pepito Loyalty Redirect application. It handles user redirection to the appropriate app store (Google Play Store or Apple App Store) based on the user's device and logs visitor analytics to a MySQL database.

## Features

- **Device Detection**: Automatically redirects users to the Play Store (Android) or App Store (iOS).
- **Analytics Logging**: Logs IP, Location (GeoIP), User-Agent, ISP, and other details to MySQL.
- **GeoIP Lookup**: Uses `ipinfo.io` (async) for location data and OpenStreetMap (OSM) for address details.
- **Bot/Crawler Detection**: Identifies basic bots and crawlers.

## Requirements

- PHP 7.4 or higher
- Composer
- MySQL Database

## Installation

1.  **Clone the repository** (if not already done).

2.  **Install Dependencies** via Composer:

    ```bash
    composer install
    ```

3.  **Environment Setup**:
    Copy the sample `.env` file (or create one) based on `.env.example`:

    ```ini
    DB_HOST=localhost
    DB_USER=root
    DB_PASSWORD=
    DB_NAME=loyalty_pepito
    DB_PORT=3306
    ```

4.  **Database Setup**:
    Import the provided `database.sql` file into your MySQL database to create the `app_download_logs` table.

## Running Locally

To run the application using the built-in PHP server:

```bash
cd public
php -S localhost:3000
```

Then access [http://localhost:3000](http://localhost:3000)

## Deployment (cPanel / Linux)

1.  **Upload Files**: Upload the entire project to your server.
    - Recommended: Place the project folder _outside_ `public_html`, and only map the `public` folder to your domain's Document Root.
    - Alternative: If uploading to `public_html/loyalty-app`, the URL will be `yourdomain.com/loyalty-app/public`.

2.  **Folder Permissions**: Ensure the `storage` or cache folders (if any) are writable (755 or 777 depending on server config).

3.  **Important Note on Folder Naming (case-sensitive)**:
    - Ensure the `app/Controllers` folder starts with a **Capital C** on the server: `app/Controllers`.
    - Ensure `DownloadController.php` is inside it.
    - If you see "Class not found" errors, check the folder name casing.

## Project Structure

```
├── app/
│   ├── Config/          # Database configuration
│   ├── Controllers/     # Application logic (DownloadController)
│   └── Utils/           # Helper classes (if any)
├── public/
│   ├── index.php        # Entry point
│   ├── .htaccess        # Apache rewrite rules
│   └── assets/          # Static images/CSS
├── views/               # HTML Views
├── vendor/              # Composer dependencies
└── composer.json
```

## Credits

- Built with [Flight PHP Framework](https://flightphp.com/)
