<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;

class StatusPageController extends Controller
{
    /**
     * Halaman status publik — tidak memerlukan autentikasi.
     */
    public function show(string $slug)
    {
        // Cari user berdasarkan slug (nama tanpa karakter khusus, lowercase)
        $user = User::all()->first(
            fn($u) => $u->statusPageSlug() === strtolower($slug)
        );

        if (!$user) {
            abort(404, 'Halaman status tidak ditemukan.');
        }

        $targets = $user->targets()
            ->active()
            ->orderBy('group_name')
            ->orderBy('name')
            ->get();

        // Statistik ringkas
        $stats = [
            'total'    => $targets->count(),
            'up'       => $targets->where('status', 'up')->count(),
            'down'     => $targets->where('status', 'down')->count(),
            'unstable' => $targets->where('status', 'unstable')->count(),
            'unknown'  => $targets->where('status', 'unknown')->count(),
        ];

        // Tentukan pesan operasional
        $operationalMessage = match (true) {
            $stats['total'] === 0                       => ['level' => 'unknown',  'text' => 'Belum ada layanan yang dipantau'],
            $stats['down'] > 0                         => ['level' => 'down',     'text' => 'Ada gangguan pada sebagian layanan'],
            $stats['unstable'] > 0                     => ['level' => 'unstable', 'text' => 'Sebagian layanan mengalami gangguan kecil'],
            $stats['unknown'] === $stats['total']      => ['level' => 'unknown',  'text' => 'Status layanan belum diketahui'],
            default                                    => ['level' => 'up',       'text' => 'Semua sistem beroperasi normal'],
        };

        // Kelompokkan target per group
        $groups = $targets->groupBy(fn($t) => $t->group_name ?: 'Umum');

        return view('status.public', compact('user', 'targets', 'stats', 'operationalMessage', 'groups'));
    }
}
