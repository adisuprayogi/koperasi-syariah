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
            background: linear-gradient(135deg, #dc2626, #ef4444);
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
            border-top: 2px solid #dc2626;
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
            color: #dc2626;
            font-size: 18px;
            font-weight: bold;
            margin-bottom: 15px;
            border-bottom: 2px solid #dc2626;
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
            color: #dc2626;
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
            border-left: 4px solid #dc2626;
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

        .danger-box {
            background: #ffebee;
            border: 1px solid #dc2626;
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
            border: 2px solid #dc2626;
            border-radius: 10px;
            padding: 20px;
            margin: 20px 0;
        }

        .toc h3 {
            color: #dc2626;
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
            color: #dc2626;
            margin-right: 10px;
        }

        .admin-highlight {
            background: #fce4ec;
            border-left: 4px solid #dc2626;
            padding: 15px;
            margin: 15px 0;
            border-radius: 0 8px 8px 0;
        }

        .security-alert {
            background: #ffebee;
            border: 2px solid #dc2626;
            border-radius: 8px;
            padding: 15px;
            margin: 15px 0;
        }

        .admin-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 15px;
            margin: 15px 0;
        }

        .admin-grid-item {
            background: #f8f9fa;
            border: 1px solid #dee2e6;
            border-radius: 8px;
            padding: 12px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <div class="subtitle">Versi {{ $version }} - {{ $date }}</div>
        <div style="margin-top: 10px; font-size: 12px;">⚠️ DOKUMEN RAHASIA - HANYA UNTUK ADMINISTRATOR SISTEM</div>
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

        <!-- Admin-Specific Sections -->
        @foreach($sections as $index => $section)
        <div class="section">
            <div class="section-title">{{ $index + 1 }}. {{ $section['title'] }}</div>
            <div class="section-content">
                {{ $section['content'] }}
            </div>

            <!-- Add admin-specific content based on section ID -->
            @if($section['id'] === 'dashboard-admin')
            <div class="admin-grid">
                <div class="admin-grid-item">
                    <strong>📊 System Metrics</strong><br>
                    • Active users online<br>
                    • Server performance<br>
                    • Database status
                </div>
                <div class="admin-grid-item">
                    <strong>🔔 Security Alerts</strong><br>
                    • Failed login attempts<br>
                    • Suspicious activities<br>
                    • System vulnerabilities
                </div>
            </div>
            @endif

            @if($section['id'] === 'user-management')
            <div class="steps">
                <strong>User Management Workflow:</strong>
                <ol>
                    <li>Access User Management menu</li>
                    <li>Use advanced filtering to find specific users</li>
                    <li>Review user permissions and roles</li>
                    <li>Perform bulk operations for efficiency</li>
                    <li>Audit user activities regularly</li>
                </ol>
            </div>
            <div class="security-alert">
                <strong>⚠️ Security Reminder:</strong><br>
                Always verify user identity before making sensitive changes. Log all admin actions for audit compliance.
            </div>
            @endif

            @if($section['id'] === 'system-config')
            <div class="admin-highlight">
                <strong>🔧 Critical System Settings:</strong><br>
                • Payment gateway configurations<br>
                • Email server settings<br>
                • Backup schedules<br>
                • Security parameters
            </div>
            <div class="danger-box">
                <strong>⚠️ WARNING:</strong> Incorrect system configuration may cause service disruption. Always test changes in staging environment first.
            </div>
            @endif

            @if($section['id'] === 'security')
            <div class="security-alert">
                <strong>🛡️ Security Best Practices:</strong><br>
                • Enable two-factor authentication (2FA)<br>
                • Regular security audits<br>
                • IP whitelisting for admin access<br>
                • Daily backup verification<br>
                • Security patch management
            </div>
            @endif

            @if($section['id'] === 'data-management')
            <div class="steps">
                <strong>Database Management Procedures:</strong>
                <ol>
                    <li>Schedule regular automated backups</li>
                    <li>Verify backup integrity weekly</li>
                    <li>Test restoration procedures monthly</li>
                    <li>Monitor storage capacity</li>
                    <li>Implement data retention policies</li>
                </ol>
            </div>
            @endif

            @if($section['id'] === 'integration')
            <div class="admin-highlight">
                <strong>🔌 Integration Management:</strong><br>
                • API key rotation schedule<br>
                • Third-party service monitoring<br>
                • Rate limiting configuration<br>
                • Webhook endpoint verification
            </div>
            @endif

            @if($section['id'] === 'maintenance')
            <div class="warning-box">
                <strong>📅 Maintenance Schedule:</strong><br>
                • Weekly system health checks<br>
                • Monthly security updates<br>
                • Quarterly performance reviews<br>
                • Annual disaster recovery testing
            </div>
            @endif
        </div>

        @if($loop->iteration % 3 == 0)
        <div class="page-break"></div>
        @endif
        @endforeach

        <!-- Emergency Contacts Section -->
        <div class="section">
            <div class="section-title">Emergency Contacts & Procedures</div>
            <div class="danger-box">
                <strong>🚨 EMERGENCY PROCEDURES:</strong><br><br>
                <strong>System Down:</strong><br>
                1. Check server status immediately<br>
                2. Notify technical team<br>
                3. Activate backup systems<br>
                4. Communicate with stakeholders<br><br>

                <strong>Security Breach:</strong><br>
                1. Isolate affected systems<br>
                2. Preserve evidence<br>
                3. Notify security team<br>
                4. Document timeline<br>
            </div>

            <div class="contact-info">
                <strong>📞 24/7 Emergency Contacts:</strong><br><br>
                <strong>System Administrator:</strong> 0811-2222-3333<br>
                <strong>Security Team:</strong> 0811-2222-4444<br>
                <strong>Data Center:</strong> 0811-2222-5555<br>
                <strong>Legal/Compliance:</strong> 0811-2222-6666
            </div>
        </div>
    </div>

    <div class="footer">
        <p><strong>© {{ date('Y') }} Koperasi Syariah - ADMINISTRATOR ACCESS ONLY</strong></p>
        <p>This document contains confidential system information</p>
        <p>Generated on: {{ \Carbon\Carbon::now()->format('d M Y H:i:s') }}</p>
    </div>
</body>
</html>