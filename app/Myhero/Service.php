<?php


namespace App\Myhero;


use App\Utils\Mobile;
use Illuminate\Http\Client\PendingRequest;
use Illuminate\Support\Facades\Http;

class Service
{
    public string $url;

    public string $apiKey;

    public PendingRequest $client;

    public function __construct()
    {
        $this->apiKey = config('myhero.api-key');
        $this->url = config('myhero.url');
        $this->client = Http::baseUrl($this->url)->withHeaders([
            'Content-Type' => 'application/json',
            'Accept' => 'application/json'
        ]);
    }

    public function getRecommends(string $phone)
    {
        $response = $this->client->post('/main/result/telegram_recommended_contents/', [
            'api_key' => $this->apiKey,
            'phone' => Mobile::local($phone)
        ]);

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

    public function postSharePicture(string $phone, string $url)
    {
        $response = $this->client->post('/main/result/telegram-request-video-image/', [
            'api_key' => $this->apiKey,
            'phone' => Mobile::local($phone),
            'image' => $url
        ]);

        dump($response->json());
        dump($response->status());

        if ($response->successful()) {
            return $response->json();
        }

        return null;
    }

}
