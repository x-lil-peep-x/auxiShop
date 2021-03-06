@extends('layouts.master')
@section('styles')
    <link rel="stylesheet" href="{{asset('css/datatables.min.css')}}">
@endsection
@section('page-title')
    Lista de respuestos
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
    @if(auth()->user()->Role->name == 'admin')
        <a href="{{route('spares.create')}}">
            <button class="btn btn-primary m-2">
                Agregar respuesto
                <span class="fa fa-plus"></span>
            </button>
        </a>
        <a href="{{route('brands.index')}}">
            <button class="btn btn-success m-2">
                Marcas
                <span class="fa fa-list"></span>
            </button>
        </a>
        <a href="{{route('car_lines.index')}}">
            <button class="btn btn-warning m-2">
                Linea de carros
                <span class="fa fa-list"></span>
            </button>
        </a>
        <a href="{{route('categories.index')}}">
            <button class="btn btn-secondary m-2">
                Categoria
                <span class="fa fa-list"></span>
            </button>
        </a>
    @endif
    <div class="container-fluid">
        <!-- Small boxes (Stat box) -->
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header bg-dark">
                        <h5>Filtros de busqueda:</h5>
                        <form action="{{route('filter-products')}}" method="get">
                            <div class="row">
                                <div class="form-group col-lg-4 col-6">
                                    <input value="{{ old('code') }}" type="text" name="code"
                                           class="form-control" placeholder="Codigo">

                                </div>
                                <div class="form-group col-lg-4 col-6">
                                    <input value="{{ old('description') }}" type="text" name="description"
                                           class="form-control" placeholder="Description">
                                </div>
                                <div class="form-group col-lg-4 col-6">
                                    <input value="{{ old('category') }}" type="text" name="category"
                                           class="form-control" placeholder="Categoria" list="categories">
                                    <datalist id="categories">
                                        @foreach($categories as $category)
                                            <option value="{{$category}}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="form-group col-lg-4 col-6">
                                    <input value="" type="text" name="brand"
                                           class="form-control" placeholder="Marca" list="brands">
                                    <datalist id="brands">
                                        @foreach($brands as $brand)
                                            <option value="{{$brand}}"></option>
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="form-group col-lg-4 col-6">
                                    <input value="{{ old('originalCode') }}" type="text" name="original_code"
                                           class="form-control" placeholder="Codigo original">
                                </div>
                                <div class="form-group col-lg-4 col-6">
                                    <input value="{{ old('measure') }}" type="text" name="measure"
                                           class="form-control" placeholder="Medida">
                                </div>

                            </div>
                            <input type="submit" class="btn btn-primary" value="Filtrar">
                        </form>
                    </div>
                    <!-- /.card-header -->
                    <div class="card-body">
                        <table id="table" class="table table-responsive table-hover table-bordered" style="width: 100%">
                            <thead class="bg-dark">
                            <tr>
                                <th width="5%">Codigo</th>
                                <th width="30%">Descripcion</th>
                                <th width="7%">Categoria</th>
                                <th>Marca</th>
                                <th>Medida</th>
                                <th width="5%">Codigo respuesto</th>
                                <th>Venta</th>
                                @if(auth()->user()->Role->name == 'admin')
                                    <th>Compra</th>
                                @endif
                                <th hidden>Cod. Original</th>
                                <th>Opciones</th>
                            </tr>
                            </thead>
                            <tbody>
                            @foreach($spares as $spare)
                                <tr>
                                    <td width="5%">{{ $spare->code }}</td>
                                    <td width="30%">{{ $spare->description }}</td>
                                    <td width="7%">{{ optional($spare->category)->name }}</td>
                                    <td>
                                            {{ optional($spare->brand)->name }}
                                            @if(isset($spare->brand->image))
                                                <img class="img-thumbnail img-size-50 align-items-end"
                                                     src="{{ optional($spare->brand)->image }}" alt="">
                                            @endif
                                    </td>
                                    <td>{{ $spare->measure }}</td>
                                    <td width="7%">{{ $spare->product_code }}</td>
                                    <td>{{ $spare->price }}</td>
                                    @if(auth()->user()->Role->name == 'admin')
                                        <td>{{ $spare->investment }}</td>
                                    @endif
                                    <td hidden>
                                        {{ $spare->original_code }}
                                    </td>
                                    <td>
                                        <form action="{{ route('spares.destroy', $spare->id) }}" method="POST">
                                            @csrf
                                            @method('DELETE')
                                            <div class="btn-group">

                                                <a class="btn btn-warning show-information" data-product="{{$spare}}"
                                                   data-carlines="{{$spare->car_lines}}"
                                                   data-stores="{{$spare->store_spare}}"
                                                >
                                                    <i class='fas fa-eye'></i>
                                                </a>
                                                @if(auth()->user()->Role->name == 'admin')
                                                    <a href="{{ route('spares.edit', $spare->id)}}"
                                                       class="btn btn-primary">
                                                        <i class='fas fa-pencil-alt'></i>
                                                    </a>
                                                @endif
                                                @if(auth()->user()->Role->name == 'admin')
                                                    <button type="submit" class="btn btn-danger"
                                                            data-toggle="modal" data-target="#deleteModal">
                                                        <i class='fas fa-trash-alt'></i>
                                                    </button>
                                                @endif
                                            </div>
                                        </form>
                                    </td>

                                </tr>
                            @endforeach
                            </tbody>
                            <tfoot>
                            <tr>
                                <th width="5%">Codigo</th>
                                <th width="30%">Decripcion</th>
                                <th width="7%">Categoria</th>
                                <th>Marca</th>
                                <th>Medida</th>
                                <th width="5%">Codigo respuesto</th>
                                <th>Venta</th>
                                @if(auth()->user()->Role->name == 'admin')
                                    <th>Compra</th>
                                @endif
                                <th hidden>Cod. Original</th>
                                <th>Opciones</th>
                            </tr>
                            </tfoot>
                        </table>
                    </div>
                    <!-- /.card-body -->
                </div>
            </div>
        </div>
        {{--        Modal to show the image--}}
        <div class="modal fade" id="info-modal" tabindex="-1" role="dialog" aria-labelledby="modal" aria-hidden="true">
            <div class="modal-dialog modal-lg" role="document">
                <div class="modal-content">
                    <div class="modal-header bg-secondary">
                        <strong><h3 class="modal-title" id="modal-label">

                            </h3></strong>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close" id="closeModal">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                        <div class="row mb-2">
                            <div class="col-7">
                                <img src="" alt="No image" class="img-fluid img-fluid" id="product-image">
                            </div>
                            <div class="col-5">
                                <ul class="list-group mb-1">
                                    <li class="list-group-item">
                                        <b>Codigo:</b> <a class="float-right" id="product-code-modal"></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Codigo original:</b> <a class="float-right"
                                                                   id="product-original-code-modal"></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Codigo producto:</b> <a class="float-right" id="product-pro-code-modal"></a>
                                    </li>
                                    <li class="list-group-item">
                                        <b>Descripcion:</b> <a class="float-right" id="product-description-modal"></a>
                                    </li>
                                </ul>
                            </div>
                        </div>

                        <div class="row">
                            <li class="list-group-item col-6">
                                <b>Categoria</b> <a class="float-right" id="product-category-modal"></a>
                            </li>
                            <li class="list-group-item col-6">
                                <b>Marca</b> <a class="float-right" id="product-brand-modal"></a>
                            </li>
                            <li class="list-group-item col-4">
                                <b>Medida</b> <a class="float-right" id="product-measure-modal"></a>
                            </li>
                            <li class="list-group-item col-4">
                                <b>Cantidad</b> <a class="float-right" id="product-quantity-modal"></a>
                            </li>
                            <li class="list-group-item col-4">
                                <b>Nacionalidad</b> <a class="float-right" id="product-brand-nationality"></a>
                            </li>
                            <li class="list-group-item col-4">
                                <b>Venta</b> <a class="float-right" id="product-price-modal"></a>
                            </li>
                            <li class="list-group-item col-4">
                                <b>Venta mayor</b> <a class="float-right" id="product-pricem-modal"></a>
                            </li>
                            <li class="list-group-item col-4">
                                @if(auth()->user()->Role->name == 'admin')
                                    <b>Compra</b> <a class="float-right" id="product-investment-modal"></a>
                                @endif
                            </li>
                            <li class="list-group-item col-6">
                                <b>Fecha creacion</b> <a class="float-right" id="product-created-at-modal"></a>
                            </li>
                            <li class="list-group-item col-6">
                                <b>Ultima actulizacion</b> <a class="float-right" id="product-updated-at-modal"></a>
                            </li>
                            <li class="list-group-item col-12">
                                <b>Linea de carro</b> <a class="float-right" id="product-code-carline"></a>
                            </li>
                            {{--Tiendas--}}
                            <br>
                            <h6 class="text-bold text-center" id="quantityStores">Cantidades segun tiendas</h6>
                            @foreach($stores as $key=>$name )
                                <li class="list-group-item col-12">
                                    <b>{{$name}}</b> <a class="float-right stores_class" id="stores{{$key}}"></a>
                                </li>
                            @endforeach
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                    </div>
                </div>
            </div>
        </div>
        {{--        Modal to show the image--}}

    </div>
@endsection
@section('scripts')
    <script src="{{ asset('js/spare/datatables.min.js') }}"></script>
    <script src="{{ asset('js/spare/datatableconfig.js') }}"></script>
    <script src="{{ asset('js/spare/modalconfig.js') }}"></script>

@endsection

