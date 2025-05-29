##Deployment & Setup Instructions

These steps explain how to **clone and run the Symfony Weather App** in any environment (local or server).

---

### Requirements

- PHP >= 8.1
- Composer
- Git
- Symfony CLI (optional but recommended)
- Web server (Apache, Nginx or Symfony CLI built-in server)

---

### 1. Clone the repository

```bash
git clone git@github.com:hodakovskiy/SymfonyTechTask.git
cd SymfonyTechTask
```

### 2. Install dependencies

```bash
composer install
```

### 3. Configure environment variables

```bash
cp .env.example .env
WEATHER_API_KEY=your_weatherapi_key_here
APP_LOCALE=en
or
APP_LOCALE=uk
```

You can get a free API key at https://weatherapi.com

### 4. Generate application secret

```bash
php bin/console security:generate-secret
```

### 5. Set permissions for the var directory if you are on Linux

```bash
chmod -R 775 var
chmod -R 775 vendor
```

### 6. Start local server

```bash
symfony server:start
```

Or

```bash
php bin/console server:start --port=8000
```

Or PHP built-in server:

```bash
php -S localhost:8000 -t public
```

### 7. Open the app in your browser

Open http://localhost:8000 in your browser to see the app.

### 8. Run tests

```bash
php bin/phpunit
```
