<?php

namespace App\Http\Controllers\Back;

use App\Http\Controllers\Controller;
use app\Telegram\Commands\StartCommand;
use Illuminate\Http\Request;
use Nutgram\Laravel\Facades\Telegram as FacadesTelegram;
use SergiX44\Nutgram\Configuration;
use SergiX44\Nutgram\Nutgram;
use SergiX44\Nutgram\RunningMode\Polling;
use SergiX44\Nutgram\RunningMode\Webhook;
use Telegram\Bot\Api;
use Telegram\Bot\Laravel\Facades\Telegram;


use Illuminate\Support\Facades\Http;


class TelegramController extends Controller
{

    public function getBotInformation()
    {
        $result = Http::get("https://api.telegram.org/bot7206508127:AAHxhEd5eIu1_7Ef3ZkOfqcpmL4dWW8PwKk/getMe");

        return response()->json(['type' => true , 'message' => 'get bot information successfully' , 'information' => json_decode($result)]);
    }


    public function sendMessage(Request $request)
    {
        $result = Http::get("https://api.telegram.org/bot7206508127:AAHxhEd5eIu1_7Ef3ZkOfqcpmL4dWW8PwKk/sendMessage?chat_id=97025321&text= " . $request->message);

        if($result){
            return response()->json(['type' => true , 'message' => 'sendMessage Successfull' , 'result' => json_decode($result)]);
        } else {
            return response()->json(['type' => false , 'message' => 'sendMessage unSuccessfull']);
        }

    }

    // public function handle(Nutgram $bot)
    // {
    //     $config = new Configuration(
    //         clientTimeout: 10, // default in seconds, when contacting the Telegram API
    //         isLocal : true,
    //     );

    //     $bot = new Nutgram(config('telegram.bots.mybot.token'), $config);
    // }

    // public function __construct()
    // {

    //     $config = new Configuration(
    //         clientTimeout: 10, // default in seconds, when contacting the Telegram API
    //         isLocal : true,
    //     );
    //     $bot = new Nutgram(env('TELEGRAM_TOKEN'), $config);
    //     return $bot;
    // }

    // public function __invoke(Nutgram $bot)
    // {
    //     $bot->run();
    // }
// 
    // public function handle()
    // {
    //     $bot = new Nutgram(env('TELEGRAM_TOKEN'));
// 
    //     // تعریف کال‌بک‌ها و میان‌افزارها
// 
    //     $bot->run();
    // }
// 
    // public function show(Nutgram $bot)
    // {
    //     $bot->setRunningMode(Polling::class);
    //     $updates = $bot->getUpdates();
    //     foreach ($updates as $update)
    //     {
    //         dd($update->message->text);
    //     }
    //     dd($updates);
    // }
// 

}
