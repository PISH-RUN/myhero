<?php

namespace App\Http\Controllers;

use App\Models\TelegramUser;
use App\Utils\Mobile;
//use App\Utils\Telegram;
use Illuminate\Http\Request;
use Telegram\Bot\FileUpload\InputFile;
use Telegram\Bot\Laravel\Facades\Telegram;


class ShareVideoHookController extends Controller
{
    public function __invoke(Request $request)
    {
        $request->validate([
            'video' => 'required',
            'phone' => 'required'
        ]);

        $url = $request->get('video');
        $phone = $request->get('phone');

        /** @var TelegramUser|null $telegramUser */
        $telegramUser = TelegramUser::where('phone_number', Mobile::international($phone))->first();

        if (is_null($telegramUser)) {
            return response()->json([
                'message' => 'there is no user with provided phone',
                'phone' => $phone
            ]);
        }


        $response = Telegram::sendVideo([
            'chat_id' => $telegramUser->tid,
            'video' => InputFile::create($url, 'vid.mp4')
        ]);

        return response()->json($response);
    }
}
