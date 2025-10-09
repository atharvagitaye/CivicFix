<?php
// Temporary script to dump issue_updates (for debugging)
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';
$kernel = $app->make(Illuminate\Contracts\Console\Kernel::class);
$kernel->bootstrap();

use App\Models\IssueUpdate;

$updates = IssueUpdate::where('issue_id', 1)->get();
if ($updates->isEmpty()) {
    echo "NO_UPDATES\n";
    exit;
}
foreach ($updates as $u) {
    echo "ID: {$u->id}\n";
    echo "Status ID: " . ($u->status_id ?? 'null') . "\n";
    echo "Update Description RAW:\n";
    echo $u->update_description . "\n";
    echo "----\n";
}
