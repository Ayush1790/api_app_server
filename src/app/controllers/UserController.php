<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use MyApp\Models\Users;

class UserController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function validateEmail($email)
    {
        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return false;
        }
        return true;
    }

    public function checkPassword($pswd)
    {
        $number = preg_match('@[0-9]@', $pswd);
        $uppercase = preg_match('@[A-Z]@', $pswd);
        $lowercase = preg_match('@[a-z]@', $pswd);
        $specialChars = preg_match('@[^\w]@', $pswd);

        if (strlen($pswd) < 8 || !$number || !$uppercase || !$lowercase || !$specialChars) {
            return false;
        }
        return true;
    }

    public function isEmailExist($email)
    {
        $user = $this->db->fetchAll(
            "SELECT * FROM `users` where `email`='$email' ",
            \Phalcon\Db\Enum::FETCH_ASSOC
        );
        if (empty($user)) {
            return false;
        }
        return true;
    }

    public function submitAction()
    {
        $name = $this->request->get('name');
        $email = $this->request->get('email');
        $pswd = $this->request->get('pswd');
        $user = new UserController();
        if ($name == '') {
            echo "Name Can not be empty";
            die;
        }
        if (!$user->validateEmail($email)) {
            echo "Please Enter valid Email";
            die;
        }
        if ($user->isEmailExist($email)) {
            echo "This Email is already exist, Please Enter Unique Email";
            die;
        }
        if (!$user->checkPassword($pswd)) {
            echo "This Password is too weak, Please Enter strong Password";
            die;
        }
        $data = new Users();
        $data->name = $name;
        $data->email = $email;
        $data->pswd = $pswd;
        $result = $data->save();
        if ($result) {
            echo "User Added Succesfully.";
            echo "<br><a href='../index/index' class='btn btn-outline-warning'>Back</a>";
        } else {
            echo "Error......";
            print_r($data->getMessages());
            echo "<br><a href='index' class='btn btn-outline-warning'>Back</a>";
        }
    }
}
