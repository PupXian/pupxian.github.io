<?php

function assert_string_array_is_in_alphabetical_order($array) {
  $a = implode(PHP_EOL, $array);
  natcasesort($array);
  $b = implode(PHP_EOL, $array);

  if ($a == $b) return;

  echo "\033[31m" . $a . PHP_EOL . PHP_EOL . "\033[32m" . $b . PHP_EOL;
  exit(1);
}

$readme = file_get_contents(__DIR__ . '/../../Index.md');
preg_match_all('/### Updates(.*)^#{0,2} /msU', $readme, $matches);
$lines = explode(PHP_EOL, $matches[1][0]);

$category = null;
$categories = [];

$links = [];

foreach($lines as $line) {
  if (str_starts_with($line, '### ')) {
    $category = $line;
    $categories[] = $line;
    $links = [];
    $links[] = $line; // # comes before - so the assert error message tells you which category needs fixing
  }

  if (! $category) continue;

  if (str_starts_with($line, '- ')) $links[] = $line;

  // not reached the end yet
  if ($line !== '') continue;

  assert_string_array_is_in_alphabetical_order($links);
  $category = null;
}

if (sizeof($categories) == 0) exit(2); // did the markdown format change?

assert_string_array_is_in_alphabetical_order($categories);
