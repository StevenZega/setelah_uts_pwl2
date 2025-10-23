<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Form')</title>

    {{-- Bootstrap & Font Awesome --}}
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.1/css/all.min.css">
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>

    <style>
        body {
            background: #bad4ff;
            color: #000;
            font-family: 'Poppins', sans-serif;
            overflow-x: hidden;
        }

        h3,
        h2 {
            color: #000;
            font-weight: 700;
            letter-spacing: 0.5px;
        }

        .card {
            border-radius: 15px;
            background: #fff;
            border: 1px solid #e0e0e0;
            box-shadow: 0px 4px 15px rgba(0, 0, 0, 0.08);
        }

        label {
            font-weight: 600;
            color: #2c3e50;
        }

        .btn-primary {
            background-color: #00b4d8;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-primary:hover {
            background-color: #0096c7;
        }

        .btn-warning {
            color: #fff;
            background-color: #f39c12;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-warning:hover {
            background-color: #d68910;
        }

        .btn-secondary {
            color: #fff;
            background-color: #6c757d;
            border: none;
            transition: all 0.3s ease;
        }

        .btn-secondary:hover {
            background-color: #565e64;
        }

        /* ===== LOADER ===== */
        #loader-wrapper {
            position: fixed;
            inset: 0;
            background: #161b22;
            display: flex;
            align-items: center;
            justify-content: center;
            z-index: 2000;
            transition: opacity 0.4s ease, visibility 0.4s ease;
        }

        .loader {
            border: 6px solid #2c2f33;
            border-top: 6px solid #00b4d8;
            border-radius: 50%;
            width: 60px;
            height: 60px;
            animation: spin 1s linear infinite;
        }

        @keyframes spin {
            to {
                transform: rotate(360deg);
            }
        }

        #loader-wrapper.hidden {
            opacity: 0;
            visibility: hidden;
        }
    </style>
</head>

<body>
    {{-- LOADER --}}
    <div id="loader-wrapper">
        <div class="loader"></div>
    </div>

    {{-- MAIN CONTENT --}}
    <div class="container mt-5 mb-5">
        @yield('content')
    </div>

    {{-- SCRIPTS --}}
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"></script>
    @stack('scripts') {{-- ✅ supaya script tambahan dari child blade bisa ditaruh di sini --}}
    <script>
        const loader = document.getElementById("loader-wrapper");

        // Loader saat halaman pertama kali dibuka
        window.addEventListener("load", () => {
            setTimeout(() => {
                loader.classList.add("hidden");

                // Notifikasi SweetAlert dari session
                @if(session('success'))
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil!',
                        text: "{{ session('success') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @elseif(session('error'))
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal!',
                        text: "{{ session('error') }}",
                        showConfirmButton: false,
                        timer: 2000
                    });
                @endif
            }, 400); // sedikit delay agar efek fade-out terlihat
        });

        // Loader aktif ketika form disubmit (selain form hapus)
        document.querySelectorAll("form").forEach(form => {
            form.addEventListener("submit", e => {
                // jika form punya class "form-delete", hentikan dulu (konfirmasi pakai swal)
                if (form.classList.contains('form-delete')) {
                    e.preventDefault();

                    const title = form.getAttribute('data-title') || 'data ini';

                    Swal.fire({
                        title: `Yakin ingin menghapus "${title}"?`,
                        text: "Data yang dihapus tidak dapat dikembalikan!",
                        icon: "warning",
                        showCancelButton: true,
                        confirmButtonColor: "#d33",
                        cancelButtonColor: "#3085d6",
                        confirmButtonText: "Ya, hapus!",
                        cancelButtonText: "Batal"
                    }).then((result) => {
                        if (result.isConfirmed) {
                            loader.classList.remove("hidden");
                            form.submit();
                        }
                    });
                } else {
                    loader.classList.remove("hidden");
                }
            });
        });
    </script>
</body>

</html>