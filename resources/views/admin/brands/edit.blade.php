@extends('layouts.master')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">{{ !empty($brand->name) ? $brand->name : 'Marca' }}</h4>
            </div>
            <div class="btn-group btn-group-sm pull-right" role="group">

                <a href="{{ route('brands.index') }}" class="btn btn-primary" title="Show All Brand">
                    <span class="fa fa-list" aria-hidden="true"></span>
                </a>

                <a href="{{ route('brands.create') }}" class="btn btn-success" title="Create New Brand">
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

            <form method="POST" action="{{ route('brands.update', $brand->id) }}" id="edit_brand_form" name="edit_brand_form" accept-charset="UTF-8" class="form-horizontal">
            {{ csrf_field() }}
            <input name="_method" type="hidden" value="PUT">
            @include ('admin.brands.form', [
                                        'brand' => $brand,
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
@section('scripts')
    <script src="{{asset('js/spare/showImage.js')}}">
    </script>
@endsection
