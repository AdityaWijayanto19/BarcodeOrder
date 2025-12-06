<?php
namespace App\Mail;
use Illuminate\Bus\Queueable;
use Illuminate\Mail\Mailable;
use Illuminate\Queue\SerializesModels;
use App\Models\Order;

class InvoiceMail extends Mailable
{
    use Queueable, SerializesModels;
    public $order;
    public function __construct(Order $order)
    {
        $this->order = $order->load('items.menu','table');
    }
    public function build()
    {
        return $this->subject("Invoice Pesanan {$this->order->invoice}")
                    ->view('emails.invoice')
                    ->with(['order' => $this->order]);
    }
}
