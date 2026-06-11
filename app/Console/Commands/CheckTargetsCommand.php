<?php

namespace App\Console\Commands;

use App\Models\Target;
use App\Jobs\PingTargetJob;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;

class CheckTargetsCommand extends Command
{
    protected $signature   = 'monitor:run';
    protected $description = 'Mencari target aktif yang sudah waktunya dicek lalu melemparnya ke Queue';

    public function handle()
    {
        $count = 0;
        $driver = DB::connection()->getDriverName();
        $dueExpression = match ($driver) {
            'mysql' => 'DATE_ADD(last_checked_at, INTERVAL check_interval SECOND) <= NOW()',
            'pgsql' => "last_checked_at + (check_interval * INTERVAL '1 second') <= NOW()",
            default => 'last_checked_at <= CURRENT_TIMESTAMP',
        };

        Target::active() // Hanya target yang tidak di-pause
            ->where(function ($query) use ($dueExpression, $driver) {
                $query->whereNull('last_checked_at')
                      ->when(
                          $driver === 'sqlite',
                          fn ($query) => $query->orWhere('last_checked_at', '<=', now()->subSeconds(60)),
                          fn ($query) => $query->orWhereRaw($dueExpression)
                      );
            })
            ->limit(500)
            ->chunk(100, function ($targets) use (&$count) {
                foreach ($targets as $target) {
                    PingTargetJob::dispatch($target->id)->onQueue('monitoring');
                    $count++;
                }
            });

        $this->info("Berhasil mengirim {$count} target aktif ke antrean ping.");
    }
}
