<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use DB;
use App\Mail\sendEmail;
use Mail;
use App\Model\Module;
use App\Model\Validate;
use App\Model\ValidationMap;
use App\Model\Validation;
use Storage;

class ValidateBackground implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    private $path;
    private $allErrors, $allData, $contentArray = [];
    private $heading , $validator, $availableValidation = [];

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct( $path)
    {
        $this->path = $path;
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

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
        $contentArray = [];
        if (($fp = fopen( $this->path  , "r")) !== FALSE) {
            while (($data = fgetcsv($fp, 1000, ",")) !== FALSE) {
                $contentArray[] = $data; 
            }
            fclose($fp);
        }
        $header =  $contentArray[0];
        #dynamic heading
        $headings = $this->heading;
        $headerValidation = [];
        $empty = false;
        if(  sizeof( $contentArray ) < 2){
            $empty = true;
        }
        for( $i=0; $i < sizeof( $headings ); $i++){
            if( isset($header[$i]) && isset($headings[$i]) ){
                if( $header[$i] != $headings[$i] ){
                    $headerValidation[] = $headings[$i];
                }
            }
            
        }    
        array_shift($contentArray);    
        $this->contentArray = $contentArray;
        $save = [];
        for( $contentIndex=0; $contentIndex < sizeof( $contentArray ); $contentIndex++){
            for( $columns=0; $columns < sizeof( $headings ); $columns++){                                            
                foreach(  $this->availableValidation as $allvalidation){#validation call loop
                    if( isset(  $this->validator[ $allvalidation ]) ){
                        if( in_array( $columns,  array_values( $this->validator[ $allvalidation ] ) )){
                            call_user_func_array( [$this , $allvalidation."Validation"], [$contentIndex , $columns ]);
                        }
                    }
                }                                                                                   
            }
            #getData
            $this->setDataArray(  $contentArray[ $contentIndex ] );         
        }        

        if( !empty( $this->allData) ){  
            Module::insert( $this->allData );
        }
  

        Validate::truncate();
        if( !empty( $this->allErrors ) ){            
            Validate::insert( $this->allErrors );
        }       
        $validationResult = Validate::select( DB::raw("GROUP_CONCAT(row SEPARATOR ',') as `rows`"))
            ->addSelect('errors')
            ->orderBy('rows', 'ASC')
            ->groupBy('errors', 'attribute')
            ->get();

        Mail::to('charush@accubits.com')->send( new sendEmail( $validationResult, $headerValidation, $empty) );      
        
    }


    
    public function setValidationArray($row, $attribute, $errors){
        $array = [];
        $array['row'] = $row;
        $array['attribute'] = $attribute;
        $array['errors'] = $errors;
        $array['created_at' ] =  \Carbon\Carbon::now();
        $array['updated_at' ] =  \Carbon\Carbon::now();  
        $this->allErrors[] = $array;
    }

    public function setDataArray($arrayIncoming){
        $array = [];
        for( $columns=0; $columns < sizeof( $this->heading ); $columns++){
            if( isset( $this->heading[$columns] ) && isset( $arrayIncoming[ $columns ] ) ){
                $array[ $this->heading[$columns] ] = $arrayIncoming[ $columns ];
                $array['created_at' ] =  \Carbon\Carbon::now();
                $array['updated_at' ] =  \Carbon\Carbon::now();
            }                           
        }
        $this->allData[] = $array;
    }

    public function emailValidation($index, $columns ){
        if( isset( $this->contentArray[ $index ][ $columns ] ) && !empty( $this->contentArray[ $index ][ $columns ] ) ){      
            if( !filter_var($this->contentArray[ $index ][ $columns ], FILTER_VALIDATE_EMAIL) ){
                $this->setValidationArray(
                    $index + 2,
                    $this->heading[ $columns ],
                    $this->heading[ $columns ].' is not a valid email'
                );
            }
        }
    }

    public function requiredValidation($index, $columns ){
        if( empty( $this->contentArray[ $index ][ $columns ]) ){                 
            $this->setValidationArray(
                $index + 2,
                $this->heading[ $columns ],
                $this->heading[ $columns ].' is missing'
            );
        }
    }


    public function symbolValidation($index, $columns ){
        if( isset( $this->contentArray[ $index ][ $columns ] ) ){
            if( preg_match('/[^a-z0-9 _]+/i', $this->contentArray[ $index ][ $columns ] )) {                  
                $this->setValidationArray(
                    $index + 2,
                    $this->heading[ $columns ],
                    $this->heading[ $columns ].' contains symbols'
                );
            }
        }
    }
}
