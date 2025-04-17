@extends('layouts.app')

@section('title','Sign in')

@section('content')
    <div class="row mt-4 justify-content-center">
        <div class="col-md-3 offset-md-3 card bg-brown-600 rounded shadow">

            <h2 class="text-center text-gray-300">Sign In</h2>

            <div class="mt-3">
                <form action="{{ route('auth.signin.post') }}" method="POST">
                    @csrf

                    <div class="form-group mb-4">
                        <input type="text" class="form-control @error('username',$errors) is-invalid @enderror" placeholder="Username" name="username" id="username">
                        @error('username',$errors)
                        <p class="text-gray-200">{{$errors->first('username')}}</p>
                        @enderror
                    </div>

                    <div class="form-group mb-4">
                        <input type="password" class="form-control @error('password',$errors) is-invalid @enderror" placeholder="Password" name="password"
                               id="password">
                        @error('password',$errors)
                        <p class="text-gray-200">{{$errors->first('password')}}</p>
                        @enderror
                    </div>
                    @include('includes.captcha')
                    <div class="form-group text-center mt-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-green-900 btn-block btn-block">Sign In</button>
                            </div>
                        </div>
                    </div>
                    @include('includes.flash.error')

                </form>
            </div>
            <div class="m-3 ps-lg-5">
                <a href="/forgotpassword" class="btn btn-outline-maroon-700" style="text-decoration: none">Reset!
                </a>
                <a href="{{ route('auth.signup') }}" class="btn btn-outline-navy-700" style="text-decoration: none">Sign Up
                </a>
            </div>
        </div>
    </div>

@endsection
