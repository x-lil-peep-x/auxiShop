@extends('layouts.master')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($store->name) ? $store->name : 'Tienda' }}</h4>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('stores.store.index') }}" class="btn btn-primary" title="Show All Store">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('stores.store.create') }}" class="btn btn-success" title="Create New Store">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>

            </div>
        </div>

        <div class="panel-body">

            @if ($errors->any())
                <ul class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            @endif

            <form method="POST" action="{{ route('stores.store.update', $store->id) }}" id="edit_store_form" name="edit_store_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin.stores.form', [
                                        'store' => $store,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Actualizar">
                    </div>
                </div>
            </form>

        </div>
    </div>

@endsection
