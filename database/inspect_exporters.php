<?php

require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$app->make(Illuminate\Contracts\Console\Kernel::class)->bootstrap();

use App\Models\User;
use App\Models\Role;
use App\Models\ExporterProfile;

echo "=== ROLES ===\n";
foreach (Role::all() as $r) {
    echo "ID {$r->id}: {$r->name} ({$r->slug})\n";
}

echo "=== ALL USERS ===\n";
foreach (User::with('roles', 'exporterProfile')->get() as $u) {
    $roles = $u->roles->pluck('slug')->join(', ');
    echo "ID {$u->id}: {$u->name} ({$u->email}) | Centre: {$u->tefc_centre} | Roles: [{$roles}]\n";
}

echo "=== EXPORTER PROFILES COUNT ===\n";
echo ExporterProfile::count() . " profiles exist.\n";
foreach (ExporterProfile::all() as $ep) {
    echo "Profile user_id {$ep->user_id}: {$ep->company_name} | {$ep->city}, {$ep->state}\n";
}
