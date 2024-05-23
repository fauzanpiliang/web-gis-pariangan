<!DOCTYPE html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width,initial-scale=1" />


    <!-- Customized Bootstrap Stylesheet -->
    <link href="<?= base_url('assets/css/landing-page/bootstrap.min.css') ?>" rel="stylesheet" />

    <!-- Libraries Stylesheet -->
    <link href="<?= base_url('assets/lib/animate/animate.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/lightbox/css/lightbox.min.css') ?>" rel="stylesheet" />
    <link href="<?= base_url('assets/lib/owlcarousel/assets/owl.carousel.min.css') ?>" rel="stylesheet" />
    <!-- Third Party CSS and JS -->
    <script src="https://code.iconify.design/2/2.2.1/iconify.min.js"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="https://kit.fontawesome.com/de7d18ea4d.js" crossorigin="anonymous"></script>

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@300&family=Rubik+Dirt&display=swap" rel="stylesheet">

    <!-- Font google -->
    <link href="https://fonts.googleapis.com/css2?family=Roboto+Condensed:wght@300;400&display=swap" rel="stylesheet">
    <!-- My CSS -->
    <link rel="stylesheet" href="<?= base_url('assets/css/main/style.css') ?>" />
    <title><?= $title ?></title>
</head>

<style type="text/css">
    /* ============ only desktop view ============ */
    @media all and (min-width: 992px) {
        .navbar .nav-item .dropdown-menu {
            display: block;
            opacity: 0;
            visibility: hidden;
            transition: .3s;
            margin-top: 0;
        }

        .navbar .nav-item:hover .nav-link {
            color: #ffff;
        }

        .navbar .dropdown-menu {
            top: 80%;
            transform: rotateX(-75deg);
            transform-origin: 0% 0%;
        }

        .navbar .dropdown-menu.fade-up {
            top: 180%;
        }

        .navbar .nav-item:hover .dropdown-menu {
            transition: .3s;
            opacity: 1;
            visibility: visible;
            top: 100%;
            transform: rotateX(0deg);
        }

    }

    /* ============ desktop view .end// ============ */
</style>

<body>
    <!-- Awal landing -->

    <!-- Awal navigasi -->
    <nav class="navbar navbar-expand-lg navbar-light position-fixed shadow-sm bg-white" style="z-index: 999; right:0;left:0;">
        <div class="container-fluid">
            <a class="navbar-brand d-flex justify-content-center" href="index.php">
                <a href="<?= base_url(''); ?>">
                    <i class="iconify" data-icon="fontisto:holiday-village" data-width="70" data-height="70"></i>
                    <span class="display-6 ms-2 mt-3" style="vertical-align: middle;">Tourism Village</span>
                </a>
            </a>
            <button class=" navbar-toggler " type=" button" data-bs-toggle="collapse" data-bs-target="#main_nav" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="main_nav">
                <ul class="navbar-nav ms-auto me-4 rounded ">
                    <li class="nav-item ">
                        <a class="nav-link text-secondary" href="#landing">LANDING PAGE</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="<?= base_url('list_object'); ?>">EXPLORE </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#About">ABOUT</a>
                    </li>
                    <li class="nav-item ">
                        <a class="nav-link text-secondary" href="#MyProjects">PREVIEW</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-secondary" href="#MyCertificates">AWARDS</a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link text-success" href="/login">LOGIN</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- Akhir navigasi -->
    <div class="bg" id="bg">
        <video autoplay muted poster="<?= base_url('/assets/images/videoparianganss.PNG') ?>" loop class="bg-video">
            <source src="<?= base_url('media/videos/videopariangan.MP4'); ?>">
        </video>
        <div class="bg-content">
            <h1 style="color: #eaeaea;">Welcome To Nagari Tuo Pariangan Tourism Village</h1>
            <p>Tanah Datar Regency, West Sumatra, Indonesia</p>
            <a class="btn btn-success btn-lg" href="<?= base_url('list_object') ?>">Explore Now!</a>
        </div>
    </div>

    <!-- Akhir landing -->
    <!-- About  -->
    <section class="container mb-2" id="About">
        <div class="row" style="position: relative;">
            <div class="col-md-6">
                <p class="text-secondary"><span class="text-success"> #</span> Welcome To Nagari Tuo Pariangan Tourism Village</p>
                <h1 class="efek1 text-success text-start display-5" style="font-family: 'Roboto Condensed', sans-serif;">Why You Should Visit Nagari Tuo Pariangan Tourism Village</h1>
                <p class="text-start text-secondary" style=" font-size:20px;font-family: 'Roboto Condensed', sans-serif; font-weight:300;">
                    <?= $parianganData->description; ?>
                </p>
            </div>
            <div class=" col-md-6 p-4 ">
                <img src="media/photos/landing-page/makam.jpg" alt="" style="max-width: 100%" class="efek1">
                <figcaption class="figure-caption text-start">The Tantejo Gurhano Long Grave holds significant historical value in Minangkabau history because Tantejo Gurhano was the architect of the first Rumah Gadang (traditional Minangkabau house) in Minangkabau.<a class="text-link text-success" href="<?= base_url('list_object'); ?>"> more info</a></figcaption>
            </div>

        </div>
        <div class="row justify" style="padding-left: 75px;">
            <div class="col-md-5 my-5 ">
                <img src="media/photos/landing-page/batulantaktigo.jpg" alt="" style="max-width: 100%;" class="efek5">
                <figcaption class="figure-caption  text-start">The Batu Lantak Tigo consists of three large stones spaced at a considerable distance, forming a hearth or fireplace.<a class="text-link text-success" href="<?= base_url('list_object'); ?>"> more info</a></figcaption>
            </div>
            <div class=" col-md-5 my-5 " style="margin-left: 120px;">
                <img src="media/photos/landing-page/masjid.jpg" alt="" style="max-width: 100%" class="efek5">
                <figcaption class="figure-caption text-start">The Ishlah Mosque is purportedly the oldest mosque in Minangkabau. It has undergone several renovations, but its general structure remains the same. Changes have mainly involved the use of different materials and the addition of functional spaces. <a class="text-link text-success" href="<?= base_url('list_object'); ?>"> more info</a></figcaption>
            </div>

        </div>
    </section>
    <!-- Akhir About me -->

    <!-- My Projects -->
    <section class="container-fluid" id="MyProjects">
        <div class="row justify-content-center ">
            <div class="col-md-10 text-center">
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('atraction')">Atraction</button>
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('culinary_place')">Culinary place</button>
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('souvenir_place')">Souvenir place</button>
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('worship_place')">Worship place</button>
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('facility')">Facility</button>
                <button class="btn btn-outline-success btn-lg mx-2 rounded shadow-sm" onclick="showObject('event')">Event</button>
            </div>
        </div>
        <div class="row justify-content-center m-2">
            <div class="col-10 shadow-sm">
                <?= $this->include('/layout/map-body.php'); ?>
            </div>
        </div>
    </section>
    <!-- Akhir My Projects -->

    <!-- Awal Award  -->
    <div class="container bg-primary facts my-5 py-5 border border-success" id="MyCertificates">
        <div class="container py-5">
            <div class="row g-4">
                <div class="col-md-6 col-lg-6 text-center">
                    <img src="media/photos/landing-page/trophy.png" alt="" style="filter: invert(100%); max-width: 4em" class="mb-3">
                    <h1 class="text-white mb-2" data-toggle="counter-up">Top 1</h1>
                    <p class="text-white mb-0">ADWI 2022</p>
                </div>
                <div class="col-md-6 col-lg-6 text-center">
                    <img src="media/photos/landing-page/rumah-gadang.png" alt="" style="filter: invert(100%); max-width: 5em">
                    <h1 class="text-white mb-2" data-toggle="counter-up">3</h1>
                    <p class="text-white mb-0">Uniqe Atraction</p>
                </div>
            </div>
        </div>
    </div>
    <!--  Akhir Award -->
    <!-- Footer Start -->
    <div class="container-fluid footer bg-dark text-light footer mt-5 pt-5 0.1s">
        <div class="container py-5">
            <div class="row g-5">
                <div class="col-lg-9 col-md-6">
                    <h5 class="text-light mb-4">Address</h5>
                    <p class="mb-2">
                        <i class="fa fa-map-marker-alt me-3"></i>Nagari Tuo Pariangan, Pariangan Distric, Tanah Datar Regency, West Sumatera Province
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-phone-alt me-3"></i>+62 822 1814 1289
                    </p>
                    <p class="mb-2">
                        <i class="fa fa-envelope me-3"></i>pokdarwispariangan1@gmail.com
                    </p>
                    <div class="d-flex pt-2">
                        <a class="btn btn-outline-light btn-social" href="https://instagram.com/pokdarwis.pariangan"><i class="fab fa-instagram"></i></a>
                        <a class="btn btn-outline-light btn-social" href="https://tiktok.com/pokdarwis.pariangan"><i class="fab fa-tiktok"></i></a>
                    </div>
                </div>

                <div class="col-lg-3 col-md-6">
                    <h5 class="text-light mb-4">Links</h5>
                    <a class="btn btn-link" href="#landing">Home</a>
                    <a class="btn btn-link" href="<?= base_url('list_object') ?>">Explore</a>
                    <a class="btn btn-link" href="#About">About</a>
                    <a class="btn btn-link" href="#award">Award</a>
                    <a class="btn btn-link" href="<?= base_url('login'); ?>">Login</a>
                </div>
            </div>
        </div>
        <div class="container">
            <div class="copyright">
                <div class="row">
                    <div class="col-md-6 text-center text-md-start mb-3 mb-md-0">
                        &copy; <a class="border-bottom" href="#">Ahmad Fauzan</a>, All
                        Right Reserved.
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- Footer End -->
    <!-- Akhir main -->
    <!-- GSAP -->
    <script src="https://cdnjs.cloudflare.com/ajax/libs/gsap/3.10.4/gsap.min.js"></script>
    <script>
        gsap.to(".bg-content", {
            duration: 2,
            x: 0,
            opacity: 1,
            ease: "bounce"
        }).delay(2);
        let datas
        let geomPariangan = JSON.parse('<?= $parianganData->geoJSON; ?>')
        let latPariangan = parseFloat(<?= $parianganData->lat; ?>)
        let lngPariangan = parseFloat(<?= $parianganData->lng; ?>)
    </script>

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.1.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-ka7Sk0Gln4gmtz2MlQnikT1wXgYsOg+OMhuP+IlRH9sENBO0LRn5q+8nbTov4+1p" crossorigin="anonymous"></script>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>
    <script src="<?= base_url('assets/js/script.js'); ?>"></script>
    <script src="<?= base_url('assets/js/map.js') ?>"></script>
    <!-- Maps JS -->
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyB8B04MTIk7abJDVESr6SUF6f3Hgt1DPAY&callback=initMap"></script>
</body>

</html>