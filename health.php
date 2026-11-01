<?php

declare(strict_types=1);
require __DIR__ . '/src/bootstrap.php';
header('Content-Type: text/plain; charset=utf-8');
if (!in_array($_SERVER['REQUEST_METHOD'], ['GET', 'HEAD'], true)) {
    header('Allow: GET, HEAD');
    http_response_code(405);
    exit('METHOD NOT ALLOWED');
}
try {
    db()->query('SELECT id FROM kegiatan LIMIT 1');
    echo 'OK';
} catch (Throwable $error) {
    http_response_code(503);
    echo 'NOT READY';
}
