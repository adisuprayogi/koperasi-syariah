<?php
$positions = $settings->positions_front ?? [
    'logo' => ['x' => 10, 'y' => 10, 'width' => 80],
    'nomor_anggota' => ['x' => 10, 'y' => 50],
    'nama_anggota' => ['x' => 10, 'y' => 70],
    'foto' => ['x' => 250, 'y' => 40, 'width' => 60],
    'tanggal_masuk' => ['x' => 10, 'y' => 90],
    'barcode' => ['x' => 200, 'y' => 130, 'width' => 40]
];

$fontSizes = $settings->font_sizes_front ?? [
    'title' => 16,
    'subtitle' => 14,
    'body' => 12,
    'small' => 10
];
?>

@php
function getBackgroundStyle($settings, $side) {
    $backgroundKey = 'background_' . $side;
    $backgroundImageKey = 'background_image_' . $side;

    if ($settings->$backgroundImageKey) {
        return "background-image: url('" . asset('storage/' . $settings->$backgroundImageKey) . "'); background-size: cover; background-position: center;";
    }

    switch($settings->$backgroundKey) {
        case 'gradient-blue':
            return 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
        case 'gradient-green':
            return 'background: linear-gradient(135deg, #84fab0 0%, #8fd3f4 100%);';
        case 'gradient-purple':
            return 'background: linear-gradient(135deg, #a8edea 0%, #fed6e3 100%);';
        case 'solid-color':
            $colorKey = 'primary_color_' . $side;
            return 'background-color: ' . ($settings->$colorKey ?? '#1e40af') . ';';
        default:
            return 'background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);';
    }
}
@endphp

<div style="{{ getBackgroundStyle($settings, 'front') }}">
    <!-- Logo Koperasi -->
    @if($settings->show_logo_front && $settings->logo_path)
    <div class="card-element logo" style="
        left: {{ $preview ? ($positions['logo']['x'] . 'px') : ($positions['logo']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['logo']['y'] . 'px') : ($positions['logo']['y'] / 3.4 . 'mm') }};
        width: {{ $preview ? ($positions['logo']['width'] . 'px') : ($positions['logo']['width'] / 3.4 . 'mm') }};
    ">
        <img src="{{ asset('storage/'.$settings->logo_path) }}" alt="Logo" class="w-full h-full object-contain">
    </div>
    @endif

    <!-- Nomor Anggota -->
    @if($settings->show_nomor_anggota_front)
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['nomor_anggota']['x'] . 'px') : ($positions['nomor_anggota']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['nomor_anggota']['y'] . 'px') : ($positions['nomor_anggota']['y'] / 3.4 . 'mm') }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
        font-weight: bold;
    ">
        {{ $anggota->nomor_anggota }}
    </div>
    @endif

    <!-- Nama Anggota -->
    @if($settings->show_nama_anggota_front)
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['nama_anggota']['x'] . 'px') : ($positions['nama_anggota']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['nama_anggota']['y'] . 'px') : ($positions['nama_anggota']['y'] / 3.4 . 'mm') }};
        font-size: {{ $preview ? ($fontSizes['subtitle'] . 'px') : ($fontSizes['subtitle'] / 3.4 . 'mm') }};
        font-weight: bold;
    ">
        {{ strtoupper($anggota->nama_lengkap) }}
    </div>
    @endif

    <!-- Foto Anggota -->
    @if($settings->show_foto_anggota_front && $anggota->foto)
    <div class="card-element foto-anggota" style="
        left: {{ $preview ? ($positions['foto']['x'] . 'px') : ($positions['foto']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['foto']['y'] . 'px') : ($positions['foto']['y'] / 3.4 . 'mm') }};
        width: {{ $preview ? ($positions['foto']['width'] . 'px') : ($positions['foto']['width'] / 3.4 . 'mm') }};
        height: {{ $preview ? ($positions['foto']['width'] . 'px') : ($positions['foto']['width'] / 3.4 . 'mm') }};
    ">
        <img src="{{ asset('storage/'.$anggota->foto) }}" alt="{{ $anggota->nama_lengkap }}"
             class="w-full h-full object-cover">
    </div>
    @endif

    <!-- Tanggal Masuk -->
    @if($settings->show_tanggal_masuk_front)
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['tanggal_masuk']['x'] . 'px') : ($positions['tanggal_masuk']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['tanggal_masuk']['y'] . 'px') : ($positions['tanggal_masuk']['y'] / 3.4 . 'mm') }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
    ">
        Masuk: {{ $anggota->tanggal_masuk ? \Carbon\Carbon::parse($anggota->tanggal_masuk)->format('d/m/Y') : '-' }}
    </div>
    @endif

    <!-- Barcode (dari nomor anggota) -->
    @if($settings->show_barcode_front)
    @php
    $nomorAnggota = $anggota->nomor_anggota ?? '001/ANG/2024';
    // Generate simple barcode pattern from member number
    $barcodePattern = '';
    $cleanNumber = preg_replace('/[^0-9]/', '', $nomorAnggota); // Extract numbers only

    // Generate barcode bars based on the number
    for ($i = 0; $i < strlen($cleanNumber); $i++) {
        $digit = $cleanNumber[$i];
        $bars = '';

        // Each digit gets a unique pattern of bars
        switch($digit) {
            case '0':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (7 + $i * 12) . '" y="5" width="3" height="25" fill="black"/>
                        <rect x="' . (11 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '1':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (8 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (10 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>';
                break;
            case '2':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (7 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (10 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>';
                break;
            case '3':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="3" height="25" fill="black"/>
                        <rect x="' . (9 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (11 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '4':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (7 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (9 + $i * 12) . '" y="5" width="3" height="25" fill="black"/>';
                break;
            case '5':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (8 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (10 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '6':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (7 + $i * 12) . '" y="5" width="3" height="25" fill="black"/>
                        <rect x="' . (11 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '7':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (8 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (11 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '8':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (7 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (9 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>
                        <rect x="' . (11 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            case '9':
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="3" height="25" fill="black"/>
                        <rect x="' . (9 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (12 + $i * 12) . '" y="5" width="1" height="25" fill="black"/>';
                break;
            default:
                $bars = '<rect x="' . (5 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>
                        <rect x="' . (8 + $i * 12) . '" y="5" width="2" height="25" fill="black"/>';
        }

        $barcodePattern .= $bars;
    }

    // Add start and end markers
    $startMarker = '<rect x="0" y="0" width="3" height="30" fill="black"/>
                    <rect x="4" y="0" width="1" height="30" fill="black"/>';
    $endMarker = '<rect x="117" y="0" width="3" height="30" fill="black"/>
                 <rect x="116" y="0" width="1" height="30" fill="black"/>';

    $barcodePattern = $startMarker . $barcodePattern . $endMarker;
    @endphp
    <div class="card-element barcode" style="
        left: {{ $preview ? ($positions['barcode']['x'] . 'px') : ($positions['barcode']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['barcode']['y'] . 'px') : ($positions['barcode']['y'] / 3.4 . 'mm') }};
        width: {{ $preview ? ($positions['barcode']['width'] . 'px') : ($positions['barcode']['width'] / 3.4 . 'mm') }};
        height: {{ $preview ? '40px' : '12mm' }};
    ">
        <svg width="100%" height="100%" viewBox="0 0 120 40">
            <rect width="100%" height="100%" fill="white" rx="2"/>
            {!! $barcodePattern !!}
            <!-- Member number below barcode -->
            <text x="60" y="38" text-anchor="middle" font-family="monospace" font-size="8" fill="black">{{ $nomorAnggota }}</text>
        </svg>
    </div>
    @endif

    <!-- Valid Until -->
    @if($settings->show_valid_until_front)
    <div class="card-element text-white" style="
        right: {{ $preview ? '10px' : '3mm' }};
        bottom: {{ $preview ? '10px' : '3mm' }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
        text-align: right;
    ">
        Berlaku: {{ \Carbon\Carbon::now()->addYears(5)->format('d/m/Y') }}
    </div>
    @endif

    <!-- Nama Koperasi -->
    <div class="card-element text-white" style="
        bottom: {{ $preview ? '10px' : '3mm' }};
        left: {{ $preview ? '10px' : '3mm' }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
        font-weight: bold;
    ">
        {{ $settings->nama_koperasi }}
    </div>
</div>