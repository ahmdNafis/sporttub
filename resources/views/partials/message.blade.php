@if(session('fail_status') || session('success_status'))
    <div class="row message">
        <div class="col-md-12">
            <div class="alert {{ session('fail_status') ? 'alert-warning' : 'alert-success' }}" role="alert">
                <p>{{ session('fail_status') ? session('fail_status') : session('success_status') }}</p>
            </div>
        </div>
    </div>
@endif