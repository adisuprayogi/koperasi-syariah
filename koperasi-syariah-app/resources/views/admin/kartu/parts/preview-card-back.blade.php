<?php
$bgStyle = '';
if ($settings->background_image_back) {
    $bgStyle = "background-image: url('" . asset('storage/' . $settings->background_image_back) . "'); background-size: cover; background-position: center;";
} elseif ($settings->background_back) {
    switch($settings->background_back) {
        case 'gradient-blue':
            $bgStyle = 'background: linear-gradient(135deg, #1e40af 0%, #3b82f6 100%);';
            break;
        case 'gradient-green':
            $bgStyle = 'background: linear-gradient(135deg, #059669 0%, #10b981 100%);';
            break;
        case 'gradient-purple':
            $bgStyle = 'background: linear-gradient(135deg, #7c3aed 0%, #a855f7 100%);';
            break;
        case 'solid-color':
            $color = $settings->primary_color_back ?? '#1e40af';
            $bgStyle = 'background-color: ' . $color . ';';
            break;
    }
}
?>

<div style="{{ $bgStyle }} position: relative; width: 100%; height: 100%; padding: 15px;">
    <?php $fontColorBack = $settings->font_color_back ?? '#ffffff'; ?>

    <!-- Tanda Tangan Ketua (Bottom Right) -->
    @if($settings->signature_path)
    <div style="position: absolute; bottom: 70px; right: 20px; width: 120px; height: 60px;">
        <img src="{{ asset('storage/'.$settings->signature_path) }}" alt="Tanda Tangan"
             class="w-full h-full object-contain" style="filter: brightness(0) invert(1);">
    </div>
    @endif

    <!-- Nama Ketua (Bottom Right) -->
    <div class="card-text-back" style="position: absolute; bottom: 15px; right: 15px; color: {{ $fontColorBack }}; font-size: 12px; font-weight: bold; text-align: right;">
        {{ $settings->nama_ketua ?? 'Nama Ketua' }}
    </div>

    <!-- Jabatan Ketua (Centered above nama ketua) -->
    <div class="card-text-back" style="position: absolute; bottom: 58px; right: 15px; color: {{ $fontColorBack }}; font-size: 12px; text-align: right;">
        Ketua
    </div>
</div>