<?php

namespace App\Jobs;

use Log;
use GoogleCloudPrint;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class PrintInvoice implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order)
    {
        $this->order = $order;
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        Log::info('PrintInvoice::handle ran');
        Log::info('Order: ' . $this->order);

        $printer_id = Setting::get('printer_id');

        Log::info('printer id: ' . $printer_id);

        if ($printer_id) 
        {
            // to test
            GoogleCloudPrint::asHtml()
                ->url('https://opensource.org/licenses/MIT')
                ->printer($printerId)
                ->send();

            $invoice_url = route('orders.invoice', ['id' => $this->order->id]);

            GoogleCloudPrint::asHtml()
                ->url($invoice_url)
                ->printer($printerId)
                ->send();
        }
    }
}
