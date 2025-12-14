<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manual Penggunaan Koperasi Syariah</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
    <style>
        .hero-gradient {
            background: linear-gradient(135deg, #059669 0%, #10b981 50%, #34d399 100%);
        }
        .role-card {
            transition: all 0.3s ease;
            border: 2px solid transparent;
        }
        .role-card:hover {
            transform: translateY(-5px);
            box-shadow: 0 20px 25px -5px rgba(0, 0, 0, 0.1), 0 10px 10px -5px rgba(0, 0, 0, 0.04);
        }
        .role-card.active {
            border-color: currentColor;
            transform: scale(1.02);
        }
        .screenshot-img {
            border-radius: 8px;
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
            transition: transform 0.2s ease;
        }
        .screenshot-img:hover {
            transform: scale(1.05);
        }
        .step-number {
            background: linear-gradient(135deg, #f3f4f6, #e5e7eb);
            min-width: 32px;
            height: 32px;
            border-radius: 50%;
            display: flex;
            align-items: center;
            justify-content: center;
            font-weight: bold;
            color: #374151;
        }
        .tip-box {
            background: linear-gradient(135deg, #f0f9ff, #e0f2fe);
            border-left: 4px solid #0ea5e9;
        }
        .section-content {
            animation: fadeInUp 0.5s ease;
        }
        @keyframes fadeInUp {
            from {
                opacity: 0;
                transform: translateY(20px);
            }
            to {
                opacity: 1;
                transform: translateY(0);
            }
        }
        .screenshot-modal {
            display: none;
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 100%;
            background: rgba(0, 0, 0, 0.8);
            z-index: 9999;
            cursor: pointer;
        }
        .modal-content {
            background: white;
            margin: 5% auto;
            padding: 0;
            border-radius: 12px;
            width: 90%;
            max-width: 800px;
            max-height: 90vh;
            overflow: hidden;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.3);
            cursor: default;
        }
        .modal-header {
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 1.5rem 2rem;
            background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
            color: white;
        }
        .modal-header h3 {
            margin: 0;
        }
        .close-button {
            background: none;
            border: none;
            color: white;
            font-size: 1.5rem;
            cursor: pointer;
            padding: 0;
            width: 30px;
            height: 30px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 50%;
            transition: background-color 0.2s;
        }
        .close-button:hover {
            background-color: rgba(255, 255, 255, 0.2);
        }
        .modal-body {
            padding: 2rem;
            background: #f9fafb;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Screenshot Modal -->
    <div id="screenshotModal" class="screenshot-modal" onclick="closeModal()">
        <div class="modal-content" onclick="event.stopPropagation()">
            <div class="modal-header">
                <h3 class="text-lg font-semibold">Screenshot Preview</h3>
                <button onclick="closeModal()" class="close-button">&times;</button>
            </div>
            <div id="modalImageContainer" class="modal-body">
                <div class="flex items-center justify-center h-96 bg-gray-100">
                    <i class="fas fa-image text-6xl text-gray-400"></i>
                </div>
            </div>
        </div>
    </div>

    <!-- Hero Section -->
    <div class="hero-gradient text-white py-16">
        <div class="container mx-auto px-4">
            <div class="text-center">
                <h1 class="text-5xl font-bold mb-4">
                    <i class="fas fa-book-open mr-3"></i>
                    Manual Penggunaan Aplikasi
                </h1>
                <p class="text-xl mb-8 opacity-90">Koperasi Syariah - Panduan Lengkap untuk Semua Role</p>
                <div class="flex justify-center space-x-4 text-sm">
                    <span class="bg-white/20 px-4 py-2 rounded-full">
                        <i class="fas fa-users mr-2"></i>Anggota
                    </span>
                    <span class="bg-white/20 px-4 py-2 rounded-full">
                        <i class="fas fa-user-tie mr-2"></i>Pengurus
                    </span>
                    <span class="bg-white/20 px-4 py-2 rounded-full">
                        <i class="fas fa-shield-alt mr-2"></i>Admin
                    </span>
                </div>
            </div>
        </div>
    </div>

    <!-- Main Content -->
    <div class="container mx-auto px-4 py-8">
        <!-- Role Selection -->
        <div class="grid md:grid-cols-3 gap-6 mb-12">
            <!-- Anggota Card -->
            <div class="role-card bg-white rounded-xl p-6 cursor-pointer"
                 onclick="loadManual('anggota')"
                 style="color: #059669;">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                         style="background: rgba(5, 150, 105, 0.1);">
                        <i class="fas fa-users text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Panduan Anggota</h3>
                    <p class="text-gray-600">Untuk anggota koperasi yang ingin mengelola simpanan, pembiayaan, dan profil</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        6 Menu Utama
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Step-by-Step
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Screenshot Lengkap
                    </div>
                </div>
            </div>

            <!-- Pengurus Card -->
            <div class="role-card bg-white rounded-xl p-6 cursor-pointer"
                 onclick="loadManual('pengurus')"
                 style="color: #7c3aed;">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                         style="background: rgba(124, 58, 237, 0.1);">
                        <i class="fas fa-user-tie text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Panduan Pengurus</h3>
                    <p class="text-gray-600">Untuk pengurus yang mengelola operasional harian dan anggota</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        5 Menu Operasional
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Workflow Approval
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Laporan Keuangan
                    </div>
                </div>
            </div>

            <!-- Admin Card -->
            <div class="role-card bg-white rounded-xl p-6 cursor-pointer"
                 onclick="loadManual('admin')"
                 style="color: #dc2626;">
                <div class="text-center mb-4">
                    <div class="w-16 h-16 mx-auto mb-3 rounded-full flex items-center justify-center"
                         style="background: rgba(220, 38, 38, 0.1);">
                        <i class="fas fa-shield-alt text-3xl"></i>
                    </div>
                    <h3 class="text-xl font-bold mb-2">Panduan Administrator</h3>
                    <p class="text-gray-600">Untuk admin sistem yang mengelola konfigurasi dan keamanan</p>
                </div>
                <div class="space-y-2">
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        4 Menu System
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        Security Management
                    </div>
                    <div class="flex items-center text-sm">
                        <i class="fas fa-check-circle mr-2"></i>
                        System Configuration
                    </div>
                </div>
            </div>
        </div>

        <!-- Manual Content -->
        <div id="manualContent" class="section-content">
            <!-- Content will be loaded here dynamically -->
            <div class="text-center py-12">
                <i class="fas fa-hand-point-up text-6xl text-gray-300 mb-4"></i>
                <p class="text-xl text-gray-500">Pilih role di atas untuk melihat panduan penggunaan</p>
                <p class="text-gray-400 mt-2">Klik salah satu kartu untuk memulai</p>
            </div>
        </div>
    </div>

    <!-- Footer -->
    <footer class="bg-gray-800 text-white py-8 mt-16">
        <div class="container mx-auto px-4 text-center">
            <p class="mb-2">© 2024 Koperasi Syariah - All Rights Reserved</p>
            <p class="text-gray-400">Manual Penggunaan Aplikasi Versi 1.0.0</p>
        </div>
    </footer>

    <script>
        let currentManual = null;

        function loadManual(role) {
            // Update active card
            document.querySelectorAll('.role-card').forEach(card => {
                card.classList.remove('active');
            });
            event.currentTarget.classList.add('active');

            // Show loading state
            const contentDiv = document.getElementById('manualContent');
            contentDiv.innerHTML = `
                <div class="text-center py-12">
                    <div class="inline-flex items-center">
                        <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-green-500 mr-3"></div>
                        <span class="text-xl text-gray-600">Loading manual...</span>
                    </div>
                </div>
            `;

            // Fetch manual data
            fetch(`/manual-preview/api/manual/${role}`)
                .then(response => response.json())
                .then(data => {
                    currentManual = data;
                    renderManual(data);
                })
                .catch(error => {
                    console.error('Error loading manual:', error);
                    contentDiv.innerHTML = `
                        <div class="text-center py-12">
                            <i class="fas fa-exclamation-triangle text-6xl text-red-300 mb-4"></i>
                            <p class="text-xl text-red-600">Error loading manual</p>
                        </div>
                    `;
                });
        }

        function renderManual(manual) {
            const contentDiv = document.getElementById('manualContent');

            // Generate app info HTML if available
            let appInfoHtml = '';
            if (manual.appInfo) {
                appInfoHtml = `
                    <div class="bg-blue-50 border-l-4 border-blue-400 p-4 mb-6 rounded">
                        <div class="flex items-center mb-2">
                            <img src="${manual.appInfo.logo}" alt="${manual.appInfo.name}" class="h-12 w-auto mr-3 rounded">
                            <div>
                                <h4 class="font-bold text-lg">${manual.appInfo.name}</h4>
                                <p class="text-sm text-gray-600">Version ${manual.appInfo.version} | ${manual.appInfo.url}</p>
                            </div>
                        </div>
                    </div>
                `;
            }

            // Generate sections HTML
            let sectionsHtml = '';
            if (manual.sections) {
                sectionsHtml = manual.sections.map((section, index) => {
                    // Generate screenshots for this section
                    let screenshotsHtml = '';
                    if (section.screenshots && section.screenshots.length > 0) {
                        screenshotsHtml = `
                            <div class="mb-6">
                                <h5 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-camera mr-2" style="color: ${manual.color};"></i>
                                    Screenshots:
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    ${section.screenshots.map((screenshot, index) => {
                                        const screenshotUrl = screenshot.startsWith('http') ? screenshot : `/${screenshot}.png`;
                                        const screenshotName = screenshotUrl.split('/').pop().replace(/\.(png|jpg|jpeg|gif)$/i, '') || `Screenshot ${index + 1}`;
                                        return `
                                            <div class="border rounded-lg overflow-hidden cursor-pointer hover:shadow-lg transition"
                                                 onclick="openModal('${screenshotUrl}')">
                                                <div class="bg-gray-100 h-48 flex items-center justify-center">
                                                    <img src="${screenshotUrl}"
                                                         alt="${screenshotName}"
                                                         class="max-w-full max-h-full object-contain"
                                                         onerror="this.onerror=null; this.parentElement.innerHTML='<i class=\\'fas fa-image text-4xl text-gray-400\\'></i>'; this.style='height: 48px;';">
                                                </div>
                                                <div class="p-2 bg-gray-100">
                                                    <p class="text-sm text-center truncate">${screenshotName}</p>
                                                </div>
                                            </div>
                                        `;
                                    }).join('')}
                                </div>
                            </div>
                        `;
                    }

                    // Generate steps for this section
                    let stepsHtml = '';
                    if (section.steps && section.steps.length > 0) {
                        stepsHtml = `
                            <div class="mb-6">
                                <h5 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-list-ol mr-2" style="color: ${manual.color};"></i>
                                    Langkah-langkah:
                                </h5>
                                ${section.steps.map((step, stepIndex) => `
                                    <div class="flex items-start mb-3">
                                        <div class="step-number mr-3">${stepIndex + 1}</div>
                                        <p class="text-gray-700 flex-1">${step}</p>
                                    </div>
                                `).join('')}
                            </div>
                        `;
                    }

                    // Generate tips for this section
                    let tipsHtml = '';
                    if (section.tips && section.tips.length > 0) {
                        tipsHtml = `
                            <div class="mb-6">
                                <h5 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-lightbulb mr-2" style="color: ${manual.color};"></i>
                                    Tips Penting:
                                </h5>
                                <ul class="space-y-2 text-gray-700 ml-4">
                                    ${section.tips.map(tip => `<li class="flex items-start"><span class="mr-2">•</span><span>${tip}</span></li>`).join('')}
                                </ul>
                            </div>
                        `;
                    }

                    // Generate features for this section
                    let featuresHtml = '';
                    if (section.features && section.features.length > 0) {
                        featuresHtml = `
                            <div class="mb-6">
                                <h5 class="font-semibold mb-3 flex items-center">
                                    <i class="fas fa-star mr-2" style="color: ${manual.color};"></i>
                                    Fitur Unggulan:
                                </h5>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-2">
                                    ${section.features.map(feature => `
                                        <div class="flex items-center text-sm text-gray-600">
                                            <i class="fas fa-check-circle mr-2 text-green-500"></i>
                                            ${feature}
                                        </div>
                                    `).join('')}
                                </div>
                            </div>
                        `;
                    }

                    return `
                        <div class="section mb-8">
                            <div class="flex items-center mb-4">
                                <i class="${section.icon} text-2xl mr-3" style="color: ${manual.color};"></i>
                                <h3 class="text-xl font-bold" style="color: ${manual.color};">
                                    ${section.title}
                                </h3>
                            </div>
                            <p class="text-gray-700 mb-4">${section.description}</p>

                            ${screenshotsHtml}
                            ${stepsHtml}
                            ${tipsHtml}
                            ${featuresHtml}
                        </div>
                    `;
                }).join('');
            }

            contentDiv.innerHTML = `
                <div class="max-w-6xl mx-auto">
                    <!-- Header -->
                    <div class="bg-white rounded-xl p-8 mb-6 shadow-sm">
                        <div class="text-center">
                            <div class="w-20 h-20 mx-auto mb-4 rounded-full flex items-center justify-center"
                                 style="background: ${manual.color}20;">
                                <i class="${manual.icon} text-4xl" style="color: ${manual.color};"></i>
                            </div>
                            <h2 class="text-3xl font-bold mb-2" style="color: ${manual.color};">
                                ${manual.title}
                            </h2>
                            <p class="text-gray-600 text-lg">${manual.subtitle}</p>
                            <p class="text-gray-500 mt-2">${manual.description}</p>
                        </div>
                    </div>

                    <!-- App Info -->
                    ${appInfoHtml}

                    <!-- Sections Content -->
                    <div class="bg-white rounded-xl p-8 shadow-sm">
                        ${sectionsHtml}
                    </div>

                    <!-- Navigation -->
                    <div class="text-center mt-6">
                        <button onclick="window.print()"
                                class="bg-green-600 text-white px-6 py-3 rounded-lg hover:bg-green-700 transition mr-4">
                            <i class="fas fa-print mr-2"></i> Cetak Manual
                        </button>
                        <button onclick="downloadPDF('${manual.title.replace(/\\s+/g, '_').toLowerCase()}')"
                                class="bg-blue-600 text-white px-6 py-3 rounded-lg hover:bg-blue-700 transition">
                            <i class="fas fa-file-pdf mr-2"></i> Download PDF
                        </button>
                        <button onclick="window.location.href='/'"
                                class="bg-gray-600 text-white px-6 py-3 rounded-lg hover:bg-gray-700 transition">
                            <i class="fas fa-home mr-2"></i> Kembali ke Aplikasi
                        </button>
                    </div>
                </div>
            `;
        }

        function openModal(imageSrc) {
            const modal = document.getElementById('screenshotModal');
            const modalContainer = document.getElementById('modalImageContainer');

            // Reset modal content
            modalContainer.innerHTML = `
                <div class="flex items-center justify-center h-96 bg-gray-100">
                    <img src="${imageSrc}"
                         alt="Screenshot"
                         class="max-w-full max-h-full object-contain rounded-lg shadow-lg"
                         onerror="this.onerror=null; this.parentElement.innerHTML='<div class=\\'text-center p-8\\'><i class=\\'fas fa-image text-6xl text-gray-400 mb-4\\'></i><p class=\\'text-gray-600\\'>Screenshot tidak tersedia</p><p class=\\'text-sm text-gray-500 mt-2\\'>${imageSrc}</p></div>';">
                </div>
            `;

            modal.style.display = 'block';
        }

        function closeModal() {
            const modal = document.getElementById('screenshotModal');
            modal.style.display = 'none';
        }

        function downloadPDF(filename) {
            // This would normally download the PDF
            // For demo purposes, we'll show an alert
            alert('Fitur download PDF akan segera tersedia');
        }

        // Handle keyboard events
        document.addEventListener('keydown', (e) => {
            if (e.key === 'Escape') {
                closeModal();
            }
        });
    </script>
</body>
</html>