<?php
// manager/index.php - The Mother Ship Simulator

$dataFile = __DIR__ . '/clients.json';

// Initialize data if not exists
if (!file_exists($dataFile)) {
    file_put_contents($dataFile, json_encode([
        '127.0.0.1:8000' => [
            'plan' => 'regular', 
            'expired_at' => '2026-12-31',
            'status' => 'active',
            'token' => 'secret-token-123'
        ]
    ]));
}

$clients = json_decode(file_get_contents($dataFile), true);

// Add missing fields for backward compatibility with old clients.json
$modified = false;
foreach ($clients as $domain => &$data) {
    if (!isset($data['status'])) {
        $data['status'] = 'active';
        $modified = true;
    }
    if (!isset($data['token'])) {
        $data['token'] = 'secret-token-123';
        $modified = true;
    }
}
unset($data);
if ($modified) {
    file_put_contents($dataFile, json_encode($clients));
}

// 0. HANDLE PING FROM CLIENT (Mothership Sync)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $input = json_decode(file_get_contents('php://input'), true);
    if (isset($input['action']) && $input['action'] == 'ping') {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success', 'message' => 'Mothership is alive']);
        exit;
    }
}

// 1. HANDLE API REQUEST (The "Phone Home" check)
if (isset($_GET['action']) && $_GET['action'] == 'verify') {
    $domain = $_GET['domain'] ?? '';
    header('Content-Type: application/json');
    if (isset($clients[$domain])) {
        echo json_encode([
            'status' => 'success',
            'plan' => $clients[$domain]['plan'],
            'expired_at' => $clients[$domain]['expired_at'],
            'client_status' => $clients[$domain]['status']
        ]);
    } else {
        echo json_encode(['status' => 'error', 'message' => 'Domain not registered']);
    }
    exit;
}

// 2. HANDLE UI ACTIONS (Toggle Plan & Reset Term)
if (isset($_POST['toggle_plan'])) {
    $domain = $_POST['domain'];
    $clients[$domain]['plan'] = ($clients[$domain]['plan'] == 'regular') ? 'pro' : 'regular';
    file_put_contents($dataFile, json_encode($clients));
    
    // If request wants JSON (e.g., from API)
    if (strpos($_SERVER['HTTP_ACCEPT'] ?? '', 'application/json') !== false || isset($_POST['api'])) {
        header('Content-Type: application/json');
        echo json_encode(['status' => 'success']);
        exit;
    }
    
    header("Location: index.php");
    exit;
}

if (isset($_POST['reset_term'])) {
    $domain = $_POST['domain'];
    $clients[$domain]['expired_at'] = date('Y-m-d', strtotime('+1 year'));
    file_put_contents($dataFile, json_encode($clients));
    header("Location: index.php");
    exit;
}

// 3. HANDLE SUSPEND/ACTIVATE
if (isset($_POST['toggle_status'])) {
    $domain = $_POST['domain'];
    $newStatus = ($clients[$domain]['status'] == 'active') ? 'suspended' : 'active';
    $clients[$domain]['status'] = $newStatus;
    
    // Send cURL to Client
    $targetAction = ($newStatus == 'active') ? 'activate' : 'suspend';
    $clientToken = $clients[$domain]['token'] ?? 'secret-token-123';
    
    $ch = curl_init("http://{$domain}/api/mothership-sync");
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(['action' => $targetAction]));
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json',
        'X-Mothership-Token: ' . $clientToken
    ]);
    
    $response = curl_exec($ch);
    $httpCode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);
    
    if ($httpCode == 200) {
        // Only save if client successfully updated
        file_put_contents($dataFile, json_encode($clients));
        $msg = "Client status updated successfully.";
    } else {
        $msg = "Failed to communicate with client. HTTP Code: " . $httpCode;
        // Revert status since client failed
        $clients[$domain]['status'] = ($newStatus == 'active') ? 'suspended' : 'active';
    }
    
    header("Location: index.php?msg=" . urlencode($msg));
    exit;
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>AutoShow Pro - Central Manager</title>
    <script src="https://cdn.tailwindcss.com"></script>
</head>
<body class="bg-slate-900 text-white font-sans p-10">
    <div class="max-w-5xl mx-auto">
        <h1 class="text-3xl font-black mb-8 text-indigo-400">Mother Ship - Central Manager</h1>
        
        <?php if (isset($_GET['msg'])): ?>
        <div class="bg-blue-900/50 border border-blue-500 text-blue-200 px-4 py-3 rounded-xl mb-6">
            <?php echo htmlspecialchars($_GET['msg']); ?>
        </div>
        <?php endif; ?>

        <div class="bg-slate-800 rounded-3xl p-8 border border-slate-700 shadow-2xl">
            <h2 class="text-xl font-bold mb-6">Registered Clients</h2>
            <table class="w-full">
                <thead>
                    <tr class="text-left text-slate-400 border-b border-slate-700">
                        <th class="pb-4">Domain</th>
                        <th class="pb-4">Status</th>
                        <th class="pb-4">Active Plan</th>
                        <th class="pb-4">Expiry</th>
                        <th class="pb-4 text-right">Actions</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-slate-700">
                    <?php foreach($clients as $domain => $data): ?>
                    <tr>
                        <td class="py-4 font-mono text-indigo-300"><?php echo $domain; ?></td>
                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo ($data['status'] ?? 'active') == 'active' ? 'bg-green-500/20 text-green-400' : 'bg-red-500/20 text-red-400'; ?>">
                                <?php echo strtoupper($data['status'] ?? 'active'); ?>
                            </span>
                        </td>
                        <td class="py-4">
                            <span class="px-3 py-1 rounded-full text-xs font-bold <?php echo $data['plan'] == 'pro' ? 'bg-purple-500/20 text-purple-400' : 'bg-blue-500/20 text-blue-400'; ?>">
                                <?php echo strtoupper($data['plan']); ?>
                            </span>
                        </td>
                        <td class="py-4 text-slate-400"><?php echo $data['expired_at']; ?></td>
                        <td class="py-4 text-right space-x-2">
                            <form method="POST" class="inline">
                                <input type="hidden" name="domain" value="<?php echo $domain; ?>">
                                <button type="submit" name="reset_term" class="bg-slate-700 hover:bg-slate-600 px-3 py-2 rounded-xl text-xs font-bold transition-all border border-slate-600">
                                    Reset Term
                                </button>
                            </form>
                            
                            <form method="POST" class="inline">
                                <input type="hidden" name="domain" value="<?php echo $domain; ?>">
                                <input type="hidden" name="api" value="1">
                                <button type="submit" name="toggle_plan" class="bg-indigo-600 hover:bg-indigo-500 px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-lg">
                                    <?php echo $data['plan'] == 'regular' ? 'Set PRO' : 'Set Regular'; ?>
                                </button>
                            </form>

                            <form method="POST" class="inline">
                                <input type="hidden" name="domain" value="<?php echo $domain; ?>">
                                <?php if (($data['status'] ?? 'active') == 'active'): ?>
                                    <button type="submit" name="toggle_status" class="bg-red-600 hover:bg-red-500 px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-lg" onclick="return confirm('Suspend this client?')">
                                        Suspend
                                    </button>
                                <?php else: ?>
                                    <button type="submit" name="toggle_status" class="bg-green-600 hover:bg-green-500 px-3 py-2 rounded-xl text-xs font-bold transition-all shadow-lg" onclick="return confirm('Activate this client?')">
                                        Activate
                                    </button>
                                <?php endif; ?>
                            </form>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <div class="mt-8 text-slate-500 text-sm italic">
            * This dashboard simulates your central server. Suspend/Activate actions will make an HTTP request to the client domain.
        </div>
    </div>
</body>
</html>
