<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <title>{{ $title }}</title>
    <style>
        @page {
            margin: 20px 30px 20px 30px;
            size: A4;
            orientation: portrait;
        }

        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.6;
            color: #333;
            margin: 0;
            padding: 0;
        }

        .header {
            background: linear-gradient(135deg, #7c3aed, #8b5cf6);
            color: white;
            padding: 30px;
            text-align: center;
            border-radius: 10px 10px 0 0;
            margin-bottom: 20px;
        }

        .header h1 {
            margin: 0;
            font-size: 24px;
            font-weight: bold;
        }

        .header .subtitle {
            font-size: 14px;
            margin-top: 5px;
            opacity: 0.9;
        }

        .footer {
            background: #f8f9fa;
            padding: 15px 30px;
            text-align: center;
            border-top: 2px solid #7c3aed;
            margin-top: 30px;
            border-radius: 0 0 10px 10px;
        }

        .content {
            padding: 0 20px;
        }

        .section {
            margin-bottom: 30px;
            page-break-inside: avoid;
        }

        .section-title {
            color: #7c3aed;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #7c3aed;
            padding-bottom: 5px;
        }

        .section-content {
            margin-bottom: 15px;
            text-align: justify;
        }

        .screenshot-container {
            margin: 20px 0;
            text-align: center;
            page-break-inside: avoid;
        }

        .screenshot {
            max-width: 100%;
            height: auto;
            border: 2px solid #ddd;
            border-radius: 8px;
            box-shadow: 0 4px 8px rgba(0,0,0,0.1);
            margin: 10px 0;
        }

        .screenshot-title {
            font-weight: bold;
            color: #7c3aed;
            margin: 10px 0 5px 0;
            font-size: 14px;
        }

        .screenshot-description {
            font-style: italic;
            color: #666;
            margin-bottom: 15px;
            font-size: 11px;
        }

        .steps {
            background: #f8f9fa;
            border-left: 4px solid #7c3aed;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .steps ol {
            margin: 10px 0;
            padding-left: 20px;
        }

        .steps li {
            margin-bottom: 8px;
        }

        .info-box {
            background: #e3f2fd;
            border: 1px solid #2196f3;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .warning-box {
            background: #fff3e0;
            border: 1px solid #ff9800;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .success-box {
            background: #e8f5e8;
            border: 1px solid #4caf50;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .contact-info {
            background: #f5f5f5;
            padding: 15px;
            border-radius: 8px;
            margin: 20px 0;
        }

        .page-break {
            page-break-before: always;
        }

        .no-break {
            page-break-inside: avoid;
        }

        .toc {
            background: #f8f9fa;
            border: 2px solid #7c3aed;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .toc h3 {
            color: #7c3aed;
            margin-top: 0;
            text-align: center;
        }

        .toc ul {
            list-style-type: none;
            padding-left: 0;
        }

        .toc li {
            margin-bottom: 8px;
            padding-left: 20px;
        }

        .toc li:before {
            content: "▶";
            color: #7c3aed;
            margin-right: 10px;
        }

        .pengurus-highlight {
            background: #f3e8ff;
            border-left: 4px solid #7c3aed;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .workflow-box {
            background: #e0f2fe;
            border: 2px solid #7c3aed;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .pengurus-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .pengurus-grid-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
        }

        .approval-flow {
            background: #fef3c7;
            border: 2px solid #f59e0b;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Versi {{ $version }} - {{ $date }}</div>
        <div style="margin-top: 10px; font-size: 12px;">📋 DOKUMEN OPERASIONAL - UNTUK PENGGURUS KOPERASI</div>
    </div>

    <div class="content">
        <!-- Table of Contents -->
        <div class="section">
            <div class="section-title">Daftar Isi</div>
            <div class="toc">
                <ul>
                    @foreach($sections as $index => $section)
                        <li>{{ $index + 1 }}. {{ $section['title'] }}</li>
                    @endforeach
                </ul>
            </div>
        </div>

        <!-- Pengurus-Specific Sections -->
        @foreach($sections as $index => $section)
        <div class="section">
            <div class="section-title">{{ $index + 1 }}. {{ $section['title'] }}</div>
            <div class="section-content">
                {{ $section['content'] }}
            </div>

            <!-- Add pengurus-specific content based on section ID -->
            @if($section['id'] === 'dashboard-pengurus')
            <div class="pengurus-grid">
                <div class="pengurus-grid-item">
                    <strong>📊 Daily Overview</strong><br>
                    • Pending approvals<br>
                    • New member applications<br>
                    • Transaction summaries
                </div>
                <div class="pengurus-grid-item">
                    <strong>⏰ Quick Actions</strong><br>
                    • Process member requests<br>
                    • Review financing applications<br>
                    • Generate daily reports
                </div>
            </div>
            @endif

            @if($section['id'] === 'member-management')
            <div class="workflow-box">
                <strong>🔄 Member Registration Workflow:</strong>
                <ol>
                    <li>Receive member application</li>
                    <li>Verify required documents</li>
                    <li>Conduct background check</li>
                    <li>Approve/reject application</li>
                    <li>Generate member ID</li>
                    <li>Create member account</li>
                </ol>
            </div>
            <div class="pengurus-highlight">
                <strong>📋 Required Documents:</strong><br>
                • KTP/Identitas<br>
                • KK (Kartu Keluarga)<br>
                • Surat Keterangan Penghasilan<br>
                • Foto 3x4 (2 lembar)
            </div>
            @endif

            @if($section['id'] === 'transaction-approval')
            <div class="approval-flow">
                <strong>✅ Transaction Approval Matrix:</strong><br><br>
                <strong>Pembiayaan &lt; 10 Juta:</strong> Direct approval by Pengurus<br>
                <strong>Pembiayaan 10-50 Juta:</strong> Requires 2 Pengurus signatures<br>
                <strong>Pembiayaan &gt; 50 Juta:</strong> Requires Board approval<br>
                <strong>Penarikan Besar:</strong> Verify member balance & purpose
            </div>
            <div class="steps">
                <strong>Approval Process Steps:</strong>
                <ol>
                    <li>Review application completeness</li>
                    <li>Check member eligibility & history</li>
                    <li>Verify collateral (if required)</li>
                    <li>Assess repayment capacity</li>
                    <li>Make approval decision</li>
                    <li>Document approval process</li>
                </ol>
            </div>
            @endif

            @if($section['id'] === 'simpanan-operations')
            <div class="pengurus-highlight">
                <strong>💰 Daily Simpanan Operations:</strong><br>
                • Process member deposits<br>
                • Calculate daily profit sharing<br>
                • Update member balances<br>
                • Generate deposit receipts<br>
                • Reconcile bank transactions
            </div>
            @endif

            @if($section['id'] === 'pembiayaan-management')
            <div class="workflow-box">
                <strong>📋 Pembiayaan Assessment Criteria:</strong><br>
                • Member payment history<br>
                • Current debt-to-income ratio<br>
                • Collateral value (if applicable)<br>
                • Purpose verification<br>
                • Compliance with Sharia principles
            </div>
            @endif

            @if($section['id'] === 'financial-reports')
            <div class="pengurus-grid">
                <div class="pengurus-grid-item">
                    <strong>📈 Daily Reports</strong><br>
                    • Cash position<br>
                    • New memberships<br>
                    • Pembiayaan disbursements<br>
                    • Collections
                </div>
                <div class="pengurus-grid-item">
                    <strong>📊 Monthly Reports</strong><br>
                    • Trial balance<br>
                    • Income statement<br>
                    • Balance sheet<br>
                    • Member statistics
                </div>
            </div>
            <div class="warning-box">
                <strong>⚠️ Report Submission Schedule:</strong><br>
                Daily reports: Submit by 17:00<br>
                Monthly reports: Submit by 5th of following month<br>
                Annual reports: Submit by 31st January
            </div>
            @endif

            @if($section['id'] === 'compliance')
            <div class="pengurus-highlight">
                <strong>📜 Regulatory Compliance Checklist:</strong><br>
                • Koperasi Law compliance<br>
                • OJK reporting requirements<br>
                • Sharia compliance certification<br>
                • Tax compliance<br>
                • AML/CFT procedures
            </div>
            @endif

            @if($section['id'] === 'member-services')
            <div class="success-box">
                <strong>🌟 Service Excellence Standards:</strong><br>
                • Response time: &lt; 24 hours<br>
                • First-contact resolution: &gt; 80%<br>
                • Member satisfaction: &gt; 90%<br>
                • Complaint resolution: &lt; 3 days
            </div>
            @endif
        </div>

        @if($loop->iteration % 3 == 0)
        <div class="page-break"></div>
        @endif
        @endforeach

        <!-- Standard Operating Procedures -->
        <div class="section">
            <div class="section-title">Standard Operating Procedures (SOP)</div>

            <div class="workflow-box">
                <strong>🏦 Daily Opening Procedures:</strong>
                <ol>
                    <li>Check previous day's closing balances</li>
                    <li>Verify cash on hand</li>
                    <li>Review pending transactions</li>
                    <li>Check system notifications</li>
                    <li>Prepare for member services</li>
                </ol>
            </div>

            <div class="workflow-box">
                <strong>🏦 Daily Closing Procedures:</strong>
                <ol>
                    <li>Reconcile all transactions</li>
                    <li>Generate daily reports</li>
                    <li>Backup critical data</li>
                    <li>Secure cash and documents</li>
                    <li>Document any exceptions</li>
                </ol>
            </div>
        </div>

        <!-- Contact Information -->
        <div class="section">
            <div class="section-title">Hubungi Kami</div>
            <div class="contact-info">
                <strong>📞 Kontak Penting:</strong><br><br>
                <strong>Office:</strong> 021-1234-5678<br>
                <strong>WhatsApp:</strong> 0812-3456-7890<br>
                <strong>Email:</strong> pengurus@koperasi-syariah.com<br><br>

                <strong>⏰ Jam Operasional Kantor:</strong><br>
                Senin - Jumat: 08:00 - 17:00<br>
                Sabtu: 08:00 - 14:00<br>
                Minggu: Tutup<br><br>

                <strong>🏢 Alamat Kantor:</strong><br>
                Jl. Syariah No. 123, Jakarta Pusat<br>
                Telp. 021-1234-5678
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} Koperasi Syariah - DOKUMEN PENGGURUS</strong></p>
        <p>Dokumen ini untuk pengurus koperasi terdaftar</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>