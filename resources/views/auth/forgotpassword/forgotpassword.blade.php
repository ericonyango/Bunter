@extends('layouts.app')


@section('title','Forgot Password')

@section('content')

    <div class="row mt-4 justify-content-center" >
        <div class="col-md-3 text-center card bg-brown-700 text-gray-300 rounded shadow">
            <h2>Forgot your password?</h2>
            <div class="alert alert-warning">
                Note that you will not be able to read messages encrypted by the key from previous password.
            </div>
            <div class="mt-3">
                <p>Please choose how to recover it</p>

                <form method="GET" action="/forgotpassword/pgp">
                    <div class="form-group text-center mb-4">
                        <div class="row">
                            <button type="submit" class="btn btn-outline-gray-300 btn-block">PGP</button>
                        </div>
                    </div>
                </form>

                <form method="GET" action="/forgotpassowrd/mnemonic">
                    <div class="form-group text-center mb-4">
                        <div class="row">
                            <button type="submit" class="btn btn-outline-gray-300 btn-block">Mnemonic</button>
                        </div>
                    </div>
                </form>

            </div>
        </div>
    </div>

@endsection
