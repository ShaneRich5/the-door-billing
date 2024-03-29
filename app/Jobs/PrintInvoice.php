<?php

namespace App\Jobs;

use Log;
use Setting;
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
    protected $printer_id;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Order $order, $printer_id)
    {
        $this->order = $order;
        $this->printer_id = $printer_id;
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

        Log::info('printer id: ' . $this->printer_id);

        if ($this->printer_id)
        {
            $invoice_url = route('orders.invoice', ['id' => $this->order->id]);

            GoogleCloudPrint::asPdf()
                ->url($invoice_url)
                ->printer($this->printer_id)
                ->send();

            Log::info('printed successfully');
        } else {
            Log::info('failed to print');
        }
    }
}
