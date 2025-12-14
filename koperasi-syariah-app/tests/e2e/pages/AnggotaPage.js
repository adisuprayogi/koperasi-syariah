const { BasePage } = require('./BasePage');

class AnggotaPage extends BasePage {
    constructor(page) {
        super(page);

        // Dashboard selectors
        this.dashboardTitle = 'h1, .dashboard-title';
        this.infoCards = '.card, .info-card, .stat-card';
        this.dashboardMenu = '.sidebar, .nav-sidebar, .main-nav';

        // Menu items
        this.menuDashboard = 'a:has-text("Dashboard"), .sidebar a[href*="dashboard"]';
        this.menuProfil = 'a:has-text("Profil"), .sidebar a[href*="profil"]';
        this.menuSimpanan = 'a:has-text("Simpanan"), .sidebar a[href*="simpanan"]';
        this.menuPembiayaan = 'a:has-text("Pembiayaan"), .sidebar a[href*="pembiayaan"]';
        this.menuAngsuran = 'a:has-text("Angsuran"), .sidebar a[href*="angsuran"]';
        this.menuLaporan = 'a:has-text("Laporan"), .sidebar a[href*="laporan"]';

        // Pengajuan pembiayaan form
        this.btnAjukanPembiayaan = 'a:has-text("Ajukan"), button:has-text("Ajukan"), .btn-tambah';
        this.jenisPembiayaanSelect = 'select[name="jenis_pembiayaan"], select[name="jenisPembiayaan"]';
        this.jumlahInput = 'input[name="jumlah"], input[name*="jumlah"]';
        this.jangkaWaktuInput = 'input[name="jangka_waktu"], input[name*="jangka"]';
        this.tujuanTextarea = 'textarea[name="tujuan"], textarea[name*="tujuan"]';
        this.sumberPengembalianInput = 'input[name="sumber_pengembalian"], input[name*="sumber"]';
        this.jaminanTextarea = 'textarea[name="jaminan"], textarea[name*="jaminan"]';
        this.btnSubmitPengajuan = 'button[type="submit"], input[type="submit"]';

        // Profil form
        this.namaInput = 'input[name="nama"], input[name*="nama"]';
        this.emailInput = 'input[name="email"]';
        this.noHpInput = 'input[name="no_hp"], input[name*="hp"]';
        this.alamatTextarea = 'textarea[name="alamat"], textarea[name*="alamat"]';
        this.tempatLahirInput = 'input[name="tempat_lahir"], input[name*="tempat"]';
        this.tanggalLahirInput = 'input[name="tanggal_lahir"], input[type="date"]';
        this.btnUpdateProfil = 'button:has-text("Update"), button[type="submit"]';

        // Tables
        this.simpananTable = 'table:has-text("Simpanan"), .simpanan-table';
        this.angsuranTable = 'table:has-text("Angsuran"), .angsuran-table';
        this.pengajuanTable = 'table:has-text("Pengajuan"), .pengajuan-table';

        // Common buttons
        this.btnEdit = 'button:has-text("Edit"), a:has-text("Edit"), .btn-edit';
        this.btnHapus = 'button:has-text("Hapus"), a:has-text("Hapus"), .btn-hapus';
        this.btnDetail = 'button:has-text("Detail"), a:has-text("Detail"), .btn-detail';
    }

    /**
     * Verify user is on anggota dashboard
     */
    async verifyOnAnggotaDashboard() {
        await this.page.waitForURL(/anggota.*dashboard/, { timeout: 10000 });
        await this.waitForPageLoad();

        const titleText = await this.getText(this.dashboardTitle);
        return titleText.toLowerCase().includes('dashboard') ||
               await this.isVisible(this.infoCards);
    }

    /**
     * Navigate to pengajuan pembiayaan page
     */
    async navigateToPengajuanPembiayaan() {
        await this.clickElement(this.menuPembiayaan);
        await this.waitForPageLoad();

        // If there's a "tambah" or "ajukan" button, click it
        if (await this.isVisible(this.btnAjukanPembiayaan)) {
            await this.clickElement(this.btnAjukanPembiayaan);
            await this.waitForPageLoad();
        }
    }

    /**
     * Fill pengajuan pembiayaan form
     */
    async fillPengajuanForm(data) {
        if (data.jenisPembiayaan) {
            await this.selectDropdown(this.jenisPembiayaanSelect, data.jenisPembiayaan);
        }
        if (data.jumlah) {
            await this.fillInput(this.jumlahInput, data.jumlah);
        }
        if (data.jangkaWaktu) {
            await this.fillInput(this.jangkaWaktuInput, data.jangkaWaktu);
        }
        if (data.tujuan) {
            await this.fillInput(this.tujuanTextarea, data.tujuan);
        }
        if (data.sumberPengembalian) {
            await this.fillInput(this.sumberPengembalianInput, data.sumberPengembalian);
        }
        if (data.jaminan) {
            await this.fillInput(this.jaminanTextarea, data.jaminan);
        }
    }

    /**
     * Submit pengajuan pembiayaan form
     */
    async submitPengajuan() {
        await this.clickElement(this.btnSubmitPengajuan);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to profil page
     */
    async navigateToProfil() {
        await this.clickElement(this.menuProfil);
        await this.waitForPageLoad();
    }

    /**
     * Update profile information
     */
    async updateProfil(data) {
        if (data.nama) {
            await this.fillInput(this.namaInput, data.nama);
        }
        if (data.email) {
            await this.fillInput(this.emailInput, data.email);
        }
        if (data.noHp) {
            await this.fillInput(this.noHpInput, data.noHp);
        }
        if (data.alamat) {
            await this.fillInput(this.alamatTextarea, data.alamat);
        }
        if (data.tempatLahir) {
            await this.fillInput(this.tempatLahirInput, data.tempatLahir);
        }
        if (data.tanggalLahir) {
            await this.fillInput(this.tanggalLahirInput, data.tanggalLahir);
        }

        await this.clickElement(this.btnUpdateProfil);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to simpanan page
     */
    async navigateToSimpanan() {
        await this.clickElement(this.menuSimpanan);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.simpananTable, { timeout: 10000 });
    }

    /**
     * Navigate to angsuran page
     */
    async navigateToAngsuran() {
        await this.clickElement(this.menuAngsuran);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.angsuranTable, { timeout: 10000 });
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
     * Get simpanan data from table
     */
    async getSimpananData() {
        const rows = await this.getTableData(this.simpananTable);
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
     * Get angsuran data from table
     */
    async getAngsuranData() {
        const rows = await this.getTableData(this.angsuranTable);
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
     * Get pengajuan data from table
     */
    async getPengajuanData() {
        const rows = await this.getTableData(this.pengajuanTable);
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
     * Check if pengajuan with specific amount exists in table
     */
    async verifyPengajuanExists(jumlah) {
        const pengajuanData = await this.getPengajuanData();
        return pengajuanData.some(row =>
            row.some(cell => cell && cell.includes(jumlah))
        );
    }

    /**
     * Verify all menu items are present
     */
    async verifyMenuItems() {
        const menuItems = [
            this.menuDashboard,
            this.menuProfil,
            this.menuSimpanan,
            this.menuPembiayaan,
            this.menuAngsuran,
            this.menuLaporan
        ];

        for (const menuItem of menuItems) {
            if (!await this.isVisible(menuItem)) {
                return false;
            }
        }
        return true;
    }
}

module.exports = { AnggotaPage };