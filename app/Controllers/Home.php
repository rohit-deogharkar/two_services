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

    public function getlogin()
    {
        return view('Login');
    }

    public function postlogin()
    {
        $username = $this->request->getPost('username');
        $password = $this->request->getPost('password');

        if (empty($username) || empty($password)) {
            return redirect()->back()->withInput()->with('error', "Please fill all the fields");
        }

        $collection = $this->mongolib->getCollection("users");
        $user = $collection->findOne([
            '$or' => [
                ['username' => $username],
                ['email' => $username]
            ]
        ]);

        if ($user) {
            if (password_verify($password, $user->password)) {
                $session = session();
                $session->set('user_id', $user->username);
                return redirect()->to('/home');
                
            } else {
                return redirect()->back()->withInput()->with('loginerror', 'Invalid username or password');
            }

        } else {
            return redirect()->back()->withInput()->with('loginerror', 'Invalid username or password');
        }
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
            return redirect()->to('/home');
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

        return redirect()->to('/home');

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
            return redirect()->to('/home');
        }
    }

    public function getregister()
    {
        return view('Registeration');
    }

    public function registeration()
    {
        $name = $this->request->getPost('name');
        $email = $this->request->getPost('email');
        $password = $this->request->getPost('password');
        $confirmpassword = $this->request->getPost('confirm_password');
        $hashpassword = password_hash($confirmpassword, PASSWORD_DEFAULT);

        $validation = \Config\Services::validation();

        $rules = [
            'password' => [
                'rules' => 'min_length[8]|regex_match[/^(?=.*[A-Z])(?=.*[a-z])(?=.*[0-9])(?=.*[@#$]).+$/]',
                'errors' => [
                    'min_length' => 'Password must be at least 8 characters long',
                    'regex_match' => 'Password must contain at least one uppercase letter, Password must contain at least one lowercase letter, Password must contain at least one number, Password must contain at least one special character',
                ]
            ]
        ];

        if (empty($name) || empty($email) || empty($password)) {

            return redirect()->back()->withInput()->with('error', 'Please fill all the fields');
        } else {
            if (strlen($name) < 8) {
                return redirect()->back()->withInput()->with('nameerror', 'Name must be at least 8 characters long');
            }
            if (!preg_match("/^[0-9a-zA-Z@#_\-$]*$/", $name)) {
                return redirect()->back()->withInput()->with('nameerror', 'Name can contain only @,#,$,_,- these special characters');
            }
            if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {

                return redirect()->back()->withInput()->with('emailerr', 'Invalid email address');
            }
            if (!$this->validate($rules)) {
                return redirect()->back()->withInput()->with('passworderr', $validation->getErrors());
            }
            if ($confirmpassword !== $password) {
                return redirect()->back()->withInput()->with('confirmerror', 'Passwords do not match');
            }
            // else{
            //     return redirect()->to('/');
            // }
        }

        echo $name . " " . $email . " " . $hashpassword . "<br> ";

        $collection = $this->mongolib->getCollection("users");
        $existinguser = $collection->findOne([
            '$or' => [
                ['username' => $name],
                ['email' => $email]
            ]
        ]);

        if ($existinguser) {
            return redirect()->back()->withInput()->with('uniqueerror', 'Username or Email already exists');
        } else {

            $data = [
                'username' => $name,
                'email' => $email,
                'password' => $hashpassword,
            ];

            $insertData = $collection->insertOne($data);

            $mongoid = (string) $insertData->getInsertedId();

            echo $mongoid;

            return redirect()->to('/login')->with('success', 'Registration successful');
        }


    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        return redirect()->to('/login');
    }

    public function newredirect(){
        return redirect()->to('/login');
    }

}



?>