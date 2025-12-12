const { BasePage } = require('./BasePage');

class AdminPage extends BasePage {
    constructor(page) {
        super(page);

        // Dashboard selectors
        this.dashboardTitle = 'h1, .dashboard-title';
        this.infoCards = '.card, .info-card, .stat-card';
        this.dashboardMenu = '.sidebar, .nav-sidebar, .main-nav';

        // Menu items
        this.menuDashboard = 'a:has-text("Dashboard"), .sidebar a[href*="dashboard"]';
        this.menuPengurus = 'a:has-text("Pengurus"), .sidebar a[href*="pengurus"]';
        this.menuKonfigurasi = 'a:has-text("Konfigurasi"), .sidebar a[href*="konfigurasi"]';
        this.menuDataMaster = 'a:has-text("Data Master"), .sidebar a[href*="data-master"]';
        this.menuLaporan = 'a:has-text("Laporan"), .sidebar a[href*="laporan"]';
        this.menuSystemSettings = 'a:has-text("System"), .sidebar a[href*="system"]';

        // Pengurus management
        this.btnTambahPengurus = 'button:has-text("Tambah"), a:has-text("Tambah"), .btn-tambah';
        this.namaPengurusInput = 'input[name="nama"], input[name*="nama"]';
        this.emailPengurusInput = 'input[name="email"], input[name*="email"]';
        this.passwordInput = 'input[name="password"], input[type="password"]';
        this.confirmPasswordInput = 'input[name="password_confirmation"], input[name*="confirm"]';
        this.jabatanSelect = 'select[name="jabatan"], select[name*="jabatan"]';
        this.statusPengurusSelect = 'select[name="status"], select[name*="status"]';

        // Konfigurasi sistem
        this.namaKoperasiInput = 'input[name="nama_koperasi"], input[name*="koperasi"]';
        this.alamatInput = 'input[name="alamat"], textarea[name*="alamat"]';
        this.noTelpInput = 'input[name="no_telp"], input[name*="telp"]';
        this.emailInput = 'input[name="email"]';
        this.websiteInput = 'input[name="website"], input[name*="website"]';

        // Data Master - Jenis Simpanan
        this.menuJenisSimpanan = 'a:has-text("Jenis Simpanan"), a[href*="jenis-simpanan"]';
        this.btnTambahJenisSimpanan = 'button:has-text("Tambah"), .btn-tambah';
        this.namaJenisSimpananInput = 'input[name="nama"], input[name*="nama"]';
        this.minimalSetoranInput = 'input[name="minimal_setoran"], input[name*="setoran"]';
        this.minimalSaldoInput = 'input[name="minimal_saldo"], input[name*="saldo"]';
        this.bungaInput = 'input[name="bunga"], input[name*="bunga"]';

        // Data Master - Jenis Pembiayaan
        this.menuJenisPembiayaan = 'a:has-text("Jenis Pembiayaan"), a[href*="jenis-pembiayaan"]';
        this.namaJenisPembiayaanInput = 'input[name="nama"], input[name*="nama"]';
        this.plafonMaksimalInput = 'input[name="plafon_maksimal"], input[name*="plafon"]';
        this.jangkaWaktuMaksimalInput = 'input[name="jangka_waktu_maksimal"], input[name*="jangka"]';
        this.marginInput = 'input[name="margin"], input[name*="margin"]';
        this.administrasiInput = 'input[name="administrasi"], input[name*="administrasi"]';
        this.provisiInput = 'input[name="provisi"], input[name*="provisi"]';

        // Tables
        this.pengurusTable = 'table:has-text("Pengurus"), .pengurus-table';
        this.jenisSimpananTable = 'table:has-text("Jenis Simpanan"), .jenis-simpanan-table';
        this.jenisPembiayaanTable = 'table:has-text("Jenis Pembiayaan"), .jenis-pembiayaan-table';

        // Laporan
        this.menuLaporanSimpanan = 'a:has-text("Laporan Simpanan"), a[href*="laporan-simpanan"]';
        this.menuLaporanPembiayaan = 'a:has-text("Laporan Pembiayaan"), a[href*="laporan-pembiayaan"]';
        this.menuLaporanLabaRugi = 'a:has-text("Laba Rugi"), a[href*="laba-rugi"]';
        this.menuLaporanNeraca = 'a:has-text("Neraca"), a[href*="neraca"]';
        this.btnExportExcel = 'button:has-text("Export"), button:has-text("Download"), .btn-export';

        // Search and filter
        this.searchInput = 'input[name="search"], input[placeholder*="cari"], .search-input';

        // Common buttons
        this.btnEdit = 'button:has-text("Edit"), a:has-text("Edit"), .btn-edit';
        this.btnHapus = 'button:has-text("Hapus"), a:has-text("Hapus"), .btn-hapus';
        this.btnSimpan = 'button:has-text("Simpan"), button[type="submit"], input[type="submit"]';
        this.btnUpdate = 'button:has-text("Update"), button[type="submit"], input[type="submit"]';
    }

    /**
     * Verify user is on admin dashboard
     */
    async verifyOnAdminDashboard() {
        await this.page.waitForURL(/admin.*dashboard/, { timeout: 10000 });
        await this.waitForPageLoad();

        const titleText = await this.getText(this.dashboardTitle);
        return titleText.toLowerCase().includes('dashboard') ||
               await this.isVisible(this.infoCards);
    }

    /**
     * Navigate to pengurus management page
     */
    async navigateToPengurus() {
        await this.clickElement(this.menuPengurus);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.pengurusTable, { timeout: 10000 });
    }

    /**
     * Add new pengurus
     */
    async addPengurus(pengurusData) {
        await this.clickElement(this.btnTambahPengurus);
        await this.waitForPageLoad();

        if (pengurusData.nama) {
            await this.fillInput(this.namaPengurusInput, pengurusData.nama);
        }
        if (pengurusData.email) {
            await this.fillInput(this.emailPengurusInput, pengurusData.email);
        }
        if (pengurusData.password) {
            await this.fillInput(this.passwordInput, pengurusData.password);
        }
        if (pengurusData.confirmPassword) {
            await this.fillInput(this.confirmPasswordInput, pengurusData.confirmPassword);
        }
        if (pengurusData.jabatan) {
            await this.selectDropdown(this.jabatanSelect, pengurusData.jabatan);
        }
        if (pengurusData.status) {
            await this.selectDropdown(this.statusPengurusSelect, pengurusData.status);
        }

        await this.clickElement(this.btnSimpan);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to konfigurasi page
     */
    async navigateToKonfigurasi() {
        await this.clickElement(this.menuKonfigurasi);
        await this.waitForPageLoad();
    }

    /**
     * Update konfigurasi sistem
     */
    async updateKonfigurasi(configData) {
        if (configData.namaKoperasi) {
            await this.fillInput(this.namaKoperasiInput, configData.namaKoperasi);
        }
        if (configData.alamat) {
            await this.fillInput(this.alamatInput, configData.alamat);
        }
        if (configData.noTelp) {
            await this.fillInput(this.noTelpInput, configData.noTelp);
        }
        if (configData.email) {
            await this.fillInput(this.emailInput, configData.email);
        }
        if (configData.website) {
            await this.fillInput(this.websiteInput, configData.website);
        }

        await this.clickElement(this.btnUpdate);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to jenis simpanan page
     */
    async navigateToJenisSimpanan() {
        await this.clickElement(this.menuDataMaster);
        await this.waitForPageLoad();
        await this.clickElement(this.menuJenisSimpanan);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.jenisSimpananTable, { timeout: 10000 });
    }

    /**
     * Add new jenis simpanan
     */
    async addJenisSimpanan(jenisSimpananData) {
        await this.clickElement(this.btnTambahJenisSimpanan);
        await this.waitForPageLoad();

        if (jenisSimpananData.namaJenis) {
            await this.fillInput(this.namaJenisSimpananInput, jenisSimpananData.namaJenis);
        }
        if (jenisSimpananData.minimalSetoran) {
            await this.fillInput(this.minimalSetoranInput, jenisSimpananData.minimalSetoran);
        }
        if (jenisSimpananData.minimalSaldo) {
            await this.fillInput(this.minimalSaldoInput, jenisSimpananData.minimalSaldo);
        }
        if (jenisSimpananData.bunga) {
            await this.fillInput(this.bungaInput, jenisSimpananData.bunga);
        }

        await this.clickElement(this.btnSimpan);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to jenis pembiayaan page
     */
    async navigateToJenisPembiayaan() {
        await this.clickElement(this.menuDataMaster);
        await this.waitForPageLoad();
        await this.clickElement(this.menuJenisPembiayaan);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.jenisPembiayaanTable, { timeout: 10000 });
    }

    /**
     * Add new jenis pembiayaan
     */
    async addJenisPembiayaan(jenisPembiayaanData) {
        await this.clickElement(this.btnTambahJenisSimpanan); // Same button text
        await this.waitForPageLoad();

        if (jenisPembiayaanData.namaJenis) {
            await this.fillInput(this.namaJenisPembiayaanInput, jenisPembiayaanData.namaJenis);
        }
        if (jenisPembiayaanData.plafonMaksimal) {
            await this.fillInput(this.plafonMaksimalInput, jenisPembiayaanData.plafonMaksimal);
        }
        if (jenisPembiayaanData.jangkaWaktuMaksimal) {
            await this.fillInput(this.jangkaWaktuMaksimalInput, jenisPembiayaanData.jangkaWaktuMaksimal);
        }
        if (jenisPembiayaanData.margin) {
            await this.fillInput(this.marginInput, jenisPembiayaanData.margin);
        }
        if (jenisPembiayaanData.administrasi) {
            await this.fillInput(this.administrasiInput, jenisPembiayaanData.administrasi);
        }
        if (jenisPembiayaanData.provisi) {
            await this.fillInput(this.provisiInput, jenisPembiayaanData.provisi);
        }

        await this.clickElement(this.btnSimpan);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to laporan simpanan
     */
    async navigateToLaporanSimpanan() {
        await this.clickElement(this.menuLaporan);
        await this.waitForPageLoad();
        await this.clickElement(this.menuLaporanSimpanan);
        await this.waitForPageLoad();
    }

    /**
     * Export laporan to Excel
     */
    async exportLaporanToExcel() {
        if (await this.isVisible(this.btnExportExcel)) {
            await this.clickElement(this.btnExportExcel);
            // Wait for download to start
            await this.page.waitForTimeout(2000);
        }
    }

    /**
     * Navigate to laporan laba rugi
     */
    async navigateToLaporanLabaRugi() {
        await this.clickElement(this.menuLaporan);
        await this.waitForPageLoad();
        await this.clickElement(this.menuLaporanLabaRugi);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to laporan neraca
     */
    async navigateToLaporanNeraca() {
        await this.clickElement(this.menuLaporan);
        await this.waitForPageLoad();
        await this.clickElement(this.menuLaporanNeraca);
        await this.waitForPageLoad();
    }

    /**
     * Get pengurus data from table
     */
    async getPengurusData() {
        const rows = await this.getTableData(this.pengurusTable);
        const data = [];

        for (let i = 0; i < await rows.count(); i++) {
            const row = rows.nth(i);
            const cells = await row.locator('td').count();
            const rowData = [];

            for (let j = 0; j < cells; j++) {
                const cellText = await row.locator(`td:nth-child(${j + 1})`).textContent();
                rowData.push(cellText?.trim());
            }

            data.push(rowData);
        }

        return data;
    }

    /**
     * Get jenis simpanan data from table
     */
    async getJenisSimpananData() {
        const rows = await this.getTableData(this.jenisSimpananTable);
        const data = [];

        for (let i = 0; i < await rows.count(); i++) {
            const row = rows.nth(i);
            const cells = await row.locator('td').count();
            const rowData = [];

            for (let j = 0; j < cells; j++) {
                const cellText = await row.locator(`td:nth-child(${j + 1})`).textContent();
                rowData.push(cellText?.trim());
            }

            data.push(rowData);
        }

        return data;
    }

    /**
     * Get dashboard info cards data
     */
    async getDashboardInfo() {
        const cards = await this.page.locator(this.infoCards).count();
        const info = [];

        for (let i = 0; i < cards; i++) {
            const card = this.page.locator(this.infoCards).nth(i);
            const title = await card.locator('.card-title, h3, h4, .title').textContent();
            const value = await card.locator('.card-value, .value, h2, .amount').textContent();
            info.push({ title: title?.trim(), value: value?.trim() });
        }

        return info;
    }

    /**
     * Verify all menu items are present
     */
    async verifyMenuItems() {
        const menuItems = [
            this.menuDashboard,
            this.menuPengurus,
            this.menuKonfigurasi,
            this.menuDataMaster,
            this.menuLaporan
        ];

        for (const menuItem of menuItems) {
            if (!await this.isVisible(menuItem)) {
                return false;
            }
        }
        return true;
    }

    /**
     * Check if pengurus exists in table
     */
    async verifyPengurusExists(nama) {
        const pengurusData = await this.getPengurusData();
        return pengurusData.some(row =>
            row.some(cell => cell && cell.includes(nama))
        );
    }

    /**
     * Check if jenis simpanan exists in table
     */
    async verifyJenisSimpananExists(nama) {
        const jenisSimpananData = await this.getJenisSimpananData();
        return jenisSimpananData.some(row =>
            row.some(cell => cell && cell.includes(nama))
        );
    }

    /**
     * Search pengurus by name or email
     */
    async searchPengurus(searchTerm) {
        if (await this.isVisible(this.searchInput)) {
            await this.fillInput(this.searchInput, searchTerm);
            await this.page.keyboard.press('Enter');
            await this.waitForPageLoad();
        }
    }
}

module.exports = { AdminPage };