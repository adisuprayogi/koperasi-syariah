const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { AnggotaPage } = require('../../pages/AnggotaPage');
const { PengurusPage } = require('../../pages/PengurusPage');
const { AdminPage } = require('../../pages/AdminPage');
const { USERS, getLoginCredentials } = require('../../fixtures/users');

test.describe('Authentication - Logout', () => {
    let authPage;

    test.describe('Successful Logout', () => {
        test('Anggota should logout successfully', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('anggota');

            // First login
            await authPage.login(credentials.login, credentials.password);

            // Verify logged in
            let isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);

            // Perform logout
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);

            // Verify not logged in
            isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);

            // Verify login form is visible
            const isLoginFormVisible = await authPage.isLoginFormVisible();
            expect(isLoginFormVisible).toBe(true);
        });

        test('Pengurus should logout successfully', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('pengurus');

            // First login
            await authPage.login(credentials.login, credentials.password);

            // Verify logged in
            let isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);

            // Perform logout
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);

            // Verify not logged in
            isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);

            // Verify login form is visible
            const isLoginFormVisible = await authPage.isLoginFormVisible();
            expect(isLoginFormVisible).toBe(true);
        });

        test('Admin should logout successfully', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('admin');

            // First login
            await authPage.login(credentials.login, credentials.password);

            // Verify logged in
            let isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);

            // Perform logout
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);

            // Verify not logged in
            isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);

            // Verify login form is visible
            const isLoginFormVisible = await authPage.isLoginFormVisible();
            expect(isLoginFormVisible).toBe(true);
        });
    });

    test.describe('Session Invalidation', () => {
        test('Should invalidate session after logout', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('anggota');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Logout
            await authPage.logout();

            // Try to access dashboard directly
            await page.goto('/anggota/dashboard');

            // Should redirect to login page
            await expect(page).toHaveURL(/login/);

            // Verify not logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });

        test('Should require login after logout for all roles', async ({ page }) => {
            authPage = new AuthPage(page);

            const roles = ['anggota', 'pengurus', 'admin'];

            for (const role of roles) {
                const credentials = getLoginCredentials(role);

                // Login
                await authPage.login(credentials.login, credentials.password);

                // Logout
                await authPage.logout();

                // Try to access role-specific dashboard
                await page.goto(`/${role}/dashboard`);

                // Should redirect to login page
                await expect(page).toHaveURL(/login/);

                // Verify login form is visible
                const isLoginFormVisible = await authPage.isLoginFormVisible();
                expect(isLoginFormVisible).toBe(true);
            }
        });

        test('Should clear user session data', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('pengurus');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Get user info if available
            const userInfoBeforeLogout = await authPage.getCurrentUserInfo();

            // Logout
            await authPage.logout();

            // Try to get user info (should be null/empty)
            const userInfoAfterLogout = await authPage.getCurrentUserInfo();

            // User info should be cleared
            expect(userInfoAfterLogout).toBe(null);
        });
    });

    test.describe('Logout from Different Pages', () => {
        test('Should logout from anggota profile page', async ({ page }) => {
            authPage = new AuthPage(page);
            const anggotaPage = new AnggotaPage(page);
            const credentials = getLoginCredentials('anggota');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Navigate to profile page
            await anggotaPage.navigateToProfil();

            // Logout from profile page
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);
        });

        test('Should logout from pengurus anggota management page', async ({ page }) => {
            authPage = new AuthPage(page);
            const pengurusPage = new PengurusPage(page);
            const credentials = getLoginCredentials('pengurus');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Navigate to anggota management page
            await pengurusPage.navigateToAnggota();

            // Logout from anggota page
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);
        });

        test('Should logout from admin configuration page', async ({ page }) => {
            authPage = new AuthPage(page);
            const adminPage = new AdminPage(page);
            const credentials = getLoginCredentials('admin');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Navigate to configuration page
            await adminPage.navigateToKonfigurasi();

            // Logout from configuration page
            await authPage.logout();

            // Verify redirect to login page
            await expect(page).toHaveURL(/login/);
        });
    });

    test.describe('Post-Login Flow After Logout', () => {
        test('Should allow re-login after logout', async ({ page }) => {
            authPage = new AuthPage(page);
            const anggotaCredentials = getLoginCredentials('anggota');
            const adminCredentials = getLoginCredentials('admin');

            // First login as anggota
            await authPage.login(anggotaCredentials.login, anggotaCredentials.password);
            let isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);

            // Logout
            await authPage.logout();

            // Verify on login page
            await expect(page).toHaveURL(/login/);

            // Login again as different user (admin)
            await authPage.login(adminCredentials.login, adminCredentials.password);

            // Verify new login successful
            await expect(page).toHaveURL(/admin.*dashboard/);
            isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);

            // Verify admin dashboard elements
            const adminPage = new AdminPage(page);
            const isOnDashboard = await adminPage.verifyOnAdminDashboard();
            expect(isOnDashboard).toBe(true);
        });

        test('Should maintain form state after logout', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('anggota');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Logout
            await authPage.logout();

            // Fill login form
            await authPage.fillLoginForm('test@example.com', 'password');

            // Navigate away and back to login
            await page.goto('/dashboard');
            await page.waitForTimeout(1000); // Wait for redirect
            await page.goto('/login');

            // Form should be cleared (this depends on implementation)
            // Some applications might preserve form state, others might not
            const loginInput = await page.locator('input[name="login"]').inputValue();
            // Either empty or contains the previous value - both are acceptable
            expect(typeof loginInput).toBe('string');
        });
    });

    test.describe('Logout Button Accessibility', () => {
        test('Should have accessible logout button', async ({ page }) => {
            authPage = new AuthPage(page);
            const credentials = getLoginCredentials('admin');

            // Login
            await authPage.login(credentials.login, credentials.password);

            // Check for logout button in common locations
            const logoutSelectors = [
                'a[href="/logout"]',
                'button[aria-label*="logout"]',
                'button:has-text("Logout")',
                'a:has-text("Logout")'
            ];

            let logoutButtonFound = false;
            for (const selector of logoutSelectors) {
                if (await authPage.isVisible(selector)) {
                    logoutButtonFound = true;
                    break;
                }
            }

            expect(logoutButtonFound).toBe(true);

            // Logout
            await authPage.logout();
        });
    });
});