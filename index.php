<?php
// $ip = $_SERVER['REMOTE_ADDR'];
// $hostname = gethostbyaddr($ip);

// echo 'IP Address: ' . $ip . '<br>';
// echo 'Hostname: ' . $hostname . '<br>';

if ($_SERVER['REQUEST_METHOD'] == 'POST') {

    $cropType = $_POST['cropType'];
    $cropName = $_POST['cropName'];
    $location = $_POST['location'];


    if ($cropType == 'Wheat' && $location == 'Bangladesh') {
        header("Location: view_crop_advisory.php?cropType=$cropType&cropName=$cropName&location=$location");
    } else {
    }
    exit();
}

?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="utf-8">
    <title>New Crop Advisory</title>
    <meta content="width=device-width, initial-scale=1.0" name="viewport">
    <meta content="" name="keywords">
    <meta content="" name="description">

    <!-- Favicon -->
    <link href="img/pragya-logo.png" rel="icon">

    <!-- Google Web Fonts -->
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Heebo:wght@400;500;600&family=Inter:wght@700;800&display=swap" rel="stylesheet">

    <!-- Icon Font Stylesheet -->
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.10.0/css/all.min.css" rel="stylesheet">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.4.1/font/bootstrap-icons.css" rel="stylesheet">

    <!-- Libraries Stylesheet -->
    <link href="lib/animate/animate.min.css" rel="stylesheet">
    <link href="lib/owlcarousel/assets/owl.carousel.min.css" rel="stylesheet">

    <!-- Customized Bootstrap Stylesheet -->
    <link href="css/bootstrap.min.css" rel="stylesheet">

    <!-- Template Stylesheet -->
    <link href="css/style.css" rel="stylesheet">
</head>

<body>
    <div class="container-xxl bg-white p-0">
        <!-- Spinner Start -->
        <div id="spinner" class="show bg-white position-fixed translate-middle w-100 vh-100 top-50 start-50 d-flex align-items-center justify-content-center">
            <div class="spinner-border text-primary" style="width: 3rem; height: 3rem;" role="status">
                <span class="sr-only">Loading...</span>
            </div>
        </div>
        <!-- Spinner End -->


        <?php include('header.php'); ?>


        <!-- Header Start -->
        <div class="container-fluid header bg-white p-0">
            <div class="row g-0 align-items-center flex-column-reverse flex-md-row">
                <div class="col-md-6 p-5 mt-lg-5">
                    <h1 class="display-5 animated fadeIn mb-4">Crop <span class="text-primary">Advisory</span></h1>
                    <p class="animated fadeIn mb-4 pb-2">Access a Climate-Smart Decision Support System for crops across the Ganges-Brahmaputra-Meghna basin. Adapt your practices to standard protocols to boost yields and manage pests for sustainable farming.</p>
                    <!-- <a href="" class="btn btn-primary py-3 px-5 me-3 animated fadeIn">Get Started</a> -->
                </div>
                <div class="col-md-6 animated fadeIn">
                    <div class="owl-carousel header-carousel">
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/kharif-crop.png" alt="">
                        </div>
                        <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/19.jpg" alt="">
                        </div>
                        <!-- <div class="owl-carousel-item">
                            <img class="img-fluid" src="img/agriculture.jpg" alt="">
                        </div> -->
                    </div>
                </div>
            </div>
        </div>
        <!-- Header End -->

        <!-- Search Start -->
        <form method="POST">
            <div class="container-fluid bg-primary mb-5 wow fadeIn" data-wow-delay="0.1s" style="padding: 35px;">
                <div class="container">
                    <div class="row g-2">
                        <div class="col-md-10">
                            <div class="row g-2">

                                <!-- <div class="col-md-4">
                                    <input type="text" name="cropType" class="form-control border-0 py-3" placeholder="Crop Type" required>
                                </div> -->
                                <div class="col-md-4">
                                    <select name="cropType" class="form-select border-0 py-3" required>
                                        <option value="" disabled selected>Crop Type</option>
                                        <option value="Wheat">Food Grain</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="cropName" class="form-select border-0 py-3" required>
                                        <option value="" disabled selected>Crop Name</option>
                                        <option value="Wheat">Wheat</option>
                                    </select>
                                </div>
                                <div class="col-md-4">
                                    <select name="location" class="form-select border-0 py-3" required>
                                        <option value="" disabled selected>Location</option>
                                        <option value="Bangladesh">Bangladesh</option>
                                        <!-- <option value="Assam">Assam</option>
                                        <option value="Bihar">Bihar</option> -->
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-2">
                            <!-- Button that triggers the form submission -->
                            <button class="btn btn-dark border-0 w-100 py-3" type="submit">Search</button>
                        </div>

                    </div>
                </div>
            </div>
        </form>
        <!-- Search End -->



        <?php // include('footer.php');
        ?>


        <!-- Back to Top -->
        <!-- <a href="#" class="btn btn-lg btn-primary btn-lg-square back-to-top"><i class="bi bi-arrow-up"></i></a> -->
    </div>

    <!-- JavaScript Libraries -->
    <script src="https://code.jquery.com/jquery-3.4.1.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.0/dist/js/bootstrap.bundle.min.js"></script>
    <script src="lib/wow/wow.min.js"></script>
    <script src="lib/easing/easing.min.js"></script>
    <script src="lib/waypoints/waypoints.min.js"></script>
    <script src="lib/owlcarousel/owl.carousel.min.js"></script>

    <!-- Template Javascript -->
    <script src="js/main.js"></script>
</body>

</html>