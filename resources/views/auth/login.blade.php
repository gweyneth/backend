<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="icon" href="{{ $perusahaan && $perusahaan->favicon ? asset('storage/' . $perusahaan->favicon) : asset('favicon.ico') }}" type="image/x-icon">
    <title>Login - {{ $perusahaan->nama_perusahaan ?? 'Digital Printing' }}</title>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <style>
        @import url('https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;600;700&display=swap');

        body {
            font-family: 'Poppins', sans-serif;
            background-color: #f8f9fa; /* Latar belakang abu-abu muda yang bersih */
            display: flex;
            justify-content: center;
            align-items: center;
            min-height: 100vh;
            margin: 0;
        }

        .login-container {
            background-color: #fff; /* Latar belakang form putih */
            padding: 40px;
            border-radius: 12px;
            box-shadow: 0 10px 30px rgba(0, 0, 0, 0.1);
            width: 400px;
            max-width: 90%;
            text-align: center;
            animation: fadeInScale 0.6s ease-out forwards;
        }

        @keyframes fadeInScale {
            from { opacity: 0; transform: scale(0.9); }
            to { opacity: 1; transform: scale(1); }
        }
        
        .company-logo {
            max-width: 150px;
            height: auto;
            margin-bottom: 20px;
        }

        .login-container h2 {
            margin-bottom: 30px;
            color: #333;
            font-size: 28px;
            font-weight: 700;
        }

        .form-group {
            margin-bottom: 25px;
            position: relative;
        }

        .form-group .input-icon {
            position: absolute;
            left: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
        }

        .form-group input {
            width: 100%;
            padding: 12px 12px 12px 45px;
            border: 1px solid #ddd;
            border-radius: 8px;
            box-sizing: border-box;
            font-size: 16px;
            transition: border-color 0.3s, box-shadow 0.3s;
        }
        
        .form-group input:focus {
            border-color: #3b5d50;
            box-shadow: 0 0 0 3px rgba(59, 93, 80, 0.2);
            outline: none;
        }
        
        .password-toggle {
            position: absolute;
            right: 15px;
            top: 50%;
            transform: translateY(-50%);
            color: #aaa;
            cursor: pointer;
        }

        .submit-button {
            width: 100%;
            padding: 12px;
            background-color: #3b5d50;
            color: white;
            border: none;
            border-radius: 8px;
            cursor: pointer;
            font-size: 18px;
            font-weight: 600;
            transition: background-color 0.3s, transform 0.2s;
        }
        .submit-button:hover {
            background-color: #2f4840;
            transform: translateY(-2px);
        }
    </style>
</head>
<body>
    <div class="login-container">
        @if ($perusahaan && $perusahaan->logo_login)
            <img src="{{ asset('storage/' . $perusahaan->logo_login) }}" alt="Logo Perusahaan" class="company-logo">
        @else
            <h2>Login Kasir</h2>
        @endif

        <form id="login-form" action="{{ route('login') }}" method="POST">
            @csrf
            <div class="form-group">
                <div class="input-icon-container">
                    <i class="fas fa-user input-icon"></i>
                    <input type="text" id="username" name="username" placeholder="Username" value="{{ old('username') }}" required autofocus>
                </div>
            </div>
            <div class="form-group">
                <div class="input-icon-container">
                    <i class="fas fa-lock input-icon"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                    <i class="fas fa-eye password-toggle" id="password-toggle-icon"></i>
                </div>
            </div>
            <button type="submit" class="submit-button">Login</button>
        </form>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const passwordInput = document.getElementById('password');
            const toggleIcon = document.getElementById('password-toggle-icon');
            toggleIcon.addEventListener('click', function() {
                const type = passwordInput.getAttribute('type') === 'password' ? 'text' : 'password';
                passwordInput.setAttribute('type', type);
                this.classList.toggle('fa-eye');
                this.classList.toggle('fa-eye-slash');
            });

            const Toast = Swal.mixin({
                toast: true,
                position: 'top-end',
                showConfirmButton: false,
                timer: 3000,
                timerProgressBar: true,
            });

            @if (session('success'))
                Toast.fire({ icon: 'success', title: '{{ session('success') }}' });
            @endif

            @if (session('error'))
                Toast.fire({ icon: 'error', title: '{{ session('error') }}' });
            @endif

            @error('username')
                Toast.fire({ icon: 'error', title: '{{ $message }}' });
            @enderror
        });
    </script>
</body>
</html>
