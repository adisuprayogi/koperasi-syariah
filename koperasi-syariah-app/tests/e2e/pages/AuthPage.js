const { BasePage } = require('./BasePage');

class AuthPage extends BasePage {
    constructor(page) {
        super(page);

        // Login form selectors
        this.loginInput = 'input[name="login"]';
        this.passwordInput = 'input[name="password"]';
        this.rememberCheckbox = 'input[name="remember"]';
        this.loginButton = 'button[type="submit"], input[type="submit"]';
        this.loginForm = 'form';

        // Common page elements
        this.loginTitle = 'h1, h2, .login-title';
        this.errorMessage = '.alert-danger, .error-message, .text-red-600';
        this.successMessage = '.alert-success, .success-message';

        // Page URLs
        this.loginUrl = '/login';
    }

    /**
     * Navigate to login page
     */
    async gotoLoginPage() {
        await this.goto(this.loginUrl);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.loginInput, { timeout: 10000 });
    }

    /**
     * Check if user is on login page
     */
    async isOnLoginPage() {
        const currentUrl = this.page.url();
        return currentUrl.includes('/login');
    }

    /**
     * Fill login form with credentials
     */
    async fillLoginForm(login, password) {
        await this.fillInput(this.loginInput, login);
        await this.fillInput(this.passwordInput, password);
    }

    /**
     * Submit login form
     */
    async submitLoginForm() {
        await this.waitAndClick(this.loginButton);
        await this.waitForPageLoad();
    }

    /**
     * Perform complete login process
     */
    async login(login, password, remember = false) {
        await this.gotoLoginPage();

        // Fill and submit form
        await this.fillLoginForm(login, password);

        if (remember) {
            await this.clickElement(this.rememberCheckbox);
        }

        await this.submitLoginForm();

        // Wait for successful login (redirect away from login page)
        await this.page.waitForFunction(() => {
            return !window.location.href.includes('/login');
        }, { timeout: 10000 });

        await this.waitForPageLoad();
    }

    /**
     * Get error message from login attempt
     */
    async getLoginErrorMessage() {
        if (await this.isVisible(this.errorMessage)) {
            return await this.getText(this.errorMessage);
        }
        return '';
    }

    /**
     * Get success message from login attempt
     */
    async getLoginSuccessMessage() {
        if (await this.isVisible(this.successMessage)) {
            return await this.getText(this.successMessage);
        }
        return '';
    }

    /**
     * Check if login form is visible
     */
    async isLoginFormVisible() {
        return await this.isVisible(this.loginInput) &&
               await this.isVisible(this.passwordInput) &&
               await this.isVisible(this.loginButton);
    }

    /**
     * Verify login page title/heading
     */
    async verifyLoginPageTitle() {
        await this.page.waitForSelector(this.loginTitle, { timeout: 10000 });
        const titleText = await this.getText(this.loginTitle);
        return titleText.toLowerCase().includes('login') ||
               titleText.toLowerCase().includes('masuk');
    }

    /**
     * Clear form fields
     */
    async clearLoginForm() {
        await this.page.fill(this.loginInput, '');
        await this.page.fill(this.passwordInput, '');
    }

    /**
     * Login with enter key
     */
    async loginWithEnter(login, password) {
        await this.gotoLoginPage();
        await this.fillLoginForm(login, password);
        await this.page.press(this.passwordInput, 'Enter');
        await this.waitForPageLoad();
    }

    /**
     * Check if remember me checkbox is checked
     */
    async isRememberMeChecked() {
        return await this.page.isChecked(this.rememberCheckbox);
    }

    /**
     * Toggle remember me checkbox
     */
    async toggleRememberMe() {
        await this.clickElement(this.rememberCheckbox);
    }

    /**
     * Wait for authentication redirect
     */
    async waitForAuthRedirect(expectedUrlPattern) {
        await this.page.waitForURL(new RegExp(expectedUrlPattern), { timeout: 10000 });
    }

    /**
     * Get current authenticated user info (if displayed on page)
     */
    async getCurrentUserInfo() {
        try {
            // Common selectors for user info display
            const userInfoSelectors = [
                '.user-info .name',
                '.navbar .user-name',
                '[data-testid="user-name"]',
                '.current-user .name'
            ];

            for (const selector of userInfoSelectors) {
                if (await this.isVisible(selector)) {
                    return await this.getText(selector);
                }
            }
        } catch (error) {
            console.log('User info not visible:', error.message);
        }
        return null;
    }

    /**
     * Verify user is logged in by checking for logout button
     */
    async isLoggedIn() {
        const logoutSelectors = [
            'a[href="/logout"]',
            'button:has-text("Logout")',
            'a:has-text("Logout")'
        ];

        for (const selector of logoutSelectors) {
            if (await this.isVisible(selector)) {
                return true;
            }
        }
        return false;
    }
}

module.exports = { AuthPage };