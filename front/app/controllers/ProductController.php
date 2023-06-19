<?php

namespace MyApp\Controllers;

use Phalcon\Mvc\Controller;

class ProductController extends Controller
{
    public function indexAction()
    {
        session_start();
        if ($_SESSION['time']) {
            if ($_SESSION['time'] < time() - 30) {
                $ch = curl_init();
                $url = 'http://172.18.0.6/products?bearer=' . $_SESSION['token'];
                curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
                curl_setopt($ch, CURLOPT_URL, $url);
                // execute!
                $res = json_decode(curl_exec($ch), true);
                if ($res[0] == 1) {
                    $_SESSION['time'] = time();
                    $this->view->data = $res[1];
                } else {
                    echo "403 UnAuthorised Access";
                    die;
                }
            } else {
                echo "limit exceed error";
                die;
            }
        } else {
            $_SESSION['time'] = time();
            $ch = curl_init();
            $url = 'http://172.18.0.6/products?bearer=' . $_SESSION['token'];
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
            curl_setopt($ch, CURLOPT_URL, $url);
            // execute!
            $res = json_decode(curl_exec($ch), true);
            if ($res[0] == 1) {
                $this->view->data = $res[1];
            } else {
                echo "403 UnAuthorised Access";
                die;
            }
        }
    }
}
