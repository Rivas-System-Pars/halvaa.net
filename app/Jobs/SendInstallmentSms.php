<?php

namespace App\Jobs;

use App\Models\OrderInstallmentItem;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Morilog\Jalali\Jalalian;

class SendInstallmentSms implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $orderInstallmentItem;
	
	public $sms_code;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($orderInstallmentItem,$sms_code)
    {
        $this->orderInstallmentItem=$orderInstallmentItem;
		$this->sms_code=$sms_code;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $item = OrderInstallmentItem::where('status','unpaid')->find($this->orderInstallmentItem);
		$user = $item->orderInstallment->order->user;
		if($item && ($user && preg_match('/^(?:98|\+98|0098|0)?9[0-9]{9}$/',$user->username))){
			$data=[];
			if($this->sms_code == 213977){
				$data = [
					'0'=>$user->full_name,
					'1'=>Jalalian::fromCarbon($item->date)->format('%A, %d %B %y'),
				];
			}elseif($this->sms_code == 213980){
				$data = [
					'0'=>$user->full_name,
					'1'=>Jalalian::fromCarbon($item->date)->format('%A, %d %B %y'),
					'2'=>number_format($item->amount)." تومان",
				];
			}elseif($this->sms_code == 213986){
				$data = [
					'0'=>$user->full_name,
					'1'=>Jalalian::fromCarbon($item->date)->format('%A, %d %B %y'),
					'2'=>number_format($item->amount)." تومان",
					'3'=>option('installment_after_day'),
				];
			}
			$user->notify(new InstallmentNotification($this->sms_code,$data));
		}
    }
}
