@extends('layout')

@section('content')
    <div class="background">
        <form method="post" action="{{ route('settings.update') }}">
            {{ csrf_field() }}
            <label>
                Is it ok if we share you awesome glitch art to our
                <a target="_blank" href="http://created-at-glitchimg.tumblr.com">tumblr blog</a>?
                <input name="share_to_our_tumblr" type="checkbox" {{ $user->settings->share_to_our_tumblr ? 'checked="true"' : '' }}">
            </label>
            <input type="submit" value="Save."/>
        </form>
    </div>
@endsection