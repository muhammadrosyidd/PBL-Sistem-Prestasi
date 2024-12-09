<?php
session_start(); // Mulai sesi

// Periksa role user
if (isset($_SESSION['role']) && $_SESSION['role'] == 1) {
?>
<aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i
            class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true"
            id="iconSidenav"></i>
        <a
            class="navbar-brand m-0"
            href="https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html"
            target="_blank">
            <img
                src="../../assets2/img/jti.png"
                width="30px"
                height="50px"
                class="navbar-brand-img h-100"
                alt="main_logo" />
            <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0" />
    <div class="w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php
            // Dapatkan URL saat ini
            $current_page = basename($_SERVER['PHP_SELF']);

            // Dapatkan role user
            $role = $_SESSION['role']; // Assumsi session 'role' sudah di set

            // Fungsi untuk memberikan kelas active
            function isActive($page)
            {
                global $current_page;
                return $current_page === $page ? 'active' : '';
            }
            ?>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive('dashboard.php'); ?>"
                    href="../pageSuperAdmin/dashboard.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            <?php if ($role == 1): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive(['dataPengguna.php', 'tambahPengguna.php', 'editPengguna.php']); ?>"
                        href="../pengguna/dataPengguna.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i
                                class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Data Admin</span>
                    </a>
                </li>
            <?php endif; ?>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataDosen.php', 'tambahDosen.php', 'editDosen.php']); ?>"
                    href="../dosen/dataDosen.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i
                            class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Dosen</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataMahasiswa.php', 'tambahMahasiswa.php', 'editMahasiswa.php']); ?>"
                    href="../mahasiswa/dataMahasiswa.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i
                            class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Mahasiswa</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataPrestasi.php', 'tambahPrestasi.php', 'editPrestasi.php']); ?>"
                    href="../prestasi/dataPrestasi.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Prestasi</span>
                </a>
            </li>
            <?php if ($role == 1 || $role == 2): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive(['dataInformasiLomba.php', 'tambahInformasiLomba.php', 'editInformasiLomba.php']); ?>"
                        href="../../system/informasiLomba/dataInformasiLomba.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Informasi Lomba</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive('laporan.html'); ?>"
                        href="../laporan/laporan.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Laporan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive('logout.php'); ?>"
                        href="../logout/logout.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-send text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Log Out</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>
<?php
} else if (isset($_SESSION['role']) && $_SESSION['role'] == 2) {
  ?>
  <aside
    class="sidenav bg-white navbar navbar-vertical navbar-expand-xs border-0 border-radius-xl my-3 fixed-start ms-4"
    id="sidenav-main">
    <div class="sidenav-header">
        <i
            class="fas fa-times p-3 cursor-pointer text-secondary opacity-5 position-absolute end-0 top-0 d-none d-xl-none"
            aria-hidden="true"
            id="iconSidenav"></i>
        <a
            class="navbar-brand m-0"
            href="https://demos.creative-tim.com/argon-dashboard/pages-SuperAdmin/dashboard.html"
            target="_blank">
            <img
                src="../../assets2/img/jti.png"
                width="30px"
                height="50px"
                class="navbar-brand-img h-100"
                alt="main_logo" />
            <span class="ms-1 font-weight-bold">Pencatatan Prestasi</span>
        </a>
    </div>
    <hr class="horizontal dark mt-0" />
    <div class="w-auto" id="sidenav-collapse-main">
        <ul class="navbar-nav">
            <?php
            // Dapatkan URL saat ini
            $current_page = basename($_SERVER['PHP_SELF']);

            // Dapatkan role user
            $role = $_SESSION['role']; // Assumsi session 'role' sudah di set

            // Fungsi untuk memberikan kelas active
            function isActive($page)
            {
                global $current_page;
                return $current_page === $page ? 'active' : '';
            }
            ?>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive('dashboard.php'); ?>"
                    href="../pageSuperAdmin/dashboard.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-tv-2 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Dashboard</span>
                </a>
            </li>
            
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataDosen.php', 'tambahDosen.php', 'editDosen.php']); ?>"
                    href="../dosen/dataDosen.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i
                            class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Dosen</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataMahasiswa.php', 'tambahMahasiswa.php', 'editMahasiswa.php']); ?>"
                    href="../mahasiswa/dataMahasiswa.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i
                            class="ni ni-calendar-grid-58 text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Mahasiswa</span>
                </a>
            </li>
            <li class="nav-item">
                <a
                    class="nav-link <?= isActive(['dataPrestasi.php', 'tambahPrestasi.php', 'editPrestasi.php']); ?>"
                    href="../prestasi/dataPrestasi.php">
                    <div
                        class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                        <i class="ni ni-credit-card text-dark text-sm opacity-10"></i>
                    </div>
                    <span class="nav-link-text ms-1">Data Prestasi</span>
                </a>
            </li>
            <?php if ($role == 1 || $role == 2): ?>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive(['dataInformasiLomba.php', 'tambahInformasiLomba.php', 'editInformasiLomba.php']); ?>"
                        href="../../system/informasiLomba/dataInformasiLomba.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-app text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Informasi Lomba</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive('laporan.php'); ?>"
                        href="../laporan/laporan.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-world-2 text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Laporan</span>
                    </a>
                </li>
                <li class="nav-item">
                    <a
                        class="nav-link <?= isActive('logout.php'); ?>"
                        href="../logout/logout.php">
                        <div
                            class="icon icon-shape icon-sm border-radius-md text-center me-2 d-flex align-items-center justify-content-center">
                            <i class="ni ni-send text-dark text-sm opacity-10"></i>
                        </div>
                        <span class="nav-link-text ms-1">Log Out</span>
                    </a>
                </li>
            <?php endif; ?>
        </ul>
    </div>
</aside>
<?php
}
?>