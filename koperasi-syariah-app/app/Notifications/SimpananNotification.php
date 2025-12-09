<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Koperasi;

class SimpananNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaksi;
    protected $jenis;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $transaksi
     * @param  string  $jenis
     * @return void
     */
    public function __construct($transaksi, $jenis = 'setor')
    {
        $this->transaksi = $transaksi;
        $this->jenis = $jenis;
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
        $actionText = 'Lihat Detail Transaksi';
        $actionUrl = route('anggota.simpanan.show', $this->transaksi->id);

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->action($actionText, $actionUrl)
            ->line('Terima kasih atas kepercayaan Anda kepada ' . ($koperasi->nama_koperasi ?? 'Koperasi Syariah') . '.')
            ->line('Semoga simpanan Anda membawa berkah dan manfaat.')
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
            'type' => 'simpanan_transaksi',
            'transaksi_id' => $this->transaksi->id,
            'kode_transaksi' => $this->transaksi->kode_transaksi,
            'jenis_transaksi' => $this->jenis,
            'jumlah' => $this->transaksi->jumlah,
            'jenis_simpanan' => $this->transaksi->jenisSimpanan->nama,
            'message' => 'Transaksi ' . $this->jenis . ' simpanan: ' . $this->transaksi->kode_transaksi,
        ];
    }

    private function getSubject()
    {
        $koperasi = Koperasi::first();

        if ($this->jenis === 'setor') {
            return '💰 Konfirmasi Setoran Simpanan - ' . $this->transaksi->kode_transaksi;
        } else {
            return '💸 Konfirmasi Penarikan Simpanan - ' . $this->transaksi->kode_transaksi;
        }
    }

    private function getGreeting()
    {
        return 'Assalamu\'alaikum Warahmatullahi Wabarakatuh,';
    }

    private function getMessage()
    {
        $koperasi = Koperasi::first();
        $formattedAmount = 'Rp ' . number_format($this->transaksi->jumlah, 0, ',', '.');

        if ($this->jenis === 'setor') {
            return 'Terima kasih! Setoran simpanan Anda telah berhasil diproses. ' .
                   'Berikut detail transaksi Anda:' . "\n\n" .
                   '• **Kode Transaksi:** ' . $this->transaksi->kode_transaksi . "\n" .
                   '• **Jenis Simpanan:** ' . $this->transaksi->jenisSimpanan->nama . "\n" .
                   '• **Jumlah:** ' . $formattedAmount . "\n" .
                   '• **Tanggal:** ' . $this->transaksi->tanggal_transaksi->format('d F Y H:i') . "\n" .
                   '• **Saldo Setelahnya:** Rp ' . number_format($this->transaksi->saldo_setelahnya, 0, ',', '.') . "\n\n" .
                   'Setoran ini telah diverifikasi dan dicatat dalam sistem kami. ' .
                   'Saldo simpanan Anda sekarang tersedia untuk digunakan sesuai kebutuhan.';
        } else {
            return 'Penarikan simpanan Anda telah berhasil diproses. ' .
                   'Berikut detail transaksi Anda:' . "\n\n" .
                   '• **Kode Transaksi:** ' . $this->transaksi->kode_transaksi . "\n" .
                   '• **Jenis Simpanan:** ' . $this->transaksi->jenisSimpanan->nama . "\n" .
                   '• **Jumlah:** ' . $formattedAmount . "\n" .
                   '• **Tanggal:** ' . $this->transaksi->tanggal_transaksi->format('d F Y H:i') . "\n" .
                   '• **Saldo Setelahnya:** Rp ' . number_format($this->transaksi->saldo_setelahnya, 0, ',', '.') . "\n\n" .
                   'Penarikan ini telah diverifikasi dan dicatat dalam sistem kami. ' .
                   'Saldo simpanan Anda yang tersedia sekarang: Rp ' . number_format($this->transaksi->saldo_setelahnya, 0, ',', '.') . '.';
        }
    }
}
