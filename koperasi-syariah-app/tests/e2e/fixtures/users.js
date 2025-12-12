// Test user credentials for Koperasi Syariah E2E testing
// These users match the existing database seeded users

export const USERS = {
    // Anggota user - uses username (nomor anggota) for login
    anggota: {
        username: '2512.00001',
        password: '22222222',
        nama: 'Test Anggota',
        email: 'anggota@test.com',
        role: 'anggota',
        expectedDashboard: '/anggota/dashboard'
    },

    // Pengurus users - use email for login
    pengurus: {
        email: 'yogi@gmail.com',
        password: '22222222',
        nama: 'Test Pengurus (Ketua)',
        role: 'pengurus',
        expectedDashboard: '/pengurus/dashboard'
    },

    bendahara: {
        email: 'fitri@gmail.com',
        password: '33333333',
        nama: 'Test Pengurus (Bendahara)',
        role: 'pengurus',
        expectedDashboard: '/pengurus/dashboard'
    },

    // Admin user - uses email for login
    admin: {
        email: 'admin@admin.com',
        password: 'password',
        nama: 'Test Admin',
        role: 'admin',
        expectedDashboard: '/admin/dashboard'
    },

    // Invalid user for negative testing
    invalid: {
        email: 'invalid@test.com',
        password: 'wrongpassword',
        username: 'invalid.user',
        nama: 'Invalid User'
    }
};

// Helper function to get login field based on user role
export function getLoginCredentials(userType) {
    const user = USERS[userType];
    if (!user) {
        throw new Error(`User type '${userType}' not found in fixtures`);
    }

    if (user.role === 'anggota') {
        return {
            login: user.username,
            password: user.password
        };
    } else {
        return {
            login: user.email,
            password: user.password
        };
    }
}

// Helper function to get expected dashboard URL
export function getExpectedDashboard(userType) {
    const user = USERS[userType];
    if (!user || !user.expectedDashboard) {
        throw new Error(`Expected dashboard for user type '${userType}' not found`);
    }
    return user.expectedDashboard;
}