<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class KartuAnggotaSetting extends Model
{
    use HasFactory;

    protected $fillable = [
        'nama_koperasi',
        'alamat_koperasi',
        'telepon_koperasi',
        'email_koperasi',
        'website_koperasi',
        'nama_ketua',
        'jabatan_ketua',
        'background_front',
        'background_image_front',
        'primary_color_front',
        'secondary_color_front',
        'text_color_front',
        'font_color_front',
        'show_logo_front',
        'show_nomor_anggota_front',
        'show_nama_anggota_front',
        'show_foto_anggota_front',
        'show_tanggal_masuk_front',
        'show_barcode_front',
        'show_custom_text_front',
        'custom_text_front',
        'background_back',
        'background_image_back',
        'primary_color_back',
        'secondary_color_back',
        'text_color_back',
        'font_color_back',
        'show_nama_ketua_back',
        'show_tanda_tangan_back',
        'show_syarat_ketentuan_back',
        'syarat_ketentuan',
        'custom_text_back',
        'positions_front',
        'positions_back',
        'font_family',
        'font_sizes_front',
        'font_sizes_back',
        'custom_fields_front',
        'custom_fields_back',
        'logo_path',
        'signature_path',
    ];

    protected $casts = [
        'show_logo_front' => 'boolean',
        'show_nomor_anggota_front' => 'boolean',
        'show_nama_anggota_front' => 'boolean',
        'show_foto_anggota_front' => 'boolean',
        'show_tanggal_masuk_front' => 'boolean',
        'show_barcode_front' => 'boolean',
        'show_custom_text_front' => 'boolean',
        'show_nama_ketua_back' => 'boolean',
        'show_tanda_tangan_back' => 'boolean',
        'show_syarat_ketentuan_back' => 'boolean',
        'positions_front' => 'array',
        'positions_back' => 'array',
        'font_sizes_front' => 'array',
        'font_sizes_back' => 'array',
        'custom_fields_front' => 'array',
        'custom_fields_back' => 'array',
    ];

    public static function getSettings()
    {
        return self::first() ?: self::getDefaultSettings();
    }

    public static function getDefaultSettings()
    {
        return (object) [
            'nama_koperasi' => 'Koperasi Syariah',
            'background_front' => 'gradient-blue',
            'primary_color_front' => '#1e40af',
            'secondary_color_front' => '#3b82f6',
            'text_color_front' => '#ffffff',
            'font_color_front' => '#ffffff',
            'show_logo_front' => true,
            'show_nomor_anggota_front' => true,
            'show_nama_anggota_front' => true,
            'show_foto_anggota_front' => true,
            'show_tanggal_masuk_front' => false,
            'show_barcode_front' => true,
            'show_valid_until_front' => true,
            'background_back' => 'gradient-blue',
            'primary_color_back' => '#1e40af',
            'secondary_color_back' => '#3b82f6',
            'text_color_back' => '#ffffff',
            'font_color_back' => '#ffffff',
            'show_nama_ketua_back' => true,
            'show_tanda_tangan_back' => true,
            'show_syarat_ketentuan_back' => true,
            'syarat_ketentuan' => 'Kartu ini berlaku sebagai identitas resmi anggota koperasi.',
            'custom_text_back' => null,
            'font_family' => 'Arial',
            'positions_front' => [
                'logo' => ['x' => 10, 'y' => 10, 'width' => 80],
                'nomor_anggota' => ['x' => 10, 'y' => 50],
                'nama_anggota' => ['x' => 10, 'y' => 70],
                'foto' => ['x' => 250, 'y' => 40, 'width' => 60],
                'tanggal_masuk' => ['x' => 10, 'y' => 90],
                'barcode' => ['x' => 200, 'y' => 130, 'width' => 40]
            ],
            'positions_back' => [
                'nama_ketua' => ['x' => 10, 'y' => 140],
                'tanda_tangan' => ['x' => 200, 'y' => 135, 'width' => 80],
                'syarat_ketentuan' => ['x' => 10, 'y' => 20],
                'custom_text' => ['x' => 10, 'y' => 100]
            ],
            'font_sizes_front' => [
                'title' => 16,
                'subtitle' => 14,
                'body' => 12,
                'small' => 10
            ],
            'font_sizes_back' => [
                'title' => 16,
                'subtitle' => 14,
                'body' => 12,
                'small' => 10
            ],
            'custom_fields_front' => [],
            'custom_fields_back' => [],
        ];
    }
}