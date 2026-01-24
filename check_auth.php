<?php

use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Hash;

require __DIR__ . '/vendor/autoload.php';
$app = require_once __DIR__ . '/bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

echo "Checking Admin...\n";
$admin = Admin::where('email', 'admin@gritfitness.com')->first();
if ($admin) {
    echo "Admin found: " . $admin->name . "\n";
    if (Hash::check('admin123', $admin->password)) {
        echo "Admin password MATCHES.\n";
    } else {
        echo "Admin password DOES NOT match.\n";
    }
} else {
    echo "Admin NOT found.\n";
}

echo "\nChecking Member...\n";
$member = User::where('email', 'member@gritfitness.com')->first();
if ($member) {
    echo "Member found: " . $member->name . "\n";
    echo "Role: " . $member->role . "\n";
    echo "Status: " . $member->membership_status . "\n";
    if (Hash::check('password', $member->password)) {
        echo "Member password MATCHES.\n";
    } else {
        echo "Member password DOES NOT match.\n";
    }
} else {
    echo "Member NOT found.\n";
}
