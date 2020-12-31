<?php

namespace App\Events;

use App\FollowUp;
use Illuminate\Broadcasting\Channel;
use Illuminate\Broadcasting\InteractsWithSockets;
use Illuminate\Broadcasting\PresenceChannel;
use Illuminate\Broadcasting\PrivateChannel;
use Illuminate\Contracts\Broadcasting\ShouldBroadcast;
use Illuminate\Foundation\Events\Dispatchable;
use Illuminate\Queue\SerializesModels;

class FollowupEvent
{
    use Dispatchable, InteractsWithSockets, SerializesModels;

    protected $inquiryID;
    protected $quotationID;
    protected $piID;
    protected $onlineInquiryID;
    protected $customerInquiryID;
    protected $remark;
    protected $createdID;
    protected $nextFollowUpDate;

    /**
     * Create a new event instance.
     *
     * @return void
     */
    public function __construct($inquiry_id = null, $quotation_id = null, $pi_id = null, $o_id=null, $c_i_id=null, $remark = null, $created_id = 0, $nextFollowUpDate = null)
    {
        $this->inquiryID = $inquiry_id;
        $this->quotationID = $quotation_id;
        $this->piID = $pi_id;
        $this->onlineInquiryID = $o_id;
        $this->customerInquiryID = $c_i_id;
        $this->remark = $remark;
        $this->createdID = $created_id;
        $this->nextFollowUpDate = $nextFollowUpDate;
        /** Create new FollowUp  */
        $followUp = new FollowUp();
        $followUp->inquiry_id = $this->inquiryID;
        $followUp->quotation_id = $this->quotationID;
        $followUp->pi_id = $this->piID;
        $followUp->o_id = $this->onlineInquiryID;
        $followUp->c_i_id = $this->customerInquiryID;
        $followUp->remark = $this->remark;
        $followUp->next_followup_date = $this->nextFollowUpDate;
        $followUp->created_id = $this->createdID;
        $followUp->save();
    }

}
