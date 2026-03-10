<?php
require_once('../../../wp-load.php');
require_once('inc/ajax_actions.php');

error_reporting(E_ALL);
ini_set('display_errors', 1);

$query = "how to create the dv360 account in cmgalaxy";
echo "Testing Smart Fallback for: $query\n\n";

$fallback = lex_call_openai($query, true);
// (The function doesn't return raw body, so I'll just rely on the fallback printout for now)
echo "Fallback result:\n";
print_r($fallback);
echo "\n\n";

if ($fallback && $fallback['type'] === 'answer' && !empty($fallback['text'])) {
    echo "SUCCESS: AI provided a direct answer!\n";
    echo $fallback['text'];
} else {
    echo "FAILURE: AI did not provide a direct answer in the expected format.\n";
}
