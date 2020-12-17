
@extends('layouts.app')


@section('content')
    @if( $errors->any() )
        @foreach( $errors->all() as $error)
            {{$error}}
        @endforeach
    @endif

    @if( session()->has('failures') )
        @foreach( session()->get('failures')  as $failure)

            Row:{{$failure->row()}} 
            Column:{{$failure->attribute()}}<br>


            @foreach( $failure->errors() as $e)
                {{ $e }}
            @endforeach
            <br><br><br>
        @endforeach
    @endif
    <form method="post" action="{{ route('store') }}"  enctype="multipart/form-data">
        @csrf
        <input type="file" name="csv_file">
        <input type="submit" value="Submit">
    </form>
@stop