<?php
$bgStyle = '';
if ($settings->background_image_front) {
    $bgStyle = "background-image: url('" . asset('storage/' . $settings->background_image_front) . "'); background-size: cover; background-position: center;";
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
}
?>

<div style="{{ $bgStyle }} position: relative; width: 100%; height: 100%; padding: 15px;">
    <?php $fontColorFront = $settings->font_color_front ?? '#ffffff'; ?>

    <!-- Nomor Anggota (Top) -->
    <div class="card-text-front" style="position: absolute; top: 90px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px; font-weight: bold;">
        No: 001/ANG/2024
    </div>

    <!-- Nama Anggota (Below Member Number) -->
    <div class="card-text-front" style="position: absolute; top: 107px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px; font-weight: bold;">
        AHMAD RIZKI
    </div>

    <!-- Tanggal Masuk (Bottom) -->
    <div class="card-text-front" style="position: absolute; top: 125px; left: 15px; color: {{ $fontColorFront }}; font-size: 14px;">
        Masuk: 01/01/2024
    </div>

    <!-- Barcode (Bottom Left) -->
    @php
    $nomorAnggota = '001/ANG/2024';
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
    <div style="position: absolute; bottom: 15px; left: 15px; width: 140px; height: 45px;">
        <svg width="100%" height="100%" viewBox="0 0 120 40">
            <rect width="100%" height="100%" fill="white" rx="2"/>
            {!! $barcodePattern !!}
            <!-- Member number below barcode -->
            <text x="60" y="38" text-anchor="middle" font-family="monospace" font-size="8" fill="black">{{ $nomorAnggota }}</text>
        </svg>
    </div>
</div>