<?php

namespace App\Controllers;

use App\Libraries\MongoDBLibrary;
use MongoDB\Client;
use MongoDB\BSON\UTCDateTime;
use MongoDB\BSON\ObjectId;
use MongoDB\BSON\Regex;

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
                $session->set('login_success', "Logged in successfully");
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
        $filtername = ucfirst(trim($this->request->getVar('filter-name')));
        $filtercat = ucfirst(trim($this->request->getVar('filter-cat')));
        $filterprice = ucfirst(trim($this->request->getVar('filter-price')));

        $collection = $this->mongolib->getCollection("crud_ops");
        $data = $collection->find()->toArray();

        $newArray = [];
        $productname = [];
        $productcategory = [];
        $productprice = [];

        // converting BSON Document to array 
        for ($i = 0; $i < count($data); $i++) {
            $newArray[$i] = [
                'productname' => $data[$i]->productname,
                'productcategory' => $data[$i]->productcategory,
                'productprice' => $data[$i]->productprice
            ];
        }

        // created three sepearate arrays to store the data seperatly so that fucntion unique can be applied on them.
        for ($i = 0; $i < count($newArray); $i++) {
            $productname[$i] = $newArray[$i]['productname'];
            $productcategory[$i] = $newArray[$i]['productcategory'];
            $productprice[$i] = $newArray[$i]['productprice'];
        }

        // executed the function and stored the values in a single array to use it in view.
        $uniqueData['productname'] = array_unique($productname);
        $uniqueData['productcategory'] = array_unique($productcategory);
        $uniqueData['productprice'] = array_unique(array: $productprice);

        // previous code followed
        $newData = [
            'documents' => $data,
            'documents_all' => $uniqueData,
        ];

        $condition = [];

        if (!empty($filtername) || !empty($filtercat) || !empty($filterprice)) {
            if ($filtername) {
                $condition_this = ['productname' => $filtername];
                array_push($condition, $condition_this);
            }
            if ($filtercat) {
                $condition_this = ['productcategory' => $filtercat];
                array_push($condition, $condition_this);
            }
            if ($filterprice) {
                $condition_this = ['productprice' => $filterprice];
                array_push($condition, $condition_this);
            }

            $result = $collection->find([
                '$and' => $condition
            ])->toArray();

            $filterdata = [
                'documents' => $result,
                'documents_all' => $uniqueData,
            ];

            return view('List', $filterdata);
        } else {
            return view('List', $newData);
        }

    }

    public function postData()
    {
        $productname = $this->request->getPost('name');
        $productcategory = $this->request->getPost('category');
        $productprice = $this->request->getPost('price');

        $data = [
            'productname' => ucfirst(trim($productname)),
            'productcategory' => ucfirst(trim($productcategory)),
            'productprice' => ucfirst(trim($productprice)),
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
        if ($data) {
            $session = session();
            $session->setFlashdata('data_deleted', 'Data Deleted Successfully!');
        }

        $url = 'http://localhost:4000/api/deleteuser/' . $_id;

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
        $updateData = $collection->findOne(['_id' => $objid]);

        return json_encode($updateData);

    }

    public function postupdate()
    {
        $collection = $this->mongolib->getCollection("crud_ops");
        $updateid = $this->request->getVar('updateId');
        $objid = new ObjectId($updateid);

        $data = [
            'productname' => ucfirst(trim($this->request->getPost('updatename'))),
            'productcategory' => ucfirst(trim($this->request->getPost('updatecat'))),
            'productprice' => ucfirst(trim($this->request->getPost('updateprice'))),
        ];

        $collection->updateOne(['_id' => $objid], ['$set' => $data]);

        $url = 'http://localhost:4000/api/udpatedata/' . $updateid;

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
        }

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
            return redirect()->to('/login')->with('success', 'Registration successful');
        }

    }

    public function logout()
    {
        $session = session();
        $session->destroy();

        $session->set('logged_out', "Logged out successfully");
        return redirect()->to('/login');
    }

    public function download()
    {
        $collection = $this->mongolib->getCollection("crud_ops");
        $datas = $collection->find()->toArray();

        $fileName = 'product-data' . date('Ymd') . '.csv';

        header('Content-Description: File Transfer');
        header("Content-type: application/csv");
        header("Content-Disposition: attachment; filename={$fileName}");

        $file = fopen('php://output', 'w');

        $headers = array('productname', 'productcategory', 'productprice');

        fputcsv($file, $headers);

        $arr = [];
        for ($i = 0; $i < count($datas); $i++) {
            $arr[$i] = [
                'productname' => $datas[$i]['productname'],
                'category' => $datas[$i]['productcategory'],
                'productprice' => $datas[$i]['productprice']
            ];
        }

        foreach ($arr as $ar) {
            fputcsv($file, $ar);
        }

        fclose($file);

        exit;
    }

    public function uploadFile()
    {
        $file = $this->request->getFile('myfile');

        $filepath = $file->getTempName();
        $csvData = array_map('str_getcsv', file($filepath));

        $headers = array_shift($csvData);

        function formatrow($headers, $rows)
        {
            return array_combine($headers, $rows);
        }

        $formatedData = array_map(fn($row) => formatrow($headers, $row), $csvData);

        $collection = $this->mongolib->getCollection("crud_ops");

        $url = "http://localhost:4000/api/insertMany";

        $newdata = [];

        $supportArray = [];

        $indexarray = [];

        $newArrayfil = [];

        for ($i = 0; $i < count($formatedData); $i++) {

            if (!empty($formatedData[$i]['productname']) && !empty($formatedData[$i]['productcategory']) && !empty($formatedData[$i]['productprice'])) {
                $insertData = $collection->insertOne([
                    'productname' => ucfirst(trim($formatedData[$i]['productname'])),
                    'productcategory' => ucfirst(trim($formatedData[$i]['productcategory'])),
                    'productprice' => ucfirst(trim($formatedData[$i]['productprice']))
                ]);
                $mongoid = (string) $insertData->getInsertedId();

                $newdata[$i] = [
                    'productid' => $mongoid,
                    'productname' => ucfirst(trim($formatedData[$i]['productname'])),
                    'productcategory' => ucfirst(trim($formatedData[$i]['productcategory'])),
                    'productprice' => ucfirst(trim($formatedData[$i]['productprice']))
                ];
            } else {
                if (empty($formatedData[$i]['productname']) || empty($formatedData[$i]['productcategory']) || empty($formatedData[$i]['productprice'])) {
                    array_push($indexarray, $i);
                    $supportArray[$i] = [
                        'productname' => $formatedData[$i]['productname'],
                        'productcategory' => $formatedData[$i]['productcategory'],
                        'productprice' => $formatedData[$i]['productprice']
                    ];

                }
            }

        }

        for ($i = 0; $i < count($indexarray); $i++) {
            $newArrayfil[$i] = [
                'productname' => $supportArray[$indexarray[$i]]['productname'],
                'productcategory' => $supportArray[$indexarray[$i]]['productcategory'],
                'productprice' => $supportArray[$indexarray[$i]]['productprice']
            ];
        }

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

            $fileName = 'product-data-empty' . date('Ymd') . '.csv';

            header('Content-Description: File Transfer');
            header("Content-type: application/csv");
            header("Content-Disposition: attachment; filename={$fileName}");

            $file = fopen('php://output', 'w');

            $headers = array('productname', 'productcategory', 'productprice');

            fputcsv($file, $headers);

            $arr = [];

            for ($i = 0; $i < count($newArrayfil); $i++) {
                $arr[$i] = [
                    'productname' => $newArrayfil[$i]['productname'],
                    'productcategory' => $newArrayfil[$i]['productcategory'],
                    'productprice' => $newArrayfil[$i]['productprice']
                ];

            }

            foreach ($arr as $ar) {
                fputcsv($file, $ar);
            }

            fclose($file);

            exit;

        } else {
            echo "Bad";
        }
    }

    public function deleteAll()
    {
        $collection = $this->mongolib->getCollection("crud_ops");
        $data = $collection->deleteMany([]);

        $url = 'http://localhost:4000/api/deleteAll';

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

}

?>