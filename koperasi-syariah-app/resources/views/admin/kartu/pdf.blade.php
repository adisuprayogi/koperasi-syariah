<html>
<head>
    <meta charset="utf-8">
    <title>Kartu Anggota - {{ $anggota->nama_lengkap }}</title>
    <style>
        @page {
            size: A4;
            margin: 1.5cm;
        }

        body {
            font-family: Arial, sans-serif;
        }

        .card-container {
            width: 340px;
            height: 214px;
            margin: 0 auto 30px auto;
            position: relative;
            page-break-after: always;
        }

        .card {
            width: 100%;
            height: 100%;
            border-radius: 8px;
            box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            overflow: hidden;
            position: relative;
            border: 2px solid #333;
            padding: 0;
            margin: 0;
        }

        /* Remove page break after last card */
        .card-container:last-child {
            page-break-after: auto;
        }
    </style>
</head>
<body>
    <!-- Front Card -->
    <div class="card-container">
        <div class="card">
        <?php
        // Use exact same logic as preview-card-front.blade.php
        $bgStyle = '';
        $showBackgroundImage = false;
        $backgroundImagePath = '';

        if (!empty($settings->background_image_front)) {
            $showBackgroundImage = true;
            $backgroundImagePath = public_path('storage/' . $settings->background_image_front);
        } elseif ($settings->background_front) {
            switch($settings->background_front) {
                case 'gradient-blue':
                    $bgStyle = 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
                    break;
                case 'gradient-green':
                    $bgStyle = 'background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);';
                    break;
                case 'gradient-purple':
                    $bgStyle = 'background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);';
                    break;
                case 'solid-color':
                    $color = $settings->primary_color_front ?? '#1e40af';
                    $bgStyle = 'background-color: ' . $color . ';';
                    break;
            }
        } else {
            // Default fallback
            $color = $settings->primary_color_front ?? '#1e40af';
            $bgStyle = 'background-color: ' . $color . ';';
        }
        $fontColorFront = $settings->font_color_front ?? '#ffffff';
        ?>

        <div style="{{ $bgStyle }} position: relative; width: 100%; height: 100%; padding: 15px;">
            <?php if ($showBackgroundImage): ?>
                <!-- Background Image as base64 encoded data for DomPDF compatibility -->
                @php
                $imageData = base64_encode(file_get_contents($backgroundImagePath));
                $imageInfo = getimagesize($backgroundImagePath);
                $imageSrc = 'data:' . $imageInfo['mime'] . ';base64,' . $imageData;
                @endphp
                <img src="{{ $imageSrc }}" style="position: absolute; top: -15px; left: -15px; width: 370px; height: 244px; object-fit: fill; z-index: -1;" alt="">
            <?php endif; ?>
            <!-- Nomor Anggota (Top) - Exact same positioning as preview -->
            <div style="position: absolute; top: 90px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px; font-weight: bold;">
                No: {{ $anggota->no_anggota }}
            </div>

            <!-- Nama Anggota (Below Member Number) - Exact same positioning as preview -->
            <div style="position: absolute; top: 107px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px; font-weight: bold;">
                {{ strtoupper($anggota->nama_lengkap) }}
            </div>

            <!-- Tanggal Masuk (Bottom) - Exact same positioning as preview -->
            @if($anggota->tanggal_gabung)
            <div style="position: absolute; top: 125px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px;">
                Masuk: {{ \Carbon\Carbon::parse($anggota->tanggal_gabung)->format('d/m/Y') }}
            </div>
            @endif

            <!-- Simple Barcode (Bottom Left) - With White Background -->
            @php
            $nomorAnggota = $anggota->no_anggota;
            @endphp
            <!-- Professional Barcode using Picqer Library - Direct with white background -->
            @php
            $cleanNumber = preg_replace('/[^0-9]/', '', $nomorAnggota);

            // Generate real barcode using professional library
            use Picqer\Barcode\BarcodeGeneratorHTML;

            $generator = new BarcodeGeneratorHTML();

            // Generate Code 128 barcode with compact width
            $barWidth = 1.0; // Width factor for Code 128

            // Parameters: data, type, widthFactor, height, color, padding
            $barcodeHtml = $generator->getBarcode($cleanNumber, $generator::TYPE_CODE_128, $barWidth, 20, 'black', array(0, 0, 0, 0));
            @endphp

            <div style="position: absolute; bottom: 45px; left: 15px; background-color: white; padding: 5px; display: inline-block;">
                {!! $barcodeHtml !!}
            </div>
        </div>
          </div>
    </div>

    <!-- Back Card -->
    <div class="card-container">
        <div class="card">
        <?php
        // Use exact same logic as preview-card-back.blade.php
        $bgStyleBack = '';
        $showBackgroundImageBack = false;
        $backgroundImagePathBack = '';

        if (!empty($settings->background_image_back)) {
            $showBackgroundImageBack = true;
            $backgroundImagePathBack = public_path('storage/' . $settings->background_image_back);
        } elseif ($settings->background_back) {
            switch($settings->background_back) {
                case 'gradient-blue':
                    $bgStyleBack = 'background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);';
                    break;
                case 'gradient-green':
                    $bgStyleBack = 'background: linear-gradient(135deg, #059669 0%, #10b981 100%);';
                    break;
                case 'gradient-purple':
                    $bgStyleBack = 'background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);';
                    break;
                case 'solid-color':
                    $color = $settings->primary_color_back ?? '#1e40af';
                    $bgStyleBack = 'background-color: ' . $color . ';';
                    break;
            }
        } else {
            // Default fallback
            $color = $settings->primary_color_back ?? '#1e40af';
            $bgStyleBack = 'background-color: ' . $color . ';';
        }
        $fontColorBack = $settings->font_color_back ?? '#ffffff';
        ?>

        <div style="{{ $bgStyleBack }} position: relative; width: 100%; height: 100%; padding: 15px;">
            <?php if ($showBackgroundImageBack): ?>
                <!-- Background Image as base64 encoded data for DomPDF compatibility -->
                @php
                $imageDataBack = base64_encode(file_get_contents($backgroundImagePathBack));
                $imageInfoBack = getimagesize($backgroundImagePathBack);
                $imageSrcBack = 'data:' . $imageInfoBack['mime'] . ';base64,' . $imageDataBack;
                @endphp
                <img src="{{ $imageSrcBack }}" style="position: absolute; top: -15px; left: -15px; width: 370px; height: 244px; object-fit: cover; z-index: -1;" alt="">
            <?php endif; ?>
            <!-- Tanda Tangan Ketua (Bottom Right) - Adjusted for PDF rendering -->
            @if($settings->signature_path)
            <div style="position: absolute; bottom: 65px; right: 15px; width: 120px; height: 60px;">
                <img src="{{ asset('storage/'.$settings->signature_path) }}" alt="Tanda Tangan"
                     style="width: 100%; height: 100%; object-fit: contain; filter: brightness(0) invert(1);">
            </div>
            @endif

            @php
            // Get ketua data from pengurus table
            use App\Models\Pengurus;
            $ketua = Pengurus::getKetuaAktif();
            $namaKetua = $ketua ? $ketua->nama_lengkap : ($settings->nama_ketua ?? 'Nama Ketua');

            // Calculate text width for nama ketua (approximate: 3px per character for Arial 12px)
            // Remove spaces and calculate only actual characters
            $actualChars = strlen(str_replace(' ', '', $namaKetua));
            $textWidth = $actualChars * 3; // Approximate width calculation
            $rightPosition = 5 + $textWidth; // 5px + text width (closer to edge)
            @endphp

            <!-- Nama Ketua (Bottom Right) - Dynamic positioning based on text length -->
            <div style="position: absolute; bottom: 40px; right: {{ $rightPosition }}px; color: {{ $fontColorBack }}; font-size: 10px; font-weight: bold; white-space: nowrap;">
                {{ $namaKetua }}
            </div>

            <!-- Jabatan Ketua (Centered above nama ketua) - Same dynamic positioning -->
            <div style="position: absolute; bottom: 83px; right: {{ $rightPosition }}px; color: {{ $fontColorBack }}; font-size: 9px; white-space: nowrap;">
                Ketua
            </div>
              </div>
      </div>
    </div>
</body>
</html>