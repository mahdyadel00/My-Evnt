<div class="col-lg-12" >
    @if ($message = Session::get('success'))
        <div class="alert alert-success  alert-dismissible" role="alert">
            <p class="mb-0" style="margin-left:40px; margin-right:40px">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                fdprocessedid="dwqnc"></button>
        </div>
    @endif


    @if ($message = Session::get('error'))
        <div class="alert alert-danger  alert-dismissible" role="alert">
            <p class="mb-0" style="margin-left:5px">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                fdprocessedid="dwqnc"></button>
        </div>
    @endif


    @if ($message = Session::get('warning'))
        <div class="alert alert-warning  alert-dismissible" role="alert">
            <p class="mb-0" style="margin-left:5px">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                fdprocessedid="dwqnc"></button>
        </div>
    @endif


    @if ($message = Session::get('info'))
        <div class="alert alert-info  alert-dismissible" role="alert">
            <p class="mb-0" style="margin-left:5px">{{ $message }}</p>
            <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"
                fdprocessedid="dwqnc"></button>
        </div>
    @endif


        @if ($errors->any())
            <div class="alert alert-danger  alert-dismissible" role="alert">
                @foreach($errors->all() as $error)
                    <p class="mb-0" style="margin-left:5px">{{ $error }}</p>
                @endforeach
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close" fdprocessedid="dwqnc"></button>
            </div>
        @endif
</div>
