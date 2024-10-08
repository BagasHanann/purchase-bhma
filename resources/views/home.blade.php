@extends('layouts.master')

@section('content')
<!-- Small boxes (Stat box) -->
    <div class="row">
        @auth
        @if(\Auth::user()->hasRole('admin'))
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-aqua">
                    <div class="inner">
                        <h3>{{ \App\Models\User::count() }}</h3>
                        <p>Daftar Pengguna</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-user-secret"></i>
                    </div>
                    <a href="/user" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif
        @if(\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('staff'))
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-green">
                    <div class="inner">
                        <h3>{{ \App\Models\Categories::count() }}</h3>
                        <p>Jenis Kategori</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-list"></i>
                    </div>
                    <a href="{{ route('categories.index') }}" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-yellow">
                    <div class="inner">
                        <h3>{{ \App\Models\Products::count() }}</h3>
                        <p>Total Produk</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-cubes"></i>
                    </div>
                    <a href="{{ route('products.index') }}" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
            <!-- ./col -->
            <div class="col-lg-3 col-xs-6">
                <!-- small box -->
                <div class="small-box bg-purple">
                    <div class="inner">
                        <h3>{{ \App\Models\Suppliers::count() }}</h3>
                        <p>Total Supplier</p>
                    </div>
                    <div class="icon">
                        <i class="fa fa-signal"></i>
                    </div>
                    <a href="{{ route('suppliers.index') }}" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
                </div>
            </div>
        @endif
        @endauth  
    </div>

    <div class="row">
        @auth
        @if(\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('staff'))
        <!-- ./col -->
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-maroon">
                <div class="inner">
                    <h3>{{ \App\Models\OrderItems::count() }}</h3>
                    <p>Total Pembelian</p>
                </div>
                <div class="icon">
                    <i class="fa fa-cart-plus"></i>
                </div>
                <a href="{{ route('productsIn.index') }}" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endif
        @endauth
        <!-- ./col -->
        @auth
        @if(\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('field') || \Auth::user()->hasRole('staff'))
        <div class="col-lg-3 col-xs-6">
            <!-- small box -->
            <div class="small-box bg-red">
                <div class="inner">
                    <h3>{{ \App\Models\RequestItems::count() }}</h3>
                    <p>Total Permintaan</p>
                </div>
                <div class="icon">
                    <i class="fa fa-envelope"></i>
                </div>
                <a href="{{ route('requestItems.index') }}" class="small-box-footer">Info <i class="fa fa-arrow-circle-right"></i></a>
            </div>
        </div>
        @endif
        @endauth
    </div>

@endsection
