@extends('voyager::master')

@section('css')
    <style>
        .user-email {
            font-size: .85rem;
            margin-bottom: 1.5em;
        }
    </style>
@stop

@section('content')
    <div style="background-size:cover; background-image: url({{ Voyager::image( Voyager::setting('admin.bg_image'), voyager_asset('/images/bg.jpg')) }}); background-position: center center;position:absolute; top:0; left:0; width:100%; height:300px;"></div>
    <div style="height:160px; display:block; width:100%"></div>
    <div style="position:relative; z-index:9; text-align:center;">
        <img src="@if( !filter_var(app('VoyagerAuth')->user()->avatar, FILTER_VALIDATE_URL)){{ Voyager::image( app('VoyagerAuth')->user()->avatar ) }}@else{{ app('VoyagerAuth')->user()->avatar }}@endif"
             class="avatar"
             style="border-radius:50%; width:150px; height:150px; border:5px solid #fff;"
             alt="{{ app('VoyagerAuth')->user()->name }} avatar">
        <h4>{{ ucwords(app('VoyagerAuth')->user()->name) }}</h4>
        <div class="user-email text-muted">{{ ucwords(app('VoyagerAuth')->user()->email) }}</div>
        <p>{{ app('VoyagerAuth')->user()->bio }}</p>
        <a href="{{ url('/') }}" class="btn btn-success"><i class="fas fa-home"></i> {{ __('voyager::generic.home') }}</a>
        <a href="{{ route('voyager.users.edit', app('VoyagerAuth')->user()->getKey()) }}" class="btn btn-primary">{{ __('voyager::profile.edit') }}</a>
        <form action="{{route('voyager.logout')}}" method="post">
            @csrf
            <button type="submit" class="btn btn-danger">{{ __('voyager::generic.logout') }}
        </form>


    </div>
@stop
