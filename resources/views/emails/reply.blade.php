@extends('beautymail::templates.ark')

@section('content')

    @include('beautymail::templates.ark.heading', [
		'heading' => "Hello, $name" ,
		'level' => 'h1'
	])

    @include('beautymail::templates.ark.contentStart')

    <p><i>{{$question}}</i></p>
    <h4 class="secondary"><strong>Thank you for getting in touch!</strong></h4>
    <p>{{ $data }}</p>

    @include('beautymail::templates.ark.contentEnd')


    @include('beautymail::templates.ark.contentStart')
    <h2 class="primary"> <a href="{{ config('app.client_url') }}">{{config('app.name')}}</a> team</h2>

    <h4 class="secondary">We appreciate you contacted us about.</h4>
    <h4 class="secondary"><strong>Have a great day!</strong></h4>
    <p>Please do not reply to it.</p>

    @include('beautymail::templates.ark.contentEnd')

@stop
