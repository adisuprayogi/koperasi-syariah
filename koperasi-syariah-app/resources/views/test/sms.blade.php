@extends('layouts.app')

@section('title', 'SMS Test')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header">
                    <h3 class="card-title">SMS Gateway Testing</h3>
                    <div class="card-tools">
                        <a href="{{ route('sms.config.check') }}" class="btn btn-tool btn-sm">
                            <i class="fas fa-cog"></i> Check Configuration
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if (session('success'))
                        <div class="alert alert-success">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if (session('error'))
                        <div class="alert alert-danger">
                            {{ session('error') }}
                        </div>
                    @endif

                    <!-- Custom SMS Test -->
                    <div class="row">
                        <div class="col-md-6">
                            <div class="card card-primary">
                                <div class="card-header">
                                    <h4 class="card-title">Custom SMS Test</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('sms.test.send') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="phone">Phone Number</label>
                                            <input type="text" class="form-control" id="phone" name="phone"
                                                   placeholder="08123456789" value="{{ old('phone') }}" required>
                                            <small class="form-text text-muted">Format: 08123456789 atau 628123456789</small>
                                        </div>
                                        <div class="form-group">
                                            <label for="message">Message</label>
                                            <textarea class="form-control" id="message" name="message" rows="3" required>{{ old('message') }}</textarea>
                                            <small class="form-text text-muted">Max 160 characters per SMS</small>
                                        </div>
                                        <button type="submit" class="btn btn-primary">
                                            <i class="fas fa-paper-plane"></i> Send SMS
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>

                        <!-- Welcome SMS Test -->
                        <div class="col-md-6">
                            <div class="card card-success">
                                <div class="card-header">
                                    <h4 class="card-title">Welcome SMS Test</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('sms.test.welcome') }}" method="POST">
                                        @csrf
                                        <div class="form-group">
                                            <label for="welcome_phone">Phone Number</label>
                                            <input type="text" class="form-control" id="welcome_phone" name="phone"
                                                   placeholder="08123456789" value="{{ old('phone') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_name">Member Name</label>
                                            <input type="text" class="form-control" id="member_name" name="member_name"
                                                   placeholder="Ahmad Rizki" value="{{ old('member_name') }}" required>
                                        </div>
                                        <div class="form-group">
                                            <label for="member_number">Member Number</label>
                                            <input type="text" class="form-control" id="member_number" name="member_number"
                                                   placeholder="KOP001" value="{{ old('member_number') }}" required>
                                        </div>
                                        <button type="submit" class="btn btn-success">
                                            <i class="fas fa-user-plus"></i> Send Welcome SMS
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Financing Status SMS Test -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-warning">
                                <div class="card-header">
                                    <h4 class="card-title">Financing Status SMS Test</h4>
                                </div>
                                <div class="card-body">
                                    <form action="{{ route('sms.test.financing') }}" method="POST">
                                        @csrf
                                        <div class="row">
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="financing_phone">Phone Number</label>
                                                    <input type="text" class="form-control" id="financing_phone" name="phone"
                                                           placeholder="08123456789" value="{{ old('phone') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="financing_member_name">Member Name</label>
                                                    <input type="text" class="form-control" id="financing_member_name" name="member_name"
                                                           placeholder="Ahmad Rizki" value="{{ old('member_name') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="application_code">Application Code</label>
                                                    <input type="text" class="form-control" id="application_code" name="application_code"
                                                           placeholder="2512MJ0001" value="{{ old('application_code') }}" required>
                                                </div>
                                            </div>
                                            <div class="col-md-3">
                                                <div class="form-group">
                                                    <label for="status">Status</label>
                                                    <select class="form-control" id="status" name="status" required>
                                                        <option value="pending">Pending</option>
                                                        <option value="approved">Approved</option>
                                                        <option value="rejected">Rejected</option>
                                                        <option value="cair">Cair (Disbursed)</option>
                                                    </select>
                                                </div>
                                            </div>
                                        </div>
                                        <button type="submit" class="btn btn-warning">
                                            <i class="fas fa-money-bill-wave"></i> Send Status SMS
                                        </button>
                                    </form>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- SMS Templates Preview -->
                    <div class="row mt-3">
                        <div class="col-md-12">
                            <div class="card card-info">
                                <div class="card-header">
                                    <h4 class="card-title">SMS Templates Preview</h4>
                                </div>
                                <div class="card-body">
                                    <div class="row">
                                        <div class="col-md-4">
                                            <h6>Welcome Message</h6>
                                            <div class="alert alert-light">
                                                <small>Selamat datang di Koperasi Syariah! Yth. {nama} (No. {nomor}). Terima kasih telah bergabung. Silakan lengkapi dokumen dan mulai menabung untuk kemakmuran bersama.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Financing Status Update</h6>
                                            <div class="alert alert-light">
                                                <small>Koperasi Syariah - Yth. {nama}, pengajuan pembiayaan {kode} status: {status}. Hubungi pengurus untuk info lebih lanjut.</small>
                                            </div>
                                        </div>
                                        <div class="col-md-4">
                                            <h6>Installment Reminder</h6>
                                            <div class="alert alert-light">
                                                <small>Koperasi Syariah - Pengingat Angsuran. Yth. {nama}, angsuran {kode} jatuh tempo tgl {tanggal} sebesar Rp {jumlah}. Bayar sebelum jatuh tempo.</small>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection