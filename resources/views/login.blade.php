@extends('layout.master')
@section('title' , 'Halaman Login')

@section('content')
<div class="container" style="display: flex; justify-content: center; align-items: center; height: 100vh;">
    <div class="row">
        <div class="col-12 border rounded pb-3 pt-3">
            <form action="{{ route('proses-login') }}" method="POST">
                @csrf
                <h3 class="text-center">Login</h3>
                @if ($errors->any())
                <div class="alert alert-danger">
                    @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                    @endforeach
                </div>
                @endif
                <div class="mb-3">
                    <label for="emailPengguna" class="form-label">Email address</label>
                    <input type="email" class="form-control" id="emailPengguna" name="emailPengguna" aria-describedby="emailHelp">
                </div>
                <div class="mb-3">
                    <label for="password" class="form-label">Password</label>
                    <input type="password" class="form-control" id="password" name="password">
                </div>
                <div class="mb-3 form-check">
                    <input type="checkbox" class="form-check-input" id="robot" onchange="toggleSubmitButton()">
                    <label class="form-check-label" for="robot">I'm not robot</label>
                </div>
                <div class="text-center">
                    <button type="submit" class="btn btn-primary" id="submitButton" disabled>Submit</button>
                </div>
            </form>
        </div>
        <div class="col-4"></div>
    </div>
</div>
<script>
    function toggleSubmitButton() {
        var checkbox = document.getElementById('robot');
        var submitButton = document.getElementById('submitButton');

        submitButton.disabled = !checkbox.checked;
    }
</script>
@endsection