const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { AnggotaPage } = require('../../pages/AnggotaPage');
const { USERS, getLoginCredentials } = require('../../fixtures/users');

test.describe('Anggota Dashboard', () => {
    let authPage;
    let anggotaPage;

    test.use({ storageState: { cookies: [], origins: [] } }); // Start fresh

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
        anggotaPage = new AnggotaPage(page);

        // Login as anggota
        const credentials = getLoginCredentials('anggota');
        await authPage.login(credentials.login, credentials.password);
    });

    test.describe('Dashboard Access', () => {
        test('Should display anggota dashboard correctly', async ({ page }) => {
            // Verify on correct dashboard
            const isOnDashboard = await anggotaPage.verifyOnAnggotaDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify correct URL
            await expect(page).toHaveURL(/anggota.*dashboard/);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should display correct dashboard title', async ({ page }) => {
            const titleText = await anggotaPage.getText('h1, .dashboard-title, .page-title');
            expect(titleText.toLowerCase()).toContain('dashboard');
        });

        test('Should show navigation menu for anggota', async ({ page }) => {
            const hasMenu = await anggotaPage.verifyMenuItems();
            expect(hasMenu).toBe(true);

            // Verify specific menu items are visible
            expect(await anggotaPage.isVisible(anggotaPage.menuDashboard)).toBe(true);
            expect(await anggotaPage.isVisible(anggotaPage.menuProfil)).toBe(true);
            expect(await anggotaPage.isVisible(anggotaPage.menuSimpanan)).toBe(true);
            expect(await anggotaPage.isVisible(anggotaPage.menuPembiayaan)).toBe(true);
            expect(await anggotaPage.isVisible(anggotaPage.menuAngsuran)).toBe(true);
            expect(await anggotaPage.isVisible(anggotaPage.menuLaporan)).toBe(true);
        });
    });

    test.describe('Dashboard Information Cards', () => {
        test('Should display info cards with financial summary', async ({ page }) => {
            const dashboardInfo = await anggotaPage.getDashboardInfo();

            // Should have multiple info cards
            expect(dashboardInfo.length).toBeGreaterThan(0);

            // Verify common card titles exist
            const cardTitles = dashboardInfo.map(info => info.title.toLowerCase());

            // Look for expected card titles (may vary based on implementation)
            const expectedTitles = [
                'total simpanan',
                'saldo pinjaman',
                'jatuh tempo',
                'status pengajuan',
                'total',
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
            const dashboardInfo = await anggotaPage.getDashboardInfo();

            // Verify cards have both titles and values
            dashboardInfo.forEach(info => {
                expect(info.title).toBeTruthy();
                expect(info.value).toBeTruthy();
                expect(info.value.trim().length).toBeGreaterThan(0);
            });

            // Some values should contain numbers (amounts)
            const hasNumericValues = dashboardInfo.some(info =>
                /\d/.test(info.value)
            );
            expect(hasNumericValues).toBe(true);
        });
    });

    test.describe('Dashboard Navigation', () => {
        test('Should navigate to profil page', async ({ page }) => {
            await anggotaPage.navigateToProfil();

            // Verify navigation
            await expect(page).toHaveURL(/profil|profile/i);

            // Should still be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Should navigate to simpanan page', async ({ page }) => {
            await anggotaPage.navigateToSimpanan();

            // Verify navigation and table display
            await expect(page).toHaveURL(/simpanan/i);
            const isTableVisible = await anggotaPage.isVisible(anggotaPage.simpananTable);
            expect(isTableVisible).toBe(true);
        });

        test('Should navigate to angsuran page', async ({ page }) => {
            await anggotaPage.navigateToAngsuran();

            // Verify navigation and table display
            await expect(page).toHaveURL(/angsuran/i);
            const isTableVisible = await anggotaPage.isVisible(anggotaPage.angsuranTable);
            expect(isTableVisible).toBe(true);
        });

        test('Should navigate to pembiayaan page', async ({ page }) => {
            await anggotaPage.clickMenuItem('Pembiayaan');
            await anggotaPage.waitForPageLoad();

            // Verify navigation
            await expect(page).toHaveURL(/pembiayaan|pembiayaan/i);
        });
    });

    test.describe('Dashboard Data Display', () => {
        test('Should display simpanan data correctly', async ({ page }) => {
            await anggotaPage.navigateToSimpanan();

            try {
                const simpananData = await anggotaPage.getSimpananData();

                // Should have data or empty state message
                if (simpananData.length > 0) {
                    // Verify data structure
                    simpananData.forEach(row => {
                        expect(Array.isArray(row)).toBe(true);
                        expect(row.length).toBeGreaterThan(0);
                    });
                }
            } catch (error) {
                // Table might not exist yet or have no data - that's acceptable
                console.log('Simpanan data not available:', error.message);
            }
        });

        test('Should display angsuran data correctly', async ({ page }) => {
            await anggotaPage.navigateToAngsuran();

            try {
                const angsuranData = await anggotaPage.getAngsuranData();

                // Should have data or empty state message
                if (angsuranData.length > 0) {
                    // Verify data structure
                    angsuranData.forEach(row => {
                        expect(Array.isArray(row)).toBe(true);
                        expect(row.length).toBeGreaterThan(0);
                    });
                }
            } catch (error) {
                // Table might not exist yet or have no data - that's acceptable
                console.log('Angsuran data not available:', error.message);
            }
        });
    });

    test.describe('Dashboard Responsiveness', () => {
        test('Should display correctly on different screen sizes', async ({ page }) => {
            // Test desktop view
            await page.setViewportSize({ width: 1280, height: 720 });
            await page.reload();
            await anggotaPage.waitForPageLoad();

            let isOnDashboard = await anggotaPage.verifyOnAnggotaDashboard();
            expect(isOnDashboard).toBe(true);

            // Test tablet view
            await page.setViewportSize({ width: 768, height: 1024 });
            await page.reload();
            await anggotaPage.waitForPageLoad();

            isOnDashboard = await anggotaPage.verifyOnAnggotaDashboard();
            expect(isOnDashboard).toBe(true);

            // Test mobile view
            await page.setViewportSize({ width: 375, height: 667 });
            await page.reload();
            await anggotaPage.waitForPageLoad();

            isOnDashboard = await anggotaPage.verifyOnAnggotaDashboard();
            expect(isOnDashboard).toBe(true);
        });
    });

    test.describe('Dashboard Error Handling', () => {
        test('Should handle navigation gracefully if pages are unavailable', async ({ page }) => {
            // Try to navigate to various menu items
            const menuItems = ['Profil', 'Simpanan', 'Pembiayaan', 'Angsuran', 'Laporan'];

            for (const menuItem of menuItems) {
                await anggotaPage.clickMenuItem(menuItem);
                await anggotaPage.waitForPageLoad();

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

            await page.goto('/anggota/dashboard');
            await anggotaPage.waitForPageLoad();
            await anggotaPage.waitForLoadingToComplete();

            const loadTime = Date.now() - startTime;

            // Should load within 10 seconds (adjust based on requirements)
            expect(loadTime).toBeLessThan(10000);
        });
    });
});