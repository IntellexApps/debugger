<?php /** @noinspection PhpComposerExtensionStubsInspection */

/**
 * @var mixed $data          The data to present to user.
 * @var bool  $useDebugPrint True to use the __debug() method of the class, if available.
 */

// Show JSON
headers_sent() or header('Content-Type: application/json');
echo json_encode(array_intersect_key($data, array_flip([ 'file', 'line', 'value' ])), JSON_PRETTY_PRINT);
