<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use App\Model\Module;
use App\Model\ValidationMap;
use App\Model\Validation;
use DB;

class ConfigurationController extends Controller
{

        private $heading , $columns = [];
        private $validator = ['required', 'symbol', 'email'];
        public function __construct(){
            $this->columns = Module::getColumns();    
            $this->validator = Validation::pluck('name');           
        }
    
     
        public function index(){
            return view('uploads.configure')
            ->with([
                'columns' => $this->columns,
                'validator'=> $this->validator,
                'validation_map' => ValidationMap::get()->toArray()   
            ]);
        }


        public function getFields(){
            $html = view('uploads.addValidator') 
            ->with([
                'columns' => $this->columns,
                'validator' => $this->validator
            ])
            ->render();
            return $html ;
        }
        
    
        
        public function store( Request $request){           
            Module::truncate();
            Schema::table('modules', function ($table) use ($request) {
                foreach( $this->columns as $column ){
                    $table->dropColumn($column);
                }                  
            });
            Schema::table('modules', function ($table) use ($request) {               
                foreach( $request->columns as $newColumn ){
                    $table->text( $newColumn)->nullable();
                }   
            });
            $this->columns = Module::getColumns();         
            ValidationMap::truncate();
            for( $i=0; $i < sizeof( $request->validating_column ) ; $i++){
                $a = [];
                $a['row'] = $request->validating_column[$i];
                $a['validation'] = $request->validator[$i];               
                ValidationMap::updateOrInsert( $a, $a );              
            }
            return back()->with('success', 'Submitted successfully');                     
        }
    
}
