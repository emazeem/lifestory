<?php

namespace App\Http\Controllers;

use Illuminate\Console\View\Components\Alert;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;

class AccessTokenController extends Controller
{
    public function generate(Request $request)
    {
        $response = Http::withHeaders([
            'api-token' => 'JQk2SUfVdhbxd3yPCKYagO4hFDMw63q-8S53Ws0j-PfjR5tVsbgRwMmmZsG6Y9pDuaI',
            'user-email' => 'rana.sharjeel.ali@napollo.net',
        ])->get('https://www.universal-tutorial.com/api/getaccesstoken');

        return response()->json($response->json());
    }
    public function getStates(Request $request)
    {
        try {
            $url = "https://www.universal-tutorial.com/api/states/".$request->country;
            $curl = curl_init();
            curl_setopt($curl, CURLOPT_URL, $url);
            curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);
            $token=$request->authToken;
            $headers = [
                'Authorization: Bearer '.$token,
                'Accept: application/json'
            ];
            
            curl_setopt($curl, CURLOPT_HTTPHEADER, $headers);
            $response = curl_exec($curl);

            return response()->json([
                "success" => true,
                "data" => $response
            ]);
                
        } catch (\Exception $e) {
            // return response()->json(['error' => $e->getMessage()], 500);
            return response()->json(['success' => false, 'message' => $e->getMessage() ]);
        }
    }

}
