<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Validator;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use DB;
use App\Http\Requests\csvValidationRequest;
use App\Jobs\ValidateBackground;
use App\Mail\sendEmail;
use Mail;
use App\Model\Module;
use App\Model\Validate;
use App\Model\ValidationMap;
use App\Model\Validation;
use Storage;

class DocumentController extends Controller
{
    private $allErrors, $allData, $contentArray = [];
    private $heading , $validator, $availableValidation  = [];
 
    public function __construct(){
        $this->heading = Module::getColumns();
        $validate =ValidationMap::select( DB::raw("GROUP_CONCAT(row SEPARATOR ',') as `rows`"))  
            ->addSelect('validation')      
            ->groupBy('validation')
            ->get();                           
        foreach($validate as $v ){
            $this->validator[ $v->validation ] = explode(',', $v->rows );
        }
        $this->availableValidation = Validation::pluck('name');            
    }

    public function index(){
        return view('uploads.index');
    }

    
   

    public function validater($request){
        $rules = ['csv_file' => 'required|mimes:csv,txt'];
        $messages = ['csv_file.mimes' => 'The file must be a type: csv.' ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {           
            return [
                'success'=>false,
                'errors' => $validator->messages(),
            ];
        }

        $file = $request->file('csv_file'); 

        #.txt Validation
        if( $file->getClientOriginalExtension() !== "csv"){
            return [
                'success'=>false,
                'errors'=> ["csv_file"=>"The file must be a type: csv"]
            ];   
        }   
        return ['success'=>true];    
    }
    
  

    public function store(Request $request){
        
        $result = $this->validater($request);
        if(!$result['success']){
            return response()->json( $result );
        }   

        $file = $request->file('csv_file');    
        $file = Storage::disk('local')->put('csv', $file);
        $path = Storage::disk('local')->path($file);
        $content = file($path);

        $job = new ValidateBackground(  $path );
        dispatch($job);

        return response()->json([
            'success'=> true,
            'message' => 'successfully uploaded'
        ]);               

        
    }


    public function documentation(){
        return view('layouts.docs');
    }


}
