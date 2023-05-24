<?php

declare(strict_types=1);

namespace Tests\Unit;

use MyApp\Controllers\UserController;
use MyApp\Models\Users;

class IndexControllerTest extends AbstractUnitTest
//class UnitTest extends \PHPUnit\Framework\TestCase
{
    public function testUsers()
    {
        $user = new UserController();
        $this->assertEquals(true, $user->validateEmail("ayushgupta@cedcoss.com"));
        $this->assertEquals(true, $user->validateEmail("ayushgupta@gmail.com"));
        $this->assertEquals(false, $user->validateEmail("ayushguptacedcoss.com"));
        $this->assertEquals(false, $user->validateEmail("ayushgupta@cedcosscom"));
        $this->assertEquals(true, $user->checkPassword("Ayush@1234"));
        $this->assertEquals(false, $user->checkPassword("ayush@1234"));
        $this->assertEquals(false, $user->checkPassword("ayush1234"));
        $this->assertEquals(false, $user->checkPassword("ayush@1"));
        $this->assertEquals(false, $user->checkPassword("asjfbfbjrhbf1234"));
        $this->assertEquals(false, $user->checkPassword("12154551214521234"));
        $this->assertEquals(false, $user->checkPassword("ayush@12"));
        $this->assertEquals(false, $user->checkPassword("ayushret5tgrhg"));
        $this->assertEquals(false, $user->checkPassword("ayush@12345"));
        $this->assertEquals(true, $user->checkPassword("ayushGupta@#&12345"));
        $this->assertEquals(false, $user->isEmailExist("ayushgupta1@cedcoss.com"));


        $data = new Users();
        $data->name = "Ayush";
        $data->email = "ayushgupta1@cedcoss.com";
        $data->pswd = "Ayush@1234";
        $this->assertTrue($user->validateEmail($data->email));
        $this->assertTrue($user->checkPassword($data->pswd));
        $this->assertFalse($user->isEmailExist($data->email));
        $result = $data->save();
        $this->assertTrue($result, "pass");
    }
}
