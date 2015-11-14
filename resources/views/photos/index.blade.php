@extends('layout')

@section('content')
    <h1>Profile</h1>
    <a style="float: left; margin: 2.8em 1em 1em; text-decoration: none" href="/settings">settings.</a>


    <div id="wrapper">
        @if ($photos->count())
            @foreach($photos as $photo)
                <a href="{{ route('photos.show', [$photo->filename]) }}"><img
                            src="{{ UrlGenerator::build('preview_image', $photo->id) }}"/></a>
                <br/>
            @endforeach
        @else
            <a href="/editor">you need to glitch-it-kid!</a>
        @endif
    </div>
@endsection