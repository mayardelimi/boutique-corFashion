<?php

namespace App\Class;

use Mailjet\Client;
use Mailjet\Resources;

class Mail
{
    public function send($to_email, $to_name, $subject, $vars = null, $template)
    {

        $templatePath = dirname(__DIR__) . DIRECTORY_SEPARATOR . 'Mail' . DIRECTORY_SEPARATOR . $template;

        if (!file_exists($templatePath)) {
            throw new \Exception("Template file not found at: " . $templatePath);
        }

        $content = file_get_contents($templatePath);

        if ($vars && is_array($vars)) {
            foreach ($vars as $key => $var) {
                $content = str_replace('{' . $key . '}', $var, $content);
            }
        }

        $apikey = $_ENV['MAILGUN_KEY'];
        $apisecret = $_ENV['MAILGUN_SECRET'];

        $mj = new Client($apikey, $apisecret, true, ['version' => 'v3.1']);

        $body = [
            'Messages' => [
                [
                    'From' => [
                        'Email' => "dlmmayar@gmail.com",
                        'Name' => "Me"
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
                    'Variables' => [
                        'content' => $content,
                    ]
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
