<?php

namespace App\Console\Commands;

use App\Models\Device;
use Illuminate\Console\Attributes\Description;
use Illuminate\Console\Attributes\Signature;
use Illuminate\Console\Command;

#[Signature('app:simulate-device-changes {id}')]
#[Description('Randomly toggle device subscription and replacement status - NOTE I ran out of time implementing this..')]
class SimulateDeviceChanges extends Command
{
    public function handle(): int
    {
        $device = Device::find($this->argument('id'));

        if (! $device) {
            $this->error('Device not found.');

            return 1;
        }

        // Toggle Subscription
        $subscription = $device->subscription;
        if ($subscription) {
            $subscription->update(['is_active' => ! $subscription->is_active]);
        }

        // Toggle/Randomise Replacement Status
        $replacement = $device->replacement;
        $statuses = ['delivered', 'pending', 'none'];
        $newStatus = $statuses[array_rand($statuses)];

        if ($replacement) {
            $replacement->update([
                'status' => $newStatus,
                'delivery_date' => $newStatus === 'delivered' ? now()->format('Y-m-d') : null,
            ]);
        }

        $this->info("Device {$device->id} updated: Sub is ".($subscription->is_active ? 'Active' : 'Inactive').", Replacement is {$newStatus}.");

        return 0;
    }
}
