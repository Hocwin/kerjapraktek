<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8">
  <meta name="viewport" content="width=device-width, initial-scale=1.0">
  <meta http-equiv="X-UA-Compatible" content="ie=edge">
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
  <link rel="stylesheet" type="text/css" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css" integrity="sha512-DTOQO9RWCH3ppGqcWaEA1BIZOC6xxalwEsw9c2QQeAIftl+Vegovlnee1c9QX4TctnWMn13TZye+giMm8e2LwA==" crossorigin="anonymous" referrerpolicy="no-referrer" />
  <title>Amelia Putratama Mandiri</title>

  <style>
    .navbar {
      background-color: #D9EDEB;
      height: 70px;
      margin: 20px;
      border-radius: 16px;
      padding: 0.5rem;
    }

    .navbar-brand {
      font-weight: 500;
      color: #009978;
      font-size: 24px;
      transition: 0.3s color;
    }

    .login-button {
      background-color: #009978;
      color: #fff;
      font-size: 14px;
      padding: 8px 20px;
      border-radius: 50px;
      text-decoration: none;
      transition: 0.3s background-color;
    }

    .login-button:hover {
      background-color: #00b383;
    }

    .navbar-toggler {
      border: none;
      font-size: 1.25rem;
    }

    .nav-link {
      color: #666777;
      font-weight: 500;
    }

    .nav-link:hover,
    .nav-link.active {
      color: #000;
    }
  </style>
</head>

<body>
  <!-- Navbar -->
  <nav class="navbar navbar-expand-lg fixed-top">
    <div class="container">
      <a href="#"><img style="margin-right: 10px" src="https://cdn.discordapp.com/attachments/1022867386057101423/1120368429887660154/Logo.png" alt="" width="30px"></a>
      <a class="navbar-brand me-auto" href="#">Amelia Putratama Mandiri</a>
      <div class="offcanvas offcanvas-end" tabindex="-1" id="offcanvasNavbar">
        <div class="offcanvas-header">
          <h5 class="offcanvas-title">Menu</h5>
          <button type="button" class="btn-close" data-bs-dismiss="offcanvas"></button>
        </div>
        <div class="offcanvas-body">
          <ul class="navbar-nav justify-content-center flex-grow-1 pe-3 mt-1">
            @if (Auth::check())
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('produk')}}">Product</a>
            </li>
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('toko')}}">Toko</a>
            </li>
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('gudang')}}">Gudang</a>
            </li>
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('transaksi')}}">Transaksi</a>
            </li>
            @if (Auth::check() && (Auth::user()->rolePengguna == 'manager'))
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('karyawan')}}">Karyawan</a>
            </li>
            @endif
            <!-- Conditionally display "Performa Bisnis" only for admin or manager -->
            @if (Auth::check() && (Auth::user()->rolePengguna == 'admin' || Auth::user()->rolePengguna == 'manager'))
            <li class="nav-item">
              <a style="margin-top: 5px" class="nav-link mx-lg-2" href="{{route('performa_bisnis')}}">Performa Bisnis</a>
            </li>
            @endif
            <form class="d-flex mt-2" role="search" action="{{ route('search') }}" method="POST">
              @csrf
              <input type="text" name="search" placeholder="Cari..." required>
              <button class="btn btn-outline-success" type="submit">Search</button>
            </form>
            @endif
          </ul>
        </div>
      </div>
      @if (!Auth::check())
      <a href="{{ route('login') }}" class="login-button">Login</a>
      <button class="navbar-toggler pe-0" type="button" data-bs-toggle="offcanvas" data-bs-target="#offcanvasNavbar" aria-controls="offcanvasNavbar" aria-label="Toggle navigation">
        <span class="navbar-toggler-icon"></span>
      </button>
      @else
      <ul class="navbar-nav">
        <li class="nav-item dropdown">
          <a class="nav-link dropdown-toggle mx-lg-2" href="#" role="button" data-bs-toggle="dropdown" aria-expanded="false">
            {{ Auth::user()->namaPengguna }}
          </a>
          <ul class="dropdown-menu">
            <li><a class="dropdown-item" href="{{route('profile')}}">Profile</a></li>
            <li><a class="dropdown-item" href="{{ route('logout') }}">Logout</a></li>
          </ul>
        </li>
      </ul>
      @endif
    </div>
  </nav>
  <!-- End Navbar -->

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>