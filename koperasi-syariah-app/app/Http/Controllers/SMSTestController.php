<?php

namespace App\Http\Controllers;

use App\Services\SMSService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;

class SMSTestController extends Controller
{
    protected $smsService;

    public function __construct(SMSService $smsService)
    {
        $this->smsService = $smsService;
    }

    /**
     * Show SMS test form
     */
    public function index()
    {
        return view('test.sms');
    }

    /**
     * Send test SMS
     */
    public function sendTestSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'message' => 'required|string',
        ]);

        $phone = $request->phone;
        $message = $request->message;

        $result = $this->smsService->send($phone, $message);

        if ($result['success']) {
            return back()->with('success', 'SMS berhasil dikirim! Message ID: ' . $result['message_id']);
        } else {
            return back()->with('error', 'SMS gagal dikirim: ' . $result['message']);
        }
    }

    /**
     * Test welcome SMS
     */
    public function testWelcomeSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'member_name' => 'required|string',
            'member_number' => 'required|string',
        ]);

        $result = $this->smsService->sendWelcomeMessage(
            $request->phone,
            $request->member_name,
            $request->member_number
        );

        if ($result['success']) {
            return back()->with('success', 'Welcome SMS berhasil dikirim!');
        } else {
            return back()->with('error', 'Welcome SMS gagal dikirim: ' . $result['message']);
        }
    }

    /**
     * Test financing status SMS
     */
    public function testFinancingStatusSMS(Request $request)
    {
        $request->validate([
            'phone' => 'required|string',
            'member_name' => 'required|string',
            'application_code' => 'required|string',
            'status' => 'required|in:approved,rejected,cair,pending',
        ]);

        $result = $this->smsService->sendFinancingStatusUpdate(
            $request->phone,
            $request->member_name,
            $request->application_code,
            $request->status
        );

        if ($result['success']) {
            return back()->with('success', 'Financing status SMS berhasil dikirim!');
        } else {
            return back()->with('error', 'Financing status SMS gagal dikirim: ' . $result['message']);
        }
    }

    /**
     * Check SMS configuration
     */
    public function checkConfiguration()
    {
        $config = [
            'api_key' => config('vonage.api_key') ? '✓ Configured' : '✗ Not configured',
            'api_secret' => config('vonage.api_secret') ? '✓ Configured' : '✗ Not configured',
            'sms_from' => config('vonage.sms_from', 'Default: Koperasi'),
            'sandbox' => config('vonage.sandbox', false) ? '✓ Sandbox mode' : '✗ Production mode',
        ];

        return view('test.sms-config', compact('config'));
    }
}