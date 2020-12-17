
@extends('layouts.app')



@section('content')


<div class="container">
    <h4 class="text-center mb-4">Configuration </h4>

    <form action="{{route('configure.post')}}" method="post">
        <div class="card">
            <div class="card-header">    <h6 class="">Add | Rename column</h6> </div>
            <div class="card-body">                                        
                @if( session()->has('success'))
                    <div class="alert alert-success alert-dismissible">
                        <button type="button" class="close" data-dismiss="alert">&times;</button>
                        <strong>{{session()->get('success')}}</strong>
                    </div>
                @endif

                @csrf
                <div class="columns" id="columns">        
                    @foreach($columns as $column)
                        <div class="form-group">
                            <div class="d-flex justify-content-between">
                                <label for="email">Column {{$loop->iteration}}</label>
                                @if(!$loop->first)
                                    <button type="button" class="remove btn btn-sm">X</button>
                                @endif
                                
                            </div>
                        
                            <input type="text" class="form-control" id="" 
                            placeholder="Enter column name" 
                            name="columns[]"
                            value="{{$column}}"
                            required
                            >
                        </div>
                    @endforeach
                </div>                                                                        
            </div> 
            <div class="card-footer">
                <div class="d-flex justify-content-end">              
                    <button type="button" class="btn btn-sm btn-info add">Add Column</button>                                
                </div>
            </div>
        </div>







        <div class="card mt-5">
            <div class="card-header">  <h6 class="">Column-Validation configuration</h6></div>
            <div class="card-body card-body-validator">
                <div class="row">
                    <div class="col-sm-5">
                        <label for="column">Column</label>               
                    </div>
                    <div class="col-sm-5">
                        <label for="validation">Validator</label>               
                    </div>
                </div>     



                @foreach($validation_map as $data)                            

                    <div class="row">
                        <div class="col-sm-5">
                            <label for="column"></label>     
                            <select class="form-control" id="column" name="validating_column[]">
                                @foreach($columns as $key=>$column)
                                    @php $selected = ''; @endphp
                                    @if( $data['row'] == $key )
                                        @php $selected = 'selected'; @endphp
                                    @endif
                                    <option value="{{$key}}" {{ $selected}} >{{$column}}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-sm-5">
                            <label for="validation"></label>   
                            <select class="form-control" id="validation" name="validator[]" >
                                @foreach($validator as $validate)
                                   
                                    @php $selected = ''; @endphp
                                    @if( $data['validation'] == $validate )
                                        @php $selected = 'selected'; @endphp
                                    @endif
                                    <option value="{{$validate}}" {{ $selected}} >{{$validate}}  </option>
                                @endforeach                                                 
                            </select>
                        </div>

                        <div class="col-sm-2">
                            @if(!$loop->first)
                                <button type="button" class="remove_validator btn btn-sm mt-2">X</button>
                            @endif

                        </div>
                       

                    </div>                                
                @endforeach            
            
            </div> 
            <div class="card-footer"> 
                <div class="d-flex justify-content-end">              
                    <button type="button" class="btn btn-sm btn-info add_validation">Add validation</button>                                
                </div>                        
            </div>
        </div>


        <div class="d-flex justify-content-end my-5">              
            <button type="submit" class="btn btn-lg btn-success">Submit</button>                                
        </div> 
          
    </form>
</div>




    <script>
        $(document).ready(function() {
            $('.add').click(function() {                
                $('#columns').append('<div class="form-group">  <div class="d-flex justify-content-between">   <label for="email">Column</label>     <button type="button" class="remove btn btn-sm">X</button>  </div> <input type="text" class="form-control" id=""                     placeholder="Enter column name"    name="columns[]"    value=""   required>    </div>'       );
                removeEvent();
            });          
            removeEvent();
            removeValidator();

            $(".add_validation").click(function(){
                $.ajax({
                    url: "/getFields",
                    success: function(result){
                        console.log( result );
                        $(".card-body-validator").append(result);
                        removeValidator();
                    }
                });
            });

        });

        function removeEvent(){
            $('.remove').click(function(){
                $(this).closest('.form-group').remove();
            });
        }

        function removeValidator(){
            $('.remove_validator').click(function(){
                $(this).closest('.row').remove();
            });
        }       
    </script>
@stop