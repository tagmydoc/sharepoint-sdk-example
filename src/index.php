<?php

use Saloon\Http\Auth\AccessTokenAuthenticator;
use TagMyDoc\SharePoint\SharePointClient;

require __DIR__ . '/../vendor/autoload.php';

function get_token(): false|string|null
{
    return @file_get_contents('./token') ?: null;
}
function store_token(string $token): void
{
    file_put_contents('./token', $token);
}

$client = new SharePointClient('<client_id>', '<client_secret>', '<tenant_id>');

$token = get_token();

if ($token === null) {
    $token = $client->getAccessToken()->serialize();
    store_token($token);
}

$auth = AccessTokenAuthenticator::unserialize($token);
$client->authenticate($auth);

$response = $client
    ->drive('<drive_id>')
    ->getItemByPath('<path>');

var_dump($response->json());