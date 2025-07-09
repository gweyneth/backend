<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Kasir - Digital Printing</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background: linear-gradient(135deg, #71b7e6, #9b59b6);
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
            overflow: hidden;
        }

        .login-container {
            background-color: #fff;
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.2);
            width: 380px;
            max-width: 90%;
            text-align: center;
            position: relative;
            z-index: 1;
            transform: scale(0.95);
            animation: fadeInScale 0.6s ease-out forwards;
        }

        @keyframes fadeInScale {
            from {
                opacity: 0;
                transform: scale(0.8);
            }
            to {
                opacity: 1;
                transform: scale(1);
            }
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
            font-weight: 700;
            position: relative;
        }

        .login-container h2::after {
            content: '';
            position: absolute;
            width: 60px;
            height: 4px;
            background-color: #007bff;
            bottom: -10px;
            left: 50%;
            transform: translateX(-50%);
            border-radius: 2px;
        }

        .form-group {
            margin-bottom: 25px;
            text-align: left;
            position: relative;
        }

        .form-group label {
            display: block;
            margin-bottom: 8px;
            color: #555;
            font-weight: 600;
        }

        .form-group .input-icon-container {
            position: relative;
        }

        .form-group .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            font-size: 18px;
            pointer-events: none;
        }

        .form-group input[type="text"],
        .form-group input[type="password"] {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: all 0.3s ease;
            box-shadow: inset 0 1px 3px rgba(0, 0, 0, 0.05);
        }

        .form-group input[type="text"]:focus,
        .form-group input[type="password"]:focus {
            border-color: #007bff;
            box-shadow: 0 0 0 3px rgba(0, 123, 255, 0.25);
            outline: none;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            background: linear-gradient(45deg, #007bff, #0056b3);
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: all 0.3s ease;
            box-shadow: 0 4px 15px rgba(0, 123, 255, 0.3);
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .submit-button:hover {
            background: linear-gradient(45deg, #0056b3, #007bff);
            box-shadow: 0 6px 20px rgba(0, 123, 255, 0.4);
            transform: translateY(-2px);
        }

        @media (max-width: 480px) {
            .login-container {
                padding: 30px;
                width: 95%;
            }
            .login-container h2 {
                font-size: 24px;
            }
            .form-group input {
                padding: 10px 10px 10px 40px;
                font-size: 15px;
            }
            .form-group .input-icon {
                font-size: 16px;
                left: 12px;
            }
            .submit-button {
                padding: 10px;
                font-size: 16px;
            }
        }
    </style>
</head>
<body>
    <div class="login-container">
        <h2>Login Kasir</h2>

        <form id="login-form" action="{{ route('login.post') }}" method="POST">
            @csrf
            <div class="form-group">
                <label for="username">Username:</label>
                <div class="input-icon-container">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" value="{{ old('username') }}" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <label for="password">Password:</label>
                <div class="input-icon-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" required>
                </div>
            </div>
            {{-- Tombol login sekarang langsung submit form --}}
            <button type="submit" class="submit-button">Login</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Inisialisasi SweetAlert2 Toast
            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end', // Posisi di kanan atas
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
                didOpen: (toast) => {
                    toast.addEventListener('mouseenter', Swal.stopTimer);
                    toast.addEventListener('mouseleave', Swal.resumeTimer);
                }
            });

            // Tampilkan pesan sukses dari sesi (misal: setelah berhasil login)
            @if (session('success'))
                Toast.fire({
                    icon: 'success',
                    title: '{{ session('success') }}'
                });
            @endif

            // Tampilkan pesan error dari sesi (misal: username/password salah)
            @if (session('error'))
                Toast.fire({
                    icon: 'error',
                    title: '{{ session('error') }}'
                });
            @endif

            // Menampilkan pesan validasi error dari Laravel (`$errors->any()`)
            // Setiap error akan ditampilkan sebagai toast terpisah
            @if ($errors->any())
                @foreach ($errors->all() as $error)
                    Toast.fire({
                        icon: 'error',
                        title: '{{ $error }}'
                    });
                @endforeach
            @endif
        });
    </script>
</body>
</html>
