<?php

namespace Database\Seeders;

use App\Models\MembershipPackage;
use App\Models\Transaction;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UpdateMembershipPackageIdSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $packages = MembershipPackage::all();
        $packageMap = $packages->pluck('id', 'name');

        User::all()->each(function ($user) use ($packageMap) {
            if ($user->membership_package && isset($packageMap[$user->membership_package])) {
                $user->membership_package_id = $packageMap[$user->membership_package];
                $user->save();
            }
        });

        Transaction::all()->each(function ($transaction) use ($packageMap) {
            if ($transaction->package && isset($packageMap[$transaction->package])) {
                $transaction->membership_package_id = $packageMap[$transaction->package];
                $transaction->save();
            }
        });
    }
}
