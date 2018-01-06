<?php

namespace App\Jobs;

use PDF;
use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Queue\SerializesModels;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;

class GenerateInvoice implements ShouldQueue
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
        $user = $order->user;
        $menu_items = $order->menuItems;
        $billing_address = $order->billing;
        $delivery = $order->delivery;
        $delivery_location = $delivery->location;
        $delivery_address = $delivery_location->address;

        $data = [
            'menu_items' => $menu_items,
            'user' => $user,
            'order' => $order,
            'billing_address' => $billing_address,
            'delivery' => $delivery,
            'delivery_address' => $delivery_address,
            'delivery_location' => $delivery_location
        ];

        return PDF::loadView('pdf.invoice', $data)
            ->save('invoices/' . $order->id . '.pdf')
            ->stream($order->id . '.pdf');
    }
}
