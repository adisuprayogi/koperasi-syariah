# 🚨 E2E Testing Troubleshooting Guide

## ❌ Common Issues and Solutions

### Database Connection Issues

#### Problem: `SQLSTATE[HY000] [1045] Access denied for user 'root'@'localhost' (using password: YES)`

**Root Cause**: MySQL user 'root' tidak memiliki akses ke database dengan password yang digunakan.

**Solutions:**

#### Option 1: Update .env.testing dengan Credentials yang Benar
```bash
# Edit .env.testing file
nano .env.testing

# Update dengan credentials MySQL yang benar:
DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi_syariah_testing
DB_USERNAME=your_mysql_username
DB_PASSWORD=your_mysql_password
```

#### Option 2: Buat Database Testing User
```sql
# Login ke MySQL sebagai root
mysql -u root -p

# Buat user untuk testing
CREATE USER 'koperasi_test'@'localhost' IDENTIFIED BY 'password';
GRANT ALL PRIVILEGES ON koperasi_syariah_testing.* TO 'koperasi_test'@'localhost';
FLUSH PRIVILEGES;

# Update .env.testing
DB_USERNAME=koperasi_test
DB_PASSWORD=password
```

#### Option 3: Reset Root Password (Development Only)
```bash
# Stop MySQL service
sudo brew services stop mysql@8.0

# Start MySQL in safe mode
sudo mysqld_safe --skip-grant-tables &

# Login tanpa password
mysql -u root

# Update password
ALTER USER 'root'@'localhost IDENTIFIED BY 'your_new_password';
FLUSH PRIVILEGES;

# Restart MySQL
sudo brew services start mysql@8.0
```

### Application Port Issues

#### Problem: `Port 8010 is already in use`

**Solutions:**
```bash
# Cari proses yang menggunakan port
lsof -ti:8010

# Kill proses tersebut
kill -9 $(lsof -ti:8010)

# Atau gunakan port berbeda
./run-tests.sh --port 8020
```

### Node.js/Playwright Issues

#### Problem: Browser installation failures

**Solutions:**
```bash
cd tests/e2e

# Clear cache dan reinstall
rm -rf node_modules package-lock.json
npm install
npx playwright install chromium --force

# Atau update Playwright
npm update @playwright/test playwright
```

#### Problem: `Error: Playwright executable not found`

**Solutions:**
```bash
cd tests/e2e

# Install dependencies globally (opsional)
npm install -g @playwright/cli

# Install locally
npx playwright install
```

### Permission Issues

#### Problem: `Permission denied` when running artisan

**Solutions:**
```bash
# Make artisan executable
chmod +x artisan

# Atau gunakan php untuk menjalankan
php artisan migrate:fresh --seed
```

### Test Environment Issues

#### Problem: Tests failing with timeout

**Solutions:**
```bash
# Increase timeout in playwright.config.js
nano tests/e2e/playwright.config.js

# Edit timeout value:
timeout: 60000, // 60 seconds instead of 30
```

#### Problem: Tests not finding elements

**Solutions:**
```bash
# Run with UI mode for debugging
cd tests/e2e
npm run test:ui

# Check if application is running
curl http://localhost:8010
```

---

## 🔧 Quick Fix Commands

### Database Setup
```bash
# Check MySQL status
brew services list | grep mysql

# Create test database if doesn't exist
mysql -u root -p -e "CREATE DATABASE IF NOT EXISTS koperasi_syariah_testing;"

# Run migrations without seed (faster)
php artisan migrate:fresh --force
```

### Check Dependencies
```bash
# Check PHP version
php --version

# Check Composer
composer --version

# Check Node.js
node --version

# Check Playwright
cd tests/e2e && npx playwright --version
```

### Manual Test Run
```bash
# Navigate to E2E directory
cd tests/e2e

# Install dependencies
npm install

# Run single test
npx playwright test tests/auth/login.spec.js --project=chromium

# Run in debug mode
npx playwright test tests/auth/login.spec.js --debug
```

## 🐛 Environment Variables

### Common .env.testing Configuration
```env
APP_NAME="Koperasi Syariah - Testing"
APP_ENV=testing
APP_DEBUG=true
APP_URL=http://localhost:8010

DB_CONNECTION=mysql
DB_HOST=127.0.0.1
DB_PORT=3306
DB_DATABASE=koperasi_syariah_testing
DB_USERNAME=your_username
DB_PASSWORD=your_password

LOG_LEVEL=debug
```

### Environment Override
```bash
# Override database settings for testing
export DB_DATABASE=koperasi_syariah_testing
export DB_USERNAME=test_user
export DB_PASSWORD=test_pass

# Run tests with custom environment
./run-tests.sh
```

---

## 📊 Debug Mode Features

### Enable Debug Mode
```bash
./run-tests.sh --debug
```

Debug mode provides:
- ✅ Detailed console output
- ✅ Screenshots for all test steps
- ✅ Application logs (`tests/e2e/logs/application.log`)
- ✅ Test execution timing
- ✅ Error stack traces

### Interactive Testing
```bash
cd tests/e2e
npm run test:ui
```

UI mode features:
- ✅ Step-by-step test execution
- ✅ Visual debugging
- ✅ Test recording
- ✅ Element inspection

---

## 📱 Test Reports Analysis

### HTML Report
```bash
# Open HTML report
open tests/e2e/reports/html/index.html

# Or view in browser
# File: tests/e2e/reports/html/index.html
```

### Screenshots & Videos
```bash
# List screenshots
ls -la tests/e2e/reports/screenshots/

# List videos
ls -la tests/e2e/reports/videos/

# View failed test screenshots
open tests/e2e/reports/screenshots/
```

### Application Logs
```bash
# View application logs
tail -f tests/e2e/logs/application.log

# Search for specific errors
grep "ERROR" tests/e2e/logs/application.log
```

---

## 🚀 Pre-Flight Checklist

### Before Running Tests

#### 1. Database Setup
- [ ] MySQL service is running
- [ ] Database credentials are correct in `.env.testing`
- [ ] Test database exists
- [ ] User has necessary privileges

#### 2. Application Setup
- [ ] Laravel dependencies installed (`composer install`)
- [   ] Application key generated (`php artisan key:generate`)
- [ ] Storage link created (`php artisan storage:link`)

#### 3. Testing Dependencies
- [ ] Node.js installed
- [ ] Playwright installed (`cd tests/e2e && npx playwright install`)
- [ ] Test directories exist (`tests/e2e/{fixtures,pages,tests,reports}`)

#### 4. Port Availability
- [ ] Port 8010 is available (or custom port)
- [ ] No conflicting processes running

### Quick Health Check
```bash
# Test all components in one command
php artisan --version && \
node --version && \
cd tests/e2e && npm list playwright && \
curl -s http://localhost:8010 > /dev/null && \
echo "✅ All systems ready!"
```

---

## 📞 Support

### If issues persist:

1. **Check logs**: `tests/e2e/logs/application.log`
2. **Review reports**: `tests/e2e/reports/html/index.html`
3. **Run debug mode**: `./run-tests.sh --debug`
4. **Check documentation**: `tests/e2e/README.md`

### Common Errors Search

```bash
# Search for specific errors in logs
grep -n "ERROR\|FATAL\|Connection refused" tests/e2e/logs/application.log

# Search for common issues
grep -rn "Access denied\|timeout\|404" tests/e2e/logs/application.log
```

---

## 🔄 Reset and Clean

### Complete Reset
```bash
# Clear all test artifacts
rm -rf tests/e2e/reports/*
rm -rf tests/e2e/logs/*
rm -rf tests/e2e/test-results/*

# Reset database
php artisan migrate:fresh --seed

# Restart testing
./run-tests.sh
```

### Cache Clear
```bash
# Clear all caches
php artisan config:clear
php artisan cache:clear
php artisan view:clear
php artisan route:clear

# Clear Playwright cache
cd tests/e2e
rm -rf node_modules/.cache
npm cache clean --force
```