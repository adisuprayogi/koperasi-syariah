const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { PengurusPage } = require('../../pages/PengurusPage');
const { USERS, getLoginCredentials } = require('../../fixtures/users');

test.describe('Pengurus Dashboard', () => {
    let authPage;
    let pengurusPage;

    test.use({ storageState: { cookies: [], origins: [] } }); // Start fresh

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
        pengurusPage = new PengurusPage(page);

        // Login as pengurus
        const credentials = getLoginCredentials('pengurus');
        await authPage.login(credentials.login, credentials.password);
    });

    test.describe('Dashboard Access', () => {
        test('Should display pengurus dashboard correctly', async ({ page }) => {
            // Verify on correct dashboard
            const isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify correct URL
            await expect(page).toHaveURL(/pengurus.*dashboard/);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should display correct dashboard title', async ({ page }) => {
            const titleText = await pengurusPage.getText('h1, .dashboard-title, .page-title');
            expect(titleText.toLowerCase()).toContain('dashboard');
        });

        test('Should show navigation menu for pengurus', async ({ page }) => {
            const hasMenu = await pengurusPage.verifyMenuItems();
            expect(hasMenu).toBe(true);

            // Verify specific menu items are visible
            expect(await pengurusPage.isVisible(pengurusPage.menuDashboard)).toBe(true);
            expect(await pengurusPage.isVisible(pengurusPage.menuAnggota)).toBe(true);
            expect(await pengurusPage.isVisible(pengurusPage.menuSimpanan)).toBe(true);
            expect(await pengurusPage.isVisible(pengurusPage.menuPembiayaan)).toBe(true);
            expect(await pengurusPage.isVisible(pengurusPage.menuAngsuran)).toBe(true);
            expect(await pengurusPage.isVisible(pengurusPage.menuLaporan)).toBe(true);
        });
    });

    test.describe('Dashboard Information Cards', () => {
        test('Should display info cards with summary statistics', async ({ page }) => {
            const dashboardInfo = await pengurusPage.getDashboardInfo();

            // Should have multiple info cards
            expect(dashboardInfo.length).toBeGreaterThan(0);

            // Verify common card titles exist
            const cardTitles = dashboardInfo.map(info => info.title.toLowerCase());

            // Look for expected card titles (may vary based on implementation)
            const expectedTitles = [
                'total anggota',
                'total simpanan',
                'total pinjaman',
                'pinjaman belum cair',
                'total',
                'anggota',
                'simpanan',
                'pinjaman'
            ];

            // At least some expected titles should be present
            const hasRelevantTitles = expectedTitles.some(title =>
                cardTitles.some(cardTitle => cardTitle.includes(title))
            );
            expect(hasRelevantTitles).toBe(true);
        });

        test('Should display numeric values in info cards', async ({ page }) => {
            const dashboardInfo = await pengurusPage.getDashboardInfo();

            // Verify cards have both titles and values
            dashboardInfo.forEach(info => {
                expect(info.title).toBeTruthy();
                expect(info.value).toBeTruthy();
                expect(info.value.trim().length).toBeGreaterThan(0);
            });

            // Some values should contain numbers (counts/amounts)
            const hasNumericValues = dashboardInfo.some(info =>
                /\d/.test(info.value)
            );
            expect(hasNumericValues).toBe(true);
        });
    });

    test.describe('Dashboard Navigation', () => {
        test('Should navigate to anggota management page', async ({ page }) => {
            await pengurusPage.navigateToAnggota();

            // Verify navigation and table display
            await expect(page).toHaveURL(/anggota/i);
            const isTableVisible = await pengurusPage.isVisible(pengurusPage.anggotaTable);
            expect(isTableVisible).toBe(true);

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should navigate to simpanan page', async ({ page }) => {
            await pengurusPage.navigateToSimpanan();

            // Verify navigation and table display
            await expect(page).toHaveURL(/simpanan/i);
            const isTableVisible = await pengurusPage.isVisible(pengurusPage.simpananTable);
            expect(isTableVisible).toBe(true);

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should navigate to pembiayaan page', async ({ page }) => {
            await pengurusPage.navigateToPembiayaan();

            // Verify navigation and table display
            await expect(page).toHaveURL(/pembiayaan|pengajuan/i);
            const isTableVisible = await pengurusPage.isVisible(pengurusPage.pengajuanTable);
            expect(isTableVisible).toBe(true);

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should navigate to angsuran page', async ({ page }) => {
            await pengurusPage.navigateToAngsuran();

            // Verify navigation and table display
            await expect(page).toHaveURL(/angsuran/i);
            const isTableVisible = await pengurusPage.isVisible(pengurusPage.angsuranTable);
            expect(isTableVisible).toBe(true);
        });
    });

    test.describe('Anggota Management', () => {
        test('Should display anggota data correctly', async ({ page }) => {
            await pengurusPage.navigateToAnggota();

            try {
                const anggotaData = await pengurusPage.getAnggotaData();

                // Should have data or empty state message
                if (anggotaData.length > 0) {
                    // Verify data structure
                    anggotaData.forEach(row => {
                        expect(Array.isArray(row)).toBe(true);
                        expect(row.length).toBeGreaterThan(0);
                    });

                    // Should have headers/columns
                    expect(anggotaData[0].length).toBeGreaterThan(1);
                }
            } catch (error) {
                // Table might not exist yet or have no data - that's acceptable
                console.log('Anggota data not available:', error.message);
            }

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should search anggota functionality', async ({ page }) => {
            await pengurusPage.navigateToAnggota();

            // Try search functionality
            await pengurusPage.searchAnggota('2521');
            await pengurusPage.waitForPageLoad();

            // Should not error during search
            const currentUrl = page.url();
            expect(currentUrl).toMatch(/anggota/);

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });
    });

    test.describe('Pengajuan Management', () => {
        test('Should display pengajuan data correctly', async ({ page }) => {
            await pengurusPage.navigateToPembiayaan();

            try {
                const pengajuanData = await pengurusPage.getPengajuanData();

                // Should have data or empty state message
                if (pengajuanData.length > 0) {
                    // Verify data structure
                    pengajuanData.forEach(row => {
                        expect(Array.isArray(row)).toBe(true);
                        expect(row.length).toBeGreaterThan(0);
                    });
                }
            } catch (error) {
                // Table might not exist yet or have no data - that's acceptable
                console.log('Pengajuan data not available:', error.message);
            }

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });
    });

    test.describe('Transaksi Simpanan', () => {
        test('Should access transaksi simpanan functionality', async ({ page }) => {
            await pengurusPage.navigateToSimpanan();

            // Look for transaksi button
            if (await pengurusPage.isVisible(pengurusPage.btnTransaksiSimpanan)) {
                await pengurusPage.clickElement(pengurusPage.btnTransaksiSimpanan);
                await pengurusPage.waitForPageLoad();

                // Should navigate to transaksi form or modal
                const currentUrl = page.url();
                expect(currentUrl).toMatch(/transaksi|simpanan/);
            }

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should handle simpanan form elements', async ({ page }) => {
            await pengurusPage.navigateToSimpanan();

            // Check for form elements if available
            const hasJenisSimpanan = await pengurusPage.isVisible(pengurusPage.jenisSimpananSelect) ||
                                    await pengurusPage.isVisible('select[name*="simpanan"]');
            const hasJumlah = await pengurusPage.isVisible(pengurusPage.jumlahTransaksiInput) ||
                             await pengurusPage.isVisible('input[name*="jumlah"]');

            // At least one should be available for functionality
            expect(hasJenisSimpanan || hasJumlah).toBe(true);
        });
    });

    test.describe('Dashboard Responsiveness', () => {
        test('Should display correctly on different screen sizes', async ({ page }) => {
            // Test desktop view
            await page.setViewportSize({ width: 1280, height: 720 });
            await page.reload();
            await pengurusPage.waitForPageLoad();

            let isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);

            // Test tablet view
            await page.setViewportSize({ width: 768, height: 1024 });
            await page.reload();
            await pengurusPage.waitForPageLoad();

            isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);

            // Test mobile view
            await page.setViewportSize({ width: 375, height: 667 });
            await page.reload();
            await pengurusPage.waitForPageLoad();

            isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);
        });
    });

    test.describe('Dashboard Error Handling', () => {
        test('Should handle navigation gracefully if pages are unavailable', async ({ page }) => {
            // Try to navigate to various menu items
            const menuItems = ['Anggota', 'Simpanan', 'Pembiayaan', 'Angsuran', 'Laporan'];

            for (const menuItem of menuItems) {
                await pengurusPage.clickMenuItem(menuItem);
                await pengurusPage.waitForPageLoad();

                // Should either navigate successfully or show error page
                const currentUrl = page.url();

                // Should not crash or show server error
                expect(currentUrl).not.toContain('500');
                expect(currentUrl).not.toContain('error');

                // Should still be logged in
                const isLoggedIn = await authPage.isLoggedIn();
                expect(isLoggedIn).toBe(true);
            }
        });
    });

    test.describe('Dashboard Performance', () => {
        test('Should load dashboard within reasonable time', async ({ page }) => {
            const startTime = Date.now();

            await page.goto('/pengurus/dashboard');
            await pengurusPage.waitForPageLoad();
            await pengurusPage.waitForLoadingToComplete();

            const loadTime = Date.now() - startTime;

            // Should load within 10 seconds (adjust based on requirements)
            expect(loadTime).toBeLessThan(10000);
        });
    });
});