@if (\Session::has('success'))
<div class="alert alert-success alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong>{{ \Session::get('success') }}</strong>
</div>
@endif
@if (\Session::has('message'))
<div class="alert alert-info alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong>{{ \Session::get('message') }}</strong>
</div>
@endif
@if (\Session::has('error'))
<div class="alert alert-danger alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong>{{ \Session::get('error') }}</strong>
</div>
@endif
@if (session('status'))
<div class="alert alert-success alert-dismissible">
	<button type="button" class="close" data-dismiss="alert" aria-hidden="true">×</button>
    <strong>{{ session('status') }}</strong>
</div>
@endif