@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
@endsection
@section('content')

    @if(Session::has('success_message'))
        <div class="alert alert-success">
            <span class="glyphicon glyphicon-ok"></span>
            {!! session('success_message') !!}

            <button type="button" class="close" data-dismiss="alert" aria-label="close">
                <span aria-hidden="true">&times;</span>
            </button>

        </div>
    @endif

    <div class="panel panel-default">

        <div class="panel-heading clearfix">

            <div class="pull-left">
                <h4 class="mt-5 mb-5">Proveedores</h4>
            </div>

            <div class="btn-group btn-group-sm pull-right" role="group">
                <a href="{{ route('providers.provider.create') }}" class="btn btn-success" title="Create New Provider">
                    <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>

        </div>

        @if(count($providers) == 0)
            <div class="panel-body text-center">
                <h4>No hay proveedores.</h4>
            </div>
        @else
        <div class="panel-body panel-body-with-table">

                <table class="table table-hover table-bordered table-responsive-md" id="table">
                    <thead class="bg-dark">
                        <tr>
                            <th>Compañía</th>
                            <th>Nombre completo</th>
                            <th>Ocupacion</th>
                            <th>Telefono</th>
                            <th>Compras</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($providers as $provider)
                        <tr>
                            <td>{{ $provider->company_name }}</td>
                            <td>{{ $provider->get_full_name() }}</td>
                            <td>{{ $provider->occupation }}</td>
                            <td>{{ $provider->phone }}</td>
                            <td>{{ $provider->count_purchases() }}</td>
                            <td>

                                <form method="POST" action="{!! route('providers.provider.destroy', $provider->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('providers.provider.show', $provider->id ) }}" class="btn btn-info" title="Show Provider">
                                            <span class="fa fa-eye" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('providers.provider.edit', $provider->id ) }}" class="btn btn-primary" title="Edit Provider">
                                            <span class="fa fa-pen" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Provider" onclick="return confirm(&quot;Click Ok to delete Provider.&quot;)">
                                            <span class="fa fa-trash" aria-hidden="true"></span>
                                        </button>
                                    </div>

                                </form>

                            </td>
                        </tr>
                    @endforeach
                    </tbody>
                </table>
        </div>

        <div class="panel-footer">
            {!! $providers->render() !!}
        </div>

        @endif

    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/spare/datatables.min.js') }}"></script>
    <script src="{{ asset('js/spare/datatableconfig.js') }}"></script>

@endsection

