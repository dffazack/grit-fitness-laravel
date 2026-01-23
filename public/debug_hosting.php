<?php
// Paksa tampilkan error untuk debugging
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

echo "<div style='font-family: monospace; padding: 20px;'>";
echo "<h1>🕵️ Debug Hosting: Verbose Mode</h1>";

try {
    echo "<p>Checking paths...</p>";
    
    // Definisi ulang path kandidat
    $candidates = [
        'si_core_inner' => __DIR__.'/si_core/vendor/autoload.php',
        'root_vendor'   => __DIR__.'/vendor/autoload.php',
        'up_si_core'    => __DIR__.'/../si_core/vendor/autoload.php',
        'up_vendor'     => __DIR__.'/../vendor/autoload.php',
    ];

    $autoloadPath = null;
    foreach ($candidates as $label => $path) {
        $exists = file_exists($path);
        echo "path [$label]: $path -> " . ($exists ? "✅ FOUND" : "❌ MISSING") . "<br>";
        if ($exists && !$autoloadPath) {
            $autoloadPath = $path;
        }
    }

    if (!$autoloadPath) {
        throw new Exception("CRITICAL: Vendor autoload not found in any candidate path.");
    }

    echo "<p>Loading Autoloader from: $autoloadPath ...</p>";
    require $autoloadPath;
    echo "<p>✅ Autoloader loaded.</p>";

    $bootstrapPath = dirname($autoloadPath) . '/../bootstrap/app.php';
    echo "<p>Loading Bootstrap from: $bootstrapPath ...</p>";
    
    if (!file_exists($bootstrapPath)) {
        throw new Exception("Bootstrap file missing at $bootstrapPath");
    }

    $app = require_once $bootstrapPath;
    echo "<p>✅ Bootstrap loaded.</p>";

    echo "<p>Booting Kernel...</p>";
    $kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
    $kernel->bootstrap();
    echo "<p>✅ Kernel Booted.</p>";

    // Tes Database
    use Illuminate\Support\Facades\DB;
    use App\Models\HomepageContent;

    $pdo = DB::connection()->getPdo();
    echo "<h3 style='color:green'>✅ Database Connected: " . DB::connection()->getDatabaseName() . "</h3>";
    
    // Cek Data
    $hero = HomepageContent::where('section', 'hero')->first();
    echo "Hero Data: " . ($hero ? "✅ FOUND" : "❌ NOT FOUND") . "<br>";
    if ($hero) {
        echo "Title: " . ($hero->content['title'] ?? 'N/A') . "<br>";
    }

    // Cek Notifikasi
    use App\Models\Notification;
    echo "<hr><h3>Checking Notifications Table</h3>";
    try {
        $notifCount = DB::table('notifications')->count();
        echo "Total Notifications in DB: <strong>$notifCount</strong><br>";
        
        $activeCount = DB::table('notifications')->where('is_active', 1)->count();
        echo "Active Notifications: <strong>$activeCount</strong><br>";
        
        if ($activeCount == 0) {
            echo "<p style='color:orange'>⚠️ No active notifications found. The marquee will NOT appear.</p>";
        } else {
            echo "<p style='color:green'>✅ Should be visible.</p>";
        }
    } catch (\Exception $e) {
        echo "Error checking notifications: " . $e->getMessage();
    }


    // Cek Controller
    $ctrlPath = base_path('app/Http/Controllers/HomeController.php');
    echo "<hr>Checking Controller at: $ctrlPath<br>";
    if (file_exists($ctrlPath)) {
        $content = file_get_contents($ctrlPath);
        if (strpos($content, "HomepageContent::all()") !== false) {
            echo "<span style='color:green; font-weight:bold'>✅ Controller Logic: UPDATED</span>";
        } else {
            echo "<span style='color:red; font-weight:bold'>❌ Controller Logic: OUTDATED</span>";
        }
    } else {
        echo "❌ Controller File Missing";
    }

} catch (Throwable $e) {
    echo "<div style='background: #ffebee; border: 2px solid red; padding: 15px; margin-top: 20px;'>";
    echo "<h2 style='margin-top:0'>🔥 FATAL ERROR</h2>";
    echo "<strong>Type:</strong> " . get_class($e) . "<br>";
    echo "<strong>Message:</strong> " . $e->getMessage() . "<br>";
    echo "<strong>File:</strong> " . $e->getFile() . ":" . $e->getLine() . "<br>";
    echo "<pre>" . $e->getTraceAsString() . "</pre>";
    echo "</div>";
}
echo "</div>";
