<?php


namespace app\common\pw;


class Pw
{
    static function encryption($data)
    {
        $options = [
            'cost' => 12,
        ];

        return password_hash($data, PASSWORD_BCRYPT, $options);
    }
}
