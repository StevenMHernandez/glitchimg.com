@extends('layout')

@section('content')
    <h1>Profile</h1>
    <a style="float: left; margin: 2.8em 1em 1em; text-decoration: none" href="/settings">settings.</a>


    <div id="wrapper">
        @if (count($photos))
            @foreach($photos as $photo)
                @if ($photo->type == 'gif')
                    <a href="{{ route('gifs.show', [$photo->filename]) }}">
                        <img src="{{ UrlGenerator::build('gif', $photo->filename, 'gif') }}"/>
                    </a>
                @else
                    <a href="{{ route('photos.show', [$photo->filename]) }}">
                        <img src="{{ UrlGenerator::build('preview_image', $photo->filename) }}"/>
                    </a>
                @endif
                <br/>
            @endforeach
        @else
            <a href="/editor">start glitching!</a>
        @endif
    </div>
@endsection