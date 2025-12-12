const { BasePage } = require('./BasePage');

class PengurusPage extends BasePage {
    constructor(page) {
        super(page);

        // Dashboard selectors
        this.dashboardTitle = 'h1, .dashboard-title';
        this.infoCards = '.card, .info-card, .stat-card';
        this.dashboardMenu = '.sidebar, .nav-sidebar, .main-nav';

        // Menu items
        this.menuDashboard = 'a:has-text("Dashboard"), .sidebar a[href*="dashboard"]';
        this.menuAnggota = 'a:has-text("Anggota"), .sidebar a[href*="anggota"]';
        this.menuSimpanan = 'a:has-text("Simpanan"), .sidebar a[href*="simpanan"]';
        this.menuPembiayaan = 'a:has-text("Pembiayaan"), .sidebar a[href*="pembiayaan"]';
        this.menuAngsuran = 'a:has-text("Angsuran"), .sidebar a[href*="angsuran"]';
        this.menuLaporan = 'a:has-text("Laporan"), .sidebar a[href*="laporan"]';

        // Anggota management
        this.btnTambahAnggota = 'button:has-text("Tambah"), a:has-text("Tambah"), .btn-tambah';
        this.nomorAnggotaInput = 'input[name="nomor_anggota"], input[name*="nomor"], input[name*="anggota"]';
        this.namaAnggotaInput = 'input[name="nama"], input[name*="nama"]';
        this.emailAnggotaInput = 'input[name="email"], input[name*="email"]';
        this.noHpAnggotaInput = 'input[name="no_hp"], input[name*="hp"]';
        this.alamatAnggotaTextarea = 'textarea[name="alamat"], textarea[name*="alamat"]';
        this.tempatLahirInput = 'input[name="tempat_lahir"], input[name*="tempat"]';
        this.tanggalLahirInput = 'input[name="tanggal_lahir"], input[type="date"]';
        this.jenisKelaminSelect = 'select[name="jenis_kelamin"], select[name*="jenis"]';
        this.statusSelect = 'select[name="status"], select[name*="status"]';

        // Transaksi simpanan
        this.btnTransaksiSimpanan = 'button:has-text("Transaksi"), a:has-text("Transaksi"), .btn-transaksi';
        this.jenisSimpananSelect = 'select[name="jenis_simpanan"], select[name*="simpanan"]';
        this.jumlahTransaksiInput = 'input[name="jumlah"], input[name*="jumlah"]';
        this.tanggalTransaksiInput = 'input[name="tanggal"], input[type="date"]';
        this.keteranganTextarea = 'textarea[name="keterangan"], textarea[name*="keterangan"]';

        // Pengajuan management
        this.btnVerifikasi = 'button:has-text("Verifikasi"), a:has-text("Verifikasi"), .btn-verifikasi';
        this.btnSetujui = 'button:has-text("Setujui"), a:has-text("Setujui"), .btn-setujui';
        this.btnTolak = 'button:has-text("Tolak"), a:has-text("Tolak"), .btn-tolak';
        this.btnCairkan = 'button:has-text("Cairkan"), a:has-text("Cairkan"), .btn-cairkan';

        // Tables
        this.anggotaTable = 'table:has-text("Anggota"), .anggota-table';
        this.simpananTable = 'table:has-text("Simpanan"), .simpanan-table';
        this.pengajuanTable = 'table:has-text("Pengajuan"), .pengajuan-table';
        this.angsuranTable = 'table:has-text("Angsuran"), .angsuran-table';

        // Search and filter
        this.searchInput = 'input[name="search"], input[placeholder*="cari"], .search-input';
        this.filterSelect = 'select[name="filter"], .filter-select';

        // Common buttons
        this.btnEdit = 'button:has-text("Edit"), a:has-text("Edit"), .btn-edit';
        this.btnHapus = 'button:has-text("Hapus"), a:has-text("Hapus"), .btn-hapus';
        this.btnDetail = 'button:has-text("Detail"), a:has-text("Detail"), .btn-detail';
        this.btnSimpan = 'button:has-text("Simpan"), button[type="submit"], input[type="submit"]';

        // Laporan
        this.btnGenerateLaporan = 'button:has-text("Generate"), a:has-text("Export"), .btn-export';
        this.dateRangeFrom = 'input[name="tanggal_from"], input[placeholder*="dari"]';
        this.dateRangeTo = 'input[name="tanggal_to"], input[placeholder*="sampai"]';
    }

    /**
     * Verify user is on pengurus dashboard
     */
    async verifyOnPengurusDashboard() {
        await this.page.waitForURL(/pengurus.*dashboard/, { timeout: 10000 });
        await this.waitForPageLoad();

        const titleText = await this.getText(this.dashboardTitle);
        return titleText.toLowerCase().includes('dashboard') ||
               await this.isVisible(this.infoCards);
    }

    /**
     * Navigate to anggota management page
     */
    async navigateToAnggota() {
        await this.clickElement(this.menuAnggota);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.anggotaTable, { timeout: 10000 });
    }

    /**
     * Add new anggota
     */
    async addAnggota(anggotaData) {
        await this.clickElement(this.btnTambahAnggota);
        await this.waitForPageLoad();

        if (anggotaData.nomorAnggota) {
            await this.fillInput(this.nomorAnggotaInput, anggotaData.nomorAnggota);
        }
        if (anggotaData.nama) {
            await this.fillInput(this.namaAnggotaInput, anggotaData.nama);
        }
        if (anggotaData.email) {
            await this.fillInput(this.emailAnggotaInput, anggotaData.email);
        }
        if (anggotaData.noHp) {
            await this.fillInput(this.noHpAnggotaInput, anggotaData.noHp);
        }
        if (anggotaData.alamat) {
            await this.fillInput(this.alamatAnggotaTextarea, anggotaData.alamat);
        }
        if (anggotaData.tempatLahir) {
            await this.fillInput(this.tempatLahirInput, anggotaData.tempatLahir);
        }
        if (anggotaData.tanggalLahir) {
            await this.fillInput(this.tanggalLahirInput, anggotaData.tanggalLahir);
        }
        if (anggotaData.jenisKelamin) {
            await this.selectDropdown(this.jenisKelaminSelect, anggotaData.jenisKelamin);
        }
        if (anggotaData.status) {
            await this.selectDropdown(this.statusSelect, anggotaData.status);
        }

        await this.clickElement(this.btnSimpan);
        await this.waitForPageLoad();
    }

    /**
     * Search anggota by name or nomor anggota
     */
    async searchAnggota(searchTerm) {
        if (await this.isVisible(this.searchInput)) {
            await this.fillInput(this.searchInput, searchTerm);
            await this.page.keyboard.press('Enter');
            await this.waitForPageLoad();
        }
    }

    /**
     * Get anggota data from table
     */
    async getAnggotaData() {
        const rows = await this.getTableData(this.anggotaTable);
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
     * Navigate to simpanan page
     */
    async navigateToSimpanan() {
        await this.clickElement(this.menuSimpanan);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.simpananTable, { timeout: 10000 });
    }

    /**
     * Add transaksi simpanan
     */
    async addTransaksiSimpanan(transaksiData) {
        if (await this.isVisible(this.btnTransaksiSimpanan)) {
            await this.clickElement(this.btnTransaksiSimpanan);
            await this.waitForPageLoad();
        }

        if (transaksiData.jenisSimpanan) {
            await this.selectDropdown(this.jenisSimpananSelect, transaksiData.jenisSimpanan);
        }
        if (transaksiData.jumlah) {
            await this.fillInput(this.jumlahTransaksiInput, transaksiData.jumlah);
        }
        if (transaksiData.tanggal) {
            await this.fillInput(this.tanggalTransaksiInput, transaksiData.tanggal);
        }
        if (transaksiData.keterangan) {
            await this.fillInput(this.keteranganTextarea, transaksiData.keterangan);
        }

        await this.clickElement(this.btnSimpan);
        await this.waitForPageLoad();
    }

    /**
     * Navigate to pembiayaan page
     */
    async navigateToPembiayaan() {
        await this.clickElement(this.menuPembiayaan);
        await this.waitForPageLoad();
        await this.page.waitForSelector(this.pengajuanTable, { timeout: 10000 });
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
     * Verifikasi pengajuan
     */
    async verifikasiPengajuan(nomorAnggota) {
        const row = await this.findTableRowByColumnText(0, nomorAnggota);
        if (row) {
            const verifikasiBtn = row.locator(this.btnVerifikasi);
            await verifikasiBtn.click();
            await this.waitForPageLoad();
        }
    }

    /**
     * Setujui pengajuan
     */
    async setujuiPengajuan(nomorAnggota) {
        const row = await this.findTableRowByColumnText(0, nomorAnggota);
        if (row) {
            const setujuiBtn = row.locator(this.btnSetujui);
            await setujuiBtn.click();
            await this.waitForPageLoad();
        }
    }

    /**
     * Tolak pengajuan
     */
    async tolakPengajuan(nomorAnggota) {
        const row = await this.findTableRowByColumnText(0, nomorAnggota);
        if (row) {
            const tolakBtn = row.locator(this.btnTolak);
            await tolakBtn.click();
            await this.waitForPageLoad();
        }
    }

    /**
     * Cairkan pembiayaan
     */
    async cairkanPembiayaan(nomorAnggota) {
        const row = await this.findTableRowByColumnText(0, nomorAnggota);
        if (row) {
            const cairkanBtn = row.locator(this.btnCairkan);
            await cairkanBtn.click();
            await this.waitForPageLoad();
        }
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
     * Verify all menu items are present
     */
    async verifyMenuItems() {
        const menuItems = [
            this.menuDashboard,
            this.menuAnggota,
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

    /**
     * Check if anggota exists in table
     */
    async verifyAnggotaExists(nomorAnggota) {
        const anggotaData = await this.getAnggotaData();
        return anggotaData.some(row =>
            row.some(cell => cell && cell.includes(nomorAnggota))
        );
    }
}

module.exports = { PengurusPage };