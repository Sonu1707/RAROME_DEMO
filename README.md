---

# Rarome Interview Process

## Dependencies

### Frontend
	- ReactJS 19.0.0
	- Node v20.15.1

### Backend
	- Symfony 6.4
	- PHP 8.1+

## Setup for Install
1. please copy and past in your terminal the following steps:
    git clone https://github.com/Sonu1707/rarome_demo.git

### Backend Setup

1. **Install Composer Dependencies**
   ```sh
   composer install
   ```
   This command will create the `symfony` vendor, config, src, var, etc., directories.

2. **Create VirtualHost**
   ```apache
   <VirtualHost *:80>
       ServerName rarome.local.com # Change the local domain name
       DocumentRoot /var/www/html/rarome_demo/public
       <Directory /var/www/html/rarome_demo/public>
           AllowOverride None
           Require all granted
           FallbackResource /index.php
       </Directory>
   </VirtualHost>
   ```

3. **Update Hosts File**
   Add the following line to `/etc/hosts`:
   ```sh
   127.0.0.1 rarome.local.com
   ```

4. **Add Environment Variables**
   If the `.env` file is not in the project directory, add the following values:
   ```env
   ###> symfony/framework-bundle ###
   APP_ENV=dev
   APP_DEBUG=true
   APP_SECRET=823e09b6bb358ff3be18502705bc11b5
   APP_DOC_STORAGE='/var/documents'
   API_DOC_URL="https://raw.githubusercontent.com/RashitKhamidullin/Educhain-Assignment/refs/heads/main/get-documents"
   ###< symfony/framework-bundle ###
   ```

### Frontend Setup

1. **Install NPM Packages**
   ```sh
   npm install
   ```

2. **Compile Assets for Development**
   ```sh
   npm run dev
   ```

3. **Compile Assets and Watch for Modifications**
   ```sh
   npm run watch
   ```

4. **Open Hostname in Browser**
   Open `http://rarome.local.com/` in your browser.

### Running Backend Tasks with Symfony

1. **API Document Service**
   The main service file `src/Service/ApiDocumentService.php` consumes the remote API for documents.

   **Testing:**
   - Using PHPUnit:
     ```sh
     ./vendor/bin/phpunit tests/ApiDocumentServiceTest.php
     ```
   - Access via URL:
     ```url
     http://rarome.local.com/documents
     ```

   **Note:**
   Ensure the storage directory exists with the following commands:
   ```sh
   mkdir -p var/documents
   chmod -R 775 var/documents
   sudo chmod -R 0777 .
   sudo chown -R www-data:www-data .
   ```

---

Let me know if there's anything else you need help with!