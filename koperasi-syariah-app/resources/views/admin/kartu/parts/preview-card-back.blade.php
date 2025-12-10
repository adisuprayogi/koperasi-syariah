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
    <?php
    $fontColorBack = $settings->font_color_back ?? '#ffffff';

    // Get ketua data from pengurus table
    use App\Models\Pengurus;
    $ketua = Pengurus::getKetuaAktif();
    $namaKetua = $ketua ? $ketua->nama_lengkap : ($settings->nama_ketua ?? 'Nama Ketua');

    // Calculate text width for nama ketua (approximate: 3px per character for Arial 12px)
    $actualChars = strlen(str_replace(' ', '', $namaKetua));
    $textWidth = $actualChars * 3; // Approximate width calculation
    $rightPosition = 10 + $textWidth; // 10px + text width (closer to edge)
    ?>

    <!-- Tanda Tangan Ketua (Bottom Right) -->
    @if($settings->signature_path)
    <div style="position: absolute; bottom: 65px; right: 15px; width: 120px; height: 60px;">
        <img src="{{ asset('storage/'.$settings->signature_path) }}" alt="Tanda Tangan"
             class="w-full h-full object-contain" style="filter: brightness(0) invert(1);">
    </div>
    @endif

    <!-- Nama Ketua (Bottom Right) - Dynamic positioning based on text length -->
    <div class="card-text-back" style="position: absolute; bottom: 15px; right: {{ $rightPosition }}px; color: {{ $fontColorBack }}; font-size: 10px; font-weight: bold; white-space: nowrap;">
        {{ $namaKetua }}
    </div>

    <!-- Jabatan Ketua (Above nama ketua) - Same dynamic positioning -->
    <div class="card-text-back" style="position: absolute; bottom: 83px; right: {{ $rightPosition }}px; color: {{ $fontColorBack }}; font-size: 9px; white-space: nowrap;">
        Ketua
    </div>
</div>