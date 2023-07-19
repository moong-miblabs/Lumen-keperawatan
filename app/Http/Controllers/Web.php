<?php

namespace App\Http\Controllers;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

use App\Models\RespondenDemografiModel;

use App\Helper\BcryptHelper;

class Web extends Controller{
    
    public function __construct(){
        //
    }

    public function login(Request $request){
        $data = RespondenDemografiModel::findAll();
        return response()->json($data);
    }

}
