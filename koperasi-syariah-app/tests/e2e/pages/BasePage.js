// Base page class with common functionality for all pages

class BasePage {
    constructor(page) {
        this.page = page;
        this.baseURL = process.env.APP_URL || 'http://localhost:8010';
    }

    // Navigation methods
    async goto(path = '') {
        await this.page.goto(`${this.baseURL}${path}`);
    }

    async waitForPageLoad() {
        await this.page.waitForLoadState('networkidle');
    }

    // Authentication methods
    async login(usernameOrEmail, password) {
        try {
            // Navigate to login page first
            await this.goto('/login');

            // Fill login form - using correct selectors from the actual login form
            await this.page.waitForSelector('input[name="login"], input[name="username"], input[name="email"], #login, #username, #email', { timeout: 10000 });
            await this.page.fill('input[name="login"], input[name="username"], input[name="email"], #login, #username, #email', usernameOrEmail);

            await this.page.waitForSelector('input[name="password"], #password', { timeout: 10000 });
            await this.page.fill('input[name="password"], #password', password);

            // Click login button - using correct button text
            const loginButton = await this.page.locator('button[type="submit"], button:has-text("Login"), button:has-text("Masuk"), button:has-text("Masuk ke Akun"), .btn-primary, [data-testid="login-btn"]').first();
            await loginButton.click();

            // Wait for navigation away from login page
            await this.page.waitForURL(/(?!login)/, { timeout: 10000 });
            await this.waitForPageLoad();

        } catch (error) {
            console.error('Login failed:', error);
            throw error;
        }
    }

    async logout() {
        try {
            // Find and click logout button
            const logoutSelectors = [
                'button:has-text("Logout")',
                'a:has-text("Logout")',
                '.logout-btn',
                '[data-testid="logout"]'
            ];

            for (const selector of logoutSelectors) {
                if (await this.isVisible(selector)) {
                    await this.clickElement(selector);
                    break;
                }
            }

            // Wait for redirect to login page
            await this.page.waitForURL(/login/, { timeout: 10000 });
        } catch (error) {
            console.error('Logout failed:', error);
            throw error;
        }
    }

    // Common element interactions
    async clickElement(selector) {
        await this.page.waitForSelector(selector);
        await this.page.click(selector);
    }

    async fillInput(selector, value) {
        await this.page.waitForSelector(selector);
        await this.page.fill(selector, value);
    }

    async selectDropdown(selector, value) {
        await this.page.waitForSelector(selector);
        await this.page.selectOption(selector, value);
    }

    async getText(selector) {
        await this.page.waitForSelector(selector);
        return await this.page.textContent(selector);
    }

    async isVisible(selector) {
        try {
            await this.page.waitForSelector(selector, { timeout: 5000 });
            return true;
        } catch (error) {
            return false;
        }
    }

    // Form methods
    async fillForm(formData) {
        for (const [selector, value] of Object.entries(formData)) {
            if (value) {
                await this.fillInput(selector, value);
            }
        }
    }

    async submitForm(formSelector = 'form') {
        await this.page.click(`${formSelector} button[type="submit"], ${formSelector} input[type="submit"]`);
    }

    // Alert and notification methods
    async getSuccessMessage() {
        const successAlert = this.page.locator('.alert-success, .success-message');
        return await successAlert.textContent();
    }

    async getErrorMessage() {
        const errorAlert = this.page.locator('.alert-danger, .error-message, .text-red-600');
        return await errorAlert.textContent();
    }

    async waitForSuccessMessage() {
        await this.page.waitForSelector('.alert-success, .success-message', { timeout: 10000 });
    }

    async waitForErrorMessage() {
        await this.page.waitForSelector('.alert-danger, .error-message, .text-red-600', { timeout: 10000 });
    }

    // Table methods
    async getTableData(tableSelector = 'table') {
        await this.page.waitForSelector(tableSelector);
        return this.page.locator(`${tableSelector} tbody tr`);
    }

    async findTableRowByColumnText(columnIndex, searchText) {
        const rows = await this.getTableData();
        for (let i = 0; i < await rows.count(); i++) {
            const row = rows.nth(i);
            const cell = row.locator(`td:nth-child(${columnIndex + 1})`);
            const text = await cell.textContent();
            if (text && text.includes(searchText)) {
                return row;
            }
        }
        return null;
    }

    // Loading and waiting methods
    async waitForLoadingToComplete() {
        // Wait for loading spinners to disappear
        try {
            await this.page.waitForSelector('.loading, .spinner', { state: 'detached', timeout: 15000 });
        } catch (error) {
            // Loading spinner might not exist, continue
        }
    }

    async waitAndClick(selector, timeout = 10000) {
        await this.page.waitForSelector(selector, { timeout });
        await this.page.click(selector);
    }

    // Menu and navigation helpers
    async navigateToMenu(menuText) {
        // Try multiple selector patterns to find and click menu items
        const menuSelectors = [
            `.sidebar a:has-text("${menuText}")`,
            `.nav-sidebar a:has-text("${menuText}")`,
            `nav a:has-text("${menuText}")`,
            `.navbar a:has-text("${menuText}")`,
            `.menu a:has-text("${menuText}")`,
            `[data-menu="${menuText}"]`,
            `a[href*="${menuText.toLowerCase()}"]`,
            `button:has-text("${menuText}")`,
            `.btn:has-text("${menuText}")`,
            `text="${menuText}"`
        ];

        for (const selector of menuSelectors) {
            try {
                await this.page.waitForSelector(selector, { timeout: 3000 });
                await this.page.click(selector);
                await this.waitForPageLoad();
                return;
            } catch (error) {
                // Continue to next selector
                continue;
            }
        }

        throw new Error(`Menu item "${menuText}" not found`);
    }

    async clickMenuItem(menuText) {
        await this.page.click(`text="${menuText}"`);
    }

    async clickSidebarMenuItem(menuText) {
        await this.page.click(`.sidebar a:has-text("${menuText}"), .nav-sidebar a:has-text("${menuText}")`);
    }

    // Logout functionality
    async logout() {
        try {
            // Try multiple possible logout selectors
            const logoutSelectors = [
                'a[href="/logout"]',
                'button:has-text("Logout")',
                'a:has-text("Logout")',
                '.logout-btn',
                '[data-testid="logout"]'
            ];

            for (const selector of logoutSelectors) {
                if (await this.isVisible(selector)) {
                    await this.clickElement(selector);
                    break;
                }
            }

            // Wait for redirect to login page
            await this.page.waitForURL(/login/, { timeout: 10000 });
        } catch (error) {
            console.error('Logout failed:', error);
            throw error;
        }
    }

    // Screenshot and debugging helpers
    async takeScreenshot(filename) {
        await this.page.screenshot({
            path: `tests/e2e/reports/screenshots/${filename}`,
            fullPage: true
        });
    }

    async waitForPageTitle(expectedTitle) {
        await this.page.waitForFunction((title) => {
            return document.title.includes(title);
        }, expectedTitle);
    }
}

module.exports = { BasePage };