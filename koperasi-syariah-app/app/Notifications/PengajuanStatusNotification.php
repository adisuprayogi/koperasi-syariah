<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Koperasi;

class PengajuanStatusNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $pengajuan;
    protected $status;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $pengajuan
     * @param  string  $status
     * @return void
     */
    public function __construct($pengajuan, $status)
    {
        $this->pengajuan = $pengajuan;
        $this->status = $status;
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

        $subject = $this->getSubject();
        $greeting = $this->getGreeting();
        $message = $this->getMessage();
        $actionText = $this->getActionText();
        $actionUrl = $this->getActionUrl();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->when($actionText && $actionUrl, function ($mailMessage) use ($actionText, $actionUrl) {
                return $mailMessage->action($actionText, $actionUrl);
            })
            ->line('Terima kasih atas kepercayaan Anda kepada ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '.')
            ->salutation('Wassalamu\'alaikum Warahmatullahi Wabarakatuh,')
            ->line('**Tim ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '**');
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
            'type' => 'pengajuan_status',
            'pengajuan_id' => $this->pengajuan->id,
            'kode_pengajuan' => $this->pengajuan->kode_pengajuan,
            'status' => $this->status,
            'message' => 'Status pengajuan ' . $this->pengajuan->kode_pengajuan . ' telah diubah menjadi: ' . $this->status,
        ];
    }

    private function getSubject()
    {
        $koperasi = Koperasi::first();

        switch ($this->status) {
            case 'approved':
                return '✅ Pengajuan Pembiayaan Disetujui - ' . $this->pengajuan->kode_pengajuan;
            case 'rejected':
                return '❌ Pengajuan Pembiayaan Ditolak - ' . $this->pengajuan->kode_pengajuan;
            case 'cair':
                return '💰 Pengajuan Pembiayaan Cair - ' . $this->pengajuan->kode_pengajuan;
            case 'verifikasi':
                return '🔍 Pengajuan Pembiayaan Sedang Diverifikasi - ' . $this->pengajuan->kode_pengajuan;
            default:
                return '📋 Update Status Pengajuan - ' . $this->pengajuan->kode_pengajuan;
        }
    }

    private function getGreeting()
    {
        switch ($this->status) {
            case 'approved':
            case 'cair':
                return 'Assalamu\'alaikum Warahmatullahi Wabarakatuh,';
            case 'rejected':
                return 'Assalamu\'alaikum Warahmatullahi Wabarakatuh,';
            default:
                return 'Assalamu\'alaikum Warahmatullahi Wabarakatuh,';
        }
    }

    private function getMessage()
    {
        $koperasi = Koperasi::first();

        switch ($this->status) {
            case 'approved':
                return '🎉 Selamat! Pengajuan pembiayaan Anda dengan kode ' . $this->pengajuan->kode_pengajuan . ' telah disetujui. ' .
                       'Pembiayaan sebesar Rp ' . number_format($this->pengajuan->jumlah_pengajuan, 0, ',', '.') . ' ' .
                       'dengan jenis ' . $this->pengajuan->jenisPembiayaan->nama_pembiayaan . ' telah disetujui. ' .
                       'Silakan menunggu proses pencairan dari pihak ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '.';

            case 'rejected':
                return 'Mohon maaf, pengajuan pembiayaan Anda dengan kode ' . $this->pengajuan->kode_pengajuan . ' ' .
                       'tidak dapat kami setujui pada saat ini. ' .
                       'Untuk informasi lebih lanjut, Anda dapat menghubungi pengurus ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '.';

            case 'cair':
                return '💰 Alhamdulillah! Dana pembiayaan Anda dengan kode ' . $this->pengajuan->kode_pengajuan . ' ' .
                       'telah cair. Jumlah yang cair: Rp ' . number_format($this->pengajuan->jumlah_pengajuan, 0, ',', '.') . '. ' .
                       'Harap digunakan sesuai dengan perjanjian yang telah disepakati. ' .
                       'Jadwal angsuran pertama akan dimulai sesuai dengan akad.';

            case 'verifikasi':
                return '🔍 Pengajuan pembiayaan Anda dengan kode ' . $this->pengajuan->kode_pengajuan . ' ' .
                       'sedang dalam proses verifikasi oleh tim ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '. ' .
                       'Proses ini biasanya memakan waktu 1-3 hari kerja. ' .
                       'Kami akan menginformasikan hasilnya segera setelah selesai.';

            default:
                return 'Status pengajuan pembiayaan Anda dengan kode ' . $this->pengajuan->kode_pengajuan . ' ' .
                       'telah diperbarui menjadi: ' . ucfirst($this->status) . '.';
        }
    }

    private function getActionText()
    {
        switch ($this->status) {
            case 'approved':
            case 'cair':
                return 'Lihat Detail Pengajuan';
            case 'rejected':
                return 'Ajukan Kembali';
            default:
                return 'Lihat Status';
        }
    }

    private function getActionUrl()
    {
        return route('anggota.pengajuan.show', $this->pengajuan->id);
    }
}
