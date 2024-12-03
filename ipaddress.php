<?php

function get_client_ip()
{
    $ipaddress = '101.2.167.255';
    // //     function get_client_ip()
    // // {
    // //     $ipaddress = '';
    //     if (isset($_SERVER['HTTP_CLIENT_IP'])) {
    //         $ipaddress = $_SERVER['HTTP_CLIENT_IP'];
    //     } else if (isset($_SERVER['HTTP_X_FORWARDED_FOR'])) {
    //         $ipaddress = $_SERVER['HTTP_X_FORWARDED_FOR'];
    //     } else if (isset($_SERVER['HTTP_X_FORWARDED'])) {
    //         $ipaddress = $_SERVER['HTTP_X_FORWARDED'];
    //     } else if (isset($_SERVER['HTTP_FORWARDED_FOR'])) {
    //         $ipaddress = $_SERVER['HTTP_FORWARDED_FOR'];
    //     } else if (isset($_SERVER['HTTP_FORWARDED'])) {
    //         $ipaddress = $_SERVER['HTTP_FORWARDED'];
    //     } else if (isset($_SERVER['REMOTE_ADDR'])) {
    //         $ipaddress = $_SERVER['REMOTE_ADDR'];
    //     } else {
    //         $ipaddress = 'UNKNOWN';
    //     }
    return $ipaddress;
}

$PublicIP = get_client_ip();
$token = '2a9ad2ad44fdeb'; // Your IPinfo.io API token
$apiUrl = "https://ipinfo.io/$PublicIP/geo?token=$token"; // Correctly append the token to the URL

// Get the JSON response from the IPinfo API
$json = @file_get_contents($apiUrl);

// Check if the request was successful
if ($json === FALSE) {
    die("Error fetching IP geolocation data.");
}

// Decode the JSON response
$json = json_decode($json, true);

// Check if the response contains valid data
if (isset($json['country'], $json['region'], $json['city'], $json['loc'])) {
    $country = $json['country'];
    $region = $json['region'];
    $city = $json['city'];
    $loc = $json['loc']; // Latitude and Longitude

    // Extract Latitude and Longitude from loc
    list($latitude, $longitude) = explode(',', $loc);

    echo "Country: $country\n";
    echo "Region: $region\n";
    echo "City: $city\n";
    echo "Location (Latitude, Longitude): $loc\n";

    // Step 2: Get Weather Data from wttr.in
    $weatherUrl = "https://wttr.in/$city?format=%C+%t"; // Custom query for weather info

    // Get weather data
    $weatherData = @file_get_contents($weatherUrl);

    // Check if the weather request was successful
    if ($weatherData === FALSE) {
        die("Error fetching weather data.");
    }

    // Output the weather data
    echo "Current Weather: $weatherData\n";
} else {
    echo "Could not retrieve geolocation information.\n";
}
