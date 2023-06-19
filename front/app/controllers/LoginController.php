<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;
use Phalcon\Security\JWT\Builder;
use Phalcon\Security\JWT\Signer\Hmac;



class LoginController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }
    public function loginAction()
    {
        session_start();
        $email = $this->request->getPost('email');
        $pswd = $this->request->getPost('pswd');
        // set post fields
        $ch = curl_init();
        $url = 'http://172.18.0.4/findUser?email=' . $email . '&pswd=' . $pswd;
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
        curl_setopt($ch, CURLOPT_URL, $url);
        // execute!
        $response = json_decode(curl_exec($ch), true);
        $loginController = new loginController();
        $role = (string)$response['name'];
        $token = $loginController->getTokenAction($role);
        $_SESSION['token'] = $token;
        $this->response->redirect('product');
    }
    public function getTokenAction($role)
    {
        $signer  = new Hmac();

        // Builder object
        $builder = new Builder($signer);

        $now        = new \DateTimeImmutable();
        $issued     = $now->getTimestamp();
        $notBefore  = $now->modify('-1 minute')->getTimestamp();
        $expires    = $now->modify('+30 minute')->getTimestamp();
        $passphrase = 'QcMpZ&b&mo3TPsPk668J6QH8JA$&U&m2';
        // $role = $this->request->getPost('role');
        // Setup
        $builder
            ->setAudience('https://target.phalcon.io')  // aud
            ->setContentType('application/json')        // cty - header
            ->setExpirationTime($expires)               // exp
            ->setId('abcd123456789')                    // JTI id
            ->setIssuedAt($issued)                      // iat
            ->setIssuer('https://phalcon.io')           // iss
            ->setNotBefore($notBefore)                  // nbf
            ->setSubject($role)                         // sub
            ->setPassphrase($passphrase)                // password
        ;

        // Phalcon\Security\JWT\Token\Token object
        $tokenObject = $builder->getToken();

        // The token
        return $tokenObject->getToken();
    }
}
