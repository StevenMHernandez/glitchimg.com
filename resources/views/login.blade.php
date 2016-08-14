@extends('layout')

@section('content')
<h1>Login to create your own glitch art!</h1> <br/>

<div id="wrapper">
    <p>Login in with Facebook or Twitter.</p>
    <ul id="socialLogin">
        <li><a id="facebook" href="/login/facebook"><img src="/assets/images/facebook.png"/></a></li>
        <li><a id="twitter" href="/login/twitter"><img src="/assets/images/twitter.png"/></a></li>
        <li><a id="twitter" href="/login/tumblr"><img src="/assets/images/tumblr.png"/></a></li>
    </ul>
</div>
<?php if(isset($_GET['img'])) echo '<img style="height:1px;width:1px;" src="https://s3.amazonaws.com/glitch-thumb/' . $_GET['img'] . '.jpeg"/>'; ?>
@endsection
