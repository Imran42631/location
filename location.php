<?php
// Get IP address from the query parameter
$ip = isset($_GET['ip']) ? $_GET['ip'] : '';

// Check if IP is provided
if (empty($ip)) {
    echo "<h3>Please provide an IP address using the 'ip' parameter.</h3>";
    exit;
}

// Define the API URL without the API key
$url = "https://api.ipgeolocation.io/ipgeo?include=hostname&ip={$ip}";

// Initialize CURL to make the API request with headers
$ch = curl_init();
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

// Add the required headers
curl_setopt($ch, CURLOPT_HTTPHEADER, [
    "User-Agent: Mozilla/5.0 (Windows NT 10.0; Win64; x64; rv:131.0) Gecko/20100101 Firefox/131.0",
    "Accept: application/json",
    "Accept-Language: en-US,en;q=0.5",
    "Accept-Encoding: gzip, deflate, br, zstd",
    "Origin: https://ipgeolocation.io",
    "Connection: keep-alive",
    "Referer: https://ipgeolocation.io/",
    "Sec-Fetch-Dest: empty",
    "Sec-Fetch-Mode: cors",
    "Sec-Fetch-Site: same-site",
    "Priority: u=0",
    "TE: trailers"
]);

// Execute CURL and decode the JSON response
$response = curl_exec($ch);
curl_close($ch);

$data = json_decode($response, true);

// Check if data was successfully retrieved
if (isset($data['message'])) {
    echo "<h3>Error: " . htmlspecialchars($data['message']) . "</h3>";
    exit;
}

// HTML output with styling for a beautiful display
echo "<!DOCTYPE html>
<html lang='en'>
<head>
    <meta charset='UTF-8'>
    <meta name='viewport' content='width=device-width, initial-scale=1.0'>
    <title>IP Geolocation Information</title>
    <style>
        body { font-family: Arial, sans-serif; background-color: #f4f4f4; }
        .container { width: 60%; margin: auto; padding: 20px; background-color: #fff; border-radius: 8px; box-shadow: 0px 0px 10px rgba(0, 0, 0, 0.1); }
        h2 { color: #333; }
        p { font-size: 16px; }
        .info { color: #555; font-weight: bold; }
    </style>
</head>
<body>
    <div class='container'>
        <h2>Geolocation Details for IP: {$ip}</h2>
        <p><span class='info'>Country:</span> {$data['country_name']}</p>
        <p><span class='info'>City:</span> {$data['city']}</p>
        <p><span class='info'>Region:</span> {$data['state_prov']}</p>
        <p><span class='info'>Latitude:</span> {$data['latitude']}</p>
        <p><span class='info'>Longitude:</span> {$data['longitude']}</p>
        <p><span class='info'>ISP:</span> {$data['isp']}</p>
        <p><span class='info'>Hostname:</span> {$data['hostname']}</p>
        <p><span class='info'>Timezone:</span> {$data['time_zone']['name']}</p>
        <p><span class='info'>Currency:</span> {$data['currency']['name']} ({$data['currency']['code']})</p>
    </div>
</body>
</html>";
?>
