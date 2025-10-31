<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <title>{{ config('app.name') }}</title>
    <style>
        .alert {
            color: red;
            background-color: rgb(236, 207, 207);
            border: 1px solid red;
            margin-top: 5px
        }
    </style>
</head>

<body class="font-sans antialiased">
    @include('layouts.customnevigation')
    <main>
        <div class="container">

            {{-- @yield('components.style') --}}
            <x-ErrorsComponent />

            <div class="container py-1">
                <div class="card shadow-sm">
                    <div class="card-header bg-primary text-white">
                        <h5 class="m-0">OTP Verification</h5>
                    </div>
                    <div class="card-body">
                        <form action="{{ route('verify-otp') }}" method="POST" enctype="multipart/form-data">

                            @csrf

                            <input type="hidden" class="form-control" id="userEmail" name="userEmail"
                                value="{{ $userMail }}">
                            <div class="mb-3 row">
                                <label for="otp" class="col-sm-3 col-form-label">Enter OTP</label>
                                <div class="col-sm-9">
                                    <input type="number" class="form-control @error('otp') is-invalid @enderror"
                                        id="otp" name="otp" maxlength="6">
                                    @error('otp')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="text-end">
                                <button type="submit" class="btn btn-primary">Verify OTP</button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
        {{-- @yield('content') --}}
    </main>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI" crossorigin="anonymous">
    </script>
</body>

</html>
