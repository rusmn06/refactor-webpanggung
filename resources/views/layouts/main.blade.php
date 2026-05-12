<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <title>@yield('title', 'SID Panggung')</title>

    <link href="{{ asset('template/vendor/fontawesome-free/css/all.min.css') }}" rel="stylesheet">
    <link href="{{ asset('template/css/sb-admin-2.min.css') }}" rel="stylesheet">

    {{-- ===== OVERRIDE WARNA UTAMA: HIJAU TEAL ===== --}}
    <style>
        :root {
            --primary: #1a7a5e;
            --primary-dark: #145e48;
            --primary-light: #c3ece3;
        }

        /* ── Sidebar ── */
        .bg-gradient-primary {
            background: linear-gradient(180deg, #1a7a5e 10%, #145e48 100%) !important;
            background-color: #1a7a5e !important;
        }

        /* ── Tombol ── */
        .btn-primary {
            background-color: #1a7a5e !important;
            border-color: #145e48 !important;
            color: #fff !important;
        }

        .btn-primary:hover,
        .btn-primary:focus,
        .btn-primary:active {
            background-color: #145e48 !important;
            border-color: #0f4535 !important;
        }

        .btn-outline-primary {
            color: #1a7a5e !important;
            border-color: #1a7a5e !important;
        }

        .btn-outline-primary:hover {
            background-color: #1a7a5e !important;
            color: #fff !important;
        }

        /* ── Teks ── */
        .text-primary {
            color: #1a7a5e !important;
        }

        a {
            color: #1a7a5e;
        }

        a:hover {
            color: #145e48;
        }

        /* ── Kartu border kiri ── */
        .border-left-primary {
            border-left: .25rem solid #1a7a5e !important;
        }

        /* ── Badge ── */
        .badge-primary {
            background-color: #1a7a5e !important;
        }

        /* ── Progress bar ── */
        .progress-bar,
        .bg-primary {
            background-color: #1a7a5e !important;
        }

        /* ── Form focus ── */
        .form-control:focus {
            border-color: #1a7a5e;
            box-shadow: 0 0 0 .2rem rgba(26, 122, 94, .25);
        }

        /* ── Pagination ── */
        .page-item.active .page-link {
            background-color: #1a7a5e !important;
            border-color: #1a7a5e !important;
        }

        .page-link {
            color: #1a7a5e !important;
        }

        .page-link:hover {
            color: #145e48 !important;
        }

        /* ── Nav aktif di sidebar ── */
        .sidebar .nav-item.active .nav-link {
            font-weight: 700;
        }

        .sidebar .nav-item .nav-link:hover,
        .sidebar .nav-item.active .nav-link {
            background: rgba(255, 255, 255, .15);
        }

        /* ── Topbar search ── */
        .navbar-search .btn {
            background-color: #1a7a5e !important;
        }

        /* ── Custom control (radio/checkbox) ── */
        .custom-control-input:checked~.custom-control-label::before {
            border-color: #1a7a5e !important;
            background-color: #1a7a5e !important;
        }

        /* ── Scroll to top button ── */
        a.scroll-to-top {
            background-color: #1a7a5e !important;
        }

        a.scroll-to-top:hover {
            background-color: #145e48 !important;
        }
    </style>
    @stack('styles')
</head>

<body id="page-top">
    <div id="wrapper">

        @include('layouts.sidebar')

        <div id="content-wrapper" class="d-flex flex-column">
            <div id="content">
                @include('layouts.navbar')

                <div class="container-fluid">
                    @yield('content')
                </div>
            </div>

            @include('layouts.footer')
        </div>
    </div>

    <a class="scroll-to-top rounded" href="#page-top">
        <i class="fas fa-angle-up"></i>
    </a>

    {{-- Modal Logout --}}
    <div class="modal fade" id="logoutModal" tabindex="-1" role="dialog" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Yakin ingin logout?</h5>
                    <button class="close" type="button" data-dismiss="modal">
                        <span>&times;</span>
                    </button>
                </div>
                <div class="modal-body">Sesi Anda akan diakhiri.</div>
                <div class="modal-footer">
                    <button class="btn btn-secondary" data-dismiss="modal">Batal</button>
                    <form action="{{ route('logout') }}" method="POST">
                        @csrf
                        <button class="btn btn-primary">Logout</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script src="{{ asset('template/vendor/jquery/jquery.min.js') }}"></script>
    <script src="{{ asset('template/vendor/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('template/vendor/jquery-easing/jquery.easing.min.js') }}"></script>
    <script src="{{ asset('template/js/sb-admin-2.min.js') }}"></script>

    {{-- Scripts tambahan dari halaman child --}}
    @stack('scripts')
</body>

</html>