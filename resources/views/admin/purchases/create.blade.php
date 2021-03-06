@extends('layouts.master')

@section('content')

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <span class="pull-left">
                <h4 class="mt-5 mb-5">Agregar nueva compra</h4>
            </span>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('purchases.purchase.index') }}" class="btn btn-primary" title="Show All Purchase">
                    <span class="fa fa-list" aria-hidden="true"></span>
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

            <form method="POST" action="{{ route('purchases.purchase.store') }}" accept-charset="UTF-8" id="create_purchase_form" name="create_purchase_form" class="form-horizontal">
            {{ csrf_field() }}
            @include ('admin.purchases.form', [
                                        'purchase' => null,
                                      ])

                <div class="form-group">
                    <div class="col-md-offset-2 col-md-10">
                        <input class="btn btn-primary" type="submit" value="Agregar">
                    </div>
                </div>

            </form>

        </div>
    </div>

@endsection

@section('scripts')
    <script src="{{asset('js/purchase/select2provider.js')}}"></script>
@endsection


