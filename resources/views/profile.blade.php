@extends('layout.master')
@section('title', 'Profil')

<style>
    * {
        margin: 0;
        padding: 0;
        box-sizing: border-box;
    }

    .container-profile {
        background: #f2f2f2;
        display: flex;
        justify-content: center;
        align-items: center;
        padding-top: 130px;
        padding-bottom: 70px;
    }

    .profile {
        width: 800px;
        min-height: 400px;
        overflow: hidden;
        border-radius: 5px;
        background: #222;
        display: flex;
        box-shadow: 15px 5px 30px rgba(0, 0, 0, 0.3);
    }

    .content {
        flex-basis: 50%;
        display: flex;
        justify-content: center;
        align-items: center;
        flex-direction: column;
        background: #fff;
    }

    .content img {
        width: 100%;
    }

    .profile-info {
        flex-basis: 50%;
        background: #F0FEFF;
        font-family: roboto;
        padding: 25px;
        padding-top: 70px;
        position: relative;
    }

    .profile-info td {
        padding-top: 15px;
        font-size: 1rem;
    }

    .profile-info th {
        padding-top: 20px;
        font-size: 1rem;
    }

    .edit-password-btn {
        position: absolute;
        top: 20px;
        right: 20px;
        padding: 10px 20px;
        background-color: #007bff;
        color: white;
        border: none;
        border-radius: 5px;
        cursor: pointer;
        font-size: 1rem;
        text-decoration: none;
        /* Menghapus underline */
    }

    .edit-password-btn:hover {
        background-color: #0056b3;
    }
</style>

@section('content')
<div class="container-profile">
    <div class="profile">
        <div class="content">
            <img src="https://img.freepik.com/free-vector/shopping-payment-online-process-computer-smartphone-tablet_1150-65523.jpg?size=626&ext=jpg&uid=R131304995&ga=GA1.2.137370369.1698152034&semt=sph" alt="">
        </div>
        <div class="profile-info">
            <!-- Button to Edit Password -->
            <a href="{{ route('gantiPassForm') }}" class="edit-password-btn">Edit Password</a>
            <table>
                <tr>
                    <td>
                        <h2><i class="fa-solid fa-circle-user me-2"></i></h2>
                    </td>
                    <td>
                        <h2>Profile</h2>
                    </td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-user"></i></td>
                    <th>Nama:</th>
                    <td>{{ Auth::user()->namaPengguna }}</td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-envelope"></i></td>
                    <th>Email:</th>
                    <td>{{ Auth::user()->emailPengguna }}</td>
                </tr>
                <tr>
                    <td><i class="fa-solid fa-address-book"></i></td>
                    <th>Address:</th>
                    <td>{{ Auth::user()->alamatPengguna }}</td>
                </tr>
                <tr>
                    <td colspan="3">
                        <br>
                        <form action="{{ route('logout') }}" method="POST">
                            @csrf
                            <button type="submit" class="btn btn-dark btn-sm" style="width:300px;">Logout</button>
                        </form>
                    </td>
                </tr>
            </table>
        </div>
    </div>
</div>
@endsection