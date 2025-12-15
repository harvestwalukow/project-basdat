<?php

/**
 * Verification Script: Fact-Only Architecture
 * 
 * Script ini memverifikasi bahwa AdminController hanya menggunakan Fact tables
 * dan tidak lagi depend pada Dim tables
 */

echo "=== VERIFICATION: Fact-Only Architecture ===\n\n";

$controllerPath = __DIR__ . '/app/Http/Controllers/AdminController.php';
$factTransaksiPath = __DIR__ . '/app/Models/DW/FactTransaksi.php';

// Check 1: Verify AdminController exists
echo "✓ Checking AdminController...\n";
if (!file_exists($controllerPath)) {
    die("❌ AdminController not found!\n");
}
echo "  → File exists: ✓\n";

// Check 2: Verify no Dim imports in controller
echo "\n✓ Checking for Dim model imports in AdminController...\n";
$controllerContent = file_get_contents($controllerPath);

$dimModels = ['DimHewan', 'DimStaff', 'DimPaket', 'DimCustomer', 'DimPembayaran', 'DimWaktu', 'DimStatusPenitipan'];
$foundDims = [];

foreach ($dimModels as $dim) {
    if (strpos($controllerContent, "use App\\Models\\DW\\$dim;") !== false) {
        $foundDims[] = $dim;
    }
}

if (empty($foundDims)) {
    echo "  → No Dim imports found: ✓\n";
} else {
    echo "  → ❌ Found Dim imports: " . implode(', ', $foundDims) . "\n";
}

// Check 3: Verify Fact imports exist
echo "\n✓ Checking for Fact model imports...\n";
$factModels = ['FactTransaksi', 'FactKeuangan', 'FactLayananPeriodik'];
$foundFacts = [];

foreach ($factModels as $fact) {
    if (strpos($controllerContent, "use App\\Models\\DW\\$fact;") !== false) {
        $foundFacts[] = $fact;
    }
}

if (count($foundFacts) === 3) {
    echo "  → All Fact models imported: ✓\n";
    foreach ($foundFacts as $fact) {
        echo "    - $fact ✓\n";
    }
} else {
    echo "  → ❌ Missing Fact imports. Found: " . implode(', ', $foundFacts) . "\n";
}

// Check 4: Verify operational model imports
echo "\n✓ Checking for Operational model imports...\n";
$operationalModels = ['Pengguna', 'Hewan', 'PaketLayanan', 'Pembayaran'];
$foundOperational = [];

foreach ($operationalModels as $model) {
    if (strpos($controllerContent, "use App\\Models\\$model;") !== false) {
        $foundOperational[] = $model;
    }
}

if (count($foundOperational) === 4) {
    echo "  → All Operational models imported: ✓\n";
    foreach ($foundOperational as $model) {
        echo "    - $model ✓\n";
    }
} else {
    echo "  → ⚠ Some operational imports missing. Found: " . implode(', ', $foundOperational) . "\n";
}

// Check 5: Verify FactTransaksi relationships
echo "\n✓ Checking FactTransaksi relationships...\n";
if (!file_exists($factTransaksiPath)) {
    echo "  → ❌ FactTransaksi model not found!\n";
} else {
    $factContent = file_get_contents($factTransaksiPath);
    
    $expectedRelations = [
        'pemilik()' => 'Pengguna',
        'hewan()' => 'Hewan',
        'paket()' => 'PaketLayanan',
        'staff()' => 'Pengguna',
    ];
    
    $relationChecks = [];
    foreach ($expectedRelations as $relation => $model) {
        if (strpos($factContent, "function $relation") !== false && 
            strpos($factContent, "\\App\\Models\\$model") !== false) {
            $relationChecks[$relation] = true;
        } else {
            $relationChecks[$relation] = false;
        }
    }
    
    $allGood = !in_array(false, $relationChecks);
    if ($allGood) {
        echo "  → All relationships configured correctly: ✓\n";
        foreach ($expectedRelations as $relation => $model) {
            echo "    - $relation → $model ✓\n";
        }
    } else {
        echo "  → ⚠ Some relationships missing:\n";
        foreach ($relationChecks as $relation => $exists) {
            echo "    - $relation: " . ($exists ? "✓" : "❌") . "\n";
        }
    }
}

// Check 6: Count methods in AdminController
echo "\n✓ Checking AdminController methods...\n";
$methods = [
    'dashboard' => 'Dashboard',
    'booking' => 'Booking Management',
    'pets' => 'Pets Management',
    'service' => 'Service Management',
    'payments' => 'Payments Management',
    'staff' => 'Staff Management',
    'reports' => 'Reports & Analytics',
];

$methodsFound = [];
foreach ($methods as $method => $description) {
    if (preg_match("/public function $method\(/", $controllerContent)) {
        $methodsFound[$method] = true;
    } else {
        $methodsFound[$method] = false;
    }
}

$allMethodsExist = !in_array(false, $methodsFound);
if ($allMethodsExist) {
    echo "  → All methods exist: ✓\n";
    foreach ($methods as $method => $description) {
        echo "    - $method(): $description ✓\n";
    }
} else {
    echo "  → ⚠ Some methods missing:\n";
    foreach ($methodsFound as $method => $exists) {
        echo "    - $method(): " . ($exists ? "✓" : "❌") . "\n";
    }
}

// Check 7: Verify no references to Dim in method bodies
echo "\n✓ Checking for Dim references in method bodies...\n";
$dimReferences = [];
preg_match_all('/dim(Hewan|Customer|Paket|Staff|Pembayaran|Status|Waktu)/', $controllerContent, $matches);

if (empty($matches[0])) {
    echo "  → No Dim references in code: ✓\n";
} else {
    echo "  → ❌ Found Dim references:\n";
    $uniqueRefs = array_unique($matches[0]);
    foreach ($uniqueRefs as $ref) {
        $count = substr_count($controllerContent, $ref);
        echo "    - $ref: $count occurrence(s)\n";
    }
}

// Summary
echo "\n" . str_repeat("=", 50) . "\n";
echo "VERIFICATION SUMMARY\n";
echo str_repeat("=", 50) . "\n";

$checks = [
    'AdminController exists' => file_exists($controllerPath),
    'No Dim imports' => empty($foundDims),
    'All Fact imports' => count($foundFacts) === 3,
    'Operational imports' => count($foundOperational) === 4,
    'FactTransaksi relationships' => isset($allGood) ? $allGood : false,
    'All methods exist' => $allMethodsExist,
    'No Dim references' => empty($matches[0]),
];

$passed = 0;
$total = count($checks);

foreach ($checks as $check => $result) {
    echo ($result ? "✓" : "❌") . " $check\n";
    if ($result) $passed++;
}

echo "\n";
echo "Result: $passed/$total checks passed\n";

if ($passed === $total) {
    echo "\n🎉 SUCCESS! All verifications passed!\n";
    echo "✅ System is using Fact-Only architecture correctly.\n";
} else {
    echo "\n⚠ WARNING: Some checks failed. Please review above.\n";
}

echo "\n";
