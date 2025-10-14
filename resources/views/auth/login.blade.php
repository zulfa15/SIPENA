<!doctype html>
<html lang="id">

<head>
  <meta charset="utf-8">
  <meta name="viewport"
    content="width=device-width, initial-scale=1, minimum-scale=1, maximum-scale=1, viewport-fit=cover" />
  <title>SIPENA</title>
  <link rel="stylesheet" href="{{ asset('assets/css/style.css') }}">
  <style>
    body {
      margin: 0;
      font-family: Arial, sans-serif;
      background-color: #f5f5f5;
    }

    /* Bagian header hijau */
    .header {
      background-color: #1B7D7E;
      text-align: center;
      padding: 60px 20px 80px;
      border-bottom-left-radius: 0px;
      border-bottom-right-radius: 00px;
      position: relative;
    }

    .header img {
      width: 90px;
      margin-bottom: 10px;
    }

    .header h1 {
      color: #fff;
      font-size: 22px;
      margin: 0;
      font-weight: bold;
    }

    /* Kotak login */
    .login-container {
      max-width: 380px;
      margin: -60px auto 0;
      background: #fff;
      padding: 25px 20px 35px;
      border-radius: 16px;
      box-shadow: 0 4px 10px rgba(0, 0, 0, 0.1);
    }

    .login-container h2 {
      font-size: 18px;
      font-weight: bold;
      margin-bottom: 20px;
      color: #000;
    }

    .form-group {
      margin-bottom: 15px;
    }

    .form-control {
      width: 100%;
      padding: 12px;
      border-radius: 10px;
      border: 1px solid #ccc;
      font-size: 14px;
      box-sizing: border-box;
    }

    .form-links {
      text-align: right;
      margin-top: -5px;
      margin-bottom: 20px;
    }

    .form-links a {
      font-size: 13px;
      color: #1B7D7E;
      text-decoration: none;
    }

    .btn-login {
      width: 100%;
      background-color: #F7A129;
      border: none;
      padding: 12px;
      border-radius: 10px;
      color: #fff;
      font-size: 16px;
      font-weight: bold;
      cursor: pointer;
      transition: background 0.3s;
    }

    .btn-login:hover {
      background-color: #e6951f;
    }
  </style>
</head>

<body>
  <div class="header">
    <img src="{{ asset('assets/img/login/logo sementara.png') }}" alt="SIPENA Logo">
    <h1>SIPENA</h1>
  </div>

  <div style="
    background-color: #FFFFFF; /* Ganti dengan warna putih jika diperlukan untuk kontras */
    margin-top: -30px; /* Tarik ke atas agar melengkung di bawah header */
    border-top-left-radius: 30px; 
    border-top-right-radius: 30px; 
    background-color: #EEEEEE; /* Warna background dashboard */
    padding: 20px 20px 0 20px; /* Padding di sekitar konten */
    z-index: 1; /* Pastikan berada di atas header yang tidak memiliki z-index tinggi */
    position: relative; /* Diperlukan agar z-index bekerja */
">
  <br>
  <br>
  <br>
  <div class="login-container">
      <h2>Masuk ke akunmu</h2>
      @php
      $messagewarning = Session::get('warning');
      @endphp
      @if (Session::get('warning'))
      <div class="alert-outline-warning">
          {{ $messagewarning }}
      </div>
      @endif
      <form action="/proseslogin" method="POST">
          @csrf
        <div class="form-group">
          <input type="text" name="nik" class="form-control" id="nik" placeholder="NIK">
        </div>
        <div class="form-group">
          <input type="password" name='password' class="form-control" id="password" placeholder="Password">
        </div>
        <div class="form-links">
          <a href="#">Lupa Password</a>
        </div>
        <button type="submit" class="btn-login">Masuk</button>
      </form>
    </div>
  </body>

</div>




  

</html>
