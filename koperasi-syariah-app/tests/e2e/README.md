# Koperasi Syariah E2E Testing

Complete end-to-end testing infrastructure for the Koperasi Syariah application using Playwright.

## 🚀 Quick Start

### Local Testing

#### Option 1: Full Test Runner (Recommended)
Automatically starts the Laravel application and runs tests:

```bash
# Run all tests
./run-tests.sh

# Run specific test types
./run-tests.sh auth      # Authentication tests only
./run-tests.sh anggota   # Member tests only
./run-tests.sh pengurus  # Staff tests only
./run-tests.sh admin     # Admin tests only

# Show browser during testing (visual mode)
./run-tests.sh auth --headed
./run-tests.sh all --browser

# Debug mode with detailed logging
./run-tests.sh --debug

# Use custom port
./run-tests.sh --port 8020
```

#### Option 2: Quick Test Runner
Runs tests against an already running application:

```bash
# First, start your Laravel application manually
php artisan serve

# Then run tests against the running app
./run-tests-quick.sh auth --headed
./run-tests-quick.sh all

# Specify custom URL if not using default port 8000
./run-tests-quick.sh all --url http://localhost:8010
```

### Manual Test Execution

If you prefer to run tests manually:

```bash
# Navigate to E2E directory
cd tests/e2e

# Install dependencies (first time only)
npm install
npx playwright install chromium

# Run tests
npm run test                    # All tests
npm run test:auth               # Authentication tests
npm run test:anggota            # Member tests
npm run test:pengurus           # Staff tests
npm run test:admin              # Admin tests
npm run test:debug              # Debug mode
npm run test:ui                 # Interactive UI mode
```

## 📊 Test Structure

```
tests/e2e/
├── fixtures/           # Test data and credentials
├── pages/             # Page Object Models
├── tests/             # Test specifications
├── support/           # Helper functions
├── reports/           # Test outputs
└── logs/              # Application logs
```

## 🧪 Test Coverage

### Authentication Tests
- ✅ Login for all user roles (Anggota, Pengurus, Bendahara, Admin)
- ✅ Invalid credentials handling
- ✅ Form validation
- ✅ Session management
- ✅ Logout functionality

### Business Flow Tests
- ✅ Dashboard access and navigation
- ✅ Data display (info cards, tables)
- ✅ Form operations (pengajuan pembiayaan)
- ✅ Search and filter functionality
- ✅ Error handling
- ✅ Responsiveness

### User Roles Tested
- **Anggota**: 2521.00001/22222222 (username login)
- **Pengurus**: yogi@gmail.com/22222222 (email login)
- **Bendahara**: fitri@gmail.com/33333333 (email login)
- **Admin**: admin@admin.com/password (email login)

## 🔧 Configuration

### Environment Setup

The testing system uses:
- **Database**: `koperasi_syariah_testing` (auto-created)
- **Application Port**: 8010 (configurable)
- **Browser**: Chromium (Playwright)
- **Timeout**: 30 seconds per test
- **Retries**: 1 on failure

### Test Data

Test credentials and data are defined in:
- `fixtures/users.js` - User credentials
- `fixtures/anggota.js` - Member-specific data
- `fixtures/pengurus.js` - Staff-specific data
- `fixtures/admin.js` - Admin-specific data

## 📋 CI/CD Integration

### GitHub Actions

The repository includes GitHub Actions workflow (`.github/workflows/e2e-tests.yml`) that:

1. **Triggers** on:
   - Push to `main` or `develop` branches
   - Pull requests to `main` or `develop`
   - Manual workflow dispatch

2. **Runs**:
   - Complete E2E test suite
   - Security scanning
   - Code quality checks

3. **Outputs**:
   - Test reports (HTML, JUnit)
   - Screenshots on failure
   - Test videos on failure

### Manual Workflow Trigger

You can manually trigger tests in GitHub Actions with custom options:
- **Test Type**: auth, anggota, pengurus, admin, or all
- **Debug Mode**: Enable detailed logging

## 🐛 Debugging

### Local Debug Mode

```bash
./run-tests.sh --debug
```

This enables:
- Detailed console output
- Screenshots for all steps
- Application logs
- Test execution timing

### Interactive Testing

```bash
cd tests/e2e
npm run test:ui
```

Opens Playwright's interactive UI for:
- Step-by-step test execution
- Visual debugging
- Test recording

### Test Report Analysis

After running tests:
- **HTML Report**: `tests/e2e/reports/html/index.html`
- **Screenshots**: `tests/e2e/reports/screenshots/`
- **Videos**: `tests/e2e/reports/videos/`
- **Application Logs**: `tests/e2e/logs/application.log`

## 📝 Writing New Tests

### Page Object Model Pattern

```javascript
// pages/ExamplePage.js
const { BasePage } = require('./BasePage');

class ExamplePage extends BasePage {
    constructor(page) {
        super(page);
        this.someButton = 'button:has-text("Submit")';
        this.someInput = 'input[name="example"]';
    }

    async doSomething() {
        await this.fillInput(this.someInput, 'test value');
        await this.clickElement(this.someButton);
        await this.waitForPageLoad();
    }
}
```

### Test Specification

```javascript
// tests/example/example.spec.js
const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { ExamplePage } = require('../../pages/ExamplePage');

test.describe('Example Feature', () => {
    test('Should perform example action', async ({ page }) => {
        const authPage = new AuthPage(page);
        const examplePage = new ExamplePage(page);

        // Login
        await authPage.login('username', 'password');

        // Perform test action
        await examplePage.doSomething();

        // Verify result
        await expect(page).toHaveURL(/expected-url/);
    });
});
```

## 🔄 Maintenance

### Updating Test Data

To update test credentials:
1. Edit `fixtures/users.js`
2. Update credentials for affected users
3. Run tests to verify

### Adding New Page Objects

1. Create new file in `pages/` directory
2. Extend from `BasePage`
3. Implement page-specific methods
4. Add to test specifications

### Updating Dependencies

```bash
cd tests/e2e
npm update
npx playwright install
```

## 🚨 Troubleshooting

### Common Issues

**Port Already in Use:**
```bash
# Check what's using the port
lsof -ti:8010

# Kill processes
kill -9 $(lsof -ti:8010)

# Or use different port
./run-tests.sh --port 8020
```

**Database Connection Errors:**
```bash
# Reset database
php artisan migrate:fresh --seed

# Check database config
cat .env.testing
```

**Browser Issues:**
```bash
# Reinstall Playwright browsers
cd tests/e2e
npx playwright install chromium --force
```

**Test Timeouts:**
- Increase timeout in `playwright.config.js`
- Check application logs in `tests/e2e/logs/application.log`
- Run with `--debug` for detailed output

### Getting Help

1. Check application logs: `tests/e2e/logs/application.log`
2. Review test reports: `tests/e2e/reports/html/index.html`
3. Run with debug mode: `./run-tests.sh --debug`
4. Examine screenshots on failure

## 📈 Performance

### Test Execution Time

- **Full Test Suite**: ~2-5 minutes
- **Authentication Only**: ~30 seconds
- **Single Role Tests**: ~1-2 minutes

### Optimization Tips

- Run specific test types when possible
- Use parallel execution for non-dependent tests
- Optimize test data setup
- Monitor test execution trends

## 🔐 Security

### Test Credentials

Test credentials are intentionally simple and should:
- Never be used in production
- Be different from production credentials
- Be rotated regularly if sensitive

### Data Privacy

Test database contains:
- Seeded test data only
- No real user information
- Automatically reset on each test run

## 📚 Additional Resources

- [Playwright Documentation](https://playwright.dev/)
- [Laravel Testing](https://laravel.com/docs/testing)
- [Page Object Model Pattern](https://www.selenium.dev/documentation/test_practices/encouraged/page_object_models/)