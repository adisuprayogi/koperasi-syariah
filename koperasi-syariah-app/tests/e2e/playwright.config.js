module.exports = {
    testDir: './tests',
    timeout: 30000,
    retries: 1,
    fullyParallel: false, // Sequential testing to avoid DB conflicts
    forbidOnly: !!process.env.CI,

    use: {
        baseURL: process.env.APP_URL || 'http://localhost:8010',
        trace: 'on-first-retry',
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
        viewport: { width: 1280, height: 720 },
        ignoreHTTPSErrors: true,
        actionTimeout: 10000,
        navigationTimeout: 15000,
    },

    projects: [
        {
            name: 'chromium',
            use: { ...require('@playwright/test').devices['Desktop Chrome'] },
        },
    ],

    reporter: [
        ['html', {
            outputFolder: '../reports/html',
            open: 'never'
        }],
        ['junit', {
            outputFile: '../reports/junit.xml'
        }],
        ['list'],
    ],

  };