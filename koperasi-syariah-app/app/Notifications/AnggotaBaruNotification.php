<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Koperasi;

class AnggotaBaruNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $anggota;
    protected $password;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $anggota
     * @param  string  $password
     * @return void
     */
    public function __construct($anggota, $password)
    {
        $this->anggota = $anggota;
        $this->password = $password;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return ['mail'];
    }

    /**
     * Get the mail representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return \Illuminate\Notifications\Messages\MailMessage
     */
    public function toMail($notifiable)
    {
        $koperasi = Koperasi::first();

        return (new MailMessage)
            ->subject('Selamat Datang di ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah'))
            ->greeting('Assalamu\'alaikum Warahmatullahi Wabarakatuh,')
            ->line('Bismillahirrahmanirrahim')
            ->line('Selamat bergabung menjadi anggota ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '! 🎉')
            ->line('Kami sangat senang menyambut Anda sebagai anggota baru koperasi syariah kami.')
            ->line('Berikut adalah informasi akun Anda untuk mengakses sistem:')
            ->line('**Nomor Anggota:** ' . $this->anggota->no_anggota)
            ->line('**Email:** ' . $this->anggota->email)
            ->line('**Password Sementara:** ' . $this->password)
            ->action('Login ke Sistem', route('login'))
            ->line('⚠️ **Penting:** Harap mengubah password sementara Anda pada saat pertama kali login untuk keamanan akun.')
            ->line('Sebagai anggota koperasi, Anda akan menikmati berbagai manfaat:')
            ->line('• Akses ke produk pembiayaan syariah')
            ->line('• Simpanan dengan sistem bagi hasil yang adil')
            ->line('• Layanan konsultasi keuangan syariah')
            ->line('• Program pengembangan ekonomi anggota')
            ->line('Jika ada pertanyaan, jangan ragu untuk menghubungi kami.')
            ->salutation('Wassalamu\'alaikum Warahmatullahi Wabarakatuh,')
            ->line('**Tim ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '**')
            ->line('📞 ' . ($koperasi->telepon ?? ''))
            ->line('📧 ' . ($koperasi->email ?? ''))
            ->line('📍 ' . ($koperasi->alamat ?? ''));
    }

    /**
     * Get the array representation of the notification.
     *
     * @param  mixed  $notifiable
     * @return array
     */
    public function toArray($notifiable)
    {
        return [
            'type' => 'anggota_baru',
            'anggota_id' => $this->anggota->id,
            'no_anggota' => $this->anggota->no_anggota,
            'nama_anggota' => $this->anggota->nama_lengkap,
            'message' => 'Anggota baru telah terdaftar: ' . $this->anggota->nama_lengkap,
        ];
    }
}
