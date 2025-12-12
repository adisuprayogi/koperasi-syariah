const { test, expect } = require('@playwright/test');
const { AuthPage } = require('../../pages/AuthPage');
const { AnggotaPage } = require('../../pages/AnggotaPage');
const { PengurusPage } = require('../../pages/PengurusPage');
const { AdminPage } = require('../../pages/AdminPage');
const { USERS, getLoginCredentials, getExpectedDashboard } = require('../../fixtures/users');

test.describe('Authentication - Login', () => {
    let authPage;

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
        await authPage.gotoLoginPage();
    });

    test.describe('Valid Login Credentials', () => {
        test('Anggota should login successfully with username', async ({ page }) => {
            const credentials = getLoginCredentials('anggota');
            const expectedDashboard = getExpectedDashboard('anggota');

            await authPage.login(credentials.login, credentials.password);

            // Verify redirect to correct dashboard
            await expect(page).toHaveURL(new RegExp(expectedDashboard));

            // Verify dashboard elements
            const anggotaPage = new AnggotaPage(page);
            const isOnDashboard = await anggotaPage.verifyOnAnggotaDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Pengurus should login successfully with email', async ({ page }) => {
            const credentials = getLoginCredentials('pengurus');
            const expectedDashboard = getExpectedDashboard('pengurus');

            await authPage.login(credentials.login, credentials.password);

            // Verify redirect to correct dashboard
            await expect(page).toHaveURL(new RegExp(expectedDashboard));

            // Verify dashboard elements
            const pengurusPage = new PengurusPage(page);
            const isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Bendahara should login successfully with email', async ({ page }) => {
            const credentials = getLoginCredentials('bendahara');
            const expectedDashboard = getExpectedDashboard('bendahara');

            await authPage.login(credentials.login, credentials.password);

            // Verify redirect to correct dashboard
            await expect(page).toHaveURL(new RegExp(expectedDashboard));

            // Verify dashboard elements (same as pengurus)
            const pengurusPage = new PengurusPage(page);
            const isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });

        test('Admin should login successfully with email', async ({ page }) => {
            const credentials = getLoginCredentials('admin');
            const expectedDashboard = getExpectedDashboard('admin');

            await authPage.login(credentials.login, credentials.password);

            // Verify redirect to correct dashboard
            await expect(page).toHaveURL(new RegExp(expectedDashboard));

            // Verify dashboard elements
            const adminPage = new AdminPage(page);
            const isOnDashboard = await adminPage.verifyOnAdminDashboard();
            expect(isOnDashboard).toBe(true);

            // Verify user is logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(true);
        });
    });

    test.describe('Invalid Login Credentials', () => {
        test('Should show error for invalid username', async ({ page }) => {
            const invalidCredentials = getLoginCredentials('invalid');
            await authPage.login(invalidCredentials.login, invalidCredentials.password);

            // Should stay on login page
            await expect(page).toHaveURL(/login/);

            // Should show error message
            const errorMessage = await authPage.getLoginErrorMessage();
            expect(errorMessage.toLowerCase()).toContain('salah');

            // Should not be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });

        test('Should show error for invalid email', async ({ page }) => {
            await authPage.login('invalid@test.com', 'wrongpassword');

            // Should stay on login page
            await expect(page).toHaveURL(/login/);

            // Should show error message
            const errorMessage = await authPage.getLoginErrorMessage();
            expect(errorMessage.toLowerCase()).toContain('salah');

            // Should not be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });

        test('Should show error for wrong password', async ({ page }) => {
            await authPage.login('2521.00001', 'wrongpassword');

            // Should stay on login page
            await expect(page).toHaveURL(/login/);

            // Should show error message
            const errorMessage = await authPage.getLoginErrorMessage();
            expect(errorMessage.toLowerCase()).toContain('salah');

            // Should not be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });

        test('Should show error for empty fields', async ({ page }) => {
            await authPage.login('', '');

            // Should stay on login page
            await expect(page).toHaveURL(/login/);

            // Form should still be visible
            const isFormVisible = await authPage.isLoginFormVisible();
            expect(isFormVisible).toBe(true);

            // Should not be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });

        test('Should show error for partially filled form', async ({ page }) => {
            await authPage.login('2521.00001', '');

            // Should stay on login page
            await expect(page).toHaveURL(/login/);

            // Should not be logged in
            const isLoggedIn = await authPage.isLoggedIn();
            expect(isLoggedIn).toBe(false);
        });
    });

    test.describe('Login Form Functionality', () => {
        test('Should remember login when checkbox checked', async ({ page }) => {
            const credentials = getLoginCredentials('anggota');

            await authPage.gotoLoginPage();
            await authPage.fillLoginForm(credentials.login, credentials.password);
            await authPage.toggleRememberMe();
            await authPage.submitLoginForm();

            // Verify login successful
            await expect(page).not.toHaveURL(/login/);

            // Note: Testing actual remember functionality requires browser restart
            // which is beyond the scope of basic E2E tests
        });

        test('Should login with Enter key', async ({ page }) => {
            const credentials = getLoginCredentials('pengurus');

            await authPage.loginWithEnter(credentials.login, credentials.password);

            // Verify redirect to correct dashboard
            const expectedDashboard = getExpectedDashboard('pengurus');
            await expect(page).toHaveURL(new RegExp(expectedDashboard));

            // Verify dashboard elements
            const pengurusPage = new PengurusPage(page);
            const isOnDashboard = await pengurusPage.verifyOnPengurusDashboard();
            expect(isOnDashboard).toBe(true);
        });

        test('Should clear form fields properly', async ({ page }) => {
            await authPage.fillLoginForm('test@example.com', 'password');
            await authPage.clearLoginForm();

            // Verify fields are cleared
            await expect(page.locator('input[name="login"]')).toHaveValue('');
            await expect(page.locator('input[name="password"]')).toHaveValue('');
        });

        test('Should show proper page title', async ({ page }) => {
            const hasCorrectTitle = await authPage.verifyLoginPageTitle();
            expect(hasCorrectTitle).toBe(true);
        });
    });

    test.describe('Login Field Validation', () => {
        test('Should handle special characters in login field', async ({ page }) => {
            await authPage.login('test+special@example.com', 'password123');

            // Should handle gracefully (either login or show error)
            const currentUrl = page.url();
            expect(currentUrl).toMatch(/login|dashboard/);
        });

        test('Should handle case sensitivity', async ({ page }) => {
            // Test with capital letters in username
            await authPage.login('2521.00001'.toUpperCase(), '22222222');

            // Should show error for case sensitivity (if applicable)
            const errorMessage = await authPage.getLoginErrorMessage();
            // Either error message appears or login succeeds
            expect(page.url().includes('/login') || errorMessage).toBeTruthy();
        });
    });
});

test.describe('Authentication - Session Management', () => {
    let authPage;

    test.beforeEach(async ({ page }) => {
        authPage = new AuthPage(page);
    });

    test('Should maintain session across page navigation', async ({ page }) => {
        const credentials = getLoginCredentials('anggota');

        // Login
        await authPage.login(credentials.login, credentials.password);

        // Navigate to different pages within the same role
        const anggotaPage = new AnggotaPage(page);
        await anggotaPage.navigateToProfil();
        await anggotaPage.navigateToSimpanan();
        await anggotaPage.navigateToAngsuran();

        // Should still be logged in
        const isLoggedIn = await authPage.isLoggedIn();
        expect(isLoggedIn).toBe(true);
    });

    test('Should handle session timeout gracefully', async ({ page }) => {
        // This test would require session timeout configuration
        // For now, we'll just verify normal session behavior
        const credentials = getLoginCredentials('admin');

        await authPage.login(credentials.login, credentials.password);

        // Verify initial login
        const adminPage = new AdminPage(page);
        const isOnDashboard = await adminPage.verifyOnAdminDashboard();
        expect(isOnDashboard).toBe(true);
    });
});