<nav class="navbar navbar-expand-lg navbar-dark bg-gray-700 rounded shadow">
    <div class="container">
        <a class="navbar-brand" href="{{ route("home") }}">{{ config('app.name') }}</a>

        <input type="checkbox" id="navbar-toggle-cbox">


        <label class="navbar-toggler" for="navbar-toggle-cbox" data-toggle="collapse"
               data-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false"
               aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </label>

        <div class="collapse navbar-collapse" id="navbarSupportedContent">

            <ul class="navbar-nav mr-auto">
{{--                @admin--}}
                <li class="nav-item @isroute('admin') active @endisroute">
                    <a class="nav-link" href="#">Admin Panel</a>
                </li>
{{--                @endadmin--}}
{{--                @moderator--}}
                <li class="nav-item @isroute('admin') active @endisroute">
                    <a class="nav-link" href="#">Moderator panel</a>
                </li>
{{--                @endmoderator--}}
{{--                @auth--}}
                    <li class="nav-item @isroute('profile.tickets') active @endisroute">
                        <a class="nav-link" href="#">Support</a>
                    </li>
{{--                @endauth--}}

            </ul>

            <ul class="navbar-nav">
                @auth

                    <li class="nav-item @isroute('profile.notifications') active @endisroute">
                        <a href="#" class="nav-link">
                            <span class="text-warning" ><i class="fa fa-bell"></i></span>
                        </a>
                    </li>
                    <li class="nav-item text-center @isroute('profile.cart') active @endisroute">
                        <a class="nav-link w-100 text-black-50 bg-secondary" href="#">
                            <i class="fas fa-shopping-cart mr-2"></i>

                        </a>
                    </li>

                    <li class="nav-item @isroute('profile.index') active @endisroute">
                        <a class="nav-link" href="#"></a>
                    </li>

                    <form class="form-inline" action="{{route('auth.signout.post')}}" method="post">
                        @csrf
                        <button class="btn btn-link text-muted my-0" type="submit" style="text-decoration: none;">Sign Out</button>
                    </form>
                @else
                    <li class="nav-item">
                        <a class="nav-link" href="{{ route('auth.signin') }}">Sign In</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link" href="{{route('auth.signup')}}">Sign Up</a>
                    </li>
                @endauth
            </ul>

        </div>
    </div>
</nav>
