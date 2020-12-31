<?php

namespace App\Notifications;

use App\Inquiry;
use App\Pi;
use App\Purchase;
use App\PurchaseOrder;
use App\Quotation;
use App\Sales;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Messages\MailMessage;
use Illuminate\Notifications\Notification;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;
use NotificationChannels\Telegram\TelegramChannel;
use NotificationChannels\Telegram\TelegramMessage;
use NotificationChannels\Telegram\TelegramFile;

class TelegramNotification extends Notification
{
    use Queueable;

    /**
     * Create a new notification instance.
     *
     * @return void
     */
    public function __construct($inquery_id, $urlType,$file_name = '')
    {
        $this->inquery_id = $inquery_id;
        $this->urlType = $urlType;
        $this->file_name = $file_name;
    }

    /**
     * Get the notification's delivery channels.
     *
     * @param mixed $notifiable
     * @return array
     */
    public function via($notifiable)
    {
        return [TelegramChannel::class];
    }

    public function toTelegram($notifiable)
    {

        $userInfo = DB::table('users')->where('id', Auth::user()->id)->first();
        $telegramID = $userInfo->telegram_id;

        if ($this->urlType == 'Inquiry') {
            $inqueryDetail = Inquiry::with('customer')->with('inquiryproduct')->where('inquiry_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->phone_no) ? $inqueryDetail[0]->phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->notes) ? $inqueryDetail[0]->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->inquiryproduct as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "inquiry No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramMessage::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);

        } elseif ($this->urlType == 'Quotation') {
            $inqueryDetail = Quotation::with('customer')->with('quotationproduct')->with('getPayment')->where('quotation_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->phone_no) ? $inqueryDetail[0]->phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->getPayment->notes) ? $inqueryDetail[0]->getPayment->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->quotationproduct as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "Quotation No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramFile::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                ->document('./telegram/'.$this->file_name,$this->inquery_id.'.pdf')
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);

        }elseif ($this->urlType == 'PI') {
            $inqueryDetail = Pi::with('customer')->with('piitem')->where('pi_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->customer->f_phone_no) ? $inqueryDetail[0]->customer->f_phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->notes) ? $inqueryDetail[0]->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->piitem as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "PI No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramFile::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                ->document('./telegram/'.$this->file_name,'PI.pdf')
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);

        }elseif ($this->urlType == 'Sales') {
            $inqueryDetail = Sales::with('customer')->with('salesitems')->where('invoice_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->customer->f_phone_no) ? $inqueryDetail[0]->customer->f_phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->notes) ? $inqueryDetail[0]->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->salesitems as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "Invoice No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramFile::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                ->document('./telegram/'.$this->file_name,'sales.pdf')
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);
        }elseif ($this->urlType == 'PO') {
            $inqueryDetail = PurchaseOrder::with('customer')->with('getpoproduct')->where('po_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->customer->f_phone_no) ? $inqueryDetail[0]->customer->f_phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->notes) ? $inqueryDetail[0]->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->getpoproduct as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "PO No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramMessage::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);

        }elseif ($this->urlType == 'Purchase') {
            $inqueryDetail = Purchase::with('customer')->with('purchaseproduct')->where('purchase_id', $this->inquery_id)->get();
            $customer_name = isset($inqueryDetail[0]->customer->customer_name) ? $inqueryDetail[0]->customer->customer_name : '';
            $email = isset($inqueryDetail[0]->customer->email) ? $inqueryDetail[0]->customer->email : '';
            $mobile = isset($inqueryDetail[0]->customer->f_phone_no) ? $inqueryDetail[0]->customer->f_phone_no : '';
            if (strlen($mobile) == 10) {
                $mobile = "91" . $mobile;
            }
            $note = isset($inqueryDetail[0]->notes) ? $inqueryDetail[0]->notes : '';
            $product_detail = '';

            foreach ($inqueryDetail[0]->purchaseproduct as $product) {
                $product_name = isset($product->getItemName->name) ? $product->getItemName->name : '';
                $qty = isset($product->qty) ? $product->qty : '';
                $rate = isset($product->rate) ? $product->rate : '';
                $product_detail .= "\n*Product* : " . $product_name . "\n*Qty* : " . $qty . "\n*Price* : " . $rate;
            }
            $product_detail .= "\n*Note* : " . $note;
            $message = "Purchase No :" . $this->inquery_id . "\n*Name* : " . $customer_name . "\n*Mobile* : " . $mobile . "\n*Email* : " . $email . $product_detail;
            $url = "https://api.whatsapp.com/send?phone=" . $mobile . "&text=" . $message;
            return TelegramMessage::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('SEND TO WHATSAPP', $url);

        }elseif ($this->urlType == 'link') {

            $message = "Customer Inquiry:" .  url('')."/customer-inquiry/create";
            $url = url('')."/customer-inquiry/create";
            return TelegramMessage::create()
                // Optional recipient user id.
                ->to($telegramID)
                // Markdown supported.
                ->content($message)
                // (Optional) Blade template for the content.
                // ->view('notification', ['url' => $url])
                // (Optional) Inline Buttons
                ->button('Click to here', $url);
        }
    }
}
