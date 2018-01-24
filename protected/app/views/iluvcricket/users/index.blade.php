@extends('iluvcricket/layout')


@section('content')
	<h1>All users</h1>
	<hr/>
	<div class="row">
		@include('iluvcricket.users.partials.userDownloadOptions')
	</div>
	@include('iluvcricket.users.partials.userList')
@stop
