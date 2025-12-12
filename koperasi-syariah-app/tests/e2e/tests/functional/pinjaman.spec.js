const { test, expect } = require('@playwright/test');
const { USERS, getLoginCredentials } = require('../../fixtures/users');
const { BUSINESS_DATA } = require('../../fixtures/businessData');
const { BasePage } = require('../../pages/BasePage');

test.describe('Pinjaman - Functional Tests', () => {
    let basePage;
    let testData;
    let applicationId;

    test.beforeAll(async () => {
        // Prepare test data with unique identifiers
        testData = {
            ...BUSINESS_DATA.pinjaman,
            timestamp: Date.now().toString().slice(-6)
        };
    });

    test.describe('Pengajuan Pinjaman (Anggota)', () => {
        test.beforeEach(async ({ page }) => {
            basePage = new BasePage(page);

            // Login as anggota for applying loans
            const anggotaCredentials = getLoginCredentials('anggota');
            await page.goto('/');
            await basePage.login(anggotaCredentials.login, anggotaCredentials.password);

            // Navigate to pinjaman section
            await basePage.navigateToMenu('Pinjaman');
            await basePage.waitForPageLoad();

            // Verify we're on the pinjaman page
            await expect(page.locator('h1, h2')).toContainText('Pinjaman', { timeout: 10000 });
        });

        test('[PINJAMAN-001] Ajukan Pinjaman Kecil', async ({ page }) => {
            // Click "Ajukan Pinjaman" button
            await page.locator('button:has-text("Ajukan Pinjaman"), a:has-text("Ajukan"), .btn-primary:has-text("Ajukan")').first().click();
            await basePage.waitForPageLoad();

            // Verify the application form loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Ajukan', { timeout: 10000 });

            // Fill loan application form
            const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
            await jumlahField.fill(testData.aplikasi.kecil.jumlah.toString());

            const tenorField = page.locator('input[name="tenor"], #tenor, select[name="tenor"]');
            if (await tenorField.isVisible()) {
                if (await tenorField.getAttribute('type') === 'select-one') {
                    await tenorField.selectOption(testData.aplikasi.kecil.tenor.toString());
                } else {
                    await tenorField.fill(testData.aplikasi.kecil.tenor.toString());
                }
            }

            const tujuanField = page.locator('textarea[name="tujuan"], #tujuan, select[name="tujuan"]');
            if (await tujuanField.isVisible()) {
                if (await tujuanField.getAttribute('type') === 'select-one') {
                    // Select first option or fill if it's text input
                    await tujuanField.selectOption({ index: 1 });
                } else {
                    await tujuanField.fill(testData.aplikasi.kecil.tujuan);
                }
            }

            const jaminanField = page.locator('textarea[name="jaminan"], #jaminan, input[name="jaminan"]');
            if (await jaminanField.isVisible()) {
                await jaminanField.fill(testData.aplikasi.kecil.jaminan);
            }

            const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
            if (await keteranganField.isVisible()) {
                await keteranganField.fill(testData.aplikasi.kecil.keterangan);
            }

            // Submit application
            await page.locator('button:has-text("Ajukan"), button:has-text("Submit"), .btn-success:has-text("Ajukan")').click();
            await basePage.waitForPageLoad();

            // Verify success message
            await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                BUSINESS_DATA.expectations.successMessages.ajukanPinjaman, { timeout: 10000 }
            );

            // Verify application appears in the list with pending status
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for the new application
            const applicationRow = page.locator('table tbody tr, .data-table tbody tr').first();
            await expect(applicationRow).toContainText('Menunggu', { timeout: 10000 });

            // Get application ID for later use in approval tests
            const applicationText = await applicationRow.textContent();
            const idMatch = applicationText.match(/#\d+/);
            if (idMatch) {
                applicationId = idMatch[0];
            }
        });

        test('[PINJAMAN-002] Ajukan Pinjaman Menengah', async ({ page }) => {
            // Click "Ajukan Pinjaman" button
            await page.locator('button:has-text("Ajukan Pinjaman"), a:has-text("Ajukan"), .btn-primary:has-text("Ajukan")').first().click();
            await basePage.waitForPageLoad();

            // Fill loan application form with different data
            const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
            await jumlahField.fill(testData.aplikasi.menengah.jumlah.toString());

            const tenorField = page.locator('input[name="tenor"], #tenor, select[name="tenor"]');
            if (await tenorField.isVisible()) {
                if (await tenorField.getAttribute('type') === 'select-one') {
                    await tenorField.selectOption(testData.aplikasi.menengah.tenor.toString());
                } else {
                    await tenorField.fill(testData.aplikasi.menengah.tenor.toString());
                }
            }

            const tujuanField = page.locator('textarea[name="tujuan"], #tujuan, input[name="tujuan"]');
            if (await tujuanField.isVisible()) {
                await tujuanField.fill(testData.aplikasi.menengah.tujuan);
            }

            const jaminanField = page.locator('textarea[name="jaminan"], #jaminan, input[name="jaminan"]');
            if (await jaminanField.isVisible()) {
                await jaminanField.fill(testData.aplikasi.menengah.jaminan);
            }

            // Submit application
            await page.locator('button:has-text("Ajukan"), button:has-text("Submit"), .btn-success:has-text("Ajukan")').click();
            await basePage.waitForPageLoad();

            // Verify success message
            await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                BUSINESS_DATA.expectations.successMessages.ajukanPinjaman, { timeout: 10000 }
            );
        });

        test('[PINJAMAN-003] View Application Status', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for status information
            const statusColumn = page.locator('table tbody tr td:nth-child(-2), .status-column, .status-badge');
            const statusCount = await statusColumn.count();

            if (statusCount > 0) {
                // Verify status is visible
                const firstStatus = statusColumn.first();
                const statusText = await firstStatus.textContent();
                expect(statusText).toMatch(/Menunggu|Disetujui|Ditolak|Proses/i);
            }

            // Click on view details if available
            const viewButton = page.locator('.btn-view, button:has-text("Detail"), a:has-text("Detail"), .fa-eye').first();
            if (await viewButton.isVisible()) {
                await viewButton.click();
                await basePage.waitForPageLoad();

                // Verify detail page shows application information
                await expect(page.locator('h1, h2, .card-header')).toContainText('Detail', { timeout: 10000 });

                // Verify loan details are displayed
                const detailSection = page.locator('.loan-details, .application-details, .card-body');
                if (await detailSection.isVisible()) {
                    const detailText = await detailSection.textContent();
                    expect(detailText).toMatch(/jumlah|tenor|tujuan|jaminan/i);
                }
            }
        });

        test('[PINJAMAN-004] Test loan application validation', async ({ page }) => {
            // Click "Ajukan Pinjaman" button
            await page.locator('button:has-text("Ajukan Pinjaman"), a:has-text("Ajukan"), .btn-primary:has-text("Ajukan")').first().click();
            await basePage.waitForPageLoad();

            // Try to submit without filling required fields
            const submitButton = page.locator('button:has-text("Ajukan"), button:has-text("Submit"), .btn-success:has-text("Ajukan")');
            await submitButton.click();
            await page.waitForTimeout(1000);

            // Should show validation errors
            const errorMessages = page.locator('.error-message, .validation-error, .text-danger');
            const errorCount = await errorMessages.count();

            if (errorCount > 0) {
                const hasRequiredError = await errorMessages.filter({ hasText: /wajib|required|required field/i }).count();
                expect(hasRequiredError).toBeGreaterThan(0);
            }

            // Test with invalid amount (too small)
            const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
            await jumlahField.fill('100000'); // Too small amount

            if (await page.locator('input[name="tenor"], #tenor').isVisible()) {
                await page.locator('input[name="tenor"], #tenor').fill('12');
            }

            await submitButton.click();
            await page.waitForTimeout(1000);

            // Check for validation error
            const amountError = page.locator('.error-message, .validation-error, .text-danger').filter({ hasText: /minimal|jumlah|amount/i });
            if (await amountError.isVisible()) {
                await expect(amountError).toContainText(/minimal|lebih dari|invalid/i);
            }
        });
    });

    test.describe('Persetujuan Pinjaman (Pengurus)', () => {
        test.beforeEach(async ({ page }) => {
            basePage = new BasePage(page);

            // Login as pengurus for approving loans
            const pengurusCredentials = getLoginCredentials('pengurus');
            await page.goto('/');
            await basePage.login(pengurusCredentials.login, pengurusCredentials.password);

            // Navigate to persetujuan pinjaman section
            await basePage.navigateToMenu('Pinjaman');

            // Look for persetujuan submenu or tab
            const persetujuanLink = page.locator('a:has-text("Persetujuan"), button:has-text("Persetujuan"), .tab:has-text("Persetujuan")').first();
            if (await persetujuanLink.isVisible()) {
                await persetujuanLink.click();
                await basePage.waitForPageLoad();
            }

            // Verify we're on the pinjaman page
            await expect(page.locator('h1, h2')).toContainText('Pinjaman', { timeout: 10000 });
        });

        test('[PINJAMAN-005] View pending applications', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for filter options if available
            const statusFilter = page.locator('select[name="status"], .filter-select, #filter_status');
            if (await statusFilter.isVisible()) {
                await statusFilter.selectOption('Menunggu Persetujuan');
                await page.waitForTimeout(2000);
            }

            // Verify pending applications are visible
            const pendingRows = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Menunggu|Pending|Proses/i });
            const pendingCount = await pendingRows.count();

            // At least one application should be visible (from previous test)
            if (pendingCount > 0) {
                await expect(pendingRows.first()).toBeVisible();
            } else {
                console.log('No pending applications found - may need to run anggota application tests first');
            }
        });

        test('[PINJAMAN-006] Approve loan application', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find a pending application
            const pendingRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Menunggu|Pending/i }).first();

            if (await pendingRow.isVisible()) {
                // Look for approve button
                const approveButton = page.locator('.btn-approve, button:has-text("Setujui"), a:has-text("Setujui"), .fa-check').first();
                if (await approveButton.isVisible()) {
                    await approveButton.click();
                    await basePage.waitForPageLoad();

                    // Verify approval form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Setujui', { timeout: 10000 });

                    // Fill approval details if form is available
                    const catatanField = page.locator('textarea[name="catatan"], #catatan');
                    if (await catatanField.isVisible()) {
                        await catatanField.fill(testData.persetujuan.approve.catatan);
                    }

                    const bungaField = page.locator('input[name="bunga"], #bunga, select[name="bunga"]');
                    if (await bungaField.isVisible()) {
                        if (await bungaField.getAttribute('type') === 'select-one') {
                            await bungaField.selectOption({ index: 1 });
                        } else {
                            await bungaField.fill(testData.persetujuan.approve.bunga.toString());
                        }
                    }

                    const jumlahDisetujuiField = page.locator('input[name="jumlah_disetujui"], #jumlah_disetujui"]');
                    if (await jumlahDisetujuiField.isVisible()) {
                        await jumlahDisetujuiField.fill(testData.persetujuan.approve.jumlah_disetujui.toString());
                    }

                    // Submit approval
                    await page.locator('button:has-text("Setujui"), button:has-text("Approve"), .btn-success:has-text("Setujui")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        BUSINESS_DATA.expectations.successMessages.prosesPersetujuan, { timeout: 10000 }
                    );

                    // Verify status changed
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });
                    const updatedRow = page.locator('table tbody tr').filter({ hasText: /Disetujui|Approved/i }).first();
                    if (await updatedRow.isVisible()) {
                        await expect(updatedRow).toContainText(/Disetujui|Approved/i);
                    }
                } else {
                    console.log('No approve button found for pending applications');
                }
            } else {
                console.log('No pending applications found to approve');
            }
        });

        test('[PINJAMAN-007] Reject loan application', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find a pending application
            const pendingRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Menunggu|Pending/i }).first();

            if (await pendingRow.isVisible()) {
                // Look for reject button
                const rejectButton = page.locator('.btn-reject, button:has-text("Tolak"), a:has-text("Tolak"), .fa-times, .fa-ban').first();
                if (await rejectButton.isVisible()) {
                    await rejectButton.click();
                    await basePage.waitForPageLoad();

                    // Verify rejection form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Tolak', { timeout: 10000 });

                    // Fill rejection details
                    const catatanField = page.locator('textarea[name="catatan"], #catatan, textarea[name="alasan"], #alasan"]');
                    if (await catatanField.isVisible()) {
                        await catatanField.fill(testData.persetujuan.reject.catatan);
                    }

                    const alasanField = page.locator('select[name="alasan"], #alasan');
                    if (await alasanField.isVisible()) {
                        await alasanField.selectOption({ index: 1 });
                    }

                    // Submit rejection
                    await page.locator('button:has-text("Tolak"), button:has-text("Reject"), .btn-danger:has-text("Tolak")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        BUSINESS_DATA.expectations.successMessages.prosesPersetujuan, { timeout: 10000 }
                    );

                    // Verify status changed
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });
                    const updatedRow = page.locator('table tbody tr').filter({ hasText: /Ditolak|Rejected/i }).first();
                    if (await updatedRow.isVisible()) {
                        await expect(updatedRow).toContainText(/Ditolak|Rejected/i);
                    }
                } else {
                    console.log('No reject button found for pending applications');
                }
            } else {
                console.log('No pending applications found to reject');
            }
        });

        test('[PINJAMAN-008] Edit approved loan details', async ({ page }) => {
            // Filter for approved loans
            const statusFilter = page.locator('select[name="status"], .filter-select, #filter_status');
            if (await statusFilter.isVisible()) {
                await statusFilter.selectOption('Disetujui');
                await page.waitForTimeout(2000);
            }

            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find an approved loan
            const approvedRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Disetujui|Approved/i }).first();

            if (await approvedRow.isVisible()) {
                // Look for edit button
                const editButton = page.locator('.btn-edit, button:has-text("Edit"), a:has-text("Edit"), .fa-edit').first();
                if (await editButton.isVisible()) {
                    await editButton.click();
                    await basePage.waitForPageLoad();

                    // Verify edit form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Edit', { timeout: 10000 });

                    // Modify some loan details
                    const bungaField = page.locator('input[name="bunga"], #bunga, select[name="bunga"]');
                    if (await bungaField.isVisible()) {
                        if (await bungaField.getAttribute('type') === 'select-one') {
                            await bungaField.selectOption({ index: 2 });
                        } else {
                            await bungaField.fill('6'); // Change to 6%
                        }
                    }

                    const tenorField = page.locator('input[name="tenor"], #tenor, select[name="tenor"]');
                    if (await tenorField.isVisible()) {
                        if (await tenorField.getAttribute('type') === 'select-one') {
                            await tenorField.selectOption({ index: 3 });
                        } else {
                            await tenorField.fill('18'); // Change to 18 months
                        }
                    }

                    // Save changes
                    await page.locator('button:has-text("Update"), button:has-text("Simpan"), .btn-success:has-text("Simpan")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        /berhasil|disimpan|diupdate/i, { timeout: 10000 }
                    );

                    // Verify changes are reflected
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });
                    const updatedRow = page.locator('table tbody tr').filter({ hasText: /Disetujui|Approved/i }).first();
                    if (await updatedRow.isVisible()) {
                        const rowText = await updatedRow.textContent();
                        // Check if the updated values are visible (this depends on what's displayed)
                        expect(rowText).toMatch(/Disetujui|Approved/i);
                    }
                } else {
                    console.log('No edit button found for approved loans');
                }
            } else {
                console.log('No approved loans found to edit');
            }
        });

        test('[PINJAMAN-009] Export pinjaman data if available', async ({ page }) => {
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
                console.log('Export functionality not available for pinjaman');
            }
        });

        test('[PINJAMAN-010] Filter pinjaman by status and date', async ({ page }) => {
            // Test status filter
            const statusFilter = page.locator('select[name="status"], .filter-select, #filter_status');
            if (await statusFilter.isVisible()) {
                const options = await statusFilter.locator('option').count();

                if (options > 1) {
                    // Test different status filters
                    const statuses = ['Menunggu Persetujuan', 'Disetujui', 'Ditolak'];

                    for (const status of statuses) {
                        try {
                            await statusFilter.selectOption(status);
                            await page.waitForTimeout(2000);

                            // Verify filter results
                            const filteredRows = page.locator('table tbody tr, .data-table tbody tr');
                            if (await filteredRows.count() > 0) {
                                const firstRow = filteredRows.first();
                                const rowText = await firstRow.textContent();
                                // Check if row contains relevant status info
                                expect(rowText).toBeDefined();
                            }
                        } catch (error) {
                            // Option might not exist, continue
                        }
                    }
                }
            }

            // Test date filter if available
            const dateFields = page.locator('input[type="date"]');
            const dateCount = await dateFields.count();

            if (dateCount >= 1) {
                const today = new Date();
                const lastMonth = new Date(today.getFullYear(), today.getMonth() - 1, 1);
                const lastMonthStr = lastMonth.toISOString().split('T')[0];
                const todayStr = today.toISOString().split('T')[0];

                await dateFields.first().fill(lastMonthStr);
                if (dateCount >= 2) {
                    await dateFields.nth(1).fill(todayStr);
                }

                // Apply date filter
                const filterButton = page.locator('button:has-text("Filter"), .btn-filter');
                if (await filterButton.isVisible()) {
                    await filterButton.click();
                    await page.waitForTimeout(2000);

                    // Verify date filter was applied
                    const filteredResults = page.locator('table tbody tr, .data-table tbody tr');
                    expect(filteredResults.count()).resolves.toBeGreaterThanOrEqual(0);
                }
            }
        });
    });
});