<?php

namespace App\Jobs;

use App\Models\Order;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Http;

class GetCustomers implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $tries = 5;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $results=[];
		$response = Http::post('http://bijanapi.ir/Send/GetCustomers?LastCustomer=0&Token=ytwmD2KqxiEMEWajB%2f4zll8xUAe1jRyMtbNP41HCKaE%3d');
		$lastCustomer=collect($response->json()['Customers'])->last()['Code'];
		$results[]=$response->json();
		foreach(range(1,30) as $i){
			$response = Http::post('http://bijanapi.ir/Send/GetCustomers?LastCustomer='.$lastCustomer.'&Token=ytwmD2KqxiEMEWajB%2f4zll8xUAe1jRyMtbNP41HCKaE%3d');
			$lastCustomer=collect($response->json()['Customers'])->last()['Code'];
			$results[]=$response->json();
		}
		return $results;
    }
}
