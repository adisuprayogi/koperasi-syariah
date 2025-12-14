# Functional Testing Guide - Koperasi Syariah E2E Tests

## Overview

This guide provides comprehensive documentation for running and maintaining functional tests for the Koperasi Syariah application. Functional tests verify that core business functionality works correctly from end to end.

## Table of Contents

1. [Architecture](#architecture)
2. [Test Structure](#test-structure)
3. [Running Functional Tests](#running-functional-tests)
4. [Test Categories](#test-categories)
5. [Test Data Management](#test-data-management)
6. [Best Practices](#best-practices)
7. [Troubleshooting](#troubleshooting)
8. [Maintenance](#maintenance)

## Architecture

### Test Framework
- **Playwright**: Modern E2E testing framework
- **JavaScript/Node.js**: Test implementation language
- **Page Object Model**: Maintainable test architecture
- **Fixtures Pattern**: Reusable test data

### Directory Structure
```
tests/e2e/
├── tests/
│   ├── functional/
│   │   ├── anggota-management.spec.js
│   │   ├── simpanan.spec.js
│   │   ├── pinjaman.spec.js
│   │   ├── angsuran.spec.js
│   │   └── laporan.spec.js
│   └── auth/
├── fixtures/
│   ├── users.js
│   └── businessData.js
├── pages/
│   └── BasePage.js
├── docs/
├── reports/
└── package.json
```

## Running Functional Tests

### Quick Start

1. **Ensure application is running:**
   ```bash
   php artisan serve --port=8010
   ```

2. **Run all functional tests:**
   ```bash
   ./run-tests-quick.sh functional --headed --url http://localhost:8010
   ```

3. **Run specific functional test:**
   ```bash
   ./run-tests-quick.sh simpanan --headed
   ./run-tests-quick.sh pinjaman --headed
   ./run-tests-quick.sh angsuran --headed
   ./run-tests-quick.sh laporan --headed
   ./run-tests-quick.sh anggota-management --headed
   ```

### Using npm scripts

```bash
# From tests/e2e directory
npm run test:functional
npm run test:simpanan
npm run test:pinjaman
npm run test:angsuran
npm run test:laporan
npm run test:anggota-management
```

### Browser Options

- **Headless (default)**: Tests run in background
- **Headed**: Show browser during testing
  ```bash
  ./run-tests-quick.sh functional --headed
  ```

### Environment Configuration

Functional tests use `.env.testing` configuration:
```bash
APP_URL=http://localhost:8010
DB_CONNECTION=mysql
DB_DATABASE=koperasi_syariah_testing
```

## Test Categories

### 1. Manajemen Anggota (anggota-management.spec.js)

**Scope**: Complete CRUD operations for member management

**Test Cases**:
- `[ANGGOTA-001]` Create new anggota with valid data
- `[ANGGOTA-002]` Edit existing anggota
- `[ANGGOTA-003]` Search and filter anggota
- `[ANGGOTA-004]` View anggota details
- `[ANGGOTA-005]` Attempt to create duplicate anggota
- `[ANGGOTA-006]` Test pagination
- `[ANGGOTA-007]` Delete anggota
- `[ANGGOTA-008]` Export anggota data

**Prerequisites**:
- Login as admin
- Navigate to Data Anggota menu

### 2. Simpanan (simpanan.spec.js)

**Scope**: Deposit management functionality

**Test Cases**:
- `[SIMPANAN-001]` Tambah Simpanan Pokok
- `[SIMPANAN-002]` Tambah Simpanan Wajib
- `[SIMPANAN-003]` Tambah Simpanan Sukarela
- `[SIMPANAN-004]` Tarik Simpanan
- `[SIMPANAN-005]` View Saldo Simpanan
- `[SIMPANAN-006]` Filter by jenis simpanan
- `[SIMPANAN-007]` Filter by date range
- `[SIMPANAN-008]` Search by anggota name
- `[SIMPANAN-009]` Export functionality
- `[SIMPANAN-010]` Validation testing

**Prerequisites**:
- Login as pengurus
- Existing anggota data

### 3. Pinjaman (pinjaman.spec.js)

**Scope**: Loan application and approval workflow

**Test Categories**:

#### Pengajuan Pinjaman (Anggota Role)
- `[PINJAMAN-001]` Ajukan Pinjaman Kecil
- `[PINJAMAN-002]` Ajukan Pinjaman Menengah
- `[PINJAMAN-003]` View Application Status
- `[PINJAMAN-004]` Application validation

#### Persetujuan Pinjaman (Pengurus Role)
- `[PINJAMAN-005]` View pending applications
- `[PINJAMAN-006]` Approve loan application
- `[PINJAMAN-007]` Reject loan application
- `[PINJAMAN-008]` Edit approved loan details
- `[PINJAMAN-009]` Export functionality
- `[PINJAMAN-010]` Filter by status and date

**Prerequisites**:
- Anggota for applications
- Pengurus for approvals
- Existing pinjaman data for edits

### 4. Angsuran (angsuran.spec.js)

**Scope**: Payment processing and tracking

**Test Categories**:

#### Bayar Angsuran (Pengurus Role)
- `[ANGSURAN-001]` Bayar angsuran bulanan
- `[ANGSURAN-002]` Bayar angsuran pelunasan
- `[ANGSURAN-003]` Bayar angsuran terlambat dengan denda
- `[ANGSURAN-004]` View jadwal angsuran
- `[ANGSURAN-005]` View riwayat pembayaran
- `[ANGSURAN-006]` Filter by status
- `[ANGSURAN-007]` Filter by date range
- `[ANGSURAN-008]` Search functionality
- `[ANGSURAN-009]` Payment validation
- `[ANGSURAN-010]` Export functionality

#### View Angsuran (Anggota Role)
- `[ANGSURAN-011]` View personal angsuran schedule
- `[ANGSURAN-012]` View payment history
- `[ANGSURAN-013]` View outstanding balance

**Prerequisites**:
- Approved pinjaman with outstanding balance
- Pengurus for processing payments

### 5. Laporan (laporan.spec.js)

**Scope**: Report generation and export functionality

**Test Cases**:
- `[LAPORAN-001]` Generate Laporan Simpanan
- `[LAPORAN-002]` Generate Laporan Pinjaman
- `[LAPORAN-003]` Generate Laporan Laba Rugi
- `[LAPORAN-004]` Generate Laporan Neraca
- `[LAPORAN-005]` Generate Laporan Tunggakan
- `[LAPORAN-006]` Custom date range reports
- `[LAPORAN-007]` Test filters and options
- `[LAPORAN-008]` Print functionality
- `[LAPORAN-009]` Validation and error handling
- `[LAPORAN-010]` Performance and pagination

**Prerequisites**:
- Transactional data in the system
- Various date ranges for comprehensive testing

## Test Data Management

### Fixtures Structure

#### Users (`fixtures/users.js`)
```javascript
export const USERS = {
    anggota: {
        username: '2512.00001',
        password: '22222222',
        role: 'anggota'
    },
    pengurus: {
        email: 'yogi@gmail.com',
        password: '22222222',
        role: 'pengurus'
    },
    admin: {
        email: 'admin@admin.com',
        password: 'password',
        role: 'admin'
    }
};
```

#### Business Data (`fixtures/businessData.js`)
```javascript
export const BUSINESS_DATA = {
    newAnggota: {
        valid: { /* test data */ },
        duplicate: { /* test data */ },
        invalid: { /* test data */ }
    },
    simpanan: {
        pokok: { /* test data */ },
        wajib: { /* test data */ },
        sukarela: { /* test data */ }
    },
    pinjaman: {
        aplikasi: { /* test data */ },
        persetujuan: { /* test data */ }
    }
};
```

### Data Generation Helpers

```javascript
// Generate unique test data
const testData = {
    nomor_anggota: BUSINESS_DATA.generators.generateNomorAnggota(),
    email: BUSINESS_DATA.generators.generateEmail('Test User'),
    telepon: BUSINESS_DATA.generators.generateTelepon()
};
```

### Data Cleanup

Functional tests should:
- Use unique identifiers for test data
- Clean up created data after tests
- Use separate test database
- Reset database state between tests

## Best Practices

### Test Design

1. **Test Independence**: Each test should run independently
2. **Descriptive Names**: Use clear test case identifiers
3. **Comprehensive Coverage**: Test happy path and edge cases
4. **Data Validation**: Verify both UI and data persistence
5. **Error Handling**: Test validation and error messages

### Page Object Model

```javascript
// Example usage in tests
const basePage = new BasePage(page);
await basePage.navigateToMenu('Simpanan');
await basePage.fillForm(formData);
await basePage.submitForm();
await basePage.verifySuccessMessage('Simpanan berhasil ditambahkan');
```

### Assertions and Verification

```javascript
// Verify success message
await expect(page.locator('.alert-success')).toContainText('berhasil');

// Verify data in table
const tableRow = page.locator('table tbody tr').filter({ hasText: testData.nama });
await expect(tableRow).toContainText(testData.email);

// Verify navigation
await expect(page.locator('h1')).toContainText('Data Simpanan');
```

### Wait Strategies

```javascript
// Wait for page load
await basePage.waitForPageLoad();

// Wait for specific element
await page.waitForSelector('table tbody tr', { timeout: 15000 });

// Wait for navigation
await expect(page.locator('h1')).toContainText('Detail', { timeout: 10000 });
```

## Troubleshooting

### Common Issues

1. **Database Connection**
   ```
   Error: SQLSTATE[HY000] [1045] Access denied
   Solution: Check .env.testing database credentials
   ```

2. **Application Not Running**
   ```
   Error: Application is not responding at APP_URL
   Solution: Start Laravel application with php artisan serve
   ```

3. **Missing Application Key**
   ```
   Error: No application encryption key has been specified
   Solution: Run php artisan key:generate in testing environment
   ```

4. **Element Not Found**
   ```
   Error: Timeout waiting for selector
   Solution: Check if element exists, increase timeout, or verify page loaded
   ```

5. **Test Data Conflicts**
   ```
   Error: Duplicate entry constraint violation
   Solution: Use unique test data generators
   ```

### Debug Mode

```bash
# Run with debug mode
./run-tests-quick.sh functional --debug

# Run with UI mode
cd tests/e2e && npx playwright test --ui

# Run with step-by-step execution
npx playwright test --debug
```

### Logs and Reports

- **Test Reports**: `tests/e2e/reports/html/index.html`
- **Screenshots**: `tests/e2e/reports/screenshots/`
- **Videos**: `tests/e2e/reports/videos/`
- **JUnit XML**: `tests/e2e/reports/junit.xml`

## Maintenance

### Regular Tasks

1. **Update Test Data**
   - Review and update test fixtures
   - Ensure data reflects current business rules
   - Add new test scenarios for features

2. **Test Review**
   - Analyze test failure patterns
   - Update flaky tests
   - Optimize test performance

3. **Documentation Updates**
   - Keep this guide current
   - Update test case descriptions
   - Document new test categories

### Performance Optimization

1. **Test Execution Order**
   - Arrange tests logically
   - Minimize test dependencies
   - Optimize wait times

2. **Resource Management**
   - Clean up test data
   - Use efficient selectors
   - Minimize browser resource usage

### CI/CD Integration

```bash
# GitHub Actions workflow
name: E2E Functional Tests
on: [push, pull_request]
jobs:
  functional-tests:
    runs-on: ubuntu-latest
    steps:
      - uses: actions/checkout@v2
      - name: Setup PHP
        uses: shivammathur/setup-php@v2
        with:
          php-version: '8.2'
      - name: Install Dependencies
        run: composer install
      - name: Setup Database
        run: |
          mysql -u root -e "CREATE DATABASE koperasi_syariah_testing;"
      - name: Run Migrations
        run: php artisan migrate --database=koperasi_syariah_testing
      - name: Install Node.js
        uses: actions/setup-node@v2
        with:
          node-version: '18'
      - name: Install Playwright
        run: cd tests/e2e && npm ci && npx playwright install
      - name: Run Functional Tests
        run: cd tests/e2e && npm run test:functional
```

## Quick Reference

### Test Commands

```bash
# All functional tests
./run-tests-quick.sh functional --headed

# Specific tests
./run-tests-quick.sh anggota-management --headed
./run-tests-quick.sh simpanan --headed
./run-tests-quick.sh pinjaman --headed
./run-tests-quick.sh angsuran --headed
./run-tests-quick.sh laporan --headed

# With custom URL
./run-tests-quick.sh functional --headed --url http://localhost:8010

# Using npm
cd tests/e2e
npm run test:functional
npm run test:anggota-management
```

### Test Identification

All functional tests use standardized IDs:
- `[ANGGOTA-XXX]` - Anggota management tests
- `[SIMPANAN-XXX]` - Simpanan tests
- `[PINJAMAN-XXX]` - Pinjaman tests
- `[ANGSURAN-XXX]` - Angsuran tests
- `[LAPORAN-XXX]` - Laporan tests

### Environment Setup

1. Ensure Laravel application running
2. Database configured for testing
3. Test fixtures updated with current credentials
4. Playwright browsers installed

This comprehensive functional testing suite ensures that all critical business functionality of the Koperasi Syariah application works correctly and maintains quality standards.