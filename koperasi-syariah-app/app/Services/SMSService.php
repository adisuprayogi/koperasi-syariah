<?php

namespace App\Services;

use Vonage\Client;
use Vonage\Client\Credentials\Basic;
use Vonage\SMS\Message\SMS;
use Illuminate\Support\Facades\Log;

class SMSService
{
    protected $client;
    protected $from;
    protected $sandbox;

    public function __construct()
    {
        $this->sandbox = config('vonage.sandbox', false);

        if (!$this->sandbox) {
            $apiKey = config('vonage.api_key');
            $apiSecret = config('vonage.api_secret');

            if ($apiKey && $apiSecret) {
                $credentials = new Basic($apiKey, $apiSecret);
                $this->client = new Client($credentials);
            }
        }

        $this->from = config('vonage.sms_from', 'Koperasi');
    }

    /**
     * Send SMS message
     *
     * @param string $to
     * @param string $message
     * @return array
     */
    public function send($to, $message)
    {
        try {
            if ($this->sandbox) {
                Log::info('SMS (Sandbox): ' . $message . ' to ' . $to);
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully (sandbox mode)',
                    'message_id' => 'sandbox_' . uniqid()
                ];
            }

            if (!$this->client) {
                Log::error('Vonage client not configured');
                return [
                    'success' => false,
                    'message' => 'SMS service not configured'
                ];
            }

            // Format Indonesian phone numbers
            if (substr($to, 0, 1) === '0') {
                $to = '62' . substr($to, 1);
            }

            $sms = new SMS($to, $this->from, $message);
            $response = $this->client->sms()->send($sms);

            $messageResult = $response->current();

            if ($messageResult->getStatus() === '0') {
                Log::info('SMS sent successfully to ' . $to . ': ' . $messageResult->getMessageId());
                return [
                    'success' => true,
                    'message' => 'SMS sent successfully',
                    'message_id' => $messageResult->getMessageId()
                ];
            } else {
                Log::error('SMS failed to ' . $to . ': ' . $messageResult->getStatusText());
                return [
                    'success' => false,
                    'message' => 'SMS failed: ' . $messageResult->getStatusText()
                ];
            }
        } catch (\Exception $e) {
            Log::error('SMS Error: ' . $e->getMessage());
            return [
                'success' => false,
                'message' => 'SMS Error: ' . $e->getMessage()
            ];
        }
    }

    /**
     * Send welcome message to new member
     *
     * @param string $phone
     * @param string $memberName
     * @param string $memberNumber
     * @return array
     */
    public function sendWelcomeMessage($phone, $memberName, $memberNumber)
    {
        $message = "Selamat datang di Koperasi Syariah! Yth. {$memberName} (No. {$memberNumber}). Terima kasih telah bergabung. Silakan lengkapi dokumen dan mulai menabung untuk kemakmuran bersama.";

        return $this->send($phone, $message);
    }

    /**
     * Send financing application status update
     *
     * @param string $phone
     * @param string $memberName
     * @param string $applicationCode
     * @param string $status
     * @return array
     */
    public function sendFinancingStatusUpdate($phone, $memberName, $applicationCode, $status)
    {
        $statusText = '';
        switch ($status) {
            case 'approved':
                $statusText = 'disetujui dan siap dicairkan';
                break;
            case 'rejected':
                $statusText = 'ditolak';
                break;
            case 'cair':
                $statusText = 'telah dicairkan';
                break;
            default:
                $statusText = 'sedang diproses';
        }

        $message = "Koperasi Syariah - Yth. {$memberName}, pengajuan pembiayaan {$applicationCode} status: {$statusText}. Hubungi pengurus untuk info lebih lanjut.";

        return $this->send($phone, $message);
    }

    /**
     * Send installment reminder
     *
     * @param string $phone
     * @param string $memberName
     * @param string $installmentCode
     * @param string $dueDate
     * @param int $amount
     * @return array
     */
    public function sendInstallmentReminder($phone, $memberName, $installmentCode, $dueDate, $amount)
    {
        $formattedAmount = number_format($amount, 0, ',', '.');
        $message = "Koperasi Syariah - Pengingat Angsuran. Yth. {$memberName}, angsuran {$installmentCode} jatuh tempo tgl {$dueDate} sebesar Rp {$formattedAmount}. Bayar sebelum jatuh tempo.";

        return $this->send($phone, $message);
    }

    /**
     * Send payment confirmation
     *
     * @param string $phone
     * @param string $memberName
     * @param string $transactionType
     * @param int $amount
     * @param string $date
     * @return array
     */
    public function sendPaymentConfirmation($phone, $memberName, $transactionType, $amount, $date)
    {
        $formattedAmount = number_format($amount, 0, ',', '.');
        $message = "Koperasi Syariah - Terima kasih {$memberName}. Pembayaran {$transactionType} Rp {$formattedAmount} telah diterima tgl {$date}. Simap bukti transaksi Anda.";

        return $this->send($phone, $message);
    }

    /**
     * Send balance notification
     *
     * @param string $phone
     * @param string $memberName
     * @param int $balance
     * @param string $accountType
     * @return array
     */
    public function sendBalanceNotification($phone, $memberName, $balance, $accountType)
    {
        $formattedBalance = number_format($balance, 0, ',', '.');
        $message = "Koperasi Syariah - Info Saldo. Yth. {$memberName}, saldo {$accountType} Anda saat ini: Rp {$formattedBalance}. Terima kasih kepercayaan Anda.";

        return $this->send($phone, $message);
    }
}