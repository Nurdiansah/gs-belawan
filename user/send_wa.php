<?php

    $data = [
            'phone' => '628981763889', //Receivers phone
            'body' => 'Test Send', //Message
        ];
        $json = json_encode($data);
        // url
        $url = 'https://eu74.chat-api.com/instance#98989/message?token=8jkocbj3y8p6uhbt';

        $options = stream_context_create(['http' => [
            'method' => 'POST',
            'header' => 'Content-type :application/json',
            'content' => $json
        ]
        ]);
        // Send a request
        $result = file_get_contents($url, false, $options);

// $data = [
//             'phone' => '628981763889', //Receivers phone
//             'body' => 'Test Send', //Message
//         ];
//         $json = json_encode($data);
//         // url
//         $url = 'https://eu74.chat-api.com/instance98989/message?token=8jkocbj3y8p6uhbt';

//         $options = stream_context_create(['http' => [
//             'method' => 'POST',
//             'header' => 'Content-type :application/json',
//             'content' => $json
//         ]
//         ]);
//         // Send a request
// 		$result = file_get_contents($url, false, $options);