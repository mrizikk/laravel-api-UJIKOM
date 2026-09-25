<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login – Sistem Peminjaman Alat</title>

    <script src="https://cdn.tailwindcss.com"></script>
    <link rel="stylesheet" href="{{ asset('css/tema-peminjaman-alat.css') }}">

    <style>
        /* Penyesuaian khusus halaman login, memakai variabel dari tema-peminjaman-alat.css */
        .login-card {
            background: #fff;
            border: 1px solid rgba(27, 36, 48, .12);
            border-top: 4px solid var(--amber);
            position: relative;
            overflow: hidden;
        }

        /* Garis marka amber putus-putus di atas kartu, senada dengan header dashboard */
        .login-card::before {
            content: "";
            position: absolute;
            left: 0; right: 0; top: -3px;
            height: 3px;
            background: repeating-linear-gradient(
                -45deg,
                var(--amber) 0 10px,
                #a86e12 10px 20px
            );
            opacity: .55;
        }

        .login-icon {
            background: linear-gradient(180deg, var(--baja-sedang) 0%, var(--baja-gelap) 100%);
            color: var(--amber);
            width: 3.25rem;
            height: 3.25rem;
            border-radius: 9999px;
            display: flex;
            align-items: center;
            justify-content: center;
            margin: 0 auto 1rem auto;
            border: 2px solid var(--amber);
        }

        .login-label {
            color: var(--teks);
        }

        .login-input {
            background: var(--papan-terang);
            border: 1px solid rgba(27, 36, 48, .2);
            color: var(--teks);
        }

        .login-input:focus {
            outline: none;
            border-color: var(--amber);
            box-shadow: 0 0 0 3px var(--amber-lembut);
        }

        .login-button {
            background: var(--amber);
            color: #fff;
            transition: background-color .15s ease;
        }

        .login-button:hover {
            background: #a86e12;
        }

        .login-alert {
            background: #f7e3df;
            border: 1px solid rgba(163, 58, 43, .4);
            border-left: 4px solid #a33a2b;
            color: #6b2519;
        }

        /* Tombol toggle lihat/sembunyikan password */
        .toggle-password {
            color: var(--teks-redup, #6b7280);
            transition: color .15s ease;
        }

        .toggle-password:hover {
            color: var(--amber);
        }
    </style>
</head>
<body class="bg-gray-100 flex items-center justify-center h-screen">

    <div class="login-card p-8 rounded-lg shadow-md w-96">

        <div class="login-icon">
            <!-- ikon kunci pas sederhana -->
            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                <path d="M14.7 6.3a1 1 0 0 0 0 1.4l1.6 1.6a1 1 0 0 0 1.4 0l3.77-3.77a6 6 0 0 1-7.94 7.94l-6.91 6.91a2.12 2.12 0 0 1-3-3l6.91-6.91a6 6 0 0 1 7.94-7.94l-3.76 3.76z"></path>
            </svg>
        </div>

        <h3 class="text-2xl font-bold text-center mb-1" style="color: var(--baja-gelap);">Login Sistem</h3>
        <p class="text-center text-sm mb-6" style="color: var(--teks-redup);">Sistem Peminjaman Alat</p>

        @if(session('error'))
            <div class="login-alert mb-4 p-3 rounded text-sm">
                {{ session('error') }}
            </div>
        @endif

        @if($errors->any())
            <div class="login-alert mb-4 p-3 rounded text-sm">
                <ul class="list-disc pl-5 mb-0">
                    @foreach($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('login') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label class="login-label block text-sm font-semibold mb-2">Email / Nama</label>
                <input type="text" name="login" value="{{ old('login') }}" required autofocus
                    class="login-input w-full px-3 py-2 rounded-lg">
            </div>

            <div class="mb-6">
                <label class="login-label block text-sm font-semibold mb-2">Password</label>
                <div class="relative">
                    <input type="password" name="password" id="password" required
                        class="login-input w-full px-3 py-2 pr-10 rounded-lg">
                    <button type="button" id="togglePassword"
                        class="toggle-password absolute inset-y-0 right-0 flex items-center pr-3"
                        tabindex="-1" aria-label="Lihat password">
                        <!-- Ikon mata terbuka (default, password tersembunyi) -->
                        <svg id="eyeOpen" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                            <path d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <!-- Ikon mata tercoret (saat password terlihat) -->
                        <svg id="eyeClosed" xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 hidden" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M13.875 18.825A10.05 10.05 0 0112 19c-4.478 0-8.268-2.943-9.542-7a10.025 10.025 0 012.174-3.362m3.196-2.12A9.958 9.958 0 0112 5c4.478 0 8.268 2.943 9.542 7a9.964 9.964 0 01-4.132 5.411M3 3l18 18" />
                        </svg>
                    </button>
                </div>
            </div>

            <button type="submit"
                class="login-button w-full font-semibold py-2 rounded-lg">
                Masuk
            </button>
        </form>
    </div>

    <script>
        const togglePassword = document.getElementById('togglePassword');
        const passwordInput = document.getElementById('password');
        const eyeOpen = document.getElementById('eyeOpen');
        const eyeClosed = document.getElementById('eyeClosed');

        togglePassword.addEventListener('click', function () {
            const isHidden = passwordInput.type === 'password';
            passwordInput.type = isHidden ? 'text' : 'password';
            eyeOpen.classList.toggle('hidden', isHidden);
            eyeClosed.classList.toggle('hidden', !isHidden);
            togglePassword.setAttribute('aria-label', isHidden ? 'Sembunyikan password' : 'Lihat password');
        });
    </script>

</body>
</html>