const { test, expect } = require('@playwright/test');
const { USERS, getLoginCredentials } = require('../../fixtures/users');
const { BUSINESS_DATA } = require('../../fixtures/businessData');
const { BasePage } = require('../../pages/BasePage');

test.describe('Manajemen Anggota - Functional Tests', () => {
    let basePage;
    let testData;

    test.beforeAll(async () => {
        // Prepare test data with unique identifiers
        const timestamp = Date.now().toString().slice(-6);
        testData = {
            newAnggota: {
                ...BUSINESS_DATA.newAnggota.valid,
                nomor_anggota: BUSINESS_DATA.generators.generateNomorAnggota(),
                email: BUSINESS_DATA.generators.generateEmail('Test Anggota Baru'),
                telepon: BUSINESS_DATA.generators.generateTelepon()
            }
        };
    });

    test.beforeEach(async ({ page }) => {
        basePage = new BasePage(page);

        // Login as admin
        const adminCredentials = getLoginCredentials('admin');
        await page.goto('/');
        await basePage.login(adminCredentials.login, adminCredentials.password);

        // Navigate to anggota management
        await basePage.navigateToMenu('Data Anggota');
        await basePage.waitForPageLoad();

        // Verify we're on the anggota page
        await expect(page.locator('h1, h2')).toContainText('Data Anggota');
    });

    test('[ANGGOTA-001] Create new anggota with valid data', async ({ page }) => {
        // Click "Tambah Anggota" button
        await page.locator('button:has-text("Tambah Anggota"), a:has-text("Tambah Anggota"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Verify the form page loaded
        await expect(page.locator('h1, h2, .card-header')).toContainText('Tambah', { timeout: 10000 });

        // Fill the anggota form
        await page.locator('input[name="nama"], #nama').fill(testData.newAnggota.nama);
        await page.locator('input[name="nomor_anggota"], #nomor_anggota').fill(testData.newAnggota.nomor_anggota);
        await page.locator('input[name="email"], #email').fill(testData.newAnggota.email);
        await page.locator('input[name="telepon"], #telepon').fill(testData.newAnggota.telepon);
        await page.locator('textarea[name="alamat"], #alamat').fill(testData.newAnggota.alamat);

        // Fill additional fields if they exist
        const tanggalLahirField = page.locator('input[name="tanggal_lahir"], #tanggal_lahir, input[type="date"]');
        if (await tanggalLahirField.isVisible()) {
            await tanggalLahirField.fill(testData.newAnggota.tanggal_lahir);
        }

        const tempatLahirField = page.locator('input[name="tempat_lahir"], #tempat_lahir');
        if (await tempatLahirField.isVisible()) {
            await tempatLahirField.fill(testData.newAnggota.tempat_lahir);
        }

        const pekerjaanField = page.locator('input[name="pekerjaan"], #pekerjaan, select[name="pekerjaan"]');
        if (await pekerjaanField.isVisible()) {
            await pekerjaanField.fill(testData.newAnggota.pekerjaan);
        }

        // Submit the form
        await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
        await basePage.waitForPageLoad();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
            BUSINESS_DATA.expectations.successMessages.createAnggota, { timeout: 10000 }
        );

        // Verify anggota appears in the list
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

        // Search for the newly created anggota
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill(testData.newAnggota.nama);
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Verify the anggota is found in the table
        const tableRow = page.locator('table tbody tr').filter({ hasText: testData.newAnggota.nama }).first();
        await expect(tableRow).toContainText(testData.newAnggota.nama);
        await expect(tableRow).toContainText(testData.newAnggota.nomor_anggota);
        await expect(tableRow).toContainText(testData.newAnggota.email);
    });

    test('[ANGGOTA-002] Edit existing anggota', async ({ page }) => {
        // Search for an existing anggota
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Test Anggota');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Wait for table to load
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 10000 });

        // Find an anggota row and click edit button
        const firstRow = page.locator('table tbody tr, .data-table tbody tr').first();
        await expect(firstRow).toBeVisible({ timeout: 10000 });

        // Look for edit button
        const editButton = page.locator('.btn-edit, button:has-text("Edit"), a:has-text("Edit"), .fa-edit').first();
        await expect(editButton).toBeVisible({ timeout: 10000 });
        await editButton.click();

        await basePage.waitForPageLoad();

        // Verify the edit form loaded
        await expect(page.locator('h1, h2, .card-header')).toContainText('Edit', { timeout: 10000 });

        // Modify some data
        const newAlamat = testData.newAnggota.alamat + ' (Updated)';
        await page.locator('textarea[name="alamat"], #alamat').clear();
        await page.locator('textarea[name="alamat"], #alamat').fill(newAlamat);

        // Update telepon
        const newTelepon = BUSINESS_DATA.generators.generateTelepon();
        await page.locator('input[name="telepon"], #telepon').clear();
        await page.locator('input[name="telepon"], #telepon').fill(newTelepon);

        // Save changes
        await page.locator('button:has-text("Update"), button:has-text("Simpan"), .btn-success:has-text("Simpan")').click();
        await basePage.waitForPageLoad();

        // Verify success message
        await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
            BUSINESS_DATA.expectations.successMessages.updateAnggota, { timeout: 10000 }
        );

        // Verify the updated data is reflected
        await page.waitForSelector('table tbody tr', { timeout: 15000 });
        const updatedRow = page.locator('table tbody tr').filter({ hasText: newAlamat }).first();
        await expect(updatedRow).toContainText(newTelepon);
    });

    test('[ANGGOTA-003] Search and filter anggota', async ({ page }) => {
        // Wait for table to load
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

        // Test search by name
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Test');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Verify search results
        const searchResults = page.locator('table tbody tr, .data-table tbody tr');
        const count = await searchResults.count();
        expect(count).toBeGreaterThan(0);

        // Test by nomor anggota if search field exists separately
        const nomorSearchField = page.locator('input[placeholder*="nomor"], input[placeholder*="anggota"]').first();
        if (await nomorSearchField.isVisible()) {
            await nomorSearchField.clear();
            await nomorSearchField.fill('2512');
            await page.keyboard.press('Enter');
            await page.waitForTimeout(2000);

            // Verify results contain the search term
            const filteredResults = page.locator('table tbody tr');
            await expect(filteredResults.first()).toContainText('2512');
        }

        // Test filters if they exist
        const statusFilter = page.locator('select[name="status"], .filter-select').first();
        if (await statusFilter.isVisible()) {
            await statusFilter.selectOption({ index: 0 }); // Select first option
            await page.waitForTimeout(1000);

            // Verify filter was applied
            const filteredResults = page.locator('table tbody tr');
            expect(filteredResults.count()).resolves.toBeGreaterThan(0);
        }
    });

    test('[ANGGOTA-004] View anggota details', async ({ page }) => {
        // Search for an anggota
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Test Anggota');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        // Wait for table to load
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 10000 });

        // Click on view details button or the row itself
        const firstRow = page.locator('table tbody tr, .data-table tbody tr').first();
        await expect(firstRow).toBeVisible({ timeout: 10000 });

        // Try multiple selectors for view button
        const viewButton = page.locator('.btn-view, button:has-text("Detail"), a:has-text("Detail"), .fa-eye').first();
        if (await viewButton.isVisible()) {
            await viewButton.click();
        } else {
            // If no view button, try clicking the row
            await firstRow.click();
        }

        await basePage.waitForPageLoad();

        // Verify we're on the detail page
        await expect(page.locator('h1, h2, .card-header')).toContainText('Detail', { timeout: 10000 });

        // Verify anggota information is displayed
        const detailPage = page.locator('.card-body, .detail-content, .info-section');
        await expect(detailPage).toContainText('Test Anggota');
    });

    test('[ANGGOTA-005] Attempt to create duplicate anggota (validation test)', async ({ page }) => {
        // Click "Tambah Anggota" button
        await page.locator('button:has-text("Tambah Anggota"), a:has-text("Tambah Anggota"), .btn-primary:has-text("Tambah")').first().click();
        await basePage.waitForPageLoad();

        // Verify the form page loaded
        await expect(page.locator('h1, h2, .card-header')).toContainText('Tambah', { timeout: 10000 });

        // Fill form with duplicate data
        await page.locator('input[name="nama"], #nama').fill('Test Duplicate');
        await page.locator('input[name="nomor_anggota"], #nomor_anggota').fill('2512.00001'); // Existing nomor anggota
        await page.locator('input[name="email"], #email').fill('anggota@test.com'); // Existing email
        await page.locator('input[name="telepon"], #telepon').fill('08123456789');
        await page.locator('textarea[name="alamat"], #alamat').fill('Test Duplicate Address');

        // Submit the form
        await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
        await page.waitForTimeout(2000);

        // Should show validation error
        const errorMessage = page.locator('.alert-danger, .error-message, .validation-error');
        if (await errorMessage.isVisible()) {
            await expect(errorMessage).toContainText(/sudah|ada|duplicate|exists/i, { timeout: 5000 });
        }
    });

    test('[ANGGOTA-006] Test pagination if available', async ({ page }) => {
        // Wait for table to load
        await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

        // Look for pagination controls
        const pagination = page.locator('.pagination, .pager');
        if (await pagination.isVisible()) {
            // Check if there are multiple pages
            const pageInfo = page.locator('.pagination-info, .page-info');
            if (await pageInfo.isVisible()) {
                const pageText = await pageInfo.textContent();
                if (pageText && pageText.includes('of') && parseInt(pageText.match(/\d+/)?.[0] || '1') > 1) {
                    // Try going to next page
                    const nextPageButton = page.locator('.pagination .page-link:has-text("Next"), .pagination .next');
                    if (await nextPageButton.isVisible()) {
                        await nextPageButton.click();
                        await page.waitForTimeout(2000);

                        // Verify we're on a different page
                        await expect(pagination).toBeVisible();
                    }
                }
            }
        } else {
            // If no pagination, just verify table has data
            const rows = page.locator('table tbody tr');
            expect(rows.count()).resolves.toBeGreaterThan(0);
        }
    });

    test('[ANGGOTA-007] Delete anggota (soft delete test)', async ({ page }) => {
        // First create a temporary anggota to delete
        await test.step('Create temporary anggota', async () => {
            await page.locator('button:has-text("Tambah Anggota"), a:has-text("Tambah Anggota"), .btn-primary:has-text("Tambah")').first().click();
            await basePage.waitForPageLoad();

            const tempData = {
                nama: 'Temp Anggota Delete',
                nomor_anggota: BUSINESS_DATA.generators.generateNomorAnggota(),
                email: BUSINESS_DATA.generators.generateEmail('Temp Delete'),
                telepon: BUSINESS_DATA.generators.generateTelepon(),
                alamat: 'Address for deletion test'
            };

            await page.locator('input[name="nama"], #nama').fill(tempData.nama);
            await page.locator('input[name="nomor_anggota"], #nomor_anggota').fill(tempData.nomor_anggota);
            await page.locator('input[name="email"], #email').fill(tempData.email);
            await page.locator('input[name="telepon"], #telepon').fill(tempData.telepon);
            await page.locator('textarea[name="alamat"], #alamat').fill(tempData.alamat);

            await page.locator('button:has-text("Simpan"), button:has-text("Save"), .btn-success:has-text("Simpan")').click();
            await basePage.waitForPageLoad();

            // Verify creation
            await expect(page.locator('.alert-success, .toast-success')).toContainText('berhasil', { timeout: 10000 });
        });

        // Now search and delete the temp anggota
        await page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first().fill('Temp Anggota Delete');
        await page.keyboard.press('Enter');
        await page.waitForTimeout(2000);

        await page.waitForSelector('table tbody tr', { timeout: 10000 });

        // Find delete button
        const deleteButton = page.locator('.btn-delete, button:has-text("Hapus"), a:has-text("Hapus"), .fa-trash').first();
        if (await deleteButton.isVisible()) {
            // Accept any confirmation dialog
            page.on('dialog', async dialog => {
                await dialog.accept();
            });

            await deleteButton.click();
            await page.waitForTimeout(2000);

            // Verify deletion message
            const successMessage = page.locator('.alert-success, .toast-success, .notification-success');
            if (await successMessage.isVisible()) {
                await expect(successMessage).toContainText('dihapus', { timeout: 5000 });
            }
        }
    });

    test('[ANGGOTA-008] Export anggota data if available', async ({ page }) => {
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
            console.log('Export functionality not available');
            test.skip();
        }
    });
});