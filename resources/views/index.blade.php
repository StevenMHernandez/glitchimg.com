@extends('layout')

@section('content')
    <h1>Create Glitch-art!!</h1>

    <div id="wrapper">
        <p>
            Hey!<br/>
            Are you ready to make some amazing glitch art?<br/>
            <a href="/login">Login</a> to create some awesome glitch art!<br/>
            Share you glitch art with the world and even order prints of your glitch art!<br/>
            <a href="/login">Start Now!</a>
        </p>
        <br/>
        <p>
            Check out this image created with the filters here:
        </p>
        <br/>
        <img src="{{ $randomImage }}"/>
    </div>
@endsection