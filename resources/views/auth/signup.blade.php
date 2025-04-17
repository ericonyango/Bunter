@extends('layouts.app')

@section('content')
    <div class="row mt-4 justify-content-center">
        <div class="col-md-3 offset-md-3 card bg-brown-600 rounded shadow">
            <h2 class="text-center text-gray-300">Sign Up</h2>


            <div class="mt-3">
                <form action="{{ route('auth.signup.post') }}" method="post">
                    @csrf

                    <div class="form-group mb-4">
                        <input type="text" class="form-control @if($errors->has('username')) is-invalid @endif" placeholder="Username" name="username" id="username">
                        @if($errors->has('username'))
                            <p class="text-gray-200">{{$errors->first('username')}}</p>
                        @endif
                    </div>
                    <div class="form-row">
                        <div class="col">
                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="Password" name="password"
                                   id="password">
                        </div>
                        <div class="col mt-4">
                            <input type="password" class="form-control @if($errors->has('password')) is-invalid @endif" placeholder="Confirm Password"
                                   name="password_confirmation" id="password_confirm">
                        </div>

                    </div>
                    @if($errors->has('password'))
                        <p class="text-gray-200">{{$errors->first('password')}}</p>
                    @endif
                    <div class="form-group mt-4 mb-4">
                        <span class="text-gray-300">
                            Your private key for decrypting messages will be protected with your password. Please make
                            sure that you choose a strong one
                        </span>
                    </div>
                    <div class="form-group">
                        <div class="input-group mb-4">
                            <div class="input-group-prepend">
                                <div class="input-group-text">Referral Code</div>
                            </div>
                            <input type="text" name="refid" value="{{$refid}}" class="form-control"
                                   @if($refid !== '') readonly @endif>
                        </div>

                    </div>
                    @include('includes.captcha')
                    @if($errors->has('captcha'))
                        <p class="text-gray-200">{{$errors->first('captcha')}}</p>
                    @endif
                    <div class="form-group text-center mt-4">
                        <div class="row">
                            <div class="col-xs-12 col-md-4 offset-md-4">
                                <button type="submit" class="btn btn-green-900 mb-2">Sign Up</button>
                            </div>
                        </div>
                    </div>
                    <div class="form-group text-center text-bg-gray-300 mb-4">
                        <a href="{{ route('auth.signin') }}" class="btn btn-outline-fuchsia-800">Sign In</a>
                    </div>
                </form>
            </div>

        </div>
    </div>

@endsection
