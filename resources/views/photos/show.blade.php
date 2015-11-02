@extends('layout')

@section('content')
    <div id="wrapper"><br/>

        <p>
            @if (Auth::check() && Auth::user()->id == $photo->user->id)
            <a target="_blank" rel="nofollow" href="{{ $printLink }}">
                Order a print
                of {{ Auth::check() && Auth::user()->id == $photo->user->id ? 'your glitch art!' : 'this glitch image!'}}
            </a>
            <br/>
            @endif
            share {{ Auth::check() && Auth::user()->id == $photo->user->id ? 'your awesome glitch art:' : '' }}
            <a target='_blank'
               rel='nofollow'
               href='http://www.tumblr.com/share/photo?source={{ urlencode($photo->uri) }}&click_thru={{ route('photos.show', $photo->filename) }}&tags=glitch%2Cglitch%20art%2Cglitchimg.com&caption=%3C%2Fbr%3E%0Aimage%20created%20at%20%3Ca%20href%3D%22glitchimg.com%22%3Eglitchimg.com%3C%2Fa%3E.'>Tumblr.</a>
            <a target='_blank' rel='nofollow'
               href='https://www.facebook.com/sharer/sharer.php?u={{ route('photos.show', $photo->filename) }}'>Facebook.</a>
            <a target='_blank' rel='nofollow'
               href='https://twitter.com/intent/tweet?url={{ route('photos.show', $photo->filename) }}&hashtags=glitchart,glitch,glitchimg.com&via=glitch_img'>Twitter.</a>
{{--               href='https://twitter.com/intent/tweet?url={{ urlencode($photo->uri) }}&hashtags=glitchart,glitch,glitchimg.com&via=glitch_img'>Twitter.</a>--}}
            <br/>
        </p>
        <br/>
        <a target="_blank" href="">
            <img src="{{ $photo->uri }}"/>
        </a>
        <br/>

        <p>
            @if (Auth::guest() || Auth::user()->id != $photo->user->id)
                glitch image created by : {{ $photo->user->name }}
            @endif
            @if (Auth::guest())
                </br>
                </br>
                Create glitch art of your own by <a href="/login">Logging In</a>!<br/> In addition, share and print your
                awesomely unique glitch-art creations!
            @endif
        </p></div>
@endsection