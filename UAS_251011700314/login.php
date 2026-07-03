<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Page login</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.2/css/all.min.css">
</head>

<body>
    <style>
        body {
            background:
                linear-gradient(135deg,
                    #030a1a,
                    #c7c4cc);

            height: 100vh;
        }

        .login-card {

            width: 420px;

            border-radius: 25px;

            background:
                rgba(255,
                    255,
                    255,
                    .15);

            backdrop-filter:
                blur(15px);

            color: white;
        }
    </style>

    <div class="container h-100 d-flex justify-content-center align-items-center">

        <div class="card border-0 shadow-lg p-5"
            style="
            width: 420px;
            border-radius: 25px;
            background: rgba(255,255,255,0.15);
            backdrop-filter: blur(15px);
            color: white;
        ">

            <div class="text-center mb-4">
                <h2 class="fw-bold">Login</h2>
                <p>Silakan masuk</p>
            </div>

            <form action="login_file/proses_login.php" method="POST">

                <div class="mb-3">
                    <label class="form-label">
                        Username
                    </label>

                    <input
                        type="text"
                        name="username"
                        class="form-control form-control-lg"
                        placeholder="Masukkan username"
                        required>
                </div>

                <div class="mb-4">
                    <label class="form-label">
                        Password
                    </label>

                    <input
                        type="password"
                        name="password"
                        class="form-control form-control-lg"
                        placeholder="Masukkan password"
                        required>
                </div>

                <button
                    type="submit"
                    class="btn btn-light w-100 fw-bold py-2">
                    Login
                </button>

            </form>

            <div class="text-center mt-3">

                <small>
                    Belum punya akun?

                    <a href="#" class="text-white">
                        Daftar
                    </a>

                </small>
                <div class="input-group">

                    <span class="input-group-text">

                        <i class="fa-solid fa-user"></i>

                    </span>

                    <input
                        type="text"
                        class="form-control">

                </div>

            </div>

        </div>

    </div>

</body>

</html>