# 🚀 **IMPLEMENTASI PLAN AUTOMASI TESTING**
## **Koperasi Syariah App - Playwright E2E Testing**

---

## 📋 **PRIORITAS IMPLEMENTASI**

### **Phase 1: Critical Foundation (Minggu 1)**
**Target: Testing core functionality yang paling penting**

#### 1.1 Infrastructure Setup
- [ ] Install Playwright dan dependencies
- [ ] Buat basic configuration file
- [ ] Setup test environment database
- [ ] Create basic test structure

#### 1.2 Critical User Authentication Tests
- [ ] Login sebagai Anggota (✅ priority #1)
- [ ] Login sebagai Pengurus (✅ priority #1)
- [ ] Login sebagai Admin (✅ priority #1)
- [ ] Logout functionality (✅ priority #1)
- [ ] Session management (✅ priority #1)

#### 1.3 Core Business Flow Tests
- [ ] Dashboard access untuk setiap role (✅ priority #1)
- [ ] Simpanan flow untuk anggota (✅ priority #2)
- [ ] Pengajuan pembiayaan flow (✅ priority #2)
- [ ] Laporan basic generation (✅ priority #2)

### **Phase 2: Complete User Flows (Minggu 2)**
**Target: Testing seluruh user journey**

#### 2.1 Anggota Complete Flow
- [ ] Profil management
- [ ] Lihat riwayat simpanan
- [ ] Ajukan pembiayaan (end-to-end)
- [ ] Track pengajuan status
- [ ] Lihat jadwal angsuran
- [ ] Download kartu anggota

#### 2.2 Pengurus Complete Flow
- [ ] Manajemen anggota (CRUD)
- [ ] Transaksi simpanan manual
- [ ] Verifikasi pengajuan
- [ ] Cairkan pembiayaan
- [ ] Generate laporan
- [ ] Manajemen angsuran

#### 2.3 Admin Complete Flow
- [ ] Manajemen pengurus
- [ ] Konfigurasi data koperasi
- [ ] Setup jenis simpanan/pembiayaan
- [ ] Kartu anggota generation
- [ ] System settings

### **Phase 3: Advanced Testing (Minggu 3)**
**Target: Edge cases dan additional scenarios**

#### 3.1 Form Validation Tests
- [ ] Required field validation
- [ ] Email format validation
- [ ] Numeric input validation
- [ ] File upload validation
- [ ] Error message display

#### 3.2 UI/UX Tests
- [ ] Responsive design (mobile)
- [ ] Navigation flows
- [ ] Modal dialogs
- [ ] Loading states
- [ ] Error handling

---

## 🏗️ **STRUKTUR FILE YANG AKAN DIBUAT**

```
koperasi-syariah-app/
├── tests/
│   └── e2e/
│       ├── playwright.config.js          # Playwright configuration
│       ├── package.json                 # Node dependencies
│       ├── .env.testing                  # Testing environment
│       ├── fixtures/                     # Test data
│       │   ├── users.js                  # Test user credentials
│       │   ├── anggota.js               # Anggota test data
│       │   ├── pengurus.js              # Pengurus test data
│       │   └── admin.js                  # Admin test data
│       ├── pages/                        # Page Object Models
│       │   ├── BasePage.js              # Base functionality
│       │   ├── AuthPage.js              # Login/Logout
│       │   ├── AnggotaPage.js           # Anggota pages
│       │   ├── PengurusPage.js          # Pengurus pages
│       │   └── AdminPage.js             # Admin pages
│       ├── tests/                        # Test specifications
│       │   ├── auth/                    # Authentication tests
│       │   ├── anggota/                 # Anggota functionality
│       │   ├── pengurus/                # Pengurus functionality
│       │   └── admin/                   # Admin functionality
│       ├── support/                      # Helper functions
│       │   ├── database.js              # Database setup
│       │   ├── dataHelpers.js           # Test data helpers
│       │   └── assertions.js            # Custom assertions
│       └── reports/                      # Test reports
├── run-tests.sh                          # Main test runner script
└── cleanup-tests.sh                       # Test cleanup script
```

---

## 🛠️ **IMPLEMENTATION DETAIL**

### **1. Main Test Runner Script**
```bash
#!/bin/bash
# run-tests.sh

echo "🚀 Starting Koperasi Syariah E2E Tests..."

# Step 1: Setup testing environment
echo "📋 Setting up test environment..."
cp .env.testing .env
php artisan config:cache
php artisan migrate:fresh --seed
php artisan storage:link

# Step 2: Start application in background
echo "🌐 Starting application..."
php artisan serve --host=0.0.0.0 --port=8010 > tests/logs/app.log 2>&1 &
APP_PID=$!

# Step 3: Wait for application to be ready
echo "⏳ Waiting for application to start..."
sleep 10

# Step 4: Check if application is running
if curl -f http://localhost:8010 > /dev/null 2>&1; then
    echo "✅ Application started successfully"
else
    echo "❌ Failed to start application"
    kill $APP_PID 2>/dev/null
    exit 1
fi

# Step 5: Run tests
echo "🧪 Running E2E tests..."
cd tests/e2e

# Install dependencies if needed
if [ ! -d "node_modules" ]; then
    echo "📦 Installing Playwright..."
    npm install
    npx playwright install
fi

# Run tests with proper environment
APP_URL=http://localhost:8010 npm run test

# Step 6: Cleanup
echo "🧹 Cleaning up..."
kill $APP_PID 2>/dev/null
echo "✅ Tests completed!"
```

### **2. Environment Configuration**
```javascript
// tests/e2e/playwright.config.js
module.exports = {
    testDir: './tests',
    timeout: 30000,
    retries: 1,
    use: {
        baseURL: process.env.APP_URL || 'http://localhost:8010',
        trace: 'on-first-retry',
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
        viewport: { width: 1280, height: 720 },
        ignoreHTTPSErrors: true,
    },
    projects: [
        { name: 'chromium', use: { ...require('@playwright/test').devices['Desktop Chrome'] } },
    ],
    reporter: [
        ['html', { outputFolder: '../reports/html' }],
        ['junit', { outputFile: '../reports/junit.xml' }],
        ['list'],
    ],
};
```

### **3. Test Data Fixtures**
```javascript
// tests/e2e/fixtures/users.js
export const USERS = {
    anggota: {
        username: '2521.00001',
        password: '22222222',
        nama: 'Test Anggota',
        email: 'anggota@test.com'
    },
    pengurus: {
        email: 'yogi@gmail.com',
        password: '22222222',
        nama: 'Test Pengurus',
        role: 'pengurus'
    },
    bendahara: {
        email: 'fitri@gmail.com',
        password: '33333333',
        nama: 'Test Bendahara',
        role: 'pengurus'
    },
    admin: {
        email: 'admin@admin.com',
        password: 'password',
        nama: 'Test Admin',
        role: 'admin'
    }
};
```

### **4. Page Object Models**
```javascript
// tests/e2e/pages/AuthPage.js
class AuthPage {
    constructor(page) {
        this.page = page;
        this.loginInput = page.locator('input[name="login"]');
        this.passwordInput = page.locator('input[name="password"]');
        this.loginButton = page.locator('button[type="submit"]');
        this.logoutButton = page.locator('a[href="/logout"]');
    }

    async login(username, password) {
        await this.loginInput.fill(username);
        await this.passwordInput.fill(password);
        await this.loginButton.click();
        await this.page.waitForURL(/dashboard/);
    }

    async logout() {
        await this.logoutButton.click();
        await this.page.waitForURL('/login');
    }

    async getErrorMessage() {
        return this.page.locator('.alert-danger').textContent();
    }
}

module.exports = { AuthPage };
```

### **5. Critical Test Examples**

#### **Authentication Tests**
```javascript
// tests/e2e/tests/auth/login.spec.js
const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { USERS } = require('../../fixtures/users');

test.describe('Authentication', () => {
    let authPage;

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
        await page.goto('/login');
    });

    test('Anggota should login successfully', async ({ page }) => {
        await authPage.login(USERS.anggota.username, USERS.anggota.password);
        await expect(page).toHaveURL(/anggota\/dashboard/);
        await expect(page.locator('h1')).toContainText('Dashboard');
    });

    test('Pengurus should login successfully', async ({ page }) => {
        await authPage.login(USERS.pengurus.email, USERS.pengurus.password);
        await expect(page).toHaveURL(/pengurus\/dashboard/);
        await expect(page.locator('h1')).toContainText('Dashboard');
    });

    test('Admin should login successfully', async ({ page }) => {
        await authPage.login(USERS.admin.email, USERS.admin.password);
        await expect(page).toHaveURL(/admin\/dashboard/);
        await expect(page.locator('h1')).toContainText('Dashboard');
    });

    test('Should show error for invalid credentials', async ({ page }) => {
        await authPage.login('invalid@test.com', 'wrongpassword');
        const errorMessage = await authPage.getErrorMessage();
        await expect(errorMessage).toContain('salah');
        await expect(page).toHaveURL('/login');
    });
});
```

#### **Core Business Flow Tests**
```javascript
// tests/e2e/tests/anggota/pengajuan-pembiayaan.spec.js
const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { AnggotaPage } = require('../../pages/AnggotaPage');
const { USERS } = require('../../fixtures/users');
const { PENGAJUAN_DATA } = require('../../fixtures/anggota');

test.describe('Pengajuan Pembiayaan', () => {
    let authPage;
    let anggotaPage;

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
        anggotaPage = new AnggotaPage(page);
    });

    test('Anggota should submit pengajuan pembiayaan successfully', async ({ page }) => {
        // Login as anggota
        await page.goto('/login');
        await authPage.login(USERS.anggota.username, USERS.anggota.password);

        // Navigate to pengajuan form
        await anggotaPage.navigateToPengajuan();

        // Fill form
        await anggotaPage.fillPengajuanForm(PENGAJUAN_DATA.valid);

        // Submit form
        await anggotaPage.submitPengajuan();

        // Verify submission success
        await expect(page.locator('.alert-success'))
            .toContainText('Pengajuan berhasil diajukan');
        await expect(page).toHaveURL(/pengajuan/);

        // Verify data appears in list
        await expect(page.locator('table')).toContainText(PENGAJUAN_DATA.valid.jumlah);
    });
});
```

---

## 📊 **TEST EXECUTION COMMANDS**

### **Local Development**
```bash
# Run all tests
./run-tests.sh

# Run specific test suite
cd tests/e2e
npm run test:auth
npm run test:anggota
npm run test:pengurus
npm run test:admin

# Run single test file
npx playwright test tests/auth/login.spec.js

# Run with debugging
npx playwright test --debug

# Run with UI mode
npx playwright test --ui
```

### **Test Reports**
```bash
# View HTML report
open tests/e2e/reports/html/index.html

# View JUnit report for CI/CD
cat tests/e2e/reports/junit.xml
```

---

## ⏱️ **ESTIMASI WAKTU IMPLEMENTASI**

| Phase | Tasks | Estimasi | Deliverables |
|-------|-------|----------|--------------|
| **Phase 1** | Setup + Critical Tests | 3-4 hari | Basic infrastructure + authentication + core flows |
| **Phase 2** | Complete User Flows | 4-5 hari | Full coverage for all roles |
| **Phase 3** | Advanced Testing | 3-4 hari | Validation + UI/UX tests |
| **Total** | **Complete Implementation** | **10-13 hari** | Production-ready E2E test suite |

---

## 🎯 **SUCCESS CRITERIA**

### **Phase 1 Success**
- [ ] All 3 user roles can login/logout successfully
- [ ] Dashboard accessible for each role
- [ ] Basic simpanan/pengajuan flow works
- [ ] Laporan can be generated
- [ ] Tests run successfully with single command

### **Phase 2 Success**
- [ ] Complete anggota flow tested
- [ ] Complete pengurus flow tested
- [ ] Complete admin flow tested
- [ ] All CRUD operations tested
- [ ] File upload/download tested

### **Phase 3 Success**
- [ ] All forms validated properly
- [ ] Mobile responsive tested
- [ ] Error handling tested
- [ ] Reports generated successfully
- [ ] 90%+ test coverage achieved

---

## 📝 **NOTES & CONSIDERATIONS**

1. **Test Data**: Akan menggunakan existing database seeding untuk konsistensi
2. **Cleanup**: Otomatis cleanup setiap test run dengan `migrate:fresh --seed`
3. **Screenshots**: Automatic screenshot on failure untuk debugging
4. **Timeout**: 30 seconds timeout untuk稳定性 di environment lokal
5. **Parallel**: Sequential testing untuk avoid database conflicts

---

## ✅ **NEXT STEPS**

Setelah Anda approve implementasi plan ini:

1. **Start Phase 1**: Setup infrastructure + critical tests
2. **Daily Checkpoint**: Review progress setiap 2 hari
3. **Adjust as Needed**: Modify berdasarkan actual implementation experience
4. **Proceed to Phase 2**: After Phase 1 success criteria met

**👉 Ready to start implementation? Saya bisa mulai dengan Phase 1 sekarang!**