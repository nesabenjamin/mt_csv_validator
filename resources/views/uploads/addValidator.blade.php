<div class="row">

    <div class="col-sm-5">
        <label for="column"></label>     
        <select class="form-control" id="column" name="validating_column[]">
            @foreach($columns as $key=>$column)               
                <option value="{{$key}}" >{{$column}}</option>
            @endforeach
        </select>
    </div>

    <div class="col-sm-5">
        <label for="validation"></label>   
        <select class="form-control" id="validation" name="validator[]" >
            @foreach($validator as $validate)                              
                <option value="{{$validate}}" >{{$validate}}  </option>
            @endforeach                                                 
        </select>
    </div>

    <div class="col-sm-2">     
        <button type="button" class="remove_validator btn btn-sm mt-2">X</button>    
    </div>
    

</div>              