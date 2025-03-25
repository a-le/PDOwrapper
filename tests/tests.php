<?php
require_once __DIR__ . '/../src/PDOWrapper.php';
require_once __DIR__ . '/../src/PDOStatementWrapper.php';

/**
 * Simple assertion function for testing.
 *
 * @param mixed $expected The expected value.
 * @param mixed $actual The actual value.
 * @param string $message The message to display for the test.
 */
function assertEqual($expected, $actual, $message = '')
{
    if ($expected === $actual) {
        echo "[PASS] $message\n";
    } else {
        echo "[FAIL] $message\n";
        echo "  Expected: " . var_export($expected, true) . "\n";
        echo "  Actual:   " . var_export($actual, true) . "\n";
    }
}

// Initialize PDOWrapper
$pdo = new DBAL\PDOWrapper('sqlite::memory:');

// Test table creation
$pdo->run('CREATE TABLE test_table (name TEXT, age INTEGER)');

// Test inserting data
$stmt = $pdo->run('INSERT INTO test_table (name, age) VALUES (?, ?)', ['Alice', 25]);
assertEqual(true, $stmt !== false, 'run() should return a valid statement');

// Test faulty query
try {
    $pdo->run('INVALID SQL QUERY');
    echo "[FAIL] No exception thrown for faulty query.\n";
} catch (PDOException $e) {
    if ($e instanceof PDOException && str_contains($e->getMessage(), 'SQLSTATE[HY000]')) {
        echo "[PASS] PDOException caught with expected message for faulty query.\n";
    } else {
        echo "[FAIL] Unexpected exception or message for faulty query.\n";
    }
}

// Test selecting data
$result = $pdo->run('SELECT * FROM test_table WHERE name = ?', ['Alice'])->fetchAll();
assertEqual(1, count($result), 'run() should execute SELECT query and return results');
assertEqual('Alice', $result[0]['name'], 'Name should be Alice');


echo "All tests completed.\n";
