<div class="container-fluid bg-brown-700 rounded py-4 shadow mb-4">
    <div class="container ">
        <div class="row justify-content-center">
            <div class="col-md-6">
                <form action="#" method="POST" class="form-inline h-100">
                    @csrf
                    <div class="input-group w-100">
                        <input type="text" class="form-control form-control" id="search" name="search"
                               placeholder="Search for something..." value="{{app('request')->input('query')}}">
                        <div class="input-group-append">
                            <button class="btn  btn-lime-600">Search</button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>
