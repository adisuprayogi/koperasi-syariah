<?php
$positions = $settings->positions_back ?? [
    'nama_ketua' => ['x' => 10, 'y' => 140],
    'tanda_tangan' => ['x' => 200, 'y' => 135, 'width' => 80],
    'syarat_ketentuan' => ['x' => 10, 'y' => 20],
    'custom_text' => ['x' => 10, 'y' => 100]
];

$fontSizes = $settings->font_sizes_back ?? [
    'title' => 16,
    'subtitle' => 14,
    'body' => 12,
    'small' => 10
];
?>

@php
function getBackBackgroundStyle($settings, $side) {
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

<div style="{{ getBackBackgroundStyle($settings, 'back') }}">
    <!-- Syarat & Ketentuan -->
    @if($settings->show_syarat_ketentuan_back && $settings->syarat_ketentuan)
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['syarat_ketentuan']['x'] . 'px') : ($positions['syarat_ketentuan']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['syarat_ketentuan']['y'] . 'px') : ($positions['syarat_ketentuan']['y'] / 3.4 . 'mm') }};
        right: {{ $preview ? '10px' : '3mm') }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
        text-align: justify;
        line-height: 1.3;
    ">
        {{ $settings->syarat_ketentuan }}
    </div>
    @endif

    <!-- Custom Text -->
    @if($settings->custom_text_back)
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['custom_text']['x'] . 'px') : ($positions['custom_text']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['custom_text']['y'] . 'px') : ($positions['custom_text']['y'] / 3.4 . 'mm') }};
        right: {{ $preview ? '10px' : '3mm' }};
        font-size: {{ $preview ? ($fontSizes['small'] . 'px') : ($fontSizes['small'] / 3.4 . 'mm') }};
        font-style: italic;
    ">
        {{ $settings->custom_text_back }}
    </div>
    @endif

    <!-- Tanda Tangan Ketua -->
    @if($settings->show_tanda_tangan_back && $settings->signature_path)
    <div class="card-element" style="
        left: {{ $preview ? ($positions['tanda_tangan']['x'] . 'px') : ($positions['tanda_tangan']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['tanda_tangan']['y'] . 'px') : ($positions['tanda_tangan']['y'] / 3.4 . 'mm') }};
        width: {{ $preview ? ($positions['tanda_tangan']['width'] . 'px') : ($positions['tanda_tangan']['width'] / 3.4 . 'mm') }};
        height: {{ $preview ? '30px' : '9mm' }};
    ">
        <img src="{{ asset('storage/'.$settings->signature_path) }}" alt="Tanda Tangan"
             class="tanda-tangan" style="filter: brightness(0) invert(1);">
    </div>
    @endif

    <!-- Nama Ketua -->
    @if($settings->show_nama_ketua_back)
    @php
    // Get ketua data from pengurus table
    use App\Models\Pengurus;
    $ketua = Pengurus::getKetuaAktif();
    $namaKetua = $ketua ? $ketua->nama_lengkap : ($settings->nama_ketua ?? 'Nama Ketua');
    @endphp
    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['nama_ketua']['x'] . 'px') : ($positions['nama_ketua']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['nama_ketua']['y'] . 'px') : ($positions['nama_ketua']['y'] / 3.4 . 'mm') }};
        font-size: {{ $preview ? '10px' : '3mm' }};
        font-weight: bold;
    ">
        {{ $namaKetua }}
    </div>

    <div class="card-element text-white" style="
        left: {{ $preview ? ($positions['nama_ketua']['x'] . 'px') : ($positions['nama_ketua']['x'] / 3.4 . 'mm') }};
        top: {{ $preview ? ($positions['nama_ketua']['y'] + 15 . 'px') : (($positions['nama_ketua']['y'] + 15) / 3.4 . 'mm') }};
        font-size: {{ $preview ? '9px' : '2.5mm' }};
    ">
        {{ $settings->jabatan_ketua ?? 'Ketua Koperasi' }}
    </div>
    @endif

    <!-- Info Kontak Koperasi -->
    <div class="card-element text-white" style="
        bottom: {{ $preview ? '10px' : '3mm' }};
        left: {{ $preview ? '10px' : '3mm' }};
        right: {{ $preview ? '10px' : '3mm' }};
        font-size: {{ $preview ? '8px' : '2.5mm' }};
        text-align: center;
        opacity: 0.8;
    ">
        @if($settings->alamat_koperasi)
            {{ $settings->alamat_koperasi }}
        @endif
        @if($settings->telepon_koperasi)
            • {{ $settings->telepon_koperasi }}
        @endif
        @if($settings->email_koperasi)
            • {{ $settings->email_koperasi }}
        @endif
    </div>
</div>