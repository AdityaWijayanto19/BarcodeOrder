<?php
namespace App\Http\Controllers\Api;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Order;
use Illuminate\Support\Facades\Log;
use App\Events\OrderPaid;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceMail;

class MidtransController extends Controller
{
    public function webhook(Request $r)
    {
        $payload = $r->all();
        Log::info('midtrans webhook', $payload);

        // Verifikasi signature_key (Midtrans)
        $serverKey = config('services.midtrans.server_key') ?? env('MIDTRANS_SERVER_KEY');
        $orderId = $payload['order_id'] ?? null;
        $statusCode = $payload['status_code'] ?? null;
        $grossAmount = $payload['gross_amount'] ?? null;
        $signatureKey = $payload['signature_key'] ?? null;

        if ($orderId && $statusCode && $grossAmount && $signatureKey) {
            $input = $orderId.$statusCode.$grossAmount.$serverKey;
            $computed = hash('sha512', $input);
            if ($computed !== $signatureKey) {
                Log::warning('midtrans signature invalid', ['computed'=>$computed,'provided'=>$signatureKey]);
                return response('invalid signature', 403);
            }
        }

        // Temukan order
        $order = Order::where('invoice', $payload['order_id'])->first();
        if (!$order) {
            return response('order not found', 404);
        }

        $transactionStatus = $payload['transaction_status'] ?? null;

        if (in_array($transactionStatus, ['capture','settlement'])) {
            $order->payment_status = 'paid';
            $order->midtrans_transaction_id = $payload['transaction_id'] ?? null;
            $order->save();

            // Kirim email invoice (sync atau dispatch job)
            if ($order->customer_email) {
                try {
                    Mail::to($order->customer_email)->send(new \App\Mail\InvoiceMail($order));
                } catch (\Exception $e) {
                    Log::error('Mail send error: '.$e->getMessage());
                }
            }

            // Broadcast event ke dapur
            event(new OrderPaid($order));
        } elseif (in_array($transactionStatus, ['deny','cancel','expire','failure'])) {
            $order->payment_status = 'failed';
            $order->save();
        } else {
            // pending -> biarkan
        }

        return response('ok', 200);
    }
}
