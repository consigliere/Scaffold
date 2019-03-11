@extends('scaffold::layouts.master')
<!--
  ~ index.blade.php
  ~ Created by @anonymoussc on 03/11/2019 7:31 PM.
  -->
@section('content')
    <h1>Hello World</h1>

    <p>
        This view is loaded from component: {!! config('scaffold.name') !!}
    </p>
@stop
