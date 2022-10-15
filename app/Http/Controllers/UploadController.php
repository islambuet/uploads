<?php
namespace App\Http\Controllers;

use App\Http\Controllers\RootController;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class UploadController extends Controller
{

    public function saveFiles(Request $request)
    {
        echo '<pre>';
        print_r($request->all());
        echo '</pre>';
    }

}

