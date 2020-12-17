
        To,<br>
        The sir<br><br>  

        @if( sizeof($headerValidation) > 0 || sizeof($validationResult) > 0 )
            <strong>Validation Errors in CSV file</strong><br>
            @if( sizeof($headerValidation) > 0 )
                <br>
                Header column (
                    @foreach( $headerValidation as $header)
                        {{$header}}
                        @if (!$loop->last)
                            ,
                        @endif
                    @endforeach
                ) is incorrect in csv file
                <br>
            @endif
        

            @for( $e=0; $e < sizeof( $validationResult ); $e++)
                {{$validationResult[$e]->errors }} at row {{$validationResult[$e]->rows}}
                <br>                        
            @endfor



        @else
            @if( $empty )
                <strong>Validation Errors in CSV file</strong><br>
                The file is empty
            @else
                <br>  There are no validation errors in csv file
                <br>  Document details saved
            @endif
            
        @endif


   
  