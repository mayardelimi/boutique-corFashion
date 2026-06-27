<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $vars = [])
    {



        $apikey = $_ENV['MAILGUN_KEY'];
        $apisecret = $_ENV['MAILGUN_SECRET'];

        if (!$apikey || !$apisecret) {
            throw new \Exception("Mailjet API configuration keys are missing in your .env file.");
        }


        $mj = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "mayardelimi@gmail.com",
                        'Name' => "La Boutique Française"
                    ],
                    'To' => [
                        [
                            'Email' => $to_email,
                            'Name' =>  $to_name
                        ]
                    ],
                    'TemplateID' => 8129540,
                    'TemplateLanguage' => true,
                    'Subject' => $subject,
                    'Variables' => $vars
                ]
            ]
        ];

        $response = $mj->post(Resources::$Email, ['body' => $body]);

        if ($response->success()) {
            var_dump($response->getData());
        } else {
            var_dump($response->getStatus());
        }
    }
}
