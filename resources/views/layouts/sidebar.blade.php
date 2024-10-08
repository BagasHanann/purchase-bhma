<aside class="main-sidebar">
    <!-- sidebar: style can be found in sidebar.less -->
    <section class="sidebar">

        <!-- Sidebar user panel (optional) -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="{{ asset('user-profile.png') }} " class="img-circle" alt="User Image">
            </div>
            <div class="pull-left info">
                <h4>{{ \Auth::user()->name  }}</h4>
            </div>
        </div>

        <ul class="sidebar-menu" data-widget="tree">
            <li class="{{ Request::is('home*') ? 'active' : '' }}">
                <a href="{{ url('/home') }}">
                    <i class="fa fa-dashboard"></i> 
                    <span>Dashboard</span>
                </a>
            </li>

            <li class="header" style="font-weight: bold;">MENU UTAMA</li>

            @if(\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('staff'))
            <li class="{{ Request::is('categories*') ? 'active' : '' }}">
                <a href="{{ route('categories.index') }}">
                    <i class="fa fa-list"></i> 
                    <span>Kategori</span>
                </a>
            </li>
            <li class="{{ Request::is('products*') && !Request::is('productsIn*') ? 'active' : '' }}">
                <a href="{{ route('products.index') }}">
                    <i class="fa fa-cubes"></i> 
                    <span>Jenis Produk</span>
                </a>
            </li>
            <li class="{{ Request::is('suppliers*') ? 'active' : '' }}">
                <a href="{{ route('suppliers.index') }}">
                    <i class="fa fa-truck"></i> 
                    <span>Supplier</span>
                </a>
            </li>
            <li class="{{ Request::is('productsIn*') ? 'active' : '' }}">
                <a href="{{ route('productsIn.index') }}">
                    <i class="fa fa-cart-plus"></i> 
                    <span>Pembelian Barang</span>
                </a>
            </li>
            @endif

            @if(\Auth::user()->hasRole('admin') || \Auth::user()->hasRole('field') || \Auth::user()->hasRole('staff'))
            <li class="{{ Request::is('requestItems*') ? 'active' : '' }}">
                <a href="{{ route('requestItems.index') }}">
                    <i class="fa fa-envelope"></i> 
                    <span>Permintaan Barang</span>
                    @if(isset($pendingRequestsCount) && $pendingRequestsCount > 0)
                        <span class="badge btn-danger">{{ $pendingRequestsCount }}</span>
                    @endif
                </a>
            </li>
            @endif 
            
            @if(\Auth::user()->hasRole('admin'))
            <li class="{{ Request::is('user*') ? 'active' : '' }}">
                <a href="{{ route('user.index') }}">
                    <i class="fa fa-user-secret"></i> 
                    <span>Daftar Pengguna</span>
                </a>
            </li>
            @endif
        </ul>
        <!-- /.sidebar-menu -->
    </section>
    <!-- /.sidebar -->
</aside>