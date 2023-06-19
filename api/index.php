<?php

use Phalcon\Loader;
use Phalcon\Mvc\Micro;
use Phalcon\Di\FactoryDefault;
use Phalcon\Security\JWT\Token\Parser;
use Phalcon\Security\JWT\Validator;

session_start();

require_once './vendor/autoload.php';
$loader = new Loader();

$loader->registerNamespaces(
    [
        'MyApp\Models' => __DIR__ . '/models/',
        'handler' => __DIR__ . '/handler/'
    ]
);
$loader->register();

$container = new FactoryDefault();
$container->set(
    'mongo',
    function () {
        $mongo = new MongoDB\Client('mongodb+srv://myAtlasDBUser:myatlas-001@myatlas' .
            'clusteredu.aocinmp.mongodb.net/?retryWrites=true&w=majority');
        return $mongo->products;
    },
    true
);


$app = new Micro($container);


// Searches for product with $name in their name
$app->get(
    '/products',
    function () use ($app) {
        $token = $app->request->get('bearer');
        if ($token) {
            $parser = new Parser();
            $tokenObject = $parser->parse($token);
            $now = new \DateTimeImmutable();
            $expirs = $now->getTimestamp();
            $validator = new Validator($tokenObject, 100);
            $validator->validateExpiration($expirs);
            $claims = $tokenObject->getClaims()->getPayload();
            $tokenval = $this->mongo->user->findOne(['app_key' => $claims['sub']]);
            if ($tokenval) {
                $product = $this->mongo->product->find([], ["limit" => (int)10]);
                foreach ($product as $value) {
                    $result[] = [
                        'id'   =>  $value->_id,
                        'name' =>  $value->name,
                        'price' => $value->price,
                        'qty' => $value->qty,
                        'desc' => $value->desc
                    ];
                }
                $data = [1, $result];
            } else {
                $data = [0];
            }
        } else {
            $data = [0];
        }
        return json_encode($data);
    }
);


$app->get(
    '/findUser',
    function () {
        $res = $this->mongo->user->findOne(['$and' => [['email' => $_GET['email'], 'pswd' => $_GET['pswd']]]]);
        return json_encode($res);
    }
);

$app->post(
    '/adduser',
    function () {
        $res = $this->mongo->user->insertOne($_POST);
        return json_encode($res->getInsertedCount());
    }
);


$app->notFound(
    function () use ($app) {
        $app->response->setStatusCode(404, 'Not Found');
        $app->response->sendHeaders();

        $message = 'Nothing to see here. Move along....';
        $app->response->setContent($message);
        $app->response->send();
    }
);

$app->handle(
    $_SERVER["REQUEST_URI"]
);
