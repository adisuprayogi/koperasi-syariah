<?php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use App\Models\Koperasi;

class AngsuranNotification extends Notification implements ShouldQueue
{
    use Queueable;

    protected $transaksi;
    protected $pembiayaan;
    protected $angsuran;

    /**
     * Create a new notification instance.
     *
     * @param  mixed  $transaksi
     * @param  mixed  $pembiayaan
     * @param  mixed  $angsuran
     * @return void
     */
    public function __construct($transaksi, $pembiayaan = null, $angsuran = null)
    {
        $this->transaksi = $transaksi;
        $this->pembiayaan = $pembiayaan;
        $this->angsuran = $angsuran;
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
        $actionText = 'Lihat Detail Angsuran';
        $actionUrl = $this->getActionUrl();

        return (new MailMessage)
            ->subject($subject)
            ->greeting($greeting)
            ->line($message)
            ->action($actionText, $actionUrl)
            ->line('Terima kasih atas kepatuhan Anda dalam melunasi kewajiban.')
            ->line('Semoga Allah SWT memudahkan urusan dan memberkahi kebaikan.')
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
            'type' => 'angsuran_pembayaran',
            'transaksi_id' => $this->transaksi->id,
            'pembiayaan_id' => $this->pembiayaan->id ?? null,
            'angsuran_id' => $this->angsuran->id ?? null,
            'kode_transaksi' => $this->transaksi->kode_transaksi,
            'jumlah' => $this->transaksi->jumlah,
            'sisa_pokok' => $this->pembiayaan ? $this->pembiayaan->sisa_pokok : 0,
            'message' => 'Pembayaran angsuran: ' . $this->transaksi->kode_transaksi,
        ];
    }

    private function getSubject()
    {
        $koperasi = Koperasi::first();

        if ($this->transaksi) {
            return '💰 Konfirmasi Pembayaran Angsuran - ' . $this->transaksi->kode_transaksi;
        }

        return '💰 Notifikasi Pembayaran Angsuran';
    }

    private function getGreeting()
    {
        return 'Assalamu\'alaikum Warahmatullahi Wabarakatuh,';
    }

    private function getMessage()
    {
        $koperasi = Koperasi::first();
        $formattedAmount = 'Rp ' . number_format($this->transaksi->jumlah, 0, ',', '.');

        $message = 'Alhamdulillah! Pembayaran angsuran Anda telah berhasil diproses. ' .
                  'Berikut detail pembayaran:' . "\n\n" .
                  '• **Kode Transaksi:** ' . $this->transaksi->kode_transaksi . "\n" .
                  '• **Jumlah Bayar:** ' . $formattedAmount . "\n" .
                  '• **Tanggal Pembayaran:** ' . $this->transaksi->tanggal_transaksi->format('d F Y H:i') . "\n";

        if ($this->angsuran) {
            $message .= '• **Angsuran Ke:** ' . $this->angsuran->angsuran_ke . ' dari ' . $this->pembiayaan->tenor . ' bulan' . "\n" .
                      '• **Jatuh Tempo Berikutnya:** ' . $this->angsuran->tanggal_jatuh_tempo->format('d F Y') . "\n";
        }

        if ($this->pembiayaan) {
            $sisaPokok = 'Rp ' . number_format($this->pembiayaan->sisa_pokok, 0, ',', '.');
            $message .= '• **Sisa Pokok:** ' . $sisaPokok . "\n" .
                      '• **Jumlah Angsuran:** Rp ' . number_format($this->pembiayaan->jumlah_angsuran, 0, ',', '.') . "\n" .
                      '• **Sisa Cicilan:** ' . $this->pembiayaan->sisa_cicilan . ' angsuran lagi' . "\n\n" .
                      'Terima kasih atas kepatuhan Anda dalam melunasi kewajiban ' .
                      'sesuai akad pembiayaan syariah yang telah disepakati.';
        }

        return $message . 'Pembayaran ini telah diverifikasi dan dicatat dalam sistem kami.';
    }

    private function getActionUrl()
    {
        if ($this->transaksi) {
            return route('anggota.pembiayaan.show', $this->pembiayaan->id);
        }

        return route('login');
    }
}
