@extends('layouts.master')

@section('top')
<style type="text/css">
    .row-centered {
        text-align: center;
    }
    .col-centered {
        display: inline-block;
        float: none;
        text-align: left;
        margin-right: -4px;
    }
    .form-group {
        position: relative;
    }
    .form-group .toggle-password {
        position: absolute;
        top: 50%;
        right: 10px;
        transform: translateY(-50%);
        cursor: pointer;
    }
    .box {
        max-width: 600px;
    }
</style>
@endsection

@section('content')
<div class="box box-success">
    <div class="box-header">
        <h3 class="box-title">Edit Profil</h3>
    </div>
    <div class="box-body">
        @if(session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif
        <form method="POST" action="{{ route('update-profile') }}">
            @csrf
            @method('PATCH')
            
            <div class="form-group">
                <label for="name">Nama</label>
                <input type="text" id="name" name="name" class="form-control" value="{{ old('name', $user->name) }}" required>
            </div>

            <div class="form-group">
                <label for="nip">NIP</label>
                <input type="text" id="nip" name="nip" class="form-control" value="{{ old('nip', $user->nip) }}" required>
            </div>

            <div class="form-group">
                <label for="password">Password Baru</label>
                <input type="password" id="password" name="password" class="form-control" placeholder="Masukkan password baru">
                <i class="fa fa-eye toggle-password" onclick="togglePasswordVisibility('password')"></i>
            </div>

            <div class="form-group">
                <label for="password_confirmation">Konfirmasi Password Baru</label>
                <input type="password" id="password_confirmation" name="password_confirmation" class="form-control" placeholder="Konfirmasi password baru">
                <i class="fa fa-eye toggle-password" onclick="togglePasswordVisibility('password_confirmation')"></i>
            </div>

            <button type="submit" class="btn btn-primary">Submit</button>
        </form>
    </div>
</div>
@endsection

@section('bot')
<script>
    function togglePasswordVisibility(fieldId) {
        var field = document.getElementById(fieldId);
        var type = field.getAttribute('type') === 'password' ? 'text' : 'password';
        field.setAttribute('type', type);
        
        var icon = field.nextElementSibling;
        icon.classList.toggle('fa-eye');
        icon.classList.toggle('fa-eye-slash');
    }
</script>
@endsection
