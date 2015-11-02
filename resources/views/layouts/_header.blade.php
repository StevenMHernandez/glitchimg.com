<nav>
    <ul>
        <li><a href="/">glitchimg</a></li>
        @if (Auth::check())
            <li><a href="/editor">editor.</a></li>
            <li><a target="_blank" href="http://created-at-glitchimg.tumblr.com/ask">contact.</a></li>
            <li><a href="/logout">logout.</a></li>
            <li><a href="/profile">{{ Auth::user()->name }}</a></li>
        @else
            <li><a target="_blank" href="http://glitch-img.tumblr.com">blog.</a></li>
            <li><a target="_blank" href="http://created-at-glitchimg.tumblr.com/ask">contact.</a></li>
            <li><a href="/login">login/sign up</a></li>
        @endif

    </ul>
</nav>