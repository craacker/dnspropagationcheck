<?php
// Define global DNS server locations with logos
$servers = [
    ['location' => 'San Jose CA, United States', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/us.svg'],
    ['location' => 'Holtsville NY, United States', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/us.svg'],
    ['location' => 'Dothan AL, United States', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/us.svg'],
    ['location' => 'Jamaica NY, United States', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/us.svg'],
    ['location' => 'Providence RI, United States', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/us.svg'],
    ['location' => 'Beaconsfield QC, Canada', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/ca.svg'],
    ['location' => 'Culiacan, Mexico', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/mx.svg'],
    ['location' => 'Sao Paulo, Brazil', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/br.svg'],
    ['location' => 'Paterna de Rivera, Spain', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/es.svg'],
    ['location' => 'Manchester, United Kingdom', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/gb.svg'],
    ['location' => 'Lille, France', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/fr.svg'],
    ['location' => 'Amsterdam, Netherlands', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/nl.svg'],
    ['location' => 'Dortmund, Germany', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/de.svg'],
    ['location' => 'Cullinan, South Africa', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/za.svg'],
    ['location' => 'St Petersburg, Russia', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/ru.svg'],
    ['location' => 'Peshawar, Pakistan', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/pk.svg'],
    ['location' => 'Hyderabad, India', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/in.svg'],
    ['location' => 'Shah Alam, Malaysia', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/my.svg'],
    ['location' => 'Singapore, Singapore', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/sg.svg'],
    ['location' => 'Beijing, China', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/cn.svg'],
    ['location' => 'Seoul, South Korea', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/kr.svg'],
    ['location' => 'Osaka, Japan', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/jp.svg'],
    ['location' => 'Adelaide SA, Australia', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/au.svg'],
    ['location' => 'Melbourne VIC, Australia', 'ip' => '8.8.8.8', 'logo' => 'https://flagcdn.com/au.svg'],
];

function checkDNS($domain, $recordType, $servers) {
    $results = [];
    
    foreach ($servers as $server) {
        $apiUrl = "https://dns.google/resolve?name={$domain}&type={$recordType}&edns_client_subnet={$server['ip']}";
        
        // Perform the DNS lookup
        $response = @file_get_contents($apiUrl);
        if ($response !== FALSE) {
            $data = json_decode($response, true);
            $results[$server['location']] = isset($data['Answer']) ? $data['Answer'] : ['No records found'];
        } else {
            $results[$server['location']] = ['Error retrieving DNS records'];
        }
    }
    
    return $results;
}

$results = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !empty($_POST['domain']) && !empty($_POST['record_type'])) {
    $domain = htmlspecialchars($_POST['domain']);
    $recordType = htmlspecialchars($_POST['record_type']);
    $results = checkDNS($domain, $recordType, $servers);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DNS Propagation Checker</title>
    <link href="https://fonts.googleapis.com/css2?family=Roboto:wght@400;500;700&display=swap" rel="stylesheet">
    <style>
        body {
            font-family: 'Roboto', sans-serif;
            background: linear-gradient(to right, #00c6ff, #0072ff);
            color: #333;
            padding: 20px;
        }
        .container {
            max-width: 800px;
            margin: auto;
            background: white;
            padding: 30px;
            border-radius: 12px;
            box-shadow: 0 4px 20px rgba(0, 0, 0, 0.2);
            transition: transform 0.3s;
        }
        .container:hover {
            transform: scale(1.02);
        }
        h1 {
            text-align: center;
            margin-bottom: 20px;
            color: #0072ff;
        }
        input, select, button {
            width: 100%;
            padding: 12px;
            margin: 10px 0;
            border: 1px solid #ddd;
            border-radius: 6px;
            font-size: 16px;
        }
        button {
            background-color: #0072ff;
            color: white;
            border: none;
            cursor: pointer;
            transition: background-color 0.3s;
        }
        button:hover {
            background-color: #005bb5;
        }
        table {
            width: 100%;
            border-collapse: collapse;
            margin-top: 20px;
        }
        th, td {
            padding: 12px;
            border: 1px solid #ddd;
            text-align: left;
        }
        th {
            background-color: #f7f7f7;
            color: #0072ff;
        }
        img {
            width: 30px;
            height: auto;
            vertical-align: middle;
            margin-right: 8px;
        }
        footer {
            text-align: center;
            margin-top: 20px;
            color: #777;
        }
    </style>
</head>
<body>
    <div class="container">
        <h1>DNS Propagation Checker</h1>
        <form method="post">
            <input type="text" name="domain" placeholder="Enter domain name..." required>
            <select name="record_type" required>
                <option value="A">A</option>
                <option value="AAAA">AAAA</option>
                <option value="CNAME">CNAME</option>
                <option value="MX">MX</option>
                <option value="NS">NS</option>
                <option value="TXT">TXT</option>
            </select>
            <button type="submit">Check DNS</button>
        </form>
        
        <?php if (!empty($results)): ?>
            <h2>Results for <?= htmlspecialchars($domain) ?> (<?= htmlspecialchars($recordType) ?>)</h2>
            <table>
                <thead>
                    <tr>
                        <th>Location</th>
                        <th>Record</th>
                        <th>Value</th>
                    </tr>
                </thead>
                <tbody>
                    <?php foreach ($results as $location => $records): ?>
                        <?php foreach ($records as $record): ?>
                            <tr>
                                <td>
                                    <img src="<?= htmlspecialchars($servers[array_search($location, array_column($servers, 'location'))]['logo']) ?>" alt="Flag">
                                    <?= htmlspecialchars($location) ?>
                                </td>
                                <td><?= isset($record['type']) ? htmlspecialchars($record['type']) : 'N/A' ?></td>
                                <td><?= isset($record['data']) ? htmlspecialchars($record['data']) : htmlspecialchars($record) ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endforeach; ?>
                </tbody>
            </table>
        <?php endif; ?>
    </div>
    <footer>
        &copy; <?= date("Y") ?> DNS Propagation Checker. All rights reserved.
    </footer>
</body>
</html>