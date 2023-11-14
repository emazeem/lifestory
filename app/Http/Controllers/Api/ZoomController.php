<?php

namespace App\Http\Controllers\Api;

use GuzzleHttp\Client;
use App\Models\Setting;
use Illuminate\Http\Request;
use App\Traits\ZoomOAuthTrait;
use App\Http\Controllers\Controller;

class ZoomController extends Controller
{
    use ZoomOAuthTrait;
    public function callback($code)
    {
        try {
            $client = new Client(['base_uri' => 'https://zoom.us']);

            $response = $client->request('POST', '/oauth/token', [
                "headers" => [
                    "Authorization" => "Basic " . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
                ],
                'form_params' => [
                    "grant_type"   => "authorization_code",
                    "code"         => $code,
                    "redirect_uri" => getZoomRedirectUri(),
                ],
            ]);

            $full_token = json_decode($response->getBody()->getContents(), true);
            $access_token = $full_token['access_token'];
            $refresh_token = $full_token['refresh_token'];

            $this->update_access_token($access_token,$refresh_token,$full_token);

            $zoomSetting = Setting::where("key", "zoom-access-authorized")->first();
            if($zoomSetting) $zoomSetting->update(["value",1]);
            else Setting::create(["key" => "zoom-access-authorized","value"=>1]);

            return response()->json([
                "success" => true,
                "message" => "Zoom authorize successful."
            ]);
            
        } catch (\Exception $e) {
            return response()->json([
                "success" => false,
                "message" => $e->getMessage()
            ]);
        }
    }
}
