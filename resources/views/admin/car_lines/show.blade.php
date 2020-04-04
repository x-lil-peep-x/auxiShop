@extends('layouts.app')

@section('content')

<div class="panel panel-default">
    <div class="panel-heading clearfix">

        <span class="pull-left">
            <h4 class="mt-5 mb-5">{{ isset($carLine->name) ? $carLine->name : 'Car Line' }}</h4>
        </span>

        <div class="pull-right">

            <form method="POST" action="{!! route('car_lines.car_line.destroy', $carLine->id) !!}" accept-charset="UTF-8">
            <input name="_method" value="DELETE" type="hidden">
            {{ csrf_field() }}
                <div class="btn-group btn-group-sm" role="group">
                    <a href="{{ route('car_lines.car_line.index') }}" class="btn btn-primary" title="Show All Car Line">
                        <span class="glyphicon glyphicon-th-list" aria-hidden="true"></span>
                    </a>

                    <a href="{{ route('car_lines.car_line.create') }}" class="btn btn-success" title="Create New Car Line">
                        <span class="glyphicon glyphicon-plus" aria-hidden="true"></span>
                    </a>
                    
                    <a href="{{ route('car_lines.car_line.edit', $carLine->id ) }}" class="btn btn-primary" title="Edit Car Line">
                        <span class="glyphicon glyphicon-pencil" aria-hidden="true"></span>
                    </a>

                    <button type="submit" class="btn btn-danger" title="Delete Car Line" onclick="return confirm(&quot;Click Ok to delete Car Line.?&quot;)">
                        <span class="glyphicon glyphicon-trash" aria-hidden="true"></span>
                    </button>
                </div>
            </form>

        </div>

    </div>

    <div class="panel-body">
        <dl class="dl-horizontal">
            <dt>Name</dt>
            <dd>{{ $carLine->name }}</dd>

        </dl>

    </div>
</div>

@endsection