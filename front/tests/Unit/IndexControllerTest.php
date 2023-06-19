<?php

declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\IndexController;
use MyApp\Models\Users;

class IndexControllerTest extends AbstractUnitTest
//class UnitTest extends \PHPUnit\Framework\TestCase
{
    public function testUsers()
    {
        $data = array(
            'name' => "Ayush",
            'email' => "a@a.com",
            'pswd' => "1",
            'pincode' => "1234",
            'app_key' => "Ayush",
            'secret_key' => "1"
        );
        // set post fields
        $ch = curl_init();
        $url = 'http://172.18.0.6/adduser';
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        // execute!
        $res = json_decode(curl_exec($ch));
        $this->assertEquals(1, $res);
    }
}
