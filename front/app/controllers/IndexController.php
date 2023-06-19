<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Escaper;

class IndexController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }
    public function registerAction()
    {
        $escaper = new Escaper();
        $data = array(
            'name' =>  $escaper->escapeHtml($this->request->getPost('name')),
            'email' => $escaper->escapeHtml($this->request->getPost('email')),
            'pswd' =>  $escaper->escapeHtml($this->request->getPost('pswd')),
            'pincode' => $escaper->escapeHtml($this->request->getPost('pincode')),
            'app_key' => $escaper->escapeHtml($this->request->getPost('name')),
            'secret_key' => $escaper->escapeHtml($this->request->getPost('pswd'))
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
        if ($res == 1) {
            $this->response->redirect('login');
        } else {
            print_r($res);
            die;
        }
    }
}
