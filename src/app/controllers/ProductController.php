<?php

namespace MyApp\Controllers;

use MyApp\Models\Products;
use Phalcon\Mvc\Controller;


class ProductController extends Controller
{
    public function indexAction()
    {
        //redirect to view
    }

    public function validateCatageory($catageory)
    {
        if ($catageory == "Electronics" || $catageory == "Jwellery") {
            return true;
        }
        return false;
    }

    public function validateInt($data)
    {
        if (filter_var($data, FILTER_VALIDATE_INT) && $data > 0) {
            return true;
        }
        return false;
    }

    public function validateName($name)
    {
        if (strlen($name) > 0) {
            return true;
        }
        return false;
    }

    public function submitAction()
    {
        $name = $this->request->get('name');
        $price = $this->request->get('price');
        $catageory = $this->request->get('catageory');
        $qty = $this->request->get('qty');
        $product = new ProductController();
        if (!$product->validateName($name)) {
            echo "Name Can not be empty";
            die;
        }
        if (!$product->validateCatageory($catageory)) {
            echo "Catageory can be only Electronics or Jwellery";
            die;
        }
        if (!$product->validateInt($price)) {
            echo "Please Enter Correct Price";
            die;
        }
        if (!$product->validateInt($qty)) {
            echo "Please Enter Correct Quantity";
            die;
        }
        $data = new Products();
        $data->name = $name;
        $data->type = $catageory;
        $data->qty = $qty;
        $data->price = $price;
        $result = $data->save();
        if ($result) {
            echo "Product Added Succesfully.";
            echo "<br><a href='../index/index' class='btn btn-outline-warning'>Back</a>";
        } else {
            echo "Error......";
            print_r($data->getMessages());
            echo "<br><a href='index' class='btn btn-outline-warning'>Back</a>";
        }
    }
}
