// File: QuestionAnsweringApi.php

namespace App\Services;

use GuzzleHttp\Client;

class QuestionAnsweringApi
{
    private $httpClient;
    private $apiUrl;

    public function __construct()
    {
        $this->httpClient = new Client();
        $this->apiUrl = 'http://127.0.0.1:8000/api/jawab-pertanyaan';
    }

    public function getAnswer($pertanyaan)
    {
        $response = $this->httpClient->post($this->apiUrl, [
            'json' => [
                'pertanyaan' => $pertanyaan
            ]
        ]);

        $responseData = json_decode($response->getBody(), true);

        if ($responseData['status'] == 'success') {
            return $responseData['jawaban'];
        } else {
            return null;
        }
    }
}