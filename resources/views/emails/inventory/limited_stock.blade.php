@extends('beautymail::templates.widgets')

@section('content')

	@include('beautymail::templates.widgets.articleStart')

		<h4 class="secondary"><strong>Hello Seller</strong></h4>
		<p>This is an email to inform you about inventory status of ingredient named {{$data['productName']}}</p>

	@include('beautymail::templates.widgets.articleEnd')


	@include('beautymail::templates.widgets.newfeatureStart')

		<h4 class="secondary"><strong>Inventory Status</strong></h4>
		<p>The stock level of inventory with id {{$data['inventoryId']}} has reached 50% or Less</p>

	@include('beautymail::templates.widgets.newfeatureEnd')

@stop