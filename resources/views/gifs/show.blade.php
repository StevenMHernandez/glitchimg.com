@extends('layout')

@section('head')
    <meta property="og:image" content="{{ $gif->uri }}"/>
@endsection

@section('content')
    <div id="wrapper"><br/>

        <p>
            share {{ Auth::check() && Auth::user()->id == $gif->user->id ? 'your awesome glitch art:' : '' }}
            <a target="_blank" href="{{ UrlGenerator::build('tumblr', $gif->id, 'gif') }}">Tumblr.</a>
            <a target="_blank" href="{{ UrlGenerator::build('facebook', $gif->id, 'gif') }}">Facebook.</a>
            <a target="_blank" href="{{ UrlGenerator::build('twitter', $gif->id, 'gif') }}">Twitter.</a>
            <br/>
        </p>
        <br/>

        <img src="{{ $gif->uri }}"/>
        <br/>

        <p>
            @if (Auth::guest() || Auth::user()->id != $gif->user->id)
                glitch image created by : {{ $gif->user->name }}
            @endif
            @if (!Auth::guest() && Auth::user()->id == $gif->user->id)
                glitch image created by you!
            @endif
            @if (Auth::guest())
                </br>
                </br>
                Create glitch art of your own by <a href="/login">Logging In</a>!<br/> In addition, share and print your
                awesomely unique glitch-art creations!
            @endif
        </p>
    </div>

    <h1>Gif Frames:</h1>
    <br/>

    <div class="wrapper">
        <br/>
        @foreach($gif->images as $frame)
            <p style="text-align: center">
                <img src="{{ UrlGenerator::build('preview_image', $frame->id) }}"/>
                <br/>
                <a href="{{ UrlGenerator::build('zazzle', $frame->id) }}"><i class="fa fa-picture-o"></i> Get it as a
                    Print</a>
                <a href="{{ UrlGenerator::build('zazzle_wrapping_paper', $frame->id) }}"><i class="fa fa-gift"></i> Get
                    it as Wrapping Paper</a>
            </p>
            <br/>
        @endforeach
    </div>
@endsection