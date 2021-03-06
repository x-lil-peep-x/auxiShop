@extends('layouts.master')

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
                <h4 class="ml-3">Marcas</h4>
            </div>
            <a  class="btn btn-info ml-3" href="{{route('spares.index')}}" >
                Volver atras <span class="fa fa-backspace" aria-hidden="true"></span>
            </a>
            <div class="btn-group" role="group">
                <a href="{{ route('brands.create') }}" class="btn btn-success" title="Create New Brand">
                    Agregar marca <span class="fa fa-plus" aria-hidden="true"></span>
                </a>
            </div>
            <br><br>
        </div>

        @if(count($brands) == 0)
            <div class="panel-body text-center">
                <h4>No hay marcas disponibles.</h4>
            </div>
        @else
        <div class="card-body col-12">

                <table class="table table-striped table-bordered">
                    <thead>
                        <tr>
                            <th>Nombre</th>
                            <th>Pais</th>
                            <th>Imagen</th>

                            <th>Opciones</th>
                        </tr>
                    </thead>
                    <tbody>
                    @foreach($brands as $brand)
                        <tr>
                            <td>{{ $brand->name }}</td>
                            <td>{{ $brand->country }}</td>
                            <td>
                                <img class="img-fluid img-size-50" src="{{ $brand->image }}" alt="No hay imagen">
                            </td>

                            <td>

                                <form method="POST" action="{!! route('brands.destroy', $brand->id) !!}" accept-charset="UTF-8">
                                <input name="_method" value="DELETE" type="hidden">
                                {{ csrf_field() }}

                                    <div class="btn-group btn-group-xs pull-right" role="group">
                                        <a href="{{ route('brands.show', $brand->id ) }}" class="btn btn-info" title="Show Brand">
                                            <span class="fa fa-eye" aria-hidden="true"></span>
                                        </a>
                                        <a href="{{ route('brands.edit', $brand->id ) }}" class="btn btn-primary" title="Edit Brand">
                                            <span class="fas fa-pen-alt" aria-hidden="true"></span>
                                        </a>

                                        <button type="submit" class="btn btn-danger" title="Delete Brand" onclick="return confirm(&quot;Click Ok to delete Brand.&quot;)">
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
            {!! $brands->render() !!}
        </div>

        @endif

    </div>
@endsection
