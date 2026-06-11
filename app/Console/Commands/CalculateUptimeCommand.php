<?php

namespace App\Console\Commands;

use App\Events\TargetPinged;
use App\Models\Target;
use Illuminate\Console\Command;

class CalculateUptimeCommand extends Command
{
    protected $signature   = 'uptime:calculate';
    protected $description = 'Hitung ulang uptime % 1d/7d/30d untuk semua target berdasarkan status_logs';

    public function handle(): void
    {
        $targets = Target::query()->get();
        $bar     = $this->output->createProgressBar($targets->count());
        $bar->start();

        foreach ($targets as $target) {
            $target->update([
                'uptime_1d'  => $target->calculateUptime(1),
                'uptime_7d'  => $target->calculateUptime(7),
                'uptime_30d' => $target->calculateUptime(30),
            ]);
            TargetPinged::dispatch($target->fresh());
            $bar->advance();
        }

        $bar->finish();
        $this->newLine();
        $this->info("Uptime % berhasil dihitung untuk {$targets->count()} target.");
    }
}
