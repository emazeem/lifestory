<?php
namespace App\Traits;

use App\Models\ZoomOauth;
use GuzzleHttp\Client;

trait ZoomOAuthTrait
{
    public function is_table_empty()
    {
        $result = ZoomOauth::where('provider', 'zoom')->exists();

        return !$result;
    }

    public function get_access_token()
    {
        $zoomOauth = ZoomOauth::where('provider', 'zoom')->first();

        if ($zoomOauth) {
            return $zoomOauth->access_token;
        }

        return null;
    }

    public function get_refresh_token()
    {
        $zoomOauth = ZoomOauth::where('provider', 'zoom')->first();

        if ($zoomOauth) {
            return $zoomOauth->refresh_token;
        }

        return null;
    }

    public function update_access_token($access_token, $refresh_token, $token)
    {
        if ($this->is_table_empty()) {
            ZoomOauth::create(['provider' => 'zoom', 'access_token' => $access_token, 'refresh_token' => $refresh_token, 'full_token' => $token]);
        } else {
            $zoomRecord = ZoomOauth::where('provider', 'zoom')->first();
            $zoomRecord->update(['access_token' => $access_token, 'refresh_token' => $refresh_token, 'full_token' => $token]);
        }
    }

    public function update_token()
    {
        $refresh_token = $this->get_refresh_token();

        $client = new Client(['base_uri' => 'https://zoom.us']);
        $response = $client->request('POST', '/oauth/token', [
            "headers" => [
                "Authorization" => "Basic " . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
            ],
            'form_params' => [
                "grant_type" => "refresh_token",
                "refresh_token" => $refresh_token,
            ],
        ]);

        $full_token = json_decode($response->getBody()->getContents(), true);
        $access_token = $full_token['access_token'];
        $refresh_token = $full_token['refresh_token'];

        $this->update_access_token($access_token, $refresh_token, $full_token);

        return $response;
    }

    public function createZoomMeeting(array $meetingData)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);

        $accessToken = $this->get_access_token();

        try {
            $response = $client->request('POST', '/v2/users/me/meetings', [
                "headers" => [
                    "Authorization" => "Bearer $accessToken",
                ],
                'json' => [
                    "topic" => $meetingData['topic'],
                    "type" => $meetingData['type'],
                    "start_time" => $meetingData['start_time'],
                    "duration" => $meetingData['duration'],
                    "password" => $meetingData['password'],
                    'agenda' => (!empty($meetingData['agenda'])) ? $meetingData['agenda'] : null,
                    'timezone' => env('TIMEZONE'),
                    'settings' => [
                        'host_video' => ($meetingData['host_video'] == "1") ? true : false,
                        'participant_video' => ($meetingData['participant_video'] == "1") ? true : false,
                        'waiting_room' => true,
                    ],
                ],
            ]);

            return json_decode($response->getBody());

        } catch (\Exception $e) {
            if (401 == $e->getCode()) {

                $refresh_token = $this->get_refresh_token();

                $client = new Client(['base_uri' => 'https://zoom.us']);
                $response = $client->request('POST', '/oauth/token', [
                    "headers" => [
                        "Authorization" => "Basic " . base64_encode(env('ZOOM_CLIENT_ID') . ':' . env('ZOOM_CLIENT_SECRET')),
                    ],
                    'form_params' => [
                        "grant_type" => "refresh_token",
                        "refresh_token" => $refresh_token,
                    ],
                ]);

                $full_token    = json_decode($response->getBody()->getContents(), true);
                $access_token  = $full_token['access_token'];
                $refresh_token = $full_token['refresh_token'];

                $this->update_access_token($access_token, $refresh_token, $full_token);

                return $this->createZoomMeeting($meetingData);
            } else {
                return $e->getMessage();
            }
        }
    }

    public function delete_meeting($id)
    {
        $client = new Client(['base_uri' => 'https://api.zoom.us']);
 
        $accessToken = $this->get_access_token();
        
        $response = $client->request('DELETE', '/v2/meetings/'.$id, [
            "headers" => [
                "Authorization" => "Bearer $accessToken"
            ]
        ]);

        if (204 == $response->getStatusCode()) {
            return true;
        } else false;
    }

}
