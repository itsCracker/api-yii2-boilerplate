<?php

namespace components;

use AfricasTalking\SDK\AfricasTalking;

class Sms
{
    const apiKey   = 'b698805ecd0bab21b89e877aef89fa0464aafeb210e755d1d78f51eed1b2fd1c';
    const username = 'TUM';
    const sender   = 'TUM';

    public static function sendOtp($data)
    {
        return self::gateway($data['to'], $data['msg']);
    }    
    protected static function gateway($receiver = [], $message = '')
    {
        $gateway  = new AfricasTalking(self::username, self::apiKey);
        return $gateway->sms()->send([
            'to'      => $receiver,
            'message' => $message,
            'from'    => self::sender,
            'enqueue' => true
        ]);
    }
}

