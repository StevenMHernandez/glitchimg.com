@extends('layout')

@section('content')
    <div id="wrapper"><br/>

        <p>
            @if (Auth::check() && Auth::user()->id == $photo->user->id)
                <i class="fa fa-fw   fa-photo"></i>
                <a target="_blank" rel="nofollow" href="{{ $urls['zazzle'] }}">
                    Order a print
                    of {{ Auth::check() && Auth::user()->id == $photo->user->id ? 'your glitch art.' : 'this glitch image.'}}
                </a>
                <br/>
                <br/><i class="fa fa-fw fa-gift"></i>
                <a target="_blank" rel="nofollow" href="{{ $urls['zazzle_wrapping_paper'] }}">
                    Make this glitch art into wrapping paper.
                </a>
                <small>(New!)</small>
                <br/>
                <br/>
            @endif
            share {{ Auth::check() && Auth::user()->id == $photo->user->id ? 'your awesome glitch art:' : '' }}
            <a target="_blank" href="{{ $urls['tumblr'] }}">Tumblr.</a>
            <a target="_blank" href="{{ $urls['facebook'] }}">Facebook.</a>
            <a target="_blank" href="{{ $urls['twitter'] }}">Twitter.</a>
            <br/>
        </p>
        <br/>

        <img src="{{ $photo->uri }}"/>
        <br/>

        <p>
            @if (Auth::guest() || Auth::user()->id != $photo->user->id)
                glitch image created by : {{ $photo->user->name }}
            @endif
            @if (Auth::user()->id == $photo->user->id)
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
@endsection