<?php

$crop_name = $_GET['cropName'];
$location = $_GET['location'];
$crop_type = $_GET['cropType'];

function get_client_ip()
{
    $ipaddress = 'UNKNOWN';
    if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
        $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    } elseif (isset($_SERVER['HTTP_CLIENT_IP'])) {
        $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    } elseif (isset($_SERVER['REMOTE_ADDR'])) {
        $ipaddress = $_SERVER['REMOTE_ADDR'];
    }
    return $ipaddress;
}

$PublicIP = get_client_ip();
$token = '2a9ad2ad44fdeb'; // Your IPinfo.io API token
$apiUrl = "https://ipinfo.io/$PublicIP/geo?token=$token";

// Initialize cURL session
$ch = curl_init();

// Set cURL options
curl_setopt($ch, CURLOPT_URL, $apiUrl);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);  // Disable SSL verification if necessary
$response = curl_exec($ch);

// Check if cURL request was successful
if ($response === FALSE) {
    die("Error fetching data from IPInfo API: " . curl_error($ch));
}

// Close cURL session
curl_close($ch);

// Decode the JSON response
$json = json_decode($response, true);

// Check if the response contains valid data
if (isset($json['country'], $json['region'], $json['city'], $json['loc'])) {
    $country = $json['country'];
    $region = $json['region'];
    $city = $json['city'];
    $loc = $json['loc']; // Latitude and Longitude

    // Extract Latitude and Longitude from loc
    list($latitude, $longitude) = explode(',', $loc);

    // Step 2: Get Weather Data from wttr.in
    // $weatherUrl = "https://wttr.in/$city?format=%C+%t";


    // // Get weather data
    // $weatherData = @file_get_contents($weatherUrl);
    // if ($weatherData === FALSE) {
    //     die("Error fetching weather data from wttr.in.");
    // }

    // // Output the weather data
    // echo "Current Weather: $weatherData\n";
} else {
    die("Could not retrieve geolocation information.\n");
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
    <!-- Add jQuery CDN -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>

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

        <form id="filter_advisory" method="POST" class="bordered-form">
            <div style="padding-top: 40px;">
                <div class="container">
                    <div class="row g-2">
                        <div class="col-md-3">
                            <label for="location">Choose Location:</label>
                            <select id="location" name="location" class="form-select">
                                <option value="Bangladesh-Munshiganj">Munshiganj</option>
                                <option value="Bangladesh-Khulna">Khulna</option>
                                <option value="Bangladesh-Sirajganj">Sirajganj</option>
                            </select>
                        </div>
                    </div>
                    <br>
                    <div class="row g-2">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="soil_ph">Soil PH</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="acidic" name="soil_ph" value="Acidic">
                                    <label class="btn" for="acidic">Acidic</label>

                                    <input type="radio" class="btn-check" id="neutral" name="soil_ph" value="Neutral">
                                    <label class="btn" for="neutral">Neutral</label>

                                    <input type="radio" class="btn-check" id="alkaline" name="soil_ph" value="Alkaline">
                                    <label class="btn" for="alkaline">Alkaline</label>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <!-- <div class="col-md-1">
                        </div> -->
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="soil_texture">Soil Texture</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="loamy" name="soil_texture" value="Loamy">
                                    <label class="btn" for="loamy">Loamy</label>

                                    <input type="radio" class="btn-check" id="clay" name="soil_texture" value="Clay">
                                    <label class="btn" for="clay">Clay</label>

                                    <input type="radio" class="btn-check" id="sandy" name="soil_texture" value="Sandy">
                                    <label class="btn" for="sandy">Sandy</label>
                                    <input type="radio" class="btn-check" id="silty" name="soil_texture" value="Silty">
                                    <label class="btn" for="silty">Silty</label>
                                    <input type="radio" class="btn-check" id="peaty" name="soil_texture" value="Peaty">
                                    <label class="btn" for="peaty">Peaty</label>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row g-2">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="soil_carbon">Soil Carbon</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="soil_carbon_low" name="soil_carbon" value="Low">
                                    <label class="btn" for="soil_carbon_low">Low</label>

                                    <input type="radio" class="btn-check" id="soil_carbon_medium" name="soil_carbon" value="Medium">
                                    <label class="btn" for="soil_carbon_medium">Medium</label>

                                    <input type="radio" class="btn-check" id="soil_carbon_high" name="soil_carbon" value="High">
                                    <label class="btn" for="soil_carbon_high">High</label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Soil Texture Selection -->
                    <!-- <div class="row g-2">

                    </div>


                    <div class="row g-2">

                    </div> -->


                    <!-- Observed Problems Selection -->

                    <div class="row g-2">
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="observed_problems">Observed Problems</label>
                                <div class="btn-group btn-group-sm" role="group">

                                    <input type="checkbox" class="btn-check" id="none" name="observed_problems[]" value="None">
                                    <label class="btn" for="none">None</label>

                                    <input type="checkbox" class="btn-check" id="yellowing" name="observed_problems[]"
                                        value="Yellowing Leaves">
                                    <label class="btn" for="yellowing">Yellowing Leaves</label>

                                    <input type="checkbox" class="btn-check" id="stunted_growth" name="observed_problems[]"
                                        value="Stunted Growth">
                                    <label class="btn" for="stunted_growth">Stunted Growth</label>

                                    <input type="checkbox" class="btn-check" id="wilting" name="observed_problems[]" value="Wilting">
                                    <label class="btn" for="wilting">Wilting</label>
                                </div>
                            </div>
                        </div>
                    </div>


                    <div class="row g-2">
                        <!-- First Row: 3 Labels -->
                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="label2">Current Pests</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="checkbox" class="btn-check" id="current_pests_none" name="current_pests[]" value="None" required>
                                    <label class="btn" for="current_pests_none">None</label>

                                    <input type="checkbox" class="btn-check" id="current_pests_aphids" name="current_pests[]" value="Aphids" required>
                                    <label class="btn" for="current_pests_aphids">Aphids</label>

                                    <input type="checkbox" class="btn-check" id="current_pests_stem" name="current_pests[]" value="Stem Borers" required>
                                    <label class="btn" for="current_pests_stem">Stem Borers</label>

                                    <input type="checkbox" class="btn-check" id="current_pests_armyworms" name="current_pests[]" value="Armyworms" required>
                                    <label class="btn" for="current_pests_armyworms">Armyworms</label>

                                    <input type="checkbox" class="btn-check" id="current_pests_rust" name="current_pests[]" value="Rust" required>
                                    <label class="btn" for="current_pests_rust">Rust</label>

                                    <input type="checkbox" class="btn-check" id="current_pests_blight" name="current_pests[]" value="Blight" required>
                                    <label class="btn" for="current_pests_blight">Blight</label>
                                </div>
                            </div>
                        </div>
                    </div>



                    <div class="row g-2">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="weed_type">Weed Type</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="weed_type_none" name="weed_type" value="None" required>
                                    <label class="btn" for="weed_type_none">None</label>
                                    <input type="radio" class="btn-check" id="weed_type_mustard" name="weed_type" value="Wild Mustard" required>
                                    <label class="btn" for="weed_type_mustard">Wild Mustard</label>
                                    <input type="radio" class="btn-check" id="weed_type_nutgrass" name="weed_type" value="Nutgrass" required>
                                    <label class="btn" for="weed_type_nutgrass">Nutgrass</label>
                                    <input type="radio" class="btn-check" id="weed_type_lambs" name="weed_type" value="Lamb’s Quarters" required>
                                    <label class="btn" for="weed_type_lambs">Lamb’s Quarters</label>
                                    <input type="radio" class="btn-check" id="weed_type_oats" name="weed_type" value="Wild Oats" required>
                                    <label class="btn" for="weed_type_oats">Wild Oats</label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="weather_condition">Weather Condition</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="weather_rainy" name="weather_condition" value="Rainy" required>
                                    <label class="btn" for="weather_rainy">Rainy</label>
                                    <input type="radio" class="btn-check" id="weather_dry" name="weather_condition" value="Dry" required>
                                    <label class="btn" for="weather_dry">Dry</label>
                                    <input type="radio" class="btn-check" id="weather_drought" name="weather_condition" value="Drought" required>
                                    <label class="btn" for="weather_drought">Drought</label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">

                        <div class="col-md-7">
                            <div class="form-group">
                                <label for="previous_crop">Previous Crop</label>
                                <div class="btn-group btn-group-sm" role="group">
                                    <input type="radio" class="btn-check" id="previous_crop_wheat" name="previous_crop" value="Wheat" required>
                                    <label class="btn" for="previous_crop_wheat">Wheat</label>
                                    <input type="radio" class="btn-check" id="previous_crop_rice" name="previous_crop" value="Rice" required>
                                    <label class="btn" for="previous_crop_rice">Rice</label>
                                    <input type="radio" class="btn-check" id="previous_crop_maize" name="previous_crop" value="Maize" required>
                                    <label class="btn" for="previous_crop_maize">Maize</label>
                                    <input type="radio" class="btn-check" id="previous_crop_soybean" name="previous_crop" value="Soybean" required>
                                    <label class="btn" for="previous_crop_soybean">Soybean</label>
                                    <input type="radio" class="btn-check" id="previous_crop_mustard" name="previous_crop" value="Mustard" required>
                                    <label class="btn" for="previous_crop_mustard">Mustard</label>

                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="row g-2">
                        <div class="col-md-2"><br>
                            <div class="form-group">
                                <button class="btn btn-dark border-0" type="button" style="width: 200px; height: 45px;" onclick="generateAdvisory()">Submit</button>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>



        <!-- Advisory Table -->
        <div class="hedingcontainer">
            <h4>Crop Advisory</h4>


        </div>
        <div class="weather-info">
            <div><span class="highlight">Region:</span> <?php echo $region; ?></div>
            <div><span class="highlight">City:</span> <?php echo $city; ?></div>
            <div class="weather-condition haze"><span class="highlight">Weather Condition:</span> <i class="fas fa-cloud-sun"></i> <?php echo $weatherData; ?></div>
            <!-- <div class="temperature"><span class="highlight">Temperature:</span> <?php echo $weatherData; ?></div> -->
        </div>
        <div class="table-container" id="advisory_table">
            <table class="styled-table">
                <thead>
                    <tr>
                        <th>Conditions</th>
                        <th>Advisory</th>

                    </tr>
                </thead>
                <tbody>

                </tbody>
            </table>
        </div>
        <style>
            /* Hide the default checkbox */
            input[type="checkbox"] {
                display: none;
            }

            /* Style for 'None' checkbox label */
            label[for="none"] {
                display: inline-block;
                padding: 8px 15px;
                /* background-color: #f0f0f0; */
                /* Light gray background */
                border: 1px solid #ccc;
                /* Light gray border */
                border-radius: 5px;
                /* Slightly rounded corners */
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s, border-color 0.3s;
            }

            /* Hover effect for 'None' label */
            label[for="none"]:hover {
                background-color: #e0e0e0;
                /* Slightly darker gray */
                border-color: #888;
                /* Darker border */
            }

            /* When 'None' checkbox is checked, change label background */
            input[type="checkbox"]:checked+label[for="none"] {
                background-color: #28a745;
                /* Green background */
                border-color: #218838;
                /* Darker green border */
                color: white;
                /* White text */
            }

            /* Add checkmark when 'None' checkbox is checked */
            input[type="checkbox"]:checked+label[for="none"]::before {
                content: "✔ ";
                /* Checkmark symbol */
            }

            /* Style for 'current_pests_none' checkbox label */
            label[for="current_pests_none"] {
                display: inline-block;
                padding: 8px 15px;
                /* background-color: #f9f9f9; */
                /* Light background for current pests */
                border: 1px solid #ccc;
                /* Light border */
                border-radius: 5px;
                /* Rounded corners */
                cursor: pointer;
                font-size: 14px;
                transition: background-color 0.3s, border-color 0.3s;
            }

            /* Hover effect for 'current_pests_none' label */
            label[for="current_pests_none"]:hover {
                /* background-color: #f0f0f0; */
                /* Slightly darker background */
                border-color: #888;
                /* Darker border */
            }

            /* When 'current_pests_none' checkbox is checked, change label background */
            input[type="checkbox"]:checked+label[for="current_pests_none"] {
                background-color: #007bff;
                /* Blue background when checked */
                border-color: #0056b3;
                /* Darker blue border */
                color: white;
                /* White text */
            }

            /* Add checkmark when 'current_pests_none' checkbox is checked */
            input[type="checkbox"]:checked+label[for="current_pests_none"]::before {
                content: "✔ ";
                /* Checkmark symbol */
            }


            /* Remove default button styling */
            .btn-check:checked+.btn {
                background-color: green !important;
                /* Green background for active selection */
                color: white !important;
            }

            .btn {
                padding: 10px 20px;
                border-radius: 10;
                border: 1px solid #ccc;
                cursor: pointer;
                text-align: center;
                /* width: 100%; */
                margin: 0;
            }

            /* Fix label width and alignment */
            .btn-group {
                display: flex;
                justify-content: space-between;
            }

            .btn-group .btn {
                flex: 1;
            }

            /* Fix label for proper alignment */
            label {
                display: block;
                font-weight: bold;
            }

            .form-group {
                margin-bottom: 20px;
            }

            /* Style for the month picker */
            input[type=" month"] {
                width: 100%;
                padding: 10px;
                border-radius: 5px;
                border: 1px solid #ccc;
            }


            /* <style>.advisory-list {
                padding-left: 20px;
            } */

            .advisory-text ul {
                list-style-type: disc;
                /* Add bullets for list items */
                padding-left: 20px;
                /* Indent the list */
            }


            /* Container for table */
            .table-container {
                width: 100%;
                overflow-x: auto;
                /* padding: 20px; */
                background-color: #f9f9f9;
                border-radius: 10px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                /* margin-left: 3%;
                margin-right: 3%; */
                display: none;
                margin-bottom: 10px;
            }

            /* Table styles */
            .styled-table {
                border-collapse: collapse;
                margin: 25px 0;
                font-size: 16px;
                font-family: Arial, sans-serif;
                width: 100%;
                background-color: #ffffff;
                border-radius: 10px;
                overflow: hidden;
                text-align: left;
            }

            /* Table Header */
            .styled-table thead tr {
                background-color: #4CAF50;
                color: #ffffff;
                text-align: left;
                font-weight: bold;
            }

            /* Table Body */
            .styled-table tbody tr {
                border-bottom: 1px solid #dddddd;
            }

            .styled-table tbody tr:nth-of-type(even) {
                background-color: #f3f3f3;
            }

            .styled-table tbody tr:hover {
                background-color: #f1f1f1;
            }

            /* Table Cells */
            .styled-table td,
            .styled-table th {
                padding: 12px 15px;
            }

            /* Buttons in Table */



            .hedingcontainer {
                margin-left: 10px;
                font-size: 16px;
                font-family: Arial, sans-serif;
                width: 100%;

            }

            .weather-info {
                background-color: #ffffff;
                padding: 15px;
                border-radius: 8px;
                box-shadow: 0 4px 8px rgba(0, 0, 0, 0.1);
                display: flex;
                justify-content: space-between;
                align-items: center;
                max-width: 1100px;
                margin: auto;
            }

            .weather-info div {
                font-size: 15px;
                color: #555;
            }

            .highlight {
                font-weight: bold;
                color: #333;
            }

            .temperature {
                font-size: 20px;
                color: #ff7b00;
                /* Warm color for temperature */
            }

            .weather-condition {
                font-size: 18px;
                color: #444;
                font-style: italic;
            }

            .weather-condition.haze {
                color: #d4a00a;
                /* Haze color */
            }

            .weather-info div i {
                margin-right: 8px;
                /* Space between icon and text */
            }
        </style>

        <script>
            // Advisory data
            const advisories = {
                locations: {
                    Bangladesh: {
                        Munshiganj: {
                            soil_pH: {
                                Acidic: [
                                    // "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    // "Use ammonium-based nitrogen fertilizers like ammonium sulfate at 40 kg/ha."
                                    "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    "Use ammonium-based nitrogen fertilizers (e.g., ammonium sulfate at 40 kg/ha) as they reduce acidification risk.",
                                    "Apply DAP at 50 kg/ha for phosphate needs, as acidic soils bind phosphorus.",
                                    "Incorporate bioinoculants such as Trichoderma and mycorrhizal fungi to improve nutrient uptake and soil health.",
                                    "Consider crops like barley, oats, or potatoes, which are tolerant to slightly acidic soils."

                                ],
                                Neutral: [
                                    "Maintain optimal pH using balanced fertilizers (e.g., 40 kg/ha urea and 30 kg/ha DAP).",
                                    "Incorporate rhizobium-based bioinoculants for leguminous crops to enhance nitrogen fixation.",
                                    "Crop options: wheat, maize, or legumes (e.g., chickpeas) for high yields under neutral soil conditions."

                                ],
                                Alkaline: [
                                    "Apply sulfur-based amendments (e.g., elemental sulfur at 100 kg/ha) or gypsum to lower pH and improve micronutrient availability.",
                                    "Use nitrate-based nitrogen fertilizers (e.g., calcium nitrate at 50 kg/ha) to avoid increasing alkalinity.",
                                    "Incorporate DAP at 30-40 kg/ha and bioinoculants like phosphate-solubilizing bacteria (PSB) to enhance phosphorus availability.",
                                    "Recommended crops: cotton, mustard, or sorghum, which tolerate slightly alkaline soils."
                                ]
                            },
                            soil_texture: {
                                Loamy: [
                                    "Loamy soil is ideal for agriculture due to balanced drainage, aeration, and fertility. Maintain its structure by avoiding over-tillage.",
                                    "Incorporate compost or farmyard manure (5-7 tons/ha) to sustain organic matter levels and microbial activity.",
                                    "Use balanced fertilizers (40 kg/ha urea, 30 kg/ha DAP) for steady nutrient supply.",
                                    "Bioinoculants: Use Rhizobium inoculants for legumes and Trichoderma to prevent root diseases.",
                                    "Crop recommendations: wheat, maize, pulses, vegetables, and fruits perform exceptionally well in loamy soils."

                                ],
                                Clay: [
                                    "Clayey soil retains water well but has poor drainage and is prone to compaction. Reduce compaction by applying gypsum (200-300 kg/ha) to improve soil structure.",
                                    "Incorporate coarse sand (2-5 tons/ha) or organic matter (7-10 tons/ha) to improve aeration and permeability.",
                                    "Use slow-release nitrogen fertilizers like coated urea (40-50 kg/ha) to minimize nutrient loss through runoff.",
                                    "Adopt conservation tillage or raised bed planting to prevent waterlogging.",
                                    "Bioinoculants: Use mycorrhizal fungi to improve phosphorus uptake and soil porosity.",
                                    "Crop recommendations: rice, soybean, cotton, and crops that can tolerate wetter conditions are suitable."

                                ],
                                Sandy: [
                                    "Sandy soil has poor water and nutrient retention. Enhance water retention by incorporating organic mulches (5-10 cm layer) or biochar (5 tons/ha).",
                                    "Apply polymer-based hydrogels (10 kg/ha) to increase soil water-holding capacity.",
                                    "Use split application of fertilizers (e.g., 30-40 kg/ha urea) to reduce leaching losses.",
                                    "Bioinoculants: Incorporate phosphate-solubilizing bacteria (PSB) and nitrogen-fixing bacteria (e.g., Azospirillum) to improve fertility.",
                                    "Crop recommendations: millet, sorghum, peanuts, and drought-tolerant vegetables are ideal for sandy soils."

                                ],
                                Silty: [
                                    "Silty soil has good nutrient retention but can compact easily. Prevent crusting by adding organic matter (compost or green manures, 5-8 tons/ha).",
                                    "Improve drainage by incorporating coarse sand (2-4 tons/ha) or planting cover crops to maintain soil structure.",
                                    "Use balanced fertilizers (e.g., 40 kg/ha urea and 20 kg/ha DAP) to support crop growth.",
                                    "Bioinoculants: Use Rhizobium inoculants for leguminous crops to improve soil nitrogen levels.",
                                    "Crop recommendations: wheat, maize, sugarcane, and vegetables like carrots and cucumbers."

                                ],
                                Peaty: [
                                    "Peaty soil is rich in organic matter but often lacks nutrients like phosphorus and potassium. Apply DAP (40-50 kg/ha) and potassium sulfate (30 kg/ha) to meet crop needs.",
                                    "Drain excess water by constructing field drains or raised beds to improve aeration.",
                                    "Incorporate lime (2-3 tons/ha) if soil is highly acidic to improve nutrient availability.",
                                    "Bioinoculants: Use phosphate-solubilizing bacteria (PSB) and Trichoderma to manage nutrient deficiencies and root diseases.",
                                    "Crop recommendations: cabbage, carrots, and other root vegetables perform well in peaty soils."

                                ]
                            },
                            soil_carbon: {
                                Low: [
                                    "Soil organic carbon is low. Incorporate organic amendments like compost (5-10 tons/ha), biochar (3-5 tons/ha), or farmyard manure to boost carbon levels.",
                                    "Adopt cover cropping with legumes (e.g., clover, vetch) or grasses (e.g., ryegrass) to improve carbon sequestration and soil structure.",
                                    "Reduce tillage or adopt no-till farming to minimize carbon losses and enhance carbon storage.",
                                    "Plant agroforestry systems with deep-rooted trees (e.g., Gliricidia or Leucaena) to improve long-term soil carbon.",
                                    "Bioinoculants: Use mycorrhizal fungi and microbial inoculants like Bacillus subtilis to enhance organic matter decomposition and nutrient cycling.",
                                    "Crop recommendations: Legumes (e.g., lentils, chickpeas) or perennial grasses for carbon enrichment and nitrogen fixation."

                                ],
                                Medium: [
                                    "Soil carbon levels are moderate. Maintain balance by incorporating crop residues and green manures (e.g., cowpea, sunn hemp).",
                                    "Rotate crops with deep-rooted species (e.g., sorghum, sunflower) to enhance below-ground carbon storage.",
                                    "Bioinoculants: Use Trichoderma and rhizosphere-promoting bacteria to sustain soil microbial health.",
                                    "Crop recommendations: Maize, wheat, or vegetables that benefit from moderate organic carbon levels."
                                ],
                                High: [
                                    "Soil organic carbon is high. Avoid excessive nitrogen application to prevent nutrient imbalances or leaching.",
                                    "Focus on nutrient balance by applying phosphorus-based fertilizers (e.g., DAP at 30-40 kg/ha) to optimize crop productivity.",
                                    "Incorporate crop rotation with carbon-demanding crops (e.g., maize, cotton) to utilize stored carbon effectively.",
                                    "Adopt controlled-release fertilizers to match nutrient supply with crop demand, avoiding excess carbon-nitrogen imbalance.",
                                    "Bioinoculants: Apply phosphate-solubilizing bacteria (PSB) to support nutrient availability in high-carbon soils.",
                                    "Crop recommendations: Cotton, maize, or high-yielding vegetables to maximize carbon utilization."
                                ]
                            },
                            observed_problems: {
                                "Yellowing Leaves": [
                                    "Yellowing leaves in wheat often indicate nitrogen deficiency. Apply urea at 40-50 kg/ha in split doses to ensure efficient uptake.",
                                    "Incorporate legume intercropping (e.g., clover or vetch) to improve nitrogen availability and reduce dependency on synthetic fertilizers.",
                                    "If yellowing occurs in patches, monitor for fungal diseases like rust or root rot. Apply biofungicides (e.g., Trichoderma spp.) or fungicides like Propiconazole as needed.",
                                    "Test soil for sulfur deficiency, which can also cause yellowing. If confirmed, apply gypsum or elemental sulfur at 30-50 kg/ha."

                                ],
                                "Stunted Growth": [
                                    "Stunted growth in wheat may indicate phosphorus deficiency. Apply DAP (40-50 kg/ha) at sowing or as a basal dose.",
                                    "If compaction is the issue, adopt reduced tillage or use subsoiling to improve aeration.",
                                    "Ensure adequate potassium levels by applying potassium sulfate (30-40 kg/ha) to enhance root development and drought resistance.",
                                    "Monitor for root diseases like nematodes or root rot, which can cause stunted growth. Apply neem cake (1-2 tons/ha) or bioinoculants like Bacillus subtilis for biological control."

                                ],
                                Wilting: [
                                    "Wilting in wheat is often due to water stress or fungal infections. Ensure consistent soil moisture by adopting drip irrigation or mulching with organic materials (5-10 cm layer).",
                                    "Test for Fusarium wilt or Verticillium wilt. If present, apply biofungicides (e.g., Trichoderma spp.) and rotate wheat with non-host crops like legumes or millet.",
                                    "Improve water retention in sandy soils by adding biochar (3-5 tons/ha) or hydrogels (10 kg/ha).",
                                    "Avoid over-fertilization with nitrogen, as excessive growth can lead to susceptibility to wilt."

                                ],
                                "None": []
                            },
                            current_pests: {
                                "None": [
                                    "No pest infestations detected. Maintain regular field monitoring and implement preventive measures such as crop rotation and intercropping to deter future infestations."
                                ],
                                Aphids: [
                                    "Aphids can cause yellowing and curling of wheat leaves. Spray neem oil (5 mL/L water) or introduce natural predators like ladybugs and lacewings to control populations.",
                                    "Apply systemic insecticides like Imidacloprid (0.5 mL/L water) if infestation is severe.",
                                    "Encourage intercropping with garlic or onion to naturally repel aphids."

                                ],
                                "Stem Borers": [
                                    "Stem borers damage wheat stems, leading to lodging. Apply Trichogramma egg cards (50,000 per hectare) to biologically control larvae.",
                                    "Use light traps or pheromone traps to monitor and manage adult populations.",
                                    "Spray Bacillus thuringiensis (Bt) formulations to target larvae effectively."

                                ],
                                Armyworms: [
                                    "Armyworms feed on wheat leaves and stems, causing significant damage. Spray neem oil (5 mL/L water) as a preventive measure.",
                                    "For heavy infestations, use chemical insecticides like Lambda-cyhalothrin (1 mL/L water).",
                                    "Apply biological controls such as NPV (Nuclear Polyhedrosis Virus) sprays to target larvae while preserving beneficial insects."

                                ],
                                Rust: [
                                    "Rust (yellow, leaf, or stem rust) is a fungal disease that weakens wheat plants. Spray Propiconazole (1 mL/L water) or Mancozeb (2 g/L water) at the first sign of infection.",
                                    "Plant resistant varieties like HD-2967 or PBW-343 to minimize susceptibility.",
                                    "Avoid overhead irrigation to reduce humidity levels that encourage fungal growth."

                                ],
                                Blight: [
                                    "Blight causes brown spots on leaves, reducing photosynthetic efficiency. Spray copper-based fungicides like Copper Oxychloride (2 g/L water) or biofungicides like Trichoderma spp.",
                                    "Remove infected plant debris from the field to prevent disease spread.",
                                    "Incorporate crop rotation and balanced fertilization to improve plant vigor and reduce disease incidence."

                                ]
                            },
                            weed_type: {
                                "Wild Mustard": [
                                    "Wild Mustard can compete heavily with wheat for nutrients. Use crop rotation with legumes or corn to break its life cycle.",
                                    "Adopt early manual weeding during the seedling stage to prevent seed set.",
                                    "Apply pre-emergent herbicides like Pendimethalin (1 kg/ha) or post-emergent herbicides like Metsulfuron-methyl (4 g/ha).",
                                    "Incorporate mulching with organic residues (e.g., wheat straw) to suppress weed growth."

                                ],
                                Nutgrass: [
                                    "Nutgrass (Cyperus rotundus) propagates through tubers, making it difficult to control. Practice deep tillage to expose and desiccate tubers.",
                                    "Adopt stale seedbed techniques by irrigating the field, allowing weeds to germinate, and then removing them mechanically.",
                                    "Apply selective herbicides like Halosulfuron-methyl (67 g/ha) for effective control.",
                                    "Use biological controls like fungal pathogens (e.g., Dactylaria higginsii) to suppress nutgrass growth."
                                ],
                                "Lamb’s Quarters": [
                                    "Lamb’s Quarters (Chenopodium album) competes for water and nutrients in wheat fields. Incorporate delayed sowing of wheat to allow early weeding of Lamb’s Quarters.",
                                    "Adopt inter-row cultivation or manual removal during the seedling stage.",
                                    "Use post-emergent herbicides like Isoxaben (50 g/ha) or 2,4-D Amine (1.5 L/ha) for control in the early growth stages.",
                                    "Incorporate cover crops (e.g., clover) to smother weed growth."

                                ],
                                "Wild Oats": [
                                    "Wild Oats (Avena fatua) is a major weed in wheat fields. Rotate wheat with crops like soybean or maize to disrupt its lifecycle.",
                                    "Adopt mechanical weeding or inter-row cultivation to manage infestations during early growth stages.",
                                    "Apply herbicides like Clodinafop-propargyl (60 g/ha) or Fenoxaprop-p-ethyl (100 g/ha) for selective control.",
                                    "Ensure clean seedbeds by removing residual weed seeds during land preparation."

                                ],
                                "None": ["No significant weed infestation detected. Continue regular monitoring and adopt good agricultural practices like crop rotation and mulching to suppress future weed growth."]
                            },
                            weather_condition: {
                                Rainy: [
                                    "Rainy conditions increase the risk of fungal diseases like rust and leaf blight. Monitor crops regularly for early signs of infection.",
                                    "Apply preventive fungicides like Mancozeb (2 g/L water) or biofungicides like Trichoderma spp. to control fungal pathogens.",
                                    "Ensure proper drainage to prevent waterlogging. Adopt raised bed planting if waterlogging is frequent.",
                                    "Avoid over-irrigation and monitor soil moisture levels to reduce disease pressure.",
                                    "Top-dress nitrogen fertilizers (e.g., urea at 20 kg/ha) only after rains to minimize leaching."

                                ],
                                Dry: [
                                    "Dry conditions stress wheat crops, leading to reduced tillering and grain size. Apply organic mulches (e.g., wheat straw, 5-10 cm layer) to retain soil moisture.",
                                    "Adopt deficit irrigation strategies, scheduling water application at critical growth stages like tillering and grain filling.",
                                    "Use anti-transpirants like kaolin clay (3-5%) spray to reduce water loss from leaves.",
                                    "Monitor for dry-weather pests like grasshoppers and apply neem oil sprays or biological controls (e.g., Beauveria bassiana) if needed.",
                                    "Split nitrogen application (e.g., 20 kg/ha at sowing and 20 kg/ha during tillering) to avoid wastage and enhance nutrient uptake."

                                ],
                                Drought: [
                                    "Under drought conditions, adopt drought-tolerant wheat varieties like HD-2967 or C306.",
                                    "Install drip or sprinkler irrigation systems to optimize water use and maintain uniform soil moisture.",
                                    "Incorporate biochar (3-5 tons/ha) or hydrogels (10 kg/ha) into the soil to improve water retention.",
                                    "Apply potassium-based fertilizers (e.g., potassium sulfate at 30-40 kg/ha) to enhance drought resistance and root growth.",
                                    "Monitor for pests like aphids, which proliferate under drought stress, and apply neem oil (5 mL/L water) or Beauveria bassiana as a biocontrol agent.",
                                    "Avoid over-fertilizing with nitrogen, as it can increase drought stress. Focus on balanced fertilizers."

                                ]
                            },

                            previous_crop: {
                                Wheat: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or soybean. Incorporate 20 kg/ha potash to support root development.",
                                    "If growing legumes (e.g., soybean), minimize nitrogen application to avoid excess vegetative growth and rely on Rhizobium inoculants for nitrogen fixation.",
                                    "Incorporate wheat stubble into the soil to enhance organic carbon levels and nutrient cycling."

                                ],
                                Rice: [
                                    "For next cropping: Incorporate 60 kg/ha DAP and 20 kg/ha potash for mustard or chickpea. Add 2 tons/ha farmyard manure to replenish organic matter lost in puddled rice fields.",
                                    "Apply gypsum (200-300 kg/ha) if the field shows signs of sodicity, improving soil structure and phosphorus availability.",
                                    "Incorporate rice straw as mulch or compost to retain soil moisture and enhance microbial activity."

                                ],
                                Maize: [
                                    "For next cropping: Apply 40 kg/ha DAP and 20 kg/ha urea for wheat or pulses. Incorporate 5 tons/ha compost to improve soil organic matter.",
                                    "In case of potassium deficiency, apply 30-40 kg/ha potassium sulfate to maintain nutrient balance.",
                                    "Add Trichoderma-enriched compost to suppress soil-borne pathogens and improve soil health."

                                ],
                                Soybean: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or wheat. Avoid excess nitrogen as residual nitrogen from soybean benefits subsequent crops.",
                                    "Incorporate crop residues as green manure to improve soil structure and organic matter.",
                                    "If phosphorus levels are low, apply rock phosphate at 100 kg/ha for long-term availability."

                                ],
                                Mustard: [
                                    "For next cropping: Apply 50 kg/ha urea and 30 kg/ha DAP for wheat or rice. Add 10 kg/ha zinc sulfate if micronutrient deficiencies are observed.",
                                    "Incorporate mustard residues into the soil to enhance microbial activity and suppress nematodes.",
                                    "Monitor for sulfur deficiency and, if needed, apply gypsum at 20-30 kg/ha to maintain balance."

                                ]
                            }

                        },
                        Khulna: {
                            soil_pH: {
                                Acidic: [
                                    // "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    // "Use ammonium-based nitrogen fertilizers like ammonium sulfate at 40 kg/ha."
                                    "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    "Use ammonium-based nitrogen fertilizers (e.g., ammonium sulfate at 40 kg/ha) as they reduce acidification risk.",
                                    "Apply DAP at 50 kg/ha for phosphate needs, as acidic soils bind phosphorus.",
                                    "Incorporate bioinoculants such as Trichoderma and mycorrhizal fungi to improve nutrient uptake and soil health.",
                                    "Consider crops like barley, oats, or potatoes, which are tolerant to slightly acidic soils."

                                ],
                                Neutral: [
                                    "Maintain optimal pH using balanced fertilizers (e.g., 40 kg/ha urea and 30 kg/ha DAP).",
                                    "Incorporate rhizobium-based bioinoculants for leguminous crops to enhance nitrogen fixation.",
                                    "Crop options: wheat, maize, or legumes (e.g., chickpeas) for high yields under neutral soil conditions."

                                ],
                                Alkaline: [
                                    "Apply sulfur-based amendments (e.g., elemental sulfur at 100 kg/ha) or gypsum to lower pH and improve micronutrient availability.",
                                    "Use nitrate-based nitrogen fertilizers (e.g., calcium nitrate at 50 kg/ha) to avoid increasing alkalinity.",
                                    "Incorporate DAP at 30-40 kg/ha and bioinoculants like phosphate-solubilizing bacteria (PSB) to enhance phosphorus availability.",
                                    "Recommended crops: cotton, mustard, or sorghum, which tolerate slightly alkaline soils."
                                ]
                            },
                            soil_texture: {
                                Loamy: [
                                    "Loamy soil is ideal for agriculture due to balanced drainage, aeration, and fertility. Maintain its structure by avoiding over-tillage.",
                                    "Incorporate compost or farmyard manure (5-7 tons/ha) to sustain organic matter levels and microbial activity.",
                                    "Use balanced fertilizers (40 kg/ha urea, 30 kg/ha DAP) for steady nutrient supply.",
                                    "Bioinoculants: Use Rhizobium inoculants for legumes and Trichoderma to prevent root diseases.",
                                    "Crop recommendations: wheat, maize, pulses, vegetables, and fruits perform exceptionally well in loamy soils."

                                ],
                                Clay: [
                                    "Clayey soil retains water well but has poor drainage and is prone to compaction. Reduce compaction by applying gypsum (200-300 kg/ha) to improve soil structure.",
                                    "Incorporate coarse sand (2-5 tons/ha) or organic matter (7-10 tons/ha) to improve aeration and permeability.",
                                    "Use slow-release nitrogen fertilizers like coated urea (40-50 kg/ha) to minimize nutrient loss through runoff.",
                                    "Adopt conservation tillage or raised bed planting to prevent waterlogging.",
                                    "Bioinoculants: Use mycorrhizal fungi to improve phosphorus uptake and soil porosity.",
                                    "Crop recommendations: rice, soybean, cotton, and crops that can tolerate wetter conditions are suitable."

                                ],
                                Sandy: [
                                    "Sandy soil has poor water and nutrient retention. Enhance water retention by incorporating organic mulches (5-10 cm layer) or biochar (5 tons/ha).",
                                    "Apply polymer-based hydrogels (10 kg/ha) to increase soil water-holding capacity.",
                                    "Use split application of fertilizers (e.g., 30-40 kg/ha urea) to reduce leaching losses.",
                                    "Bioinoculants: Incorporate phosphate-solubilizing bacteria (PSB) and nitrogen-fixing bacteria (e.g., Azospirillum) to improve fertility.",
                                    "Crop recommendations: millet, sorghum, peanuts, and drought-tolerant vegetables are ideal for sandy soils."

                                ],
                                Silty: [
                                    "Silty soil has good nutrient retention but can compact easily. Prevent crusting by adding organic matter (compost or green manures, 5-8 tons/ha).",
                                    "Improve drainage by incorporating coarse sand (2-4 tons/ha) or planting cover crops to maintain soil structure.",
                                    "Use balanced fertilizers (e.g., 40 kg/ha urea and 20 kg/ha DAP) to support crop growth.",
                                    "Bioinoculants: Use Rhizobium inoculants for leguminous crops to improve soil nitrogen levels.",
                                    "Crop recommendations: wheat, maize, sugarcane, and vegetables like carrots and cucumbers."

                                ],
                                Peaty: [
                                    "Peaty soil is rich in organic matter but often lacks nutrients like phosphorus and potassium. Apply DAP (40-50 kg/ha) and potassium sulfate (30 kg/ha) to meet crop needs.",
                                    "Drain excess water by constructing field drains or raised beds to improve aeration.",
                                    "Incorporate lime (2-3 tons/ha) if soil is highly acidic to improve nutrient availability.",
                                    "Bioinoculants: Use phosphate-solubilizing bacteria (PSB) and Trichoderma to manage nutrient deficiencies and root diseases.",
                                    "Crop recommendations: cabbage, carrots, and other root vegetables perform well in peaty soils."

                                ]
                            },
                            soil_carbon: {
                                Low: [
                                    "Soil organic carbon is low. Incorporate organic amendments like compost (5-10 tons/ha), biochar (3-5 tons/ha), or farmyard manure to boost carbon levels.",
                                    "Adopt cover cropping with legumes (e.g., clover, vetch) or grasses (e.g., ryegrass) to improve carbon sequestration and soil structure.",
                                    "Reduce tillage or adopt no-till farming to minimize carbon losses and enhance carbon storage.",
                                    "Plant agroforestry systems with deep-rooted trees (e.g., Gliricidia or Leucaena) to improve long-term soil carbon.",
                                    "Bioinoculants: Use mycorrhizal fungi and microbial inoculants like Bacillus subtilis to enhance organic matter decomposition and nutrient cycling.",
                                    "Crop recommendations: Legumes (e.g., lentils, chickpeas) or perennial grasses for carbon enrichment and nitrogen fixation."

                                ],
                                Medium: [
                                    "Soil carbon levels are moderate. Maintain balance by incorporating crop residues and green manures (e.g., cowpea, sunn hemp).",
                                    "Rotate crops with deep-rooted species (e.g., sorghum, sunflower) to enhance below-ground carbon storage.",
                                    "Bioinoculants: Use Trichoderma and rhizosphere-promoting bacteria to sustain soil microbial health.",
                                    "Crop recommendations: Maize, wheat, or vegetables that benefit from moderate organic carbon levels."
                                ],
                                High: [
                                    "Soil organic carbon is high. Avoid excessive nitrogen application to prevent nutrient imbalances or leaching.",
                                    "Focus on nutrient balance by applying phosphorus-based fertilizers (e.g., DAP at 30-40 kg/ha) to optimize crop productivity.",
                                    "Incorporate crop rotation with carbon-demanding crops (e.g., maize, cotton) to utilize stored carbon effectively.",
                                    "Adopt controlled-release fertilizers to match nutrient supply with crop demand, avoiding excess carbon-nitrogen imbalance.",
                                    "Bioinoculants: Apply phosphate-solubilizing bacteria (PSB) to support nutrient availability in high-carbon soils.",
                                    "Crop recommendations: Cotton, maize, or high-yielding vegetables to maximize carbon utilization."
                                ]
                            },
                            observed_problems: {
                                "Yellowing Leaves": [
                                    "Yellowing leaves in wheat often indicate nitrogen deficiency. Apply urea at 40-50 kg/ha in split doses to ensure efficient uptake.",
                                    "Incorporate legume intercropping (e.g., clover or vetch) to improve nitrogen availability and reduce dependency on synthetic fertilizers.",
                                    "If yellowing occurs in patches, monitor for fungal diseases like rust or root rot. Apply biofungicides (e.g., Trichoderma spp.) or fungicides like Propiconazole as needed.",
                                    "Test soil for sulfur deficiency, which can also cause yellowing. If confirmed, apply gypsum or elemental sulfur at 30-50 kg/ha."

                                ],
                                "Stunted Growth": [
                                    "Stunted growth in wheat may indicate phosphorus deficiency. Apply DAP (40-50 kg/ha) at sowing or as a basal dose.",
                                    "If compaction is the issue, adopt reduced tillage or use subsoiling to improve aeration.",
                                    "Ensure adequate potassium levels by applying potassium sulfate (30-40 kg/ha) to enhance root development and drought resistance.",
                                    "Monitor for root diseases like nematodes or root rot, which can cause stunted growth. Apply neem cake (1-2 tons/ha) or bioinoculants like Bacillus subtilis for biological control."

                                ],
                                Wilting: [
                                    "Wilting in wheat is often due to water stress or fungal infections. Ensure consistent soil moisture by adopting drip irrigation or mulching with organic materials (5-10 cm layer).",
                                    "Test for Fusarium wilt or Verticillium wilt. If present, apply biofungicides (e.g., Trichoderma spp.) and rotate wheat with non-host crops like legumes or millet.",
                                    "Improve water retention in sandy soils by adding biochar (3-5 tons/ha) or hydrogels (10 kg/ha).",
                                    "Avoid over-fertilization with nitrogen, as excessive growth can lead to susceptibility to wilt."

                                ],
                                "None": []
                            },
                            current_pests: {
                                "None": [
                                    "No pest infestations detected. Maintain regular field monitoring and implement preventive measures such as crop rotation and intercropping to deter future infestations."
                                ],
                                Aphids: [
                                    "Aphids can cause yellowing and curling of wheat leaves. Spray neem oil (5 mL/L water) or introduce natural predators like ladybugs and lacewings to control populations.",
                                    "Apply systemic insecticides like Imidacloprid (0.5 mL/L water) if infestation is severe.",
                                    "Encourage intercropping with garlic or onion to naturally repel aphids."

                                ],
                                "Stem Borers": [
                                    "Stem borers damage wheat stems, leading to lodging. Apply Trichogramma egg cards (50,000 per hectare) to biologically control larvae.",
                                    "Use light traps or pheromone traps to monitor and manage adult populations.",
                                    "Spray Bacillus thuringiensis (Bt) formulations to target larvae effectively."

                                ],
                                Armyworms: [
                                    "Armyworms feed on wheat leaves and stems, causing significant damage. Spray neem oil (5 mL/L water) as a preventive measure.",
                                    "For heavy infestations, use chemical insecticides like Lambda-cyhalothrin (1 mL/L water).",
                                    "Apply biological controls such as NPV (Nuclear Polyhedrosis Virus) sprays to target larvae while preserving beneficial insects."

                                ],
                                Rust: [
                                    "Rust (yellow, leaf, or stem rust) is a fungal disease that weakens wheat plants. Spray Propiconazole (1 mL/L water) or Mancozeb (2 g/L water) at the first sign of infection.",
                                    "Plant resistant varieties like HD-2967 or PBW-343 to minimize susceptibility.",
                                    "Avoid overhead irrigation to reduce humidity levels that encourage fungal growth."

                                ],
                                Blight: [
                                    "Blight causes brown spots on leaves, reducing photosynthetic efficiency. Spray copper-based fungicides like Copper Oxychloride (2 g/L water) or biofungicides like Trichoderma spp.",
                                    "Remove infected plant debris from the field to prevent disease spread.",
                                    "Incorporate crop rotation and balanced fertilization to improve plant vigor and reduce disease incidence."

                                ]
                            },
                            weed_type: {
                                "Wild Mustard": [
                                    "Wild Mustard can compete heavily with wheat for nutrients. Use crop rotation with legumes or corn to break its life cycle.",
                                    "Adopt early manual weeding during the seedling stage to prevent seed set.",
                                    "Apply pre-emergent herbicides like Pendimethalin (1 kg/ha) or post-emergent herbicides like Metsulfuron-methyl (4 g/ha).",
                                    "Incorporate mulching with organic residues (e.g., wheat straw) to suppress weed growth."

                                ],
                                Nutgrass: [
                                    "Nutgrass (Cyperus rotundus) propagates through tubers, making it difficult to control. Practice deep tillage to expose and desiccate tubers.",
                                    "Adopt stale seedbed techniques by irrigating the field, allowing weeds to germinate, and then removing them mechanically.",
                                    "Apply selective herbicides like Halosulfuron-methyl (67 g/ha) for effective control.",
                                    "Use biological controls like fungal pathogens (e.g., Dactylaria higginsii) to suppress nutgrass growth."
                                ],
                                "Lamb’s Quarters": [
                                    "Lamb’s Quarters (Chenopodium album) competes for water and nutrients in wheat fields. Incorporate delayed sowing of wheat to allow early weeding of Lamb’s Quarters.",
                                    "Adopt inter-row cultivation or manual removal during the seedling stage.",
                                    "Use post-emergent herbicides like Isoxaben (50 g/ha) or 2,4-D Amine (1.5 L/ha) for control in the early growth stages.",
                                    "Incorporate cover crops (e.g., clover) to smother weed growth."

                                ],
                                "Wild Oats": [
                                    "Wild Oats (Avena fatua) is a major weed in wheat fields. Rotate wheat with crops like soybean or maize to disrupt its lifecycle.",
                                    "Adopt mechanical weeding or inter-row cultivation to manage infestations during early growth stages.",
                                    "Apply herbicides like Clodinafop-propargyl (60 g/ha) or Fenoxaprop-p-ethyl (100 g/ha) for selective control.",
                                    "Ensure clean seedbeds by removing residual weed seeds during land preparation."

                                ],
                                "None": ["No significant weed infestation detected. Continue regular monitoring and adopt good agricultural practices like crop rotation and mulching to suppress future weed growth."]
                            },
                            weather_condition: {
                                Rainy: [
                                    "Rainy conditions increase the risk of fungal diseases like rust and leaf blight. Monitor crops regularly for early signs of infection.",
                                    "Apply preventive fungicides like Mancozeb (2 g/L water) or biofungicides like Trichoderma spp. to control fungal pathogens.",
                                    "Ensure proper drainage to prevent waterlogging. Adopt raised bed planting if waterlogging is frequent.",
                                    "Avoid over-irrigation and monitor soil moisture levels to reduce disease pressure.",
                                    "Top-dress nitrogen fertilizers (e.g., urea at 20 kg/ha) only after rains to minimize leaching."

                                ],
                                Dry: [
                                    "Dry conditions stress wheat crops, leading to reduced tillering and grain size. Apply organic mulches (e.g., wheat straw, 5-10 cm layer) to retain soil moisture.",
                                    "Adopt deficit irrigation strategies, scheduling water application at critical growth stages like tillering and grain filling.",
                                    "Use anti-transpirants like kaolin clay (3-5%) spray to reduce water loss from leaves.",
                                    "Monitor for dry-weather pests like grasshoppers and apply neem oil sprays or biological controls (e.g., Beauveria bassiana) if needed.",
                                    "Split nitrogen application (e.g., 20 kg/ha at sowing and 20 kg/ha during tillering) to avoid wastage and enhance nutrient uptake."

                                ],
                                Drought: [
                                    "Under drought conditions, adopt drought-tolerant wheat varieties like HD-2967 or C306.",
                                    "Install drip or sprinkler irrigation systems to optimize water use and maintain uniform soil moisture.",
                                    "Incorporate biochar (3-5 tons/ha) or hydrogels (10 kg/ha) into the soil to improve water retention.",
                                    "Apply potassium-based fertilizers (e.g., potassium sulfate at 30-40 kg/ha) to enhance drought resistance and root growth.",
                                    "Monitor for pests like aphids, which proliferate under drought stress, and apply neem oil (5 mL/L water) or Beauveria bassiana as a biocontrol agent.",
                                    "Avoid over-fertilizing with nitrogen, as it can increase drought stress. Focus on balanced fertilizers."

                                ]
                            },

                            previous_crop: {
                                Wheat: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or soybean. Incorporate 20 kg/ha potash to support root development.",
                                    "If growing legumes (e.g., soybean), minimize nitrogen application to avoid excess vegetative growth and rely on Rhizobium inoculants for nitrogen fixation.",
                                    "Incorporate wheat stubble into the soil to enhance organic carbon levels and nutrient cycling."

                                ],
                                Rice: [
                                    "For next cropping: Incorporate 60 kg/ha DAP and 20 kg/ha potash for mustard or chickpea. Add 2 tons/ha farmyard manure to replenish organic matter lost in puddled rice fields.",
                                    "Apply gypsum (200-300 kg/ha) if the field shows signs of sodicity, improving soil structure and phosphorus availability.",
                                    "Incorporate rice straw as mulch or compost to retain soil moisture and enhance microbial activity."

                                ],
                                Maize: [
                                    "For next cropping: Apply 40 kg/ha DAP and 20 kg/ha urea for wheat or pulses. Incorporate 5 tons/ha compost to improve soil organic matter.",
                                    "In case of potassium deficiency, apply 30-40 kg/ha potassium sulfate to maintain nutrient balance.",
                                    "Add Trichoderma-enriched compost to suppress soil-borne pathogens and improve soil health."

                                ],
                                Soybean: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or wheat. Avoid excess nitrogen as residual nitrogen from soybean benefits subsequent crops.",
                                    "Incorporate crop residues as green manure to improve soil structure and organic matter.",
                                    "If phosphorus levels are low, apply rock phosphate at 100 kg/ha for long-term availability."

                                ],
                                Mustard: [
                                    "For next cropping: Apply 50 kg/ha urea and 30 kg/ha DAP for wheat or rice. Add 10 kg/ha zinc sulfate if micronutrient deficiencies are observed.",
                                    "Incorporate mustard residues into the soil to enhance microbial activity and suppress nematodes.",
                                    "Monitor for sulfur deficiency and, if needed, apply gypsum at 20-30 kg/ha to maintain balance."

                                ]
                            }
                        },
                        Sirajganj: {
                            soil_pH: {
                                Acidic: [
                                    // "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    // "Use ammonium-based nitrogen fertilizers like ammonium sulfate at 40 kg/ha."
                                    "Apply lime (2-3 tons/ha) to raise pH and improve nutrient availability.",
                                    "Use ammonium-based nitrogen fertilizers (e.g., ammonium sulfate at 40 kg/ha) as they reduce acidification risk.",
                                    "Apply DAP at 50 kg/ha for phosphate needs, as acidic soils bind phosphorus.",
                                    "Incorporate bioinoculants such as Trichoderma and mycorrhizal fungi to improve nutrient uptake and soil health.",
                                    "Consider crops like barley, oats, or potatoes, which are tolerant to slightly acidic soils."

                                ],
                                Neutral: [
                                    "Maintain optimal pH using balanced fertilizers (e.g., 40 kg/ha urea and 30 kg/ha DAP).",
                                    "Incorporate rhizobium-based bioinoculants for leguminous crops to enhance nitrogen fixation.",
                                    "Crop options: wheat, maize, or legumes (e.g., chickpeas) for high yields under neutral soil conditions."

                                ],
                                Alkaline: [
                                    "Apply sulfur-based amendments (e.g., elemental sulfur at 100 kg/ha) or gypsum to lower pH and improve micronutrient availability.",
                                    "Use nitrate-based nitrogen fertilizers (e.g., calcium nitrate at 50 kg/ha) to avoid increasing alkalinity.",
                                    "Incorporate DAP at 30-40 kg/ha and bioinoculants like phosphate-solubilizing bacteria (PSB) to enhance phosphorus availability.",
                                    "Recommended crops: cotton, mustard, or sorghum, which tolerate slightly alkaline soils."
                                ]
                            },
                            soil_texture: {
                                Loamy: [
                                    "Loamy soil is ideal for agriculture due to balanced drainage, aeration, and fertility. Maintain its structure by avoiding over-tillage.",
                                    "Incorporate compost or farmyard manure (5-7 tons/ha) to sustain organic matter levels and microbial activity.",
                                    "Use balanced fertilizers (40 kg/ha urea, 30 kg/ha DAP) for steady nutrient supply.",
                                    "Bioinoculants: Use Rhizobium inoculants for legumes and Trichoderma to prevent root diseases.",
                                    "Crop recommendations: wheat, maize, pulses, vegetables, and fruits perform exceptionally well in loamy soils."

                                ],
                                Clay: [
                                    "Clayey soil retains water well but has poor drainage and is prone to compaction. Reduce compaction by applying gypsum (200-300 kg/ha) to improve soil structure.",
                                    "Incorporate coarse sand (2-5 tons/ha) or organic matter (7-10 tons/ha) to improve aeration and permeability.",
                                    "Use slow-release nitrogen fertilizers like coated urea (40-50 kg/ha) to minimize nutrient loss through runoff.",
                                    "Adopt conservation tillage or raised bed planting to prevent waterlogging.",
                                    "Bioinoculants: Use mycorrhizal fungi to improve phosphorus uptake and soil porosity.",
                                    "Crop recommendations: rice, soybean, cotton, and crops that can tolerate wetter conditions are suitable."

                                ],
                                Sandy: [
                                    "Sandy soil has poor water and nutrient retention. Enhance water retention by incorporating organic mulches (5-10 cm layer) or biochar (5 tons/ha).",
                                    "Apply polymer-based hydrogels (10 kg/ha) to increase soil water-holding capacity.",
                                    "Use split application of fertilizers (e.g., 30-40 kg/ha urea) to reduce leaching losses.",
                                    "Bioinoculants: Incorporate phosphate-solubilizing bacteria (PSB) and nitrogen-fixing bacteria (e.g., Azospirillum) to improve fertility.",
                                    "Crop recommendations: millet, sorghum, peanuts, and drought-tolerant vegetables are ideal for sandy soils."

                                ],
                                Silty: [
                                    "Silty soil has good nutrient retention but can compact easily. Prevent crusting by adding organic matter (compost or green manures, 5-8 tons/ha).",
                                    "Improve drainage by incorporating coarse sand (2-4 tons/ha) or planting cover crops to maintain soil structure.",
                                    "Use balanced fertilizers (e.g., 40 kg/ha urea and 20 kg/ha DAP) to support crop growth.",
                                    "Bioinoculants: Use Rhizobium inoculants for leguminous crops to improve soil nitrogen levels.",
                                    "Crop recommendations: wheat, maize, sugarcane, and vegetables like carrots and cucumbers."

                                ],
                                Peaty: [
                                    "Peaty soil is rich in organic matter but often lacks nutrients like phosphorus and potassium. Apply DAP (40-50 kg/ha) and potassium sulfate (30 kg/ha) to meet crop needs.",
                                    "Drain excess water by constructing field drains or raised beds to improve aeration.",
                                    "Incorporate lime (2-3 tons/ha) if soil is highly acidic to improve nutrient availability.",
                                    "Bioinoculants: Use phosphate-solubilizing bacteria (PSB) and Trichoderma to manage nutrient deficiencies and root diseases.",
                                    "Crop recommendations: cabbage, carrots, and other root vegetables perform well in peaty soils."

                                ]
                            },
                            soil_carbon: {
                                Low: [
                                    "Soil organic carbon is low. Incorporate organic amendments like compost (5-10 tons/ha), biochar (3-5 tons/ha), or farmyard manure to boost carbon levels.",
                                    "Adopt cover cropping with legumes (e.g., clover, vetch) or grasses (e.g., ryegrass) to improve carbon sequestration and soil structure.",
                                    "Reduce tillage or adopt no-till farming to minimize carbon losses and enhance carbon storage.",
                                    "Plant agroforestry systems with deep-rooted trees (e.g., Gliricidia or Leucaena) to improve long-term soil carbon.",
                                    "Bioinoculants: Use mycorrhizal fungi and microbial inoculants like Bacillus subtilis to enhance organic matter decomposition and nutrient cycling.",
                                    "Crop recommendations: Legumes (e.g., lentils, chickpeas) or perennial grasses for carbon enrichment and nitrogen fixation."

                                ],
                                Medium: [
                                    "Soil carbon levels are moderate. Maintain balance by incorporating crop residues and green manures (e.g., cowpea, sunn hemp).",
                                    "Rotate crops with deep-rooted species (e.g., sorghum, sunflower) to enhance below-ground carbon storage.",
                                    "Bioinoculants: Use Trichoderma and rhizosphere-promoting bacteria to sustain soil microbial health.",
                                    "Crop recommendations: Maize, wheat, or vegetables that benefit from moderate organic carbon levels."
                                ],
                                High: [
                                    "Soil organic carbon is high. Avoid excessive nitrogen application to prevent nutrient imbalances or leaching.",
                                    "Focus on nutrient balance by applying phosphorus-based fertilizers (e.g., DAP at 30-40 kg/ha) to optimize crop productivity.",
                                    "Incorporate crop rotation with carbon-demanding crops (e.g., maize, cotton) to utilize stored carbon effectively.",
                                    "Adopt controlled-release fertilizers to match nutrient supply with crop demand, avoiding excess carbon-nitrogen imbalance.",
                                    "Bioinoculants: Apply phosphate-solubilizing bacteria (PSB) to support nutrient availability in high-carbon soils.",
                                    "Crop recommendations: Cotton, maize, or high-yielding vegetables to maximize carbon utilization."
                                ]
                            },
                            observed_problems: {
                                "Yellowing Leaves": [
                                    "Yellowing leaves in wheat often indicate nitrogen deficiency. Apply urea at 40-50 kg/ha in split doses to ensure efficient uptake.",
                                    "Incorporate legume intercropping (e.g., clover or vetch) to improve nitrogen availability and reduce dependency on synthetic fertilizers.",
                                    "If yellowing occurs in patches, monitor for fungal diseases like rust or root rot. Apply biofungicides (e.g., Trichoderma spp.) or fungicides like Propiconazole as needed.",
                                    "Test soil for sulfur deficiency, which can also cause yellowing. If confirmed, apply gypsum or elemental sulfur at 30-50 kg/ha."

                                ],
                                "Stunted Growth": [
                                    "Stunted growth in wheat may indicate phosphorus deficiency. Apply DAP (40-50 kg/ha) at sowing or as a basal dose.",
                                    "If compaction is the issue, adopt reduced tillage or use subsoiling to improve aeration.",
                                    "Ensure adequate potassium levels by applying potassium sulfate (30-40 kg/ha) to enhance root development and drought resistance.",
                                    "Monitor for root diseases like nematodes or root rot, which can cause stunted growth. Apply neem cake (1-2 tons/ha) or bioinoculants like Bacillus subtilis for biological control."

                                ],
                                Wilting: [
                                    "Wilting in wheat is often due to water stress or fungal infections. Ensure consistent soil moisture by adopting drip irrigation or mulching with organic materials (5-10 cm layer).",
                                    "Test for Fusarium wilt or Verticillium wilt. If present, apply biofungicides (e.g., Trichoderma spp.) and rotate wheat with non-host crops like legumes or millet.",
                                    "Improve water retention in sandy soils by adding biochar (3-5 tons/ha) or hydrogels (10 kg/ha).",
                                    "Avoid over-fertilization with nitrogen, as excessive growth can lead to susceptibility to wilt."

                                ],
                                "None": []
                            },
                            current_pests: {
                                "None": [
                                    "No pest infestations detected. Maintain regular field monitoring and implement preventive measures such as crop rotation and intercropping to deter future infestations."
                                ],
                                Aphids: [
                                    "Aphids can cause yellowing and curling of wheat leaves. Spray neem oil (5 mL/L water) or introduce natural predators like ladybugs and lacewings to control populations.",
                                    "Apply systemic insecticides like Imidacloprid (0.5 mL/L water) if infestation is severe.",
                                    "Encourage intercropping with garlic or onion to naturally repel aphids."

                                ],
                                "Stem Borers": [
                                    "Stem borers damage wheat stems, leading to lodging. Apply Trichogramma egg cards (50,000 per hectare) to biologically control larvae.",
                                    "Use light traps or pheromone traps to monitor and manage adult populations.",
                                    "Spray Bacillus thuringiensis (Bt) formulations to target larvae effectively."

                                ],
                                Armyworms: [
                                    "Armyworms feed on wheat leaves and stems, causing significant damage. Spray neem oil (5 mL/L water) as a preventive measure.",
                                    "For heavy infestations, use chemical insecticides like Lambda-cyhalothrin (1 mL/L water).",
                                    "Apply biological controls such as NPV (Nuclear Polyhedrosis Virus) sprays to target larvae while preserving beneficial insects."

                                ],
                                Rust: [
                                    "Rust (yellow, leaf, or stem rust) is a fungal disease that weakens wheat plants. Spray Propiconazole (1 mL/L water) or Mancozeb (2 g/L water) at the first sign of infection.",
                                    "Plant resistant varieties like HD-2967 or PBW-343 to minimize susceptibility.",
                                    "Avoid overhead irrigation to reduce humidity levels that encourage fungal growth."

                                ],
                                Blight: [
                                    "Blight causes brown spots on leaves, reducing photosynthetic efficiency. Spray copper-based fungicides like Copper Oxychloride (2 g/L water) or biofungicides like Trichoderma spp.",
                                    "Remove infected plant debris from the field to prevent disease spread.",
                                    "Incorporate crop rotation and balanced fertilization to improve plant vigor and reduce disease incidence."

                                ]
                            },
                            weed_type: {
                                "Wild Mustard": [
                                    "Wild Mustard can compete heavily with wheat for nutrients. Use crop rotation with legumes or corn to break its life cycle.",
                                    "Adopt early manual weeding during the seedling stage to prevent seed set.",
                                    "Apply pre-emergent herbicides like Pendimethalin (1 kg/ha) or post-emergent herbicides like Metsulfuron-methyl (4 g/ha).",
                                    "Incorporate mulching with organic residues (e.g., wheat straw) to suppress weed growth."

                                ],
                                Nutgrass: [
                                    "Nutgrass (Cyperus rotundus) propagates through tubers, making it difficult to control. Practice deep tillage to expose and desiccate tubers.",
                                    "Adopt stale seedbed techniques by irrigating the field, allowing weeds to germinate, and then removing them mechanically.",
                                    "Apply selective herbicides like Halosulfuron-methyl (67 g/ha) for effective control.",
                                    "Use biological controls like fungal pathogens (e.g., Dactylaria higginsii) to suppress nutgrass growth."
                                ],
                                "Lamb’s Quarters": [
                                    "Lamb’s Quarters (Chenopodium album) competes for water and nutrients in wheat fields. Incorporate delayed sowing of wheat to allow early weeding of Lamb’s Quarters.",
                                    "Adopt inter-row cultivation or manual removal during the seedling stage.",
                                    "Use post-emergent herbicides like Isoxaben (50 g/ha) or 2,4-D Amine (1.5 L/ha) for control in the early growth stages.",
                                    "Incorporate cover crops (e.g., clover) to smother weed growth."

                                ],
                                "Wild Oats": [
                                    "Wild Oats (Avena fatua) is a major weed in wheat fields. Rotate wheat with crops like soybean or maize to disrupt its lifecycle.",
                                    "Adopt mechanical weeding or inter-row cultivation to manage infestations during early growth stages.",
                                    "Apply herbicides like Clodinafop-propargyl (60 g/ha) or Fenoxaprop-p-ethyl (100 g/ha) for selective control.",
                                    "Ensure clean seedbeds by removing residual weed seeds during land preparation."

                                ],
                                "None": ["No significant weed infestation detected. Continue regular monitoring and adopt good agricultural practices like crop rotation and mulching to suppress future weed growth."]
                            },
                            weather_condition: {
                                Rainy: [
                                    "Rainy conditions increase the risk of fungal diseases like rust and leaf blight. Monitor crops regularly for early signs of infection.",
                                    "Apply preventive fungicides like Mancozeb (2 g/L water) or biofungicides like Trichoderma spp. to control fungal pathogens.",
                                    "Ensure proper drainage to prevent waterlogging. Adopt raised bed planting if waterlogging is frequent.",
                                    "Avoid over-irrigation and monitor soil moisture levels to reduce disease pressure.",
                                    "Top-dress nitrogen fertilizers (e.g., urea at 20 kg/ha) only after rains to minimize leaching."

                                ],
                                Dry: [
                                    "Dry conditions stress wheat crops, leading to reduced tillering and grain size. Apply organic mulches (e.g., wheat straw, 5-10 cm layer) to retain soil moisture.",
                                    "Adopt deficit irrigation strategies, scheduling water application at critical growth stages like tillering and grain filling.",
                                    "Use anti-transpirants like kaolin clay (3-5%) spray to reduce water loss from leaves.",
                                    "Monitor for dry-weather pests like grasshoppers and apply neem oil sprays or biological controls (e.g., Beauveria bassiana) if needed.",
                                    "Split nitrogen application (e.g., 20 kg/ha at sowing and 20 kg/ha during tillering) to avoid wastage and enhance nutrient uptake."

                                ],
                                Drought: [
                                    "Under drought conditions, adopt drought-tolerant wheat varieties like HD-2967 or C306.",
                                    "Install drip or sprinkler irrigation systems to optimize water use and maintain uniform soil moisture.",
                                    "Incorporate biochar (3-5 tons/ha) or hydrogels (10 kg/ha) into the soil to improve water retention.",
                                    "Apply potassium-based fertilizers (e.g., potassium sulfate at 30-40 kg/ha) to enhance drought resistance and root growth.",
                                    "Monitor for pests like aphids, which proliferate under drought stress, and apply neem oil (5 mL/L water) or Beauveria bassiana as a biocontrol agent.",
                                    "Avoid over-fertilizing with nitrogen, as it can increase drought stress. Focus on balanced fertilizers."

                                ]
                            },

                            previous_crop: {
                                Wheat: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or soybean. Incorporate 20 kg/ha potash to support root development.",
                                    "If growing legumes (e.g., soybean), minimize nitrogen application to avoid excess vegetative growth and rely on Rhizobium inoculants for nitrogen fixation.",
                                    "Incorporate wheat stubble into the soil to enhance organic carbon levels and nutrient cycling."

                                ],
                                Rice: [
                                    "For next cropping: Incorporate 60 kg/ha DAP and 20 kg/ha potash for mustard or chickpea. Add 2 tons/ha farmyard manure to replenish organic matter lost in puddled rice fields.",
                                    "Apply gypsum (200-300 kg/ha) if the field shows signs of sodicity, improving soil structure and phosphorus availability.",
                                    "Incorporate rice straw as mulch or compost to retain soil moisture and enhance microbial activity."

                                ],
                                Maize: [
                                    "For next cropping: Apply 40 kg/ha DAP and 20 kg/ha urea for wheat or pulses. Incorporate 5 tons/ha compost to improve soil organic matter.",
                                    "In case of potassium deficiency, apply 30-40 kg/ha potassium sulfate to maintain nutrient balance.",
                                    "Add Trichoderma-enriched compost to suppress soil-borne pathogens and improve soil health."

                                ],
                                Soybean: [
                                    "For next cropping: Apply 50 kg/ha urea and 40 kg/ha DAP for maize or wheat. Avoid excess nitrogen as residual nitrogen from soybean benefits subsequent crops.",
                                    "Incorporate crop residues as green manure to improve soil structure and organic matter.",
                                    "If phosphorus levels are low, apply rock phosphate at 100 kg/ha for long-term availability."

                                ],
                                Mustard: [
                                    "For next cropping: Apply 50 kg/ha urea and 30 kg/ha DAP for wheat or rice. Add 10 kg/ha zinc sulfate if micronutrient deficiencies are observed.",
                                    "Incorporate mustard residues into the soil to enhance microbial activity and suppress nematodes.",
                                    "Monitor for sulfur deficiency and, if needed, apply gypsum at 20-30 kg/ha to maintain balance."

                                ]
                            }
                        }
                    }
                }
            };

            // This function handles enabling/disabling of checkboxes based on the "None" checkbox state
            // Handle the "None" checkbox for observed problems
            function handleNoneCheckbox() {
                const noneCheckbox = document.getElementById("none");
                const otherCheckboxes = document.querySelectorAll('input[name="observed_problems[]"]:not(#none)');

                if (noneCheckbox.checked) {
                    // Disable all other checkboxes and uncheck them
                    otherCheckboxes.forEach(input => {
                        input.disabled = true;
                        input.checked = false; // Uncheck the boxes
                    });
                } else {
                    // Enable the other checkboxes if "None" is unchecked
                    otherCheckboxes.forEach(input => {
                        input.disabled = false;
                    });
                }
            }

            // Handle the "None" checkbox for current pests
            function handleCurrentPestsNoneCheckbox() {
                const currentPestsNoneCheckbox = document.getElementById("current_pests_none");
                const otherPestsCheckboxes = document.querySelectorAll('input[name="current_pests[]"]:not(#current_pests_none)');

                if (currentPestsNoneCheckbox.checked) {
                    // Disable all other checkboxes and uncheck them
                    otherPestsCheckboxes.forEach(input => {
                        input.disabled = true;
                        input.checked = false; // Uncheck the boxes
                    });
                } else {
                    // Enable the other checkboxes if "None" is unchecked
                    otherPestsCheckboxes.forEach(input => {
                        input.disabled = false;
                    });
                }
            }

            // Attach the functions to the change events
            document.getElementById("none").addEventListener("change", handleNoneCheckbox);
            document.getElementById("current_pests_none").addEventListener("change", handleCurrentPestsNoneCheckbox);

            // Function to generate advisory after the form is submitted
            function generateAdvisory() {
                const table = document.getElementById("advisory_table");
                table.style.display = "table";

                setTimeout(function() {
                    table.scrollIntoView({
                        behavior: "smooth", // Smooth scroll
                        block: "start" // Align at the start of the page
                    });
                }, 100); // Delay to


                const location = document.getElementById("location").value;
                const [country, district] = location.split("-");

                const tableBody = table.getElementsByTagName("tbody")[0];

                // Clear existing rows
                tableBody.innerHTML = "";

                // Function to handle advisory data for different conditions (soil_pH, soil_texture, etc.)
                function processAdvisory(conditionType, conditionValue) {
                    if (conditionValue) {
                        const advisoryData = advisories.locations[country]?.[district]?.[conditionType]?.[conditionValue];

                        if (advisoryData) {
                            const row = tableBody.insertRow();
                            const conditionCell = row.insertCell(0);
                            const advisoryCell = row.insertCell(1);
                            conditionCell.textContent = `${conditionType.replace('_', ' ').toUpperCase()} [${conditionValue}] in ${district}`;
                            advisoryCell.classList.add('advisory-text');

                            // Create the list of advisory points
                            const ul = document.createElement("ul");
                            advisoryData.forEach(advice => {
                                const li = document.createElement("li");
                                li.textContent = advice;
                                ul.appendChild(li);
                            });
                            advisoryCell.appendChild(ul);
                        } else {
                            const row = tableBody.insertRow();
                            const messageCell = row.insertCell(0);
                            messageCell.textContent = `No advisory available for this location and ${conditionType.replace('_', ' ')} combination.`;
                        }
                    }
                }

                // Process soil pH, soil texture, and soil carbon advisories
                processAdvisory("soil_pH", document.querySelector('input[name="soil_ph"]:checked')?.value);
                processAdvisory("soil_texture", document.querySelector('input[name="soil_texture"]:checked')?.value);
                processAdvisory("soil_carbon", document.querySelector('input[name="soil_carbon"]:checked')?.value);

                // Process observed problems (with handling for the "None" checkbox)
                const observedProblems = Array.from(document.querySelectorAll('input[name="observed_problems[]"]:checked'))
                    .map(input => input.value);

                // Process each observed problem
                observedProblems.forEach(problem => processAdvisory("observed_problems", problem));

                // Process current pests (with handling for the "None" checkbox)
                const currentPests = Array.from(document.querySelectorAll('input[name="current_pests[]"]:checked')).map(input => input.value);
                currentPests.forEach(pest => processAdvisory("current_pests", pest));

                // Process weed type
                processAdvisory("weed_type", document.querySelector('input[name="weed_type"]:checked')?.value);

                // Process weather condition
                processAdvisory("weather_condition", document.querySelector('input[name="weather_condition"]:checked')?.value);

                // Process previous crop
                processAdvisory("previous_crop", document.querySelector('input[name="previous_crop"]:checked')?.value);
            }

            // Initialize the checkbox states when the page loads
            document.addEventListener('DOMContentLoaded', function() {
                handleNoneCheckbox(); // Ensure checkbox state is set correctly when the page is loaded
                handleCurrentPestsNoneCheckbox(); // Ensure state for current_pests_none is set when the page is loaded
            });
        </script>
    </div>

    <?php // include('footer.php');
    ?>

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