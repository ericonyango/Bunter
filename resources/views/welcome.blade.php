@extends('layouts.app')

@section('title','Home Page')

@section('content')
    <div class="row">
        <div class="col-md-2 col-sm-12 bg-brown-700 rounded mx-3 pt-3" style="margin-top: 2.3em">
{{--            @include('includes.categories')--}}
        </div>
        <div class="col-md-9 col-sm-12 mt-3">

            <div class="row">
                <div class="col">
                    <h1 class="col-10">
                        Welcome to {{ config('app.name') }}
                    </h1>

                    <hr>
                </div>
            </div>

            <div class="row">
                <div class="col">
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Aliquam, aliquid cupiditate dolore enim et
                    eveniet fugiat illum ipsum itaque minus molestias nihil optio porro quisquam quo saepe sunt velit
                    veritatis erion thesting.
                </div>
            </div>
            <div class="row mt-5">
                <h4>
                    <i class="fa fa-money-bill-wave-alt text-info"></i>
                    No deposit
                </h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid dolorem hic nisi
                    ratione repellendus suscipit totam vitae!
                </p>
            </div>
            <div class="col-md-4">
                <h4>
                    <i class="fa fa-shield-alt text-info"></i>
                    Escrow
                </h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid dolorem hic nisi
                    ratione repellendus suscipit totam vitae!
                </p>
            </div>
            <div class="col-md-4">
                <h4><i class="fa fa-coins text-info"></i>
                    Multiple-Coins
                </h4>
                <p>
                    Lorem ipsum dolor sit amet, consectetur adipisicing elit. Accusantium aliquid dolorem hic nisi
                    ratione repellendus suscipit totam vitae!
                </p>
            </div>


            <div class="row">
                <div class="col">
                    <hr>
                </div>
            </div>
            {{--       feartured products --}}
{{--            @isModuleEnabled('FeaturedProducts')--}}
{{--            @include('featuredproducts::frontpagedisplay')--}}
{{--            @endisModuleEnabled--}}
            <div class="row mt-4">
                <div class="col-md-4">
                    <h4>
                        Top Vendors
                    </h4>
                    <hr>
                    {{--               foreach--}}
{{--                    @foreach(App\Models\Vendor::topVendors() as $vendor)--}}
                        <table class="table table-borderless table-hover">
                            <tr>
                                <td>
                                    <a href="#" style="text-decoration: none; color: #6c757d">
                                        Vendor Usernames
                                    </a>
                                </td>
                                <td class="text-end">
                           <span class="btn btn-sm btn-primary active"
                                 style="cursor: default">
                               Level of vendor
                           </span>
                                </td>
                            </tr>
                        </table>
{{--                    @endforeach--}}
                </div>

                <div class="col-md-4">
                    <h4>
                        Latest orders
                    </h4>
                    <hr>
                    {{--               foreach loop--}}
{{--                    @foreach(\App\Models\Purchase::latestOrders() as $order)--}}
                        <table class="table table-borderless table-hover">
                            <tr>
                                <td>
                                    <img class="img-fluid" height="23px" width="23px"
                                         src="#"
                                         alt="">
                                </td>
                                <td class="text-end">
                                    currency
                                </td>
                            </tr>
                        </table>
{{--                    @endforeach--}}
                </div>

                <div class="col-md-4">
                    <h4>
                        Rising Vendors
                    </h4>
                    <hr>
                    {{--               foreach loop--}}
{{--                    @foreach(\App\Models\Vendor::risingVendors() as $vendor)--}}
                        <table class="table table-borderless table-hover">
                            <tr>
                                <td>
                                    <a href="#" style="text-decoration: none; color: #6c757d">Vendor username</a>
                                </td>
                                <td class="text-end">
                           <span class="btn btn-sm btn-primary active"
                                 style="cursor: default">
                               Level of Vendor
                           </span>
                                </td>
                            </tr>
                        </table>
{{--                    @endforeach--}}
                </div>
            </div>
        </div>
    </div>
@endsection
