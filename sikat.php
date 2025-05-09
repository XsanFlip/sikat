<?php
// c0ded by XsanLahci 2025 - dedicated for Kang Service 
function runCommand($target, $mode) {
    $target = escapeshellarg($target);
    $isWindows = strtoupper(substr(PHP_OS, 0, 3)) === 'WIN';

    switch ($mode) {
        case 'ping':
            return $isWindows ? shell_exec("ping -n 4 $target") : shell_exec("ping -c 4 $target");
        case 'mtr':
            if (!$isWindows && shell_exec("which mtr")) {
                return shell_exec("mtr -n --report $target");
            } else {
                return "MTR tidak tersedia di sistem ini.";
            }
        case 'traceroute':
        default:
            return $isWindows ? shell_exec("tracert -d $target") : shell_exec("traceroute -n $target");
    }
}

function analyzeTraceroute($output, &$graphData) {
    $lines = explode("\n", $output);
    $analysis = "";
    $hops = 0;
    $timeouts = 0;
    $graphData = [];

    foreach ($lines as $line) {
        if (preg_match('/^\s*(\d+)\s+(.*)/', $line)) {
            $hops++;
            preg_match_all('/(\d+)\s*ms/', $line, $times);
            if (empty($times[1])) {
                $timeouts++;
                $graphData[] = 'null';
            } else {
                $avg = array_sum($times[1]) / count($times[1]);
                $graphData[] = round($avg, 2);
            }
        }
    }

    $analysis .= "<strong>Jumlah Hop:</strong> $hops<br>";
    $analysis .= "<strong>Hop Timeout:</strong> $timeouts<br>";
    if ($timeouts > 2) {
        $analysis .= "<strong><span style='color:red'>Banyak hop yang timeout, kemungkinan ada gangguan jaringan atau firewall memblokir ICMP.</span></strong>";
    } else {
        $analysis .= "<strong><span style='color:green'>Jalur jaringan terlihat cukup stabil.</span></strong>";
    }

    return $analysis;
}

$target = $_POST['target'] ?? '';
$mode = $_POST['mode'] ?? 'traceroute';
$output = $target ? runCommand($target, $mode) : '';
$graphData = [];
$analysis = ($mode === 'traceroute' && $output) ? analyzeTraceroute($output, $graphData) : '';
?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>-[ SIKAT - BY IT TAMPAN ]-</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        :root {
            --primary: #00c6ff;
            --secondary: #0072ff;
            --accent: #ff4b1f;
            --dark: #1a1a2e;
            --glass: rgba(255, 255, 255, 0.05);
        }

        * {
            box-sizing: border-box;
            transition: all 0.3s ease;
        }

        body {
            font-family: 'Poppins', system-ui, sans-serif;
            margin: 0;
            background: linear-gradient(45deg, var(--dark), #16213e);
            color: #fff;
            min-height: 100vh;
            display: flex;
            justify-content: center;
            align-items: center;
            padding: 2rem;
        }

        .container {
            background: linear-gradient(145deg, var(--glass), rgba(0,0,0,0.2));
            backdrop-filter: blur(12px);
            padding: 2.5rem;
            border-radius: 1.5rem;
            width: 100%;
            max-width: 1000px;
            box-shadow: 0 8px 32px rgba(0,0,0,0.3);
            border: 1px solid rgba(255,255,255,0.1);
        }

        h1 {
            text-align: center;
            margin-bottom: 2rem;
            font-weight: 700;
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
        }

        .form-group {
            margin-bottom: 1.5rem;
        }

        label {
            display: block;
            margin-bottom: 0.5rem;
            font-weight: 500;
            color: rgba(255,255,255,0.9);
        }

        input, select {
            width: 100%;
            padding: 1rem 1.5rem;
            border: none;
            border-radius: 12px;
            background: rgba(0,0,0,0.4);
            color: #fff;
            font-size: 1rem;
            border: 1px solid rgba(255,255,255,0.1);
        }

        input:focus, select:focus {
            outline: 2px solid var(--primary);
            box-shadow: 0 0 15px rgba(0,198,255,0.3);
        }

        input[type="submit"] {
            background: linear-gradient(45deg, var(--primary), var(--secondary));
            font-weight: 600;
            text-transform: uppercase;
            letter-spacing: 1px;
            padding: 1.2rem;
            cursor: pointer;
        }

        input[type="submit"]:hover {
            transform: translateY(-2px);
            box-shadow: 0 5px 20px rgba(0,198,255,0.4);
        }

        pre {
            background: rgba(0,0,0,0.7);
            padding: 1.5rem;
            border-radius: 12px;
            overflow-x: auto;
            color: #7af78e;
            font-family: 'Fira Code', monospace;
            border: 1px solid rgba(255,255,255,0.1);
            white-space: pre-wrap;
        }

        .analysis {
            margin: 2rem 0;
            padding: 1.5rem;
            background: linear-gradient(45deg, rgba(255,75,31,0.15), rgba(0,114,255,0.15));
            border-radius: 12px;
            border: 1px solid rgba(255,75,31,0.3);
        }

        .analysis h3 {
            margin-top: 0;
            color: var(--accent);
        }

        canvas {
            background: rgba(0,0,0,0.3);
            border-radius: 12px;
            padding: 1.5rem;
            margin: 2rem 0;
        }

        @media (max-width: 768px) {
            .container {
                padding: 1.5rem;
                border-radius: 1rem;
            }
            
            body {
                padding: 1rem;
            }
            
            h1 {
                font-size: 1.8rem;
            }
        }

        .pulse {
            animation: pulse 2s infinite;
        }

        @keyframes pulse {
            0% { opacity: 0.8; }
            50% { opacity: 1; }
            100% { opacity: 0.8; }
        }
    </style>
</head>
<body>
<div class="container">
    <h1 class="pulse">📡 SIKAT</h1>
        <center><p class="tagline">Sistem Informasi Koneksi & Analisis Traceroute</p></center>
    <form method="POST">
        <div class="form-group">
            <label for="target">🔍 Target Network</label>
            <input type="text" name="target" id="target" 
                   placeholder="example.com / 192.168.1.1" 
                   required value="<?=htmlspecialchars($target)?>">
        </div>

        <div class="form-group">
            <label for="mode">⚙️ Analysis Mode</label>
            <select name="mode" id="mode">
                <option value="ping" <?= $mode === 'ping' ? 'selected' : '' ?>>📡 Ping</option>
                <option value="traceroute" <?= $mode === 'traceroute' ? 'selected' : '' ?>>🛤️ Traceroute</option>
                <option value="mtr" <?= $mode === 'mtr' ? 'selected' : '' ?>>📊 MTR (Linux)</option>
            </select>
        </div>

        <input type="submit" value="🚀 Start Analysis">
    </form>

    <?php if ($output): ?>
        <div class="results">
            <h2>📊 <?= strtoupper($mode) ?> Results</h2>
            <pre><?=htmlspecialchars($output)?></pre>

            <?php if ($mode === 'traceroute'): ?>
                <div class="analysis">
                    <h3>🔍 Path Analysis</h3>
                    <?= $analysis ?>
                </div>

                <canvas id="tracerouteChart"></canvas>
                <script>
    const ctx = document.getElementById('tracerouteChart').getContext('2d');
    
    // Konversi null values menjadi 0 dengan styling berbeda
    const processedData = <?= json_encode($graphData) ?>.map(val => val === 'null' ? 0 : val);
    const pointBackgroundColors = <?= json_encode($graphData) ?>.map(val => 
        val === 'null' ? 'rgba(255, 75, 31, 1)' : 'rgba(0, 198, 255, 1)'
    );
    const pointStyles = <?= json_encode($graphData) ?>.map(val => 
        val === 'null' ? 'triangle' : 'circle'
    );

    new Chart(ctx, {
        type: 'line',
        data: {
            labels: <?= json_encode(range(1, count($graphData))) ?>,
            datasets: [{
                label: 'Latency (ms)',
                data: processedData,
                borderColor: 'rgba(0, 198, 255, 0.7)',
                backgroundColor: 'rgba(0, 198, 255, 0.1)',
                borderWidth: 2,
                tension: 0.1,
                pointRadius: 6,
                pointBackgroundColor: pointBackgroundColors,
                pointStyle: pointStyles,
                pointHoverRadius: 8,
                segment: {
                    borderColor: ctx => ctx.p0.parsed.y === 0 || ctx.p1.parsed.y === 0 ? 
                                       'rgba(255, 75, 31, 0.5)' : 'rgba(0, 198, 255, 0.7)',
                    borderDash: ctx => ctx.p0.parsed.y === 0 || ctx.p1.parsed.y === 0 ? 
                                      [5, 3] : [0, 0]
                }
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: true,
            aspectRatio: 1.5,
            plugins: {
                legend: {
                    display: false
                },
                tooltip: {
                    callbacks: {
                        label: function(context) {
                            const original = <?= json_encode($graphData) ?>[context.dataIndex];
                            if (original === 'null') {
                                return 'Timeout (no response)';
                            }
                            return `Hop ${context.label}: ${context.parsed.y} ms`;
                        }
                    }
                },
                annotation: {
                    annotations: {
                        timeoutAnnotation: {
                            type: 'line',
                            yMin: 0,
                            yMax: 0,
                            borderColor: 'rgba(255, 75, 31, 0.5)',
                            borderWidth: 1,
                            borderDash: [6, 6],
                            label: {
                                content: 'Timeout',
                                enabled: true,
                                position: 'right'
                            }
                        }
                    }
                }
            },
            scales: {
                y: {
                    beginAtZero: true,
                    title: {
                        display: true,
                        text: 'Latency (ms)',
                        color: '#fff'
                    },
                    grid: {
                        color: 'rgba(255, 255, 255, 0.1)'
                    },
                    ticks: {
                        color: '#fff'
                    }
                },
                x: {
                    title: {
                        display: true,
                        text: 'Hop Sequence',
                        color: '#fff'
                    },
                    grid: {
                        display: false
                    },
                    ticks: {
                        color: '#fff'
                    }
                }
            },
            interaction: {
                intersect: false,
                mode: 'index'
            }
        }
    });
</script>
            <?php endif; ?>
        </div>
    <?php endif; ?>
</div>
</body>
</html>
