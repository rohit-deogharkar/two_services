<?php 

namespace App\Filters;

use Codeigniter\HTTP\RequestInterface;
use Codeigniter\HTTP\ResponseInterface;
use Codeigniter\Filters\FilterInterface;

class Authfilter implements FilterInterface{
    public function before(RequestInterface $request, $arguments=null){
        $session = session();
        if(!$session->has('user_id')){
            return redirect()->to('/login');
        }
    }
    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null){

    }
}

?>