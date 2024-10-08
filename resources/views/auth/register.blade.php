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
    .invalid-feedback {
        margin-top: 5px;
        margin-bottom: 0;
    }
</style>
@endsection

@section('content')
<div class="row justify-content-center">
    <div class="col-md-6">
        <div class="box box-success">
            <div class="box-header">
                <h3 class="box-title">Tambah Pengguna</h3>
            </div>
            <div class="box-body">
                @if(session('success'))
                    <div class="alert alert-success">
                        {{ session('success') }}
                    </div>
                @endif
                <form class="form-auth-small" method="POST" action="{{ route('register') }}">
                    @csrf
                    <div class="form-group">
                        <label for="signup-email" class="control-label sr-only">Name</label>
                        <input type="text" class="form-control{{ $errors->has('name') ? ' is-invalid' : '' }}" id="signup-email" name="name" value="{{ old('name') }}" required autofocus placeholder="Name">
                        @if ($errors->has('name'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('name') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="signup-nip" class="control-label sr-only">NIP</label>
                        <input type="text" class="form-control{{ $errors->has('nip') ? ' is-invalid' : '' }}" id="signup-nip" name="nip" value="{{ old('nip') }}" required placeholder="NIP">
                        @if ($errors->has('nip'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('nip') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="signup-password" class="control-label sr-only">Password</label>
                        <input type="password" class="form-control{{ $errors->has('password') ? ' is-invalid' : '' }}" id="signup-password" name="password" required placeholder="Password">
                        <i class="fa fa-eye toggle-password" onclick="togglePasswordVisibility('signup-password')"></i>
                        @if ($errors->has('password'))
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $errors->first('password') }}</strong>
                        </span>
                        @endif
                    </div>
                    <div class="form-group">
                        <label for="signup-password-confirm" class="control-label sr-only">Confirm Password</label>
                        <input id="signup-password-confirm" type="password" class="form-control" placeholder="Confirm Password" name="password_confirmation" required>
                        <i class="fa fa-eye toggle-password" onclick="togglePasswordVisibility('signup-password-confirm')"></i>
                    </div>
                    <div class="form-group">
                        <label for="role" class="control-label sr-only">Role</label>
                        <select name="role" id="role" class="form-control" required>
                            <option value="">Select Role</option>
                            <option value="admin">Admin</option>
                            <option value="field">Field</option>
                            <option value="staff">Staff</option>
                        </select>
                    </div>
                    <div class="form-group">
                        <button type="submit" class="btn btn-primary">Register</button>
                        <a href="/user" class="btn btn-danger">Back</a>
                    </div>
                </form>
            </div>
        </div>
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
