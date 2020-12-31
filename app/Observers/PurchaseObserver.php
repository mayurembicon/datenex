<?php

namespace App\Observers;

use App\Events\PurchaseReturnRemoveEvent;
use App\Events\PurchaseStockAddEvent;
use App\Events\PurchaseStockRemoveEvent;
use App\PurchaseProduct;

class PurchaseObserver
{
    /**
     * Handle the purchase product "created" event.
     *
     * @param \App\PurchaseProduct $purchaseProduct
     * @return void
     */
    public function created(PurchaseProduct $purchaseProduct)
    {
    }


    public function updated(PurchaseProduct $purchaseProduct)
    {


    }

    /**
     * Handle the purchase product "deleted" event.
     *
     * @param \App\PurchaseProduct $purchaseProduct
     * @return void
     */
    public function deleted(PurchaseProduct $purchaseProduct)
    {

    }

    /**
     * Handle the purchase product "restored" event.
     *
     * @param \App\PurchaseProduct $purchaseProduct
     * @return void
     */
    public function restored(PurchaseProduct $purchaseProduct)
    {
        //
    }

    /**
     * Handle the purchase product "force deleted" event.
     *
     * @param \App\PurchaseProduct $purchaseProduct
     * @return void
     */
    public function forceDeleted(PurchaseProduct $purchaseProduct)
    {
        //
    }
}
