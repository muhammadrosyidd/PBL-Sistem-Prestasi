<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8" />
    <link rel="apple-touch-icon" sizes="76x76" href="assets/img/apple-icon.png">
    <link rel="icon" type="image/png" sizes="96x96" href="assets/img/jtionly.png">

    <meta http-equiv="X-UA-Compatible" content="IE=edge,chrome=1" />
    <title>SISTEM PENCATATAN PRESTASI</title>
    <meta content='width=device-width, initial-scale=1.0, maximum-scale=1.0, user-scalable=0' name='viewport' />
    <link href="assets/css/bootstrap.css" rel="stylesheet" />
    <link href="assets/css/gaia.css" rel="stylesheet" />
    <link href="assets/css/leaderboard.css" rel="stylesheet">
    <link href="assets/css/infolomba.css" rel="stylesheet">
    <!--     Fonts and icons     -->
    <link href='https://fonts.googleapis.com/css?family=Cambo|Poppins:400,600' rel='stylesheet' type='text/css'>
    <link href="http://maxcdn.bootstrapcdn.com/font-awesome/latest/css/font-awesome.min.css" rel="stylesheet">
    <link href="assets/css/fonts/pe-icon-7-stroke.css" rel="stylesheet">

</head>

<body>

    <nav class="navbar navbar-default navbar-transparent navbar-fixed-top" color-on-scroll="200">

        <div class="container">
            <div class="navbar-header">
                <button id="menu-toggle" type="button" class="navbar-toggle" data-toggle="collapse"
                    data-target="#navbarMenu">
                    <span class="sr-only">Toggle navigation</span>
                </button>
                <a class="navbar-brand" href="#">
                    <img src="./assets/img/jti.png" alt="Logo" style="width: 90px; height: 45px;">
                </a>
            </div>
            <div class="collapse navbar-collapse" id="navbarMenu">
                <ul class="nav navbar-nav navbar-right navbar-uppercase">
                    <li>
                        <a href="../system/pages-Sign-in/Login.php" class="btn btn-danger btn-fill">Login</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>



    <div class="section section-header">
        <div class="parallax filter">
            <video class="background-video" autoplay loop muted playsinline>
                <source src="assets/img/jti.mp4" type="video/mp4">
            </video>
        </div>

        <div class="container">
            <div class="content">
                <div class="title-area">
                    <h1 class="title-modern">SISTEM PENCATATAN PRESTASI JTI POLINEMA</h1>
                    <div class="separator line-separator">♦</div>
                </div>
            </div>

        </div>
    </div>
    </div>

    <div class="section section-lomba" style="margin-bottom: -60px;">
        <div class="container">
            <div class="content">
                <div class="row">
                    <div class="title-area">
                        <h2>Informasi Lomba</h2>
                        <div class="separator separator-danger">✻</div>
                        <p class="description">Ayo bergabung dan jadilah bagian dari kompetisi yang luar biasa ini!
                            Jangan lewatkan kesempatan emas ini untuk menantang diri sendiri dan menjadi juara! 🥇
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="team">
        <div class="slider-container">
            <div class="slider-track">
                <?php include 'get_lomba.php'; ?>
            </div>
        </div>
    </div>


    </div>

    <div class="lider">
        <div class="leaderboard">
            <div class="leaderboard-title">LEADERBOARD</div>
            <?php include 'get_poin.php' ?>
        </div>
    </div>

    <footer class="footer footer-big footer-color-black" data-color="black">
        <div class="container">
            <div class="text-center">
                <h1 style="color: white;">Kelompok 4 - SIB 2D</h1>
                <ul class="list-unstyled" style="color: white;">
                    <li>Farel Maryam Laila Hajiri - 8</li>
                    <li>Imel Theresia Br Sembiring - 11</li>
                    <li>Keysha Arindra Fabian - 15</li>
                    <li>Muhammad Rosyid - 22</li>
                    <li>Satrio Dian Nugroho - 27</li>
                </ul>
                <hr style="border-top: 1px solid white; width: 50%; margin: 20px auto;">
                <p style="color: white;">©
                    <script>document.write(new Date().getFullYear())</script> Creative Tim, made with love
                </p>
            </div>
        </div>
    </footer>

</body>



<script src="assets/js/jquery.min.js" type="text/javascript"></script>
<script src="assets/js/bootstrap.js" type="text/javascript"></script>
<script type="text/javascript" src="assets/js/modernizr.js"></script>
<script type="text/javascript" src="assets/js/gaia.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/js/all.min.js"></script>

<script>
    $(document).ready(function () {
        $('.slider-track').slick({
            slidesToShow: 3,
            slidesToScroll: 1,
            infinite: true,
            autoplay: true,
            autoplaySpeed: 3000,
            arrows: true,
            dots: true
        });
    });
</script>


</html>