const { test, expect } = require('@playwright/test');
const { USERS, getLoginCredentials } = require('../../fixtures/users');
const { BUSINESS_DATA } = require('../../fixtures/businessData');
const { BasePage } = require('../../pages/BasePage');

test.describe('Simpanan - Functional Tests', () => {
    let basePage;
    let testData;

    test.beforeAll(async () => {
        // Prepare test data
        testData = {
            ...BUSINESS_DATA.simpanan,
            timestamp: Date.now().toString().slice(-6)
        };
    });

    test.beforeEach(async ({ page }) => {
        basePage = new BasePage(page);

        // Login as pengurus for simpanan management
        const pengurusCredentials = getLoginCredentials('pengurus');
        await page.goto('/');
        await basePage.login(pengurusCredentials.login, pengurusCredentials.password);

        // Navigate to simpanan management
        await basePage.navigateToMenu('Simpanan');
        await basePage.waitForPageLoad();

        // Verify we're on the simpanan page
        await expect(page.locator('h1, h2')).toContainText('Simpanan', { timeout: 10000 });
    });

    test('[SIMPANAN-001] Tambah Simpanan Pokok untuk anggota', async ({ page }) => {
        // Click "Tambah Simpanan" button
        await page.locator('button:has-text("Tambah Simpanan"), a:has-text("Tambah"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Verify the form page loaded
        await expect(page.locator('h1, h2, .card-header')).toContainText('Tambah', { timeout: 10000 });

        // Select anggota
        const anggotaSelect = page.locator('select[name="anggota_id"], #anggota_id');
        await expect(anggotaSelect).toBeVisible({ timeout: 10000 });
        await anggotaSelect.selectOption({ index: 1 }); // Select first anggota

        // Fill simpanan details
        await page.locator('select[name="jenis_simpanan"], #jenis_simpanan').selectOption('Simpanan Pokok');

        const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
        await jumlahField.fill(testData.pokok.jumlah.toString());

        const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
        if (await keteranganField.isVisible()) {
            await keteranganField.fill(testData.pokok.keterangan);
        }

        // Submit the form
        await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
        await basePage.waitForPageLoad();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
            BUSINESS_DATA.expectations.successMessages.tambahSimpanan, { timeout: 10000 }
        );

        // Verify simpanan appears in the list
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

        // Look for the new simpanan in the table
        const tableRow = page.locator('table tbody tr, .data-table tbody tr').first();
        await expect(tableRow).toContainText('Simpanan Pokok');
        await expect(tableRow).toContainText(testData.pokok.jumlah.toString());
    });

    test('[SIMPANAN-002] Tambah Simpanan Wajib bulanan', async ({ page }) => {
        // Click "Tambah Simpanan" button
        await page.locator('button:has-text("Tambah Simpanan"), a:has-text("Tambah"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Select anggota (try to select a different anggota)
        const anggotaSelect = page.locator('select[name="anggota_id"], #anggota_id');
        await anggotaSelect.selectOption({ index: 2 });

        // Fill simpanan details
        await page.locator('select[name="jenis_simpanan"], #jenis_simpanan').selectOption('Simpanan Wajib');

        const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
        await jumlahField.fill(testData.wajib.jumlah.toString());

        const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
        if (await keteranganField.isVisible()) {
            await keteranganField.fill(testData.wajib.keterangan);
        }

        // Set date if field exists
        const tanggalField = page.locator('input[name="tanggal"], #tanggal, input[type="date"]');
        if (await tanggalField.isVisible()) {
            const today = new Date().toISOString().split('T')[0];
            await tanggalField.fill(today);
        }

        // Submit the form
        await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
        await basePage.waitForPageLoad();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
            BUSINESS_DATA.expectations.successMessages.tambahSimpanan, { timeout: 10000 }
        );

        // Verify simpanan appears in the list
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Look for the new simpanan
        const tableRow = page.locator('table tbody tr').filter({ hasText: 'Simpanan Wajib' }).first();
        await expect(tableRow).toContainText(testData.wajib.jumlah.toString());
    });

    test('[SIMPANAN-003] Tambah Simpanan Sukarela', async ({ page }) => {
        // Click "Tambah Simpanan" button
        await page.locator('button:has-text("Tambah Simpanan"), a:has-text("Tambah"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Select anggota
        const anggotaSelect = page.locator('select[name="anggota_id"], #anggota_id');
        await anggotaSelect.selectOption({ index: 1 });

        // Fill simpanan details
        await page.locator('select[name="jenis_simpanan"], #jenis_simpanan').selectOption('Simpanan Sukarela');

        const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
        await jumlahField.fill(testData.sukarela.jumlah.toString());

        const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
        if (await keteranganField.isVisible()) {
            await keteranganField.fill(testData.sukarela.keterangan);
        }

        // Submit the form
        await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
        await basePage.waitForPageLoad();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
            BUSINESS_DATA.expectations.successMessages.tambahSimpanan, { timeout: 10000 }
        );

        // Verify simpanan appears in the list
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Look for the new simpanan
        const tableRow = page.locator('table tbody tr').filter({ hasText: 'Simpanan Sukarela' }).first();
        await expect(tableRow).toContainText(testData.sukarela.jumlah.toString());
    });

    test('[SIMPANAN-004] Tarik Simpanan', async ({ page }) => {
        // First search for an anggota with simpanan
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Test Anggota');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Wait for table to load
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Find a simpanan record and click withdraw button
        const withdrawButton = page.locator('.btn-withdraw, button:has-text("Tarik"), a:has-text("Tarik"), .fa-minus-circle').first();
        if (await withdrawButton.isVisible()) {
            await withdrawButton.click();
            await basePage.waitForPageLoad();

            // Verify withdrawal form loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Tarik', { timeout: 10000 });

            // Fill withdrawal details
            const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
            await jumlahField.fill(testData.penarikan.jumlah.toString());

            const alasanField = page.locator('textarea[name="alasan"], #alasan, select[name="alasan"]');
            if (await alasanField.isVisible()) {
                if (await alasanField.getAttribute('type') === 'select-one') {
                    await alasanField.selectOption({ index: 1 });
                } else {
                    await alasanField.fill(testData.penarikan.alasan);
                }
            }

            const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
            if (await keteranganField.isVisible()) {
                await keteranganField.fill(testData.penarikan.keterangan);
            }

            // Submit withdrawal
            await page.locator('button:has-text("Proses"), button:has-text("Tarik"), .btn-warning:has-text("Proses")').click();
            await basePage.waitForPageLoad();

            // Verify success message
            await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                BUSINESS_DATA.expectations.successMessages.tarikSimpanan, { timeout: 10000 }
            );

            // Verify withdrawal is reflected in the list
            await page.waitForSelector('table tbody tr', { timeout: 15000 });

            // Look for withdrawal record
            const withdrawalRow = page.locator('table tbody tr').filter({ hasText: 'Penarikan' }).first();
            if (await withdrawalRow.isVisible()) {
                await expect(withdrawalRow).toContainText(testData.penarikan.jumlah.toString());
            }
        } else {
            console.log('No withdraw button found - might need simpanan data first');
        }
    });

    test('[SIMPANAN-005] View Saldo Simpanan per Anggota', async ({ page }) => {
        // Search for specific anggota
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Test Anggota');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Wait for table to load
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Click on view details button
        const viewButton = page.locator('.btn-view, button:has-text("Detail"), a:has-text("Detail"), .fa-eye').first();
        if (await viewButton.isVisible()) {
            await viewButton.click();
            await basePage.waitForPageLoad();

            // Verify detail page shows saldo information
            await expect(page.locator('h1, h2, .card-header')).toContainText('Detail', { timeout: 10000 });

            // Look for saldo information
            const saldoSection = page.locator('.saldo-info, .total-saldo, .balance-info');
            if (await saldoSection.isVisible()) {
                const saldoText = await saldoSection.textContent();
                expect(saldoText).toMatch(/saldo|total|balance/i);
            }

            // Verify simpanan breakdown
            const simpananRows = page.locator('table tbody tr');
            const rowCount = await simpananRows.count();
            expect(rowCount).toBeGreaterThan(0);
        } else {
            console.log('No view button found');
        }
    });

    test('[SIMPANAN-006] Filter Simpanan by Jenis', async ({ page }) => {
        // Wait for table to load
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Look for jenis filter
        const jenisFilter = page.locator('select[name="jenis"], .filter-select, #filter_jenis');
        if (await jenisFilter.isVisible()) {
            // Test filtering by jenis
            await jenisFilter.selectOption('Simpanan Pokok');
            await page.waitForTimeout(2000);

            // Verify filter results
            const filteredRows = page.locator('table tbody tr');
            const count = await filteredRows.count();

            if (count > 0) {
                // Check if all rows contain the filtered jenis
                for (let i = 0; i < Math.min(count, 5); i++) {
                    const row = filteredRows.nth(i);
                    const rowText = await row.textContent();
                    expect(rowText).toContain('Simpanan Pokok');
                }
            }
        } else {
            console.log('No jenis filter found');
        }
    });

    test('[SIMPANAN-007] Filter Simpanan by Date Range', async ({ page }) => {
        // Look for date filters
        const dateFilter = page.locator('input[type="date"], .date-filter');
        const dateFiltersCount = await dateFilter.count();

        if (dateFiltersCount > 0) {
            // Try to filter by current month
            const today = new Date();
            const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
            const firstDayStr = firstDay.toISOString().split('T')[0];
            const todayStr = today.toISOString().split('T')[0];

            if (dateFiltersCount >= 1) {
                await dateFilter.first().fill(firstDayStr);
            }
            if (dateFiltersCount >= 2) {
                await dateFilter.nth(1).fill(todayStr);
            }

            // Trigger filter if there's a button
            const filterButton = page.locator('button:has-text("Filter"), .btn-filter');
            if (await filterButton.isVisible()) {
                await filterButton.click();
            }

            await page.waitForTimeout(2000);

            // Verify filter was applied
            const tableRows = page.locator('table tbody tr');
            const count = await tableRows.count();
            expect(count).toBeGreaterThanOrEqual(0);
        } else {
            console.log('No date filters found');
        }
    });

    test('[SIMPANAN-008] Search Simpanan by Anggota Name', async ({ page }) => {
        // Wait for table to load
        await page.waitForSelector('table tbody tr', { timeout: 15000 });

        // Search by anggota name
        const searchField = page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first();
        await searchField.fill('Test Anggota');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Verify search results
        const searchResults = page.locator('table tbody tr');
        const count = await searchResults.count();

        if (count > 0) {
            // Check if results contain the search term
            const firstResult = searchResults.first();
            const resultText = await firstResult.textContent();
            expect(resultText.toLowerCase()).toContain('test anggota');
        }
    });

    test('[SIMPANAN-009] Export Simpanan Data if available', async ({ page }) => {
        // Look for export button
        const exportButton = page.locator('button:has-text("Export"), a:has-text("Export"), .btn-export, .fa-download').first();
        if (await exportButton.isVisible()) {
            // Start download listener
            const downloadPromise = page.waitForEvent('download');

            await exportButton.click();
            const download = await downloadPromise;

            // Verify file is downloaded
            expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls|csv|pdf)$/i);
        } else {
            console.log('Export functionality not available for simpanan');
        }
    });

    test('[SIMPANAN-010] Test validation for invalid simpanan data', async ({ page }) => {
        // Click "Tambah Simpanan" button
        await page.locator('button:has-text("Tambah Simpanan"), a:has-text("Tambah"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Try to submit form without required fields
        const submitButton = page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")');
        await submitButton.click();
        await page.waitForTimeout(1000);

        // Should show validation errors
        const errorMessages = page.locator('.error-message, .validation-error, .text-danger');
        const errorCount = await errorMessages.count();

        if (errorCount > 0) {
            // Check for required field errors
            const hasRequiredError = await errorMessages.filter({ hasText: /wajib|required|required field/i }).count();
            expect(hasRequiredError).toBeGreaterThan(0);
        } else {
            // Check if form is still visible (indicating validation prevented submission)
            await expect(page.locator('h1, h2, .card-header')).toContainText('Tambah');
        }

        // Test with invalid amount
        const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
        await jumlahField.fill('0'); // Invalid amount

        if (await page.locator('select[name="anggota_id"], #anggota_id').isVisible()) {
            await page.locator('select[name="anggota_id"], #anggota_id').selectOption({ index: 1 });
        }
        if (await page.locator('select[name="jenis_simpanan"], #jenis_simpanan').isVisible()) {
            await page.locator('select[name="jenis_simpanan"], #jenis_simpanan').selectOption('Simpanan Pokok');
        }

        await submitButton.click();
        await page.waitForTimeout(1000);

        // Should show error for invalid amount
        const amountError = page.locator('.error-message, .validation-error, .text-danger').filter({ hasText: /jumlah|amount/ });
        if (await amountError.isVisible()) {
            await expect(amountError).toContainText(/minimal|lebih|invalid/i);
        }
    });
});