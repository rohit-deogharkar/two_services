<?php

namespace App\Controllers;

use App\Libraries\MongoDBLibrary;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;

class Home extends BaseController
{
    protected $mongolib;
    public function __construct()
    {
        $this->mongolib = new MongoDBLibrary();
        $this->session = \Config\Services::session();
    }
    public function index(): string
    {
        return view();
    }

    public function getData()
    {

        $collection = $this->mongolib->getCollection("crud_ops");
        $data = $collection->find()->toArray();

        return view('List', ['documents' => $data]);
    }

    public function getForm()
    {
        return view('form');
    }

    public function postData()
    {
        $productname = $this->request->getPost('name');
        $productcategory = $this->request->getPost('category');
        $productprice = $this->request->getPost('price');

        $data = [
            'productname' => $productname,
            'productcategory' => $productcategory,
            'productprice' => $productprice,
        ];

        $collection = $this->mongolib->getCollection("crud_ops");
        $insertData = $collection->insertOne($data);
        $mongoid = (string) $insertData->getInsertedId();

        $url = "http://localhost:4000/api/insertdata/";

        $newdata = [
            'productid' => $mongoid,
            'productname' => $productname,
            'productcategory' => $productcategory,
            'productprice' => $productprice,
        ];

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_POST, true);
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($newdata));

        $response = curl_exec($ch);

        curl_close($ch);

        $message = json_decode($response, true);

        if ($message['message']) {
            return redirect()->to('/');
        }

    }

    public function deleteData($_id)
    {

        $objid = new ObjectId($_id);
        $collection = $this->mongolib->getCollection("crud_ops");
        $data = $collection->deleteOne(['_id' => $objid]);

        $url = 'http://localhost:4000/api/deleteuser/' . $_id;
        echo $url;

        $ch = curl_init();

        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "DELETE");
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        $result = curl_exec($ch);

        curl_close($ch);

        $message = json_decode($result, true);

        if ($message['message']) {
            $this->session->setFlashdata("success", "Data Deleted Succesfully!");
        }

        return redirect()->to('/');

    }

    public function datatoupdate($_id)
    {
        $collection = $this->mongolib->getCollection("crud_ops");
        $objid = new ObjectId($_id);
        $data = $collection->findOne(['_id' => $objid]);

        return view('update', ['item' => $data]);

    }

    public function postupdate($_id)
    {
        $objid = new ObjectId($_id);
        $collection = $this->mongolib->getCollection("crud_ops");

        $data = [
            'productname' => $this->request->getPost('updatename'),
            'productcategory' => $this->request->getPost('updatecat'),
            'productprice' => $this->request->getPost('updateprice'),
        ];
        $collection->updateOne(['_id' => $objid], ['$set' => $data]);

        $url = 'http://localhost:4000/api/udpatedata/' . $_id;

        echo $url;

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "PUT");
        curl_setopt($ch, CURLOPT_HTTPHEADER, ['Content-Type: application/json']);
        curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode($data));

        $response = curl_exec($ch);

        curl_close($ch);

        $message = json_decode($response, true);

        if ($message['message']) {
            return redirect()->to('/');
        }
    }

    public function getregister(){
        return view('Registeration');
    }

    public function registeration(){
        $name = $this->request->getPost('name');
    }
}


?>