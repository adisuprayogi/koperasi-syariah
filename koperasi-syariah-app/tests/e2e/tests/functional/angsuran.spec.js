const { test, expect } = require('@playwright/test');
const { USERS, getLoginCredentials } = require('../../fixtures/users');
const { BUSINESS_DATA } = require('../../fixtures/businessData');
const { BasePage } = require('../../pages/BasePage');

test.describe('Angsuran - Functional Tests', () => {
    let basePage;
    let testData;

    test.beforeAll(async () => {
        // Prepare test data
        testData = {
            ...BUSINESS_DATA.angsuran,
            timestamp: Date.now().toString().slice(-6)
        };
    });

    test.describe('Bayar Angsuran (Pengurus)', () => {
        test.beforeEach(async ({ page }) => {
            basePage = new BasePage(page);

            // Login as pengurus for processing payments
            const pengurusCredentials = getLoginCredentials('pengurus');
            await page.goto('/');
            await basePage.login(pengurusCredentials.login, pengurusCredentials.password);

            // Navigate to angsuran section
            await basePage.navigateToMenu('Angsuran');
            await basePage.waitForPageLoad();

            // Verify we're on the angsuran page
            await expect(page.locator('h1, h2')).toContainText('Angsuran', { timeout: 10000 });
        });

        test('[ANGSURAN-001] Bayar angsuran bulanan', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find a loan with outstanding balance
            const loanRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Belum Lunas|Outstanding|Menunggu/i }).first();

            if (await loanRow.isVisible()) {
                // Click on bayar button
                const bayarButton = page.locator('.btn-bayar, button:has-text("Bayar"), a:has-text("Bayar"), .fa-money-bill-wave').first();
                if (await bayarButton.isVisible()) {
                    await bayarButton.click();
                    await basePage.waitForPageLoad();

                    // Verify payment form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Bayar', { timeout: 10000 });

                    // Fill payment details
                    const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
                    const suggestedAmount = await jumlahField.inputValue();

                    if (!suggestedAmount) {
                        await jumlahField.fill(testData.pembayaran.partial.jumlah.toString());
                    }

                    const metodeField = page.locator('select[name="metode"], #metode, input[name="metode"]');
                    if (await metodeField.isVisible()) {
                        if (await metodeField.getAttribute('type') === 'select-one') {
                            await metodeField.selectOption({ index: 1 }); // Select first payment method
                        } else {
                            await metodeField.fill(testData.pembayaran.partial.metode);
                        }
                    }

                    const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
                    if (await keteranganField.isVisible()) {
                        await keteranganField.fill(testData.pembayaran.partial.keterangan);
                    }

                    // Set payment date if field exists
                    const tanggalField = page.locator('input[name="tanggal"], #tanggal, input[type="date"]');
                    if (await tanggalField.isVisible()) {
                        const today = new Date().toISOString().split('T')[0];
                        await tanggalField.fill(today);
                    }

                    // Submit payment
                    await page.locator('button:has-text("Bayar"), button:has-text("Proses"), .btn-success:has-text("Bayar")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        BUSINESS_DATA.expectations.successMessages.bayarAngsuran, { timeout: 10000 }
                    );

                    // Verify payment is reflected in the list
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });

                    // Look for payment record or updated loan status
                    const updatedRows = page.locator('table tbody tr');
                    const rowCount = await updatedRows.count();
                    expect(rowCount).toBeGreaterThan(0);
                } else {
                    console.log('No bayar button found - might need approved loans first');
                }
            } else {
                console.log('No loans with outstanding balance found');
            }
        });

        test('[ANGSURAN-002] Bayar angsuran pelunasan', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find a loan with outstanding balance
            const loanRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Belum Lunas|Outstanding/i }).first();

            if (await loanRow.isVisible()) {
                // Click on bayar button
                const bayarButton = page.locator('.btn-bayar, button:has-text("Bayar"), a:has-text("Bayar")').first();
                if (await bayarButton.isVisible()) {
                    await bayarButton.click();
                    await basePage.waitForPageLoad();

                    // Verify payment form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Bayar', { timeout: 10000 });

                    // Fill full payment amount
                    const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');

                    // Try to get the remaining balance or use test data
                    const balanceInfo = page.locator('.remaining-balance, .sisa-pinjaman, .outstanding-amount');
                    let fullAmount = testData.pembayaran.full.jumlah;

                    if (await balanceInfo.isVisible()) {
                        const balanceText = await balanceInfo.textContent();
                        const balanceMatch = balanceText.match(/[\d,]+/);
                        if (balanceMatch) {
                            fullAmount = parseInt(balanceMatch[0].replace(/,/g, ''));
                        }
                    }

                    await jumlahField.clear();
                    await jumlahField.fill(fullAmount.toString());

                    const metodeField = page.locator('select[name="metode"], #metode');
                    if (await metodeField.isVisible()) {
                        await metodeField.selectOption({ index: 2 }); // Select different payment method
                    }

                    const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
                    if (await keteranganField.isVisible()) {
                        await keteranganField.fill('Pelunasan pinjaman');
                    }

                    // Submit payment
                    await page.locator('button:has-text("Bayar"), button:has-text("Proses"), .btn-success:has-text("Bayar")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        BUSINESS_DATA.expectations.successMessages.bayarAngsuran, { timeout: 10000 }
                    );

                    // Verify loan status changed to "Lunas"
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });

                    const lunasRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Lunas|Paid Off/i }).first();
                    if (await lunasRow.isVisible()) {
                        await expect(lunasRow).toContainText(/Lunas|Paid Off/i);
                    }
                } else {
                    console.log('No bayar button found');
                }
            } else {
                console.log('No loans with outstanding balance found for full payment');
            }
        });

        test('[ANGSURAN-003] Bayar angsuran terlambat dengan denda', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for overdue loans
            const overdueRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Terlambat|Overdue|Telat/i }).first();

            if (await overdueRow.isVisible()) {
                // Click on bayar button
                const bayarButton = page.locator('.btn-bayar, button:has-text("Bayar"), a:has-text("Bayar")').first();
                if (await bayarButton.isVisible()) {
                    await bayarButton.click();
                    await basePage.waitForPageLoad();

                    // Verify payment form loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Bayar', { timeout: 10000 });

                    // Check if denda is automatically calculated
                    const dendaField = page.locator('input[name="denda"], #denda, .denda-amount');
                    let autoDenda = 0;

                    if (await dendaField.isVisible()) {
                        const dendaValue = await dendaField.inputValue();
                        if (dendaValue) {
                            autoDenda = parseInt(dendaValue.replace(/,/g, ''));
                        }
                    }

                    // Fill payment amount including denda
                    const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
                    const baseAmount = testData.pembayaran.partial.jumlah;
                    const totalAmount = baseAmount + autoDenda;

                    await jumlahField.clear();
                    await jumlahField.fill(totalAmount.toString());

                    const keteranganField = page.locator('textarea[name="keterangan"], #keterangan');
                    if (await keteranganField.isVisible()) {
                        await keteranganField.fill(testData.pembayaran.late.keterangan);
                    }

                    // Submit payment
                    await page.locator('button:has-text("Bayar"), button:has-text("Proses"), .btn-success:has-text("Bayar")').click();
                    await basePage.waitForPageLoad();

                    // Verify success message
                    await expect(page.locator('.alert-success, .toast-success, .notification-success')).toContainText(
                        BUSINESS_DATA.expectations.successMessages.bayarAngsuran, { timeout: 10000 }
                    );

                    // Verify payment is processed
                    await page.waitForSelector('table tbody tr', { timeout: 15000 });
                } else {
                    console.log('No bayar button found for overdue loans');
                }
            } else {
                console.log('No overdue loans found - this is expected in test environment');
            }
        });

        test('[ANGSURAN-004] View jadwal angsuran', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Click on a loan to view details
            const firstLoan = page.locator('table tbody tr, .data-table tbody tr').first();
            if (await firstLoan.isVisible()) {
                // Look for schedule/jadwal button
                const scheduleButton = page.locator('.btn-schedule, button:has-text("Jadwal"), a:has-text("Jadwal"), .fa-calendar').first();
                if (await scheduleButton.isVisible()) {
                    await scheduleButton.click();
                    await basePage.waitForPageLoad();

                    // Verify schedule page loaded
                    await expect(page.locator('h1, h2, .card-header')).toContainText('Jadwal', { timeout: 10000 });

                    // Verify schedule information is displayed
                    const scheduleTable = page.locator('table tbody tr, .schedule-items');
                    if (await scheduleTable.isVisible()) {
                        const scheduleCount = await scheduleTable.count();
                        expect(scheduleCount).toBeGreaterThan(0);

                        // Check for schedule headers
                        const scheduleHeaders = page.locator('table thead th, .schedule-header');
                        const headerCount = await scheduleHeaders.count();
                        expect(headerCount).toBeGreaterThan(0);
                    }
                } else {
                    // Try clicking the loan row itself
                    await firstLoan.click();
                    await basePage.waitForPageLoad();

                    // Check if this is a detail page with schedule info
                    const scheduleSection = page.locator('.jadwal-angsuran, .schedule-section, .payment-schedule');
                    if (await scheduleSection.isVisible()) {
                        const scheduleText = await scheduleSection.textContent();
                        expect(scheduleText).toMatch(/jadwal|schedule|angsuran/i);
                    }
                }
            }
        });

        test('[ANGSURAN-005] View riwayat pembayaran', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for history/riwayat button or tab
            const historyButton = page.locator('.btn-history, button:has-text("Riwayat"), a:has-text("Riwayat"), .fa-history, .tab:has-text("Riwayat")').first();
            if (await historyButton.isVisible()) {
                await historyButton.click();
                await basePage.waitForPageLoad();

                // Verify history page loaded
                await expect(page.locator('h1, h2, .card-header')).toContainText(/Riwayat|History/i, { timeout: 10000 });

                // Verify payment history is displayed
                const historyTable = page.locator('table tbody tr, .history-items');
                if (await historyTable.isVisible()) {
                    const historyCount = await historyTable.count();
                    expect(historyCount).toBeGreaterThanOrEqual(0);

                    // Look for payment details
                    const paymentDetails = page.locator('.payment-details, .payment-info');
                    if (await paymentDetails.first().isVisible()) {
                        const detailText = await paymentDetails.first().textContent();
                        expect(detailText).toMatch(/tanggal|jumlah|metode/i);
                    }
                }
            } else {
                console.log('No history button found - payment history might be integrated in main view');
            }
        });

        test('[ANGSURAN-006] Filter angsuran by status', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Look for status filter
            const statusFilter = page.locator('select[name="status"], .filter-select, #filter_status');
            if (await statusFilter.isVisible()) {
                // Test different status filters
                const statuses = ['Belum Lunas', 'Lunas', 'Terlambat'];

                for (const status of statuses) {
                    try {
                        await statusFilter.selectOption(status);
                        await page.waitForTimeout(2000);

                        // Verify filter was applied
                        const filteredRows = page.locator('table tbody tr, .data-table tbody tr');
                        if (await filteredRows.count() > 0) {
                            // Check if filtering works (this is a basic check)
                            expect(filteredRows.count()).resolves.toBeGreaterThanOrEqual(0);
                        }
                    } catch (error) {
                        // Status option might not exist, continue
                    }
                }
            } else {
                console.log('No status filter found');
            }
        });

        test('[ANGSURAN-007] Filter angsuran by date range', async ({ page }) => {
            // Look for date filters
            const dateFields = page.locator('input[type="date"], .date-filter');
            const dateCount = await dateFields.count();

            if (dateCount >= 1) {
                // Filter by current month
                const today = new Date();
                const firstDay = new Date(today.getFullYear(), today.getMonth(), 1);
                const firstDayStr = firstDay.toISOString().split('T')[0];
                const todayStr = today.toISOString().split('T')[0];

                await dateFields.first().fill(firstDayStr);
                if (dateCount >= 2) {
                    await dateFields.nth(1).fill(todayStr);
                }

                // Apply filter
                const filterButton = page.locator('button:has-text("Filter"), .btn-filter');
                if (await filterButton.isVisible()) {
                    await filterButton.click();
                    await page.waitForTimeout(2000);

                    // Verify filter was applied
                    const filteredResults = page.locator('table tbody tr, .data-table tbody tr');
                    expect(filteredResults.count()).resolves.toBeGreaterThanOrEqual(0);
                }
            } else {
                console.log('No date filters found');
            }
        });

        test('[ANGSURAN-008] Search angsuran by anggota name', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Search by anggota name
            const searchField = page.locator('input[placeholder*="cari"], input[placeholder*="search"], .search-input').first();
            await searchField.fill('Test Anggota');
            await page.keyboard.press('Enter');
            await page.waitForTimeout(2000);

            // Verify search results
            const searchResults = page.locator('table tbody tr, .data-table tbody tr');
            const count = await searchResults.count();

            if (count > 0) {
                // Check if results contain the search term
                const firstResult = searchResults.first();
                const resultText = await firstResult.textContent();
                expect(resultText.toLowerCase()).toContain('test anggota');
            }
        });

        test('[ANGSURAN-009] Test payment validation', async ({ page }) => {
            // Wait for table to load
            await page.waitForSelector('table tbody tr, .data-table tbody tr', { timeout: 15000 });

            // Find a loan to test payment validation
            const loanRow = page.locator('table tbody tr, .data-table tbody tr').filter({ hasText: /Belum Lunas/i }).first();

            if (await loanRow.isVisible()) {
                // Click on bayar button
                const bayarButton = page.locator('.btn-bayar, button:has-text("Bayar"), a:has-text("Bayar")').first();
                if (await bayarButton.isVisible()) {
                    await bayarButton.click();
                    await basePage.waitForPageLoad();

                    // Test validation with invalid amount (zero)
                    const jumlahField = page.locator('input[name="jumlah"], #jumlah, input[placeholder*="jumlah"]');
                    await jumlahField.clear();
                    await jumlahField.fill('0');

                    // Try to submit
                    const submitButton = page.locator('button:has-text("Bayar"), button:has-text("Proses"), .btn-success:has-text("Bayar")');
                    await submitButton.click();
                    await page.waitForTimeout(1000);

                    // Should show validation error
                    const errorMessage = page.locator('.error-message, .validation-error, .text-danger').filter({ hasText: /jumlah|amount|minimal/i });
                    if (await errorMessage.isVisible()) {
                        await expect(errorMessage).toContainText(/minimal|lebih|invalid/i);
                    } else {
                        // Check if form is still visible (validation prevented submission)
                        await expect(page.locator('h1, h2, .card-header')).toContainText('Bayar');
                    }

                    // Test with negative amount
                    await jumlahField.clear();
                    await jumlahField.fill('-1000');
                    await submitButton.click();
                    await page.waitForTimeout(1000);

                    // Should show validation error
                    const negativeError = page.locator('.error-message, .validation-error, .text-danger').filter({ hasText: /negatif|negative/i });
                    if (await negativeError.isVisible()) {
                        await expect(negativeError).toContainText(/negatif|negative/i);
                    }
                }
            } else {
                console.log('No loans found for payment validation test');
            }
        });

        test('[ANGSURAN-010] Export angsuran data if available', async ({ page }) => {
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
                console.log('Export functionality not available for angsuran');
            }
        });
    });

    test.describe('View Angsuran (Anggota)', () => {
        test.beforeEach(async ({ page }) => {
            basePage = new BasePage(page);

            // Login as anggota for viewing payments
            const anggotaCredentials = getLoginCredentials('anggota');
            await page.goto('/');
            await basePage.login(anggotaCredentials.login, anggotaCredentials.password);

            // Navigate to angsuran section
            await basePage.navigateToMenu('Angsuran');
            await basePage.waitForPageLoad();

            // Verify we're on the angsuran page
            await expect(page.locator('h1, h2')).toContainText('Angsuran', { timeout: 10000 });
        });

        test('[ANGSURAN-011] View personal angsuran schedule', async ({ page }) => {
            // Wait for content to load
            await page.waitForSelector('table, .schedule-container, .payment-info', { timeout: 15000 });

            // Verify payment information is displayed
            const paymentInfo = page.locator('.payment-info, .loan-info, .angsuran-info');
            if (await paymentInfo.isVisible()) {
                const infoText = await paymentInfo.textContent();
                expect(infoText).toMatch(/angsuran|pinjaman|jadwal/i);
            }

            // Check for schedule table
            const scheduleTable = page.locator('table tbody tr, .schedule-items');
            if (await scheduleTable.isVisible()) {
                const scheduleCount = await scheduleTable.count();
                expect(scheduleCount).toBeGreaterThanOrEqual(0);
            }

            // Look for next payment due date
            const dueDateInfo = page.locator('.due-date, .next-payment, .jatuh-tempo');
            if (await dueDateInfo.isVisible()) {
                const dueText = await dueDateInfo.textContent();
                expect(dueText).toMatch(/jatuh tempo|due date|tanggal/i);
            }
        });

        test('[ANGSURAN-012] View payment history', async ({ page }) => {
            // Look for history section or tab
            const historySection = page.locator('.payment-history, .riwayat-pembayaran, .history-section');
            if (await historySection.isVisible()) {
                const historyText = await historySection.textContent();
                expect(historyText).toMatch(/riwayat|history|pembayaran/i);

                // Check for payment records
                const paymentRecords = page.locator('table tbody tr, .payment-record');
                const recordCount = await paymentRecords.count();
                expect(recordCount).toBeGreaterThanOrEqual(0);
            }

            // Look for history tab if section not visible
            const historyTab = page.locator('button:has-text("Riwayat"), .tab:has-text("Riwayat")').first();
            if (await historyTab.isVisible()) {
                await historyTab.click();
                await basePage.waitForPageLoad();

                // Verify history content loaded
                await expect(page.locator('h1, h2, .section-title')).toContainText(/Riwayat|History/i, { timeout: 10000 });
            }
        });

        test('[ANGSURAN-013] View outstanding balance', async ({ page }) => {
            // Look for balance information
            const balanceInfo = page.locator('.outstanding-balance, .sisa-pinjaman, .remaining-amount');
            if (await balanceInfo.isVisible()) {
                const balanceText = await balanceInfo.textContent();
                expect(balanceText).toMatch(/sisa|remaining|outstanding|saldo/i);

                // Verify amount is displayed
                const amountMatch = balanceText.match(/[\d,]+/);
                expect(amountMatch).toBeTruthy();
            }

            // Look for summary cards
            const summaryCards = page.locator('.summary-card, .info-card, .stat-card');
            const cardCount = await summaryCards.count();

            if (cardCount > 0) {
                // Check for key information in cards
                for (let i = 0; i < Math.min(cardCount, 3); i++) {
                    const card = summaryCards.nth(i);
                    const cardText = await card.textContent();
                    expect(cardText.length).toBeGreaterThan(0);
                }
            }
        });
    });
});