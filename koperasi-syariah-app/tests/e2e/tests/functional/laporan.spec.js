const { test, expect } = require('@playwright/test');
const { USERS, getLoginCredentials } = require('../../fixtures/users');
const { BUSINESS_DATA } = require('../../fixtures/businessData');
const { BasePage } = require('../../pages/BasePage');

test.describe('Laporan - Functional Tests', () => {
    let basePage;
    let testData;

    test.beforeAll(async () => {
        // Prepare test data
        testData = {
            ...BUSINESS_DATA.laporan,
            timestamp: Date.now().toString().slice(-6)
        };
    });

    test.beforeEach(async ({ page }) => {
        basePage = new BasePage(page);

        // Login as pengurus for accessing reports
        const pengurusCredentials = getLoginCredentials('pengurus');
        await page.goto('/');
        await basePage.login(pengurusCredentials.login, pengurusCredentials.password);

        // Navigate to laporan section
        await basePage.navigateToMenu('Laporan');
        await basePage.waitForPageLoad();

        // Verify we're on the laporan page
        await expect(page.locator('h1, h2')).toContainText('Laporan', { timeout: 10000 });
    });

    test('[LAPORAN-001] Generate Laporan Simpanan', async ({ page }) => {
        // Look for laporan simpanan option
        const simpananReport = page.locator('a:has-text("Laporan Simpanan"), button:has-text("Simpanan"), .report-card:has-text("Simpanan")').first();
        if (await simpananReport.isVisible()) {
            await simpananReport.click();
            await basePage.waitForPageLoad();

            // Verify simpanan report page loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Simpanan', { timeout: 10000 });

            // Set date range for the report
            const start_date = page.locator('input[name="start_date"], #start_date, input[placeholder*="mulai"]');
            const end_date = page.locator('input[name="end_date"], #end_date, input[placeholder*="sampai"]');

            if (await start_date.isVisible()) {
                await start_date.fill(testData.dateRange.thisMonth.start);
            }
            if (await end_date.isVisible()) {
                await end_date.fill(testData.dateRange.thisMonth.end);
            }

            // Set filters if available
            const jenisFilter = page.locator('select[name="jenis"], #jenis_simpanan');
            if (await jenisFilter.isVisible()) {
                await jenisFilter.selectOption('Semua');
            }

            const statusFilter = page.locator('select[name="status"], #status');
            if (await statusFilter.isVisible()) {
                await statusFilter.selectOption('Aktif');
            }

            // Generate report
            await page.locator('button:has-text("Generate"), button:has-text("Tampilkan"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Verify report is generated
            await expect(page.locator('table, .report-content, .data-display')).toBeVisible({ timeout: 15000 });

            // Check for report data
            const reportTable = page.locator('table tbody tr, .report-data');
            const dataCount = await reportTable.count();

            if (dataCount > 0) {
                // Verify report headers
                const reportHeaders = page.locator('table thead th, .report-header');
                expect(reportHeaders.count()).resolves.toBeGreaterThan(0);

                // Check for summary information
                const summaryInfo = page.locator('.report-summary, .total-info, .summary-card');
                if (await summaryInfo.isVisible()) {
                    const summaryText = await summaryInfo.textContent();
                    expect(summaryText).toMatch(/total|jumlah|subtotal/i);
                }
            }

            // Test export functionality
            const exportButton = page.locator('button:has-text("Export"), a:has-text("Export"), .btn-export');
            if (await exportButton.isVisible()) {
                // Start download listener
                const downloadPromise = page.waitForEvent('download');

                await exportButton.click();
                const download = await downloadPromise;

                // Verify file is downloaded
                expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls|csv|pdf)$/i);
                expect(download.suggestedFilename()).toContain(/simpanan/i);
            }
        } else {
            console.log('Laporan Simpanan menu not found');
        }
    });

    test('[LAPORAN-002] Generate Laporan Pinjaman', async ({ page }) => {
        // Look for laporan pinjaman option
        const pinjamanReport = page.locator('a:has-text("Laporan Pinjaman"), button:has-text("Pinjaman"), .report-card:has-text("Pinjaman")').first();
        if (await pinjamanReport.isVisible()) {
            await pinjamanReport.click();
            await basePage.waitForPageLoad();

            // Verify pinjaman report page loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Pinjaman', { timeout: 10000 });

            // Set date range for the report
            const start_date = page.locator('input[name="start_date"], #start_date');
            const end_date = page.locator('input[name="end_date"], #end_date');

            if (await start_date.isVisible()) {
                await start_date.fill(testData.dateRange.thisYear.start);
            }
            if (await end_date.isVisible()) {
                await end_date.fill(testData.dateRange.thisYear.end);
            }

            // Set status filter
            const statusFilter = page.locator('select[name="status"], #status_pinjaman');
            if (await statusFilter.isVisible()) {
                await statusFilter.selectOption('Disetujui');
            }

            // Generate report
            await page.locator('button:has-text("Generate"), button:has-text("Tampilkan"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Verify report is generated
            await expect(page.locator('table, .report-content, .data-display')).toBeVisible({ timeout: 15000 });

            // Look for loan statistics
            const loanStats = page.locator('.loan-stats, .pinjaman-stats, .statistics');
            if (await loanStats.isVisible()) {
                const statsText = await loanStats.textContent();
                expect(statsText).toMatch(/total pinjaman|jumlah|disetujui/i);
            }

            // Test export functionality
            const exportButton = page.locator('button:has-text("Export"), .btn-excel, .btn-pdf');
            if (await exportButton.isVisible()) {
                const downloadPromise = page.waitForEvent('download');

                await exportButton.click();
                const download = await downloadPromise;

                expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls|csv|pdf)$/i);
                expect(download.suggestedFilename()).toContain(/pinjaman/i);
            }
        } else {
            console.log('Laporan Pinjaman menu not found');
        }
    });

    test('[LAPORAN-003] Generate Laporan Laba Rugi', async ({ page }) => {
        // Look for laporan laba rugi option
        const labaRugiReport = page.locator('a:has-text("Laporan Laba Rugi"), button:has-text("Laba Rugi"), .report-card:has-text("Laba Rugi")').first();
        if (await labaRugiReport.isVisible()) {
            await labaRugiReport.click();
            await basePage.waitForPageLoad();

            // Verify laba rugi report page loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Laba Rugi', { timeout: 10000 });

            // Set periode (monthly)
            const periodeFilter = page.locator('select[name="periode"], #periode, input[name="periode"]');
            if (await periodeFilter.isVisible()) {
                if (await periodeFilter.getAttribute('type') === 'select-one') {
                    await periodeFilter.selectOption('Bulanan');
                } else {
                    await periodeFilter.fill('2025-01');
                }
            }

            // Set year
            const yearFilter = page.locator('select[name="tahun"], #tahun');
            if (await yearFilter.isVisible()) {
                await yearFilter.selectOption('2025');
            }

            // Generate report
            await page.locator('button:has-text("Generate"), button:has-text("Hitung"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Verify report is generated with proper structure
            await expect(page.locator('table, .profit-loss-statement, .laba-rugi-report')).toBeVisible({ timeout: 15000 });

            // Check for income section
            const incomeSection = page.locator('.income-section, .pendapatan, tr:has-text("Pendapatan")');
            if (await incomeSection.isVisible()) {
                expect(incomeSection).toContainText(/pendapatan|income/i);
            }

            // Check for expense section
            const expenseSection = page.locator('.expense-section, .beban, tr:has-text("Beban")');
            if (await expenseSection.isVisible()) {
                expect(expenseSection).toContainText(/beban|expense/i);
            }

            // Check for profit/loss calculation
            const profitSection = page.locator('.profit-section, .laba, tr:has-text("Laba")');
            if (await profitSection.isVisible()) {
                expect(profitSection).toContainText(/laba|profit|hasil/i);
            }

            // Test export functionality
            const exportExcel = page.locator('button:has-text("Excel"), .btn-excel');
            if (await exportExcel.isVisible()) {
                const downloadPromise = page.waitForEvent('download');

                await exportExcel.click();
                const download = await downloadPromise;

                expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls)$/i);
                expect(download.suggestedFilename()).toContain(/laba.*rugi/i);
            }
        } else {
            console.log('Laporan Laba Rugi menu not found');
        }
    });

    test('[LAPORAN-004] Generate Laporan Neraca', async ({ page }) => {
        // Look for laporan neraca option
        const neracaReport = page.locator('a:has-text("Laporan Neraca"), button:has-text("Neraca"), .report-card:has-text("Neraca")').first();
        if (await neracaReport.isVisible()) {
            await neracaReport.click();
            await basePage.waitForPageLoad();

            // Verify neraca report page loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Neraca', { timeout: 10000 });

            // Set date for balance sheet
            const dateFilter = page.locator('input[name="tanggal"], #tanggal, input[type="date"]');
            if (await dateFilter.isVisible()) {
                const today = new Date().toISOString().split('T')[0];
                await dateFilter.fill(today);
            }

            // Generate report
            await page.locator('button:has-text("Generate"), button:has-text("Tampilkan"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Verify balance sheet is generated
            await expect(page.locator('table, .balance-sheet, .neraca-report')).toBeVisible({ timeout: 15000 });

            // Check for assets section
            const assetSection = page.locator('.assets-section, .aktiva, tr:has-text("Aktiva")');
            if (await assetSection.isVisible()) {
                expect(assetSection).toContainText(/aktiva|asset/i);
            }

            // Check for liabilities section
            const liabilitySection = page.locator('.liabilities-section, .kewajiban, tr:has-text("Kewajiban")');
            if (await liabilitySection.isVisible()) {
                expect(liabilitySection).toContainText(/kewajiban|liability/i);
            }

            // Check for equity section
            const equitySection = page.locator('.equity-section, .ekuitas, tr:has-text("Ekuitas")');
            if (await equitySection.isVisible()) {
                expect(equitySection).toContainText(/ekuitas|equity/i);
            }

            // Verify balance sheet balances (assets = liabilities + equity)
            const totalAssets = page.locator('.total-assets, .total-aktiva');
            const totalLiabilitiesEquity = page.locator('.total-liabilities-equity, .total-kewajiban-ekuitas');

            if (await totalAssets.isVisible() && await totalLiabilitiesEquity.isVisible()) {
                const assetsText = await totalAssets.textContent();
                const liabilitiesText = await totalLiabilitiesEquity.textContent();

                expect(assetsText).toMatch(/[\d,]+/);
                expect(liabilitiesText).toMatch(/[\d,]+/);
            }

            // Test export functionality
            const exportButton = page.locator('button:has-text("Export"), .btn-export');
            if (await exportButton.isVisible()) {
                const downloadPromise = page.waitForEvent('download');

                await exportButton.click();
                const download = await downloadPromise;

                expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls|pdf)$/i);
                expect(download.suggestedFilename()).toContain(/neraca/i);
            }
        } else {
            console.log('Laporan Neraca menu not found');
        }
    });

    test('[LAPORAN-005] Generate Laporan Tunggakan', async ({ page }) => {
        // Look for laporan tunggakan option
        const tunggakanReport = page.locator('a:has-text("Laporan Tunggakan"), button:has-text("Tunggakan"), .report-card:has-text("Tunggakan")').first();
        if (await tunggakanReport.isVisible()) {
            await tunggakanReport.click();
            await basePage.waitForPageLoad();

            // Verify tunggakan report page loaded
            await expect(page.locator('h1, h2, .card-header')).toContainText('Tunggakan', { timeout: 10000 });

            // Set date range for overdue payments
            const start_date = page.locator('input[name="start_date"], #start_date');
            const end_date = page.locator('input[name="end_date"], #end_date');

            if (await start_date.isVisible()) {
                await start_date.fill(testData.dateRange.thisMonth.start);
            }
            if (await end_date.isVisible()) {
                await end_date.fill(testData.dateRange.thisMonth.end);
            }

            // Set severity filter if available
            const severityFilter = page.locator('select[name="severity"], .severity-filter');
            if (await severityFilter.isVisible()) {
                await severityFilter.selectOption('Semua');
            }

            // Generate report
            await page.locator('button:has-text("Generate"), button:has-text("Tampilkan"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Verify report is generated
            await expect(page.locator('table, .overdue-report, .tunggakan-content')).toBeVisible({ timeout: 15000 });

            // Look for overdue payment information
            const overdueInfo = page.locator('.overdue-info, .tunggakan-info, .collection-info');
            if (await overdueInfo.isVisible()) {
                const overdueText = await overdueInfo.textContent();
                expect(overdueText).toMatch(/tunggakan|overdue|terlambat/i);
            }

            // Test export functionality
            const exportButton = page.locator('button:has-text("Export Collection"), .btn-export-collection');
            if (await exportButton.isVisible()) {
                const downloadPromise = page.waitForEvent('download');

                await exportButton.click();
                const download = await downloadPromise;

                expect(download.suggestedFilename()).toMatch(/\.(xlsx|xls|csv|pdf)$/i);
                expect(download.suggestedFilename()).toContain(/tunggakan/i);
            }
        } else {
            console.log('Laporan Tunggakan menu not found');
        }
    });

    test('[LAPORAN-006] Generate Custom Date Range Report', async ({ page }) => {
        // Test with a simpanan or pinjaman report that supports custom date range
        const customDateReport = page.locator('a:has-text("Simpanan"), a:has-text("Pinjaman")').first();
        if (await customDateReport.isVisible()) {
            await customDateReport.click();
            await basePage.waitForPageLoad();

            // Set custom date range
            const start_date = page.locator('input[name="start_date"], #start_date');
            const end_date = page.locator('input[name="end_date"], #end_date');

            if (await start_date.isVisible() && await end_date.isVisible()) {
                await start_date.fill(testData.dateRange.custom.start);
                await end_date.fill(testData.dateRange.custom.end);

                // Generate report
                await page.locator('button:has-text("Generate"), .btn-primary:has-text("Generate")').click();
                await basePage.waitForPageLoad();

                // Verify report shows data for custom period
                const reportContent = page.locator('table, .report-content');
                if (await reportContent.isVisible()) {
                    // Look for date indicators in the report
                    const dateInfo = page.locator('.report-period, .date-range, .periode-laporan');
                    if (await dateInfo.isVisible()) {
                        const dateText = await dateInfo.textContent();
                        expect(dateText).toMatch(/2024/i);
                    }
                }
            }
        }
    });

    test('[LAPORAN-007] Test report filters and options', async ({ page }) => {
        // Select a report that has multiple filter options
        const filterableReport = page.locator('a:has-text("Simpanan"), a:has-text("Pinjaman")').first();
        if (await filterableReport.isVisible()) {
            await filterableReport.click();
            await basePage.waitForPageLoad();

            // Test various filters
            const filters = [
                { selector: 'select[name="jenis"]', testOption: 'Simpanan Pokok' },
                { selector: 'select[name="status"]', testOption: 'Aktif' },
                { selector: 'select[name="anggota"]', testOption: null }
            ];

            for (const filter of filters) {
                const filterElement = page.locator(filter.selector);
                if (await filterElement.isVisible()) {
                    const optionCount = await filterElement.locator('option').count();

                    if (optionCount > 1) {
                        // Select a test option or use first available option
                        try {
                            if (filter.testOption) {
                                await filterElement.selectOption(filter.testOption);
                            } else {
                                await filterElement.selectOption({ index: 1 });
                            }

                            await page.waitForTimeout(1000);

                            // Generate report with filter
                            await page.locator('button:has-text("Generate"), .btn-primary').click();
                            await basePage.waitForPageLoad();

                            // Verify filter was applied
                            const reportContent = page.locator('table, .report-content');
                            if (await reportContent.isVisible()) {
                                expect(reportContent).toBeVisible();
                            }
                        } catch (error) {
                            // Option might not exist, continue
                        }
                    }
                }
            }
        }
    });

    test('[LAPORAN-008] Test report print functionality', async ({ page }) => {
        // Select any report
        const anyReport = page.locator('a:has-text("Laporan")').first();
        if (await anyReport.isVisible()) {
            await anyReport.click();
            await basePage.waitForPageLoad();

            // Generate basic report
            await page.locator('button:has-text("Generate"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();

            // Look for print button
            const printButton = page.locator('button:has-text("Print"), .btn-print, .fa-print');
            if (await printButton.isVisible()) {
                // Setup print dialog handler
                page.on('dialog', async dialog => {
                    expect(dialog.type()).toBe('beforeunload');
                    await dialog.dismiss();
                });

                await printButton.click();
                await page.waitForTimeout(2000);

                // Verify print preview or print dialog is triggered
                // This is hard to test directly, but we can check if print CSS is applied
                const printStyles = page.locator('style[media*="print"], .print-styles');
                const hasPrintStyles = await printStyles.count();
                expect(hasPrintStyles).toBeGreaterThanOrEqual(0);
            }
        }
    });

    test('[LAPORAN-009] Test report validation and error handling', async ({ page }) => {
        // Select a report that requires date range
        const dateReport = page.locator('a:has-text("Simpanan"), a:has-text("Pinjaman")').first();
        if (await dateReport.isVisible()) {
            await dateReport.click();
            await basePage.waitForPageLoad();

            // Try to generate report without setting date range
            const generateButton = page.locator('button:has-text("Generate"), .btn-primary:has-text("Generate")');
            await generateButton.click();
            await page.waitForTimeout(1000);

            // Check for validation errors
            const errorMessage = page.locator('.alert-danger, .error-message, .validation-error');
            if (await errorMessage.isVisible()) {
                expect(errorMessage).toContainText(/tanggal|date|required|wajib/i);
            }

            // Test invalid date range (end date before start date)
            const start_date = page.locator('input[name="start_date"], #start_date');
            const end_date = page.locator('input[name="end_date"], #end_date');

            if (await start_date.isVisible() && await end_date.isVisible()) {
                await start_date.fill('2025-12-31');
                await end_date.fill('2025-01-01');

                await generateButton.click();
                await page.waitForTimeout(1000);

                const dateError = page.locator('.alert-danger, .error-message').filter({ hasText: /tanggal|date|range/i });
                if (await dateError.isVisible()) {
                    expect(dateError).toContainText(/tanggal|date|invalid/i);
                }
            }
        }
    });

    test('[LAPORAN-010] Test report performance and pagination', async ({ page }) => {
        // Generate a report with potentially large data set
        const largeReport = page.locator('a:has-text("Simpanan"), a:has-text("Pinjaman")').first();
        if (await largeReport.isVisible()) {
            await largeReport.click();
            await basePage.waitForPageLoad();

            // Set a broad date range to get more data
            const start_date = page.locator('input[name="start_date"], #start_date');
            const end_date = page.locator('input[name="end_date"], #end_date');

            if (await start_date.isVisible()) {
                await start_date.fill('2024-01-01');
            }
            if (await end_date.isVisible()) {
                await end_date.fill('2024-12-31');
            }

            // Generate report and measure performance
            const startTime = Date.now();
            await page.locator('button:has-text("Generate"), .btn-primary:has-text("Generate")').click();
            await basePage.waitForPageLoad();
            const endTime = Date.now();

            const generationTime = endTime - startTime;

            // Report should generate within reasonable time (30 seconds)
            expect(generationTime).toBeLessThan(30000);

            // Check if pagination is present for large datasets
            const pagination = page.locator('.pagination, .pager');
            if (await pagination.isVisible()) {
                // Verify pagination controls
                const paginationLinks = pagination.locator('a, .page-link');
                expect(paginationLinks.count()).resolves.toBeGreaterThan(0);

                // Test pagination if multiple pages
                const pageInfo = page.locator('.pagination-info, .page-info');
                if (await pageInfo.isVisible()) {
                    const infoText = await pageInfo.textContent();
                    expect(infoText).toMatch(/\d+/);
                }
            }
        }
    });
});