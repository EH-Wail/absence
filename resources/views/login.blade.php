@extends('layout')
@section('css')
    <link rel="stylesheet" href="{{asset('styles/main.css')}}">
@endsection
@section('title')
    Login
@endsection
@section('content')
    <div class="container row">
        <div class="col-xxl-5 col-xl-4 col-lg-4 col-md-0 col-sm-12 img">
            <img src="{{asset('images/test.png')}}">
        </div>
        <div class="col-xxl-2 col-xl-1 col-lg-1 col-md-0 col-sm-12"></div>
        <div class="col-xxl-5 col-xl-5 col-lg-4 col-md-12 col-sm-12 login">
            <a href="#" class="logo"><img src="{{asset('images/logo.jpg')}}" alt="ofppt" width="100px"/></a>
            <div class="header">
                <h1>Bienvenue !</h1>
                <p>Connectez-vous</p>
            </div>
            <form class="login-form" action="{{route('login.attempt')}}" method="post">
                @csrf
                <div class="informations">
                    <label for="username">Nom d'utilisateur</label>
                    <input type="text" name="username" id="username">
                    @error('username')
                        <p class="text-danger mx-2 mt-1"><small>{{$message}}</small></p>
                    @enderror
                </div>
                <div class="informations">
                    <label for="password">Mot de passe</label>
                    <input type="password" name="password" id="passowrd">
                    @error('password')
                        <p class="text-danger mx-2 mt-1"><small>{{$message}}</small></p>
                    @enderror
                </div>
                <div class="remember">
                    <div>
                        <input type="checkbox" value="remember" name="remember"/>
                        <label for="remember">Souvenir de moi</label>
                    </div>
                </div>
                <button type="submit">Se connecter</button>
            </form>
        </div>
    </div>
    
@endsection