@extends('layouts.profile')

@section('profile-content')
    @include('includes.flash.error')
    @include('includes.flash.success')

    <div class="bg-brown-600 card mt-4 ps-4">
        <h1 class="my-3 text-gray-300 text-center">Settings</h1>

        <h3 class="mt-4 text-gray-300 text-center">Change password</h3>
        <hr>
        <form action="{{ route('profile.password.change') }}" method="POST" class="justify-content-between">
            @csrf
            <div class="form-row my-2 text-gray-300 justify-content-center">
                <label for="old_password" class="col-form-label col-md-2">Old password:</label>
                <div class="col-md-3">
                    <input type="password" class="form-control" id="old_password" name="old_password" placeholder="Type the old password">
                </div>
            </div>
            <div class="form-row my-2">
                <label for="new_password" class="col-form-label col-md-2 text-gray-300">New password:</label>
                <div class="col-md-3 mb-4">
                    <input type="password" class="form-control @error('new_password', $errors) is-invalid @enderror" id="new_password" name="new_password" placeholder="Type new password">
                </div>
                <div class="col-md-3 ">
                    <input type="password" class="form-control @error('new_password', $errors) is-invalid @enderror" id="new_password_confirmation" name="new_password_confirmation" placeholder="Confirm new password">
                </div>
            </div>
            <div class="form-row text-right justify-content-between">
                <div class="col-md-9 text-left">
                    @error('new_password', $errors)
                    <p class="invalid-feedback d-block">{{ $errors -> first('new_password') }}</p>
                    @enderror
                </div>
                <div class="col-md-3">
                    <button class="btn btn-outline-gray-300" type="submit">Change password</button>
                </div>
            </div>
        </form>
        @if(\App\Marketplace\Utility\CurrencyConverter::isEnabled())
            @include('multicurrency::changeform')
        @endif
        <h3 class="mt-4 text-gray-300">Two Factor Authentication</h3>
        <hr>
        <div class="row">
            <div class="col-md-4 text-gray-300">
                <label>2-Factor Authentication:</label>
            </div>
            <div class="col-md-6 text-right">
                <div class="btn-group" role="group" aria-label="Basic example">
                    <a href="{{ route('profile.2fa.change', true) }}" class="btn @if(auth() -> user() -> login_2fa == true) btn-success @else btn-outline-gray-300 @endif">On</a>
                    <a href="{{ route('profile.2fa.change', 0) }}" class="btn @if(auth() -> user() -> login_2fa == false) btn-red-700 @else btn-outline-red-700 @endif">Off</a>
                </div>
            </div>
        </div>

        <h3 class="mt-4 text-gray-300">Referral link</h3>
        <hr>
        <div class="row">
            <div class="col-md-4">
                <input type="url" readonly class="form-control disabled" value="{{ route('auth.signup', auth() -> user() -> referral_code) }}">
                <p class="text-muted text-gray-300">Paste this address to other users who wants to sign up on the market!</p>
            </div>
        </div>

        <h3 class="mt-4 text-gray-300">Payment Addresses</h3>
        <hr>

        <div class="row">
            <div class="col-md-12">
                <form action="" method="POST">
                    @csrf
                    <div class="form-row">
                        <div class="col-md-4">
                            <input type="text" class="form-control form-control-lg d-flex" name="address" id="address" placeholder="Place your new address(pubkey) here">
                        </div>
                        <div class="col-md-2 mt-4">
                            <select name="coin" id="coin" class="form-control form-control-lg d-flex">
                                <option>Coin</option>
                                @foreach(config('coins.coin_list') as $supportedCoin => $instance)
                                    <option value="{{ $supportedCoin }}">{{ strtoupper(\App\Models\Address::label($supportedCoin)) }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="col-md-4">
                            <button class="btn btn-block btn-outline-gray-300 btn-lg mt-4">Change</button>
                        </div>
                    </div>
                </form>
                <p class="text-muted text-gray-300 mt-3">On this address you will receive payments from purchases! Funds will be sent to your most recent added address of coin!</p>


                @if(auth() -> user() -> addresses -> isNotEmpty())
                    <table class="table table-striped table-hover">
                        <thead>
                        <tr>
                            <th>Address</th>
                            <th>Coin</th>
                            <th class="text-right text-gray-300">Added</th>
                            <th></th>
                        </tr>
                        </thead>
                        <tbody>
                        @foreach(auth() -> user() -> addresses as $address)
                            <tr>
                                <td>
                                    <input type="text" readonly class="form-control" value="{{ $address -> address }}">
                                </td>
                                <td><span class="badge badge-info">{{ strtoupper($address -> coin) }}</span></td>
                                <td class="text-muted text-right">
                                    {{ $address -> added_ago }}
                                </td>
                                <td class="text-right"><a href="{{ route('profile.vendor.address.remove', $address) }}" class="btn btn-danger"><i class="fa fa-trash mr-1"></i>Remove</a></td>
                            </tr>
                        @endforeach
                        </tbody>

                    </table>
                @else
                    <div class="alert text-center alert-warning text-gray-300">You addresses list is empty!</div>
                @endif
            </div>
        </div>
    </div>

@endsection
