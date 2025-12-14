# Functional Test Plan - Koperasi Syariah E2E Testing

## Overview
Functional testing focuses on testing the core business functionality of the Koperasi Syariah application beyond authentication. These tests verify that all business processes work correctly from end to end.

## Testing Scope

### 1. Manajemen Anggota Module
**User Role:** Admin, Pengurus (Ketua, Bendahara)

#### Test Scenarios:
1. **Create New Anggota**
   - Navigate to Anggota management
   - Click "Tambah Anggota" button
   - Fill form with valid data
   - Submit and verify success message
   - Verify anggota appears in list

2. **Edit Existing Anggota**
   - Search for specific anggota
   - Click edit button
   - Modify data
   - Save changes
   - Verify updated information

3. **Delete Anggota**
   - Select anggota to delete
   - Confirm deletion
   - Verify anggota removed from list
   - Verify deletion doesn't break related data

4. **Search and Filter Anggota**
   - Search by name
   - Filter by status
   - Verify pagination works

### 2. Simpanan Module
**User Role:** All roles (different access levels)

#### Test Scenarios:
1. **Tambah Simpanan**
   - Navigate to Simpanan section
   - Click "Tambah Simpanan"
   - Select anggota
   - Input amount and jenis simpanan
   - Submit and verify record created

2. **Tarik Simpanan**
   - Select existing simpanan
   - Click "Tarik"
   - Input withdrawal amount
   - Verify saldo updated correctly

3. **View Saldo Simpanan**
   - Check dashboard for total saldo
   - View detailed simpanan history
   - Verify calculations are correct

4. **Filter Simpanan Reports**
   - Filter by date range
   - Filter by jenis simpanan
   - Export to Excel

### 3. Pinjaman Module
**User Role:** Anggota (apply), Pengurus (approve)

#### Test Scenarios:
1. **Ajukan Pinjaman (Anggota)**
   - Navigate to Pinjaman section
   - Click "Ajukan Pinjaman"
   - Fill loan application form
   - Submit application
   - Verify status "Menunggu Persetujuan"

2. **Proses Persetujuan (Pengurus)**
   - Navigate to persetujuan pinjaman
   - View pending applications
   - Review application details
   - Approve/Reject application
   - Add notes if needed

3. **View Pinjaman Status**
   - Check application status
   - View approved loans
   - Verify repayment schedule

4. **Edit Pinjaman Details**
   - Modify approved loan amount
   - Adjust interest rate
   - Update repayment terms

### 4. Angsuran Module
**User Role:** Pengurus, Anggota (view)

#### Test Scenarios:
1. **Bayar Angsuran**
   - Select existing pinjaman
   - Click "Bayar Angsuran"
   - Input payment amount
   - Select payment method
   - Process payment
   - Verify updated saldo

2. **View Angsuran Schedule**
   - Check repayment schedule
   - Verify due dates
   - View payment history

3. **Calculate Denda**
   - Process late payment
   - Verify denda calculation
   - Add denda to outstanding amount

4. **Angsuran Reports**
   - Generate angsuran reports
   - Filter by date range
   - Export to Excel

### 5. Laporan Module
**User Role:** Admin, Pengurus

#### Test Scenarios:
1. **Generate Laporan Simpanan**
   - Select date range
   - Choose report type
   - Generate report
   - Verify data accuracy
   - Export to Excel/PDF

2. **Generate Laporan Pinjaman**
   - Filter by status
   - Include payment details
   - Verify calculations
   - Export functionality

3. **Laporan Laba Rugi**
   - Generate monthly report
   - Verify income calculations
   - Verify expense tracking
   - Export to Excel

4. **Laporan Neraca**
   - Generate balance sheet
   - Verify asset calculations
   - Verify liability tracking
   - Export functionality

5. **Laporan Tunggakan**
   - View overdue payments
   - Filter by severity
   - Generate collection report
   - Export data

## Test Data Requirements

### Pre-condition Data:
- Existing anggota with various statuses
- Multiple simpanan records
- Active pinjaman applications
- Payment history data
- Different jenis simpanan (Pokok, Wajib, Sukarela)

### Test Data Fixtures:
```javascript
// Additional test data for functional tests
TEST_ANGGOTA = {
  new: {
    nama: "Test Anggota Baru",
    nomor_anggota: "2512.00099",
    email: "test.baru@example.com",
    telepon: "08123456789",
    alamat: "Alamat Test Baru"
  }
}

TEST_SIMPANAN = {
  pokok: { amount: 500000, jenis: "Simpanan Pokok" },
  wajib: { amount: 100000, jenis: "Simpanan Wajib" },
  sukarela: { amount: 250000, jenis: "Simpanan Sukarela" }
}

TEST_PINJAMAN = {
  kecil: { amount: 5000000, tenor: 6, purpose: "Modal Usaha Kecil" },
  menengah: { amount: 15000000, tenor: 12, purpose: "Modal Usaha Menengah" },
  besar: { amount: 50000000, tenor: 24, purpose: "Modal Usaha Besar" }
}
```

## Success Criteria

### Manajemen Anggota:
- ✅ CRUD operations work correctly
- ✅ Search and filter functions properly
- ✅ Data validation works
- ✅ No broken relationships when deleting

### Simpanan:
- ✅ Saldo calculations are accurate
- ✅ Transactions update correctly
- ✅ Reports generate correct data
- ✅ Excel export includes all required fields

### Pinjaman:
- ✅ Application workflow complete
- ✅ Approval/rejection process works
- ✅ Status updates correctly
- ✅ Notifications sent appropriately

### Angsuran:
- ✅ Payment processing accurate
- ✅ Schedules calculated correctly
- ✅ Denda applied properly
- ✅ History tracking complete

### Laporan:
- ✅ All report types generate
- ✅ Data accuracy verified
- ✅ Export functionality works
- ✅ Date filters work correctly

## Technical Considerations

### Test Execution Order:
1. Setup test data
2. Manajemen Anggota (creates anggota)
3. Simpanan (requires anggota)
4. Pinjaman (requires anggota)
5. Angsuran (requires approved pinjaman)
6. Laporan (uses all previous data)

### Data Cleanup:
- Use separate test database
- Clean up created data after each test
- Reset database state between test runs

### Performance:
- Tests should complete within reasonable time
- Use data factories for bulk data creation
- Optimize wait times for page loads

### Error Handling:
- Test validation messages
- Test edge cases (empty data, invalid input)
- Test error recovery scenarios

## Implementation Priority

### Phase 1 - Critical (High Priority):
1. Manajemen Anggota CRUD
2. Simpanan Tambah/Tarik
3. Pinjaman Application & Approval
4. Basic Reports generation

### Phase 2 - Important (Medium Priority):
1. Angsuran processing
2. Advanced reports
3. Data validation tests
4. Search and filter tests

### Phase 3 - Nice to Have (Low Priority):
1. Performance testing
2. Load testing for reports
3. Edge case scenarios
4. Accessibility testing

## Test Environment Setup

### Database:
- Use dedicated testing database
- Seed with initial data
- Run migrations fresh for each test

### Browser Configuration:
- Use consistent viewport size
- Test on Chromium (primary)
- Consider Firefox for compatibility

### Test Data Management:
- Use fixtures for consistent test data
- Create data factories for dynamic test data
- Ensure test isolation between tests

## Reporting and Metrics

### Test Coverage:
- Track which features are tested
- Measure code coverage if possible
- Identify untested critical paths

### Test Results:
- Pass/fail status for each scenario
- Execution time tracking
- Error logs and screenshots for failures

### Continuous Improvement:
- Review test failures regularly
- Update tests as features change
- Add new test cases for new features