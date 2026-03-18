<?php
declare(strict_types=1);

/**
 * Session-backed storage for dashboard tables.
 *
 * Structure:
 * - $_SESSION['items'][<type>] = array of ['id','comm','dt']
 * - $_SESSION['completed'] = array of ['id','comm','dt','type']
 * - $_SESSION['csrf'] = string
 */

if (session_status() !== PHP_SESSION_ACTIVE) {
    session_start();
}

$_SESSION['csrf'] ??= bin2hex(random_bytes(16));
$_SESSION['items'] ??= [];
$_SESSION['completed'] ??= [];

function ensure_item_id(array $item, string $fallbackPrefix): array
{
    if (!isset($item['id']) || !is_string($item['id']) || $item['id'] === '') {
        $comm = (string)($item['comm'] ?? '');
        $dt = (string)($item['dt'] ?? '');
        $item['id'] = $fallbackPrefix . '-' . substr(hash('sha256', $comm . '|' . $dt), 0, 16);
    }
    return $item;
}

function seed_demo_if_missing(): void
{
    if (!empty($_SESSION['seeded_demo'])) {
        return;
    }

    $now = time();
    $mk = static function (string $comm, string $dt): array {
        return ['id' => 'demo-' . substr(hash('sha256', $comm . '|' . $dt), 0, 16), 'comm' => $comm, 'dt' => $dt];
    };

    $_SESSION['items']['agenda'] = [
        $mk('AG-2026-001', date('M j, Y • g:i A', $now - 86400 * 1 + 60 * 20)),
        $mk('AG-2026-002', date('M j, Y • g:i A', $now - 86400 * 2 + 60 * 65)),
        $mk('AG-2026-003', date('M j, Y • g:i A', $now - 86400 * 3 + 60 * 280)),
    ];
    $_SESSION['items']['ldr'] = [
        $mk('LDR-2026-010', date('M j, Y • g:i A', $now - 86400 * 1 + 60 * 45)),
        $mk('LDR-2026-011', date('M j, Y • g:i A', $now - 86400 * 2 + 60 * 130)),
        $mk('LDR-2026-012', date('M j, Y • g:i A', $now - 86400 * 3 + 60 * 70)),
    ];
    $_SESSION['items']['ao'] = [
        $mk('AO-2026-101', date('M j, Y • g:i A', $now - 86400 * 1 + 60 * 15)),
        $mk('AO-2026-102', date('M j, Y • g:i A', $now - 86400 * 2 + 60 * 265)),
        $mk('AO-2026-103', date('M j, Y • g:i A', $now - 86400 * 3 + 60 * 30)),
    ];
    $_SESSION['items']['action'] = [
        $mk('FA-2026-201', date('M j, Y • g:i A', $now - 86400 * 1 + 60 * 95)),
        $mk('FA-2026-202', date('M j, Y • g:i A', $now - 86400 * 2 + 60 * 160)),
        $mk('FA-2026-203', date('M j, Y • g:i A', $now - 86400 * 3 + 60 * 305)),
    ];

    $_SESSION['seeded_demo'] = true;
}

function list_items(string $type): array
{
    $rows = (array)($_SESSION['items'][$type] ?? []);
    $out = [];
    foreach ($rows as $r) {
        if (is_array($r)) {
            $out[] = ensure_item_id($r, $type);
        }
    }
    return $out;
}

function delete_item(string $type, string $id): void
{
    $rows = (array)($_SESSION['items'][$type] ?? []);
    $kept = [];
    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $r = ensure_item_id($r, $type);
        if ((string)$r['id'] !== $id) {
            $kept[] = $r;
        }
    }
    $_SESSION['items'][$type] = $kept;
}

function move_to_completed(string $type, string $id): void
{
    $rows = (array)($_SESSION['items'][$type] ?? []);
    $kept = [];
    $moved = null;

    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $r = ensure_item_id($r, $type);
        if ((string)$r['id'] === $id && $moved === null) {
            $moved = $r;
            continue;
        }
        $kept[] = $r;
    }

    $_SESSION['items'][$type] = $kept;

    if (is_array($moved)) {
        $_SESSION['completed'][] = [
            'id' => (string)$moved['id'],
            'comm' => (string)($moved['comm'] ?? ''),
            'dt' => (string)($moved['dt'] ?? ''),
            'type' => $type,
        ];
    }
}

function list_completed(): array
{
    $rows = (array)($_SESSION['completed'] ?? []);
    $out = [];
    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $r = ensure_item_id($r, 'completed');
        $out[] = $r;
    }
    return $out;
}

function delete_completed(string $id): void
{
    $rows = (array)($_SESSION['completed'] ?? []);
    $kept = [];
    foreach ($rows as $r) {
        if (!is_array($r)) {
            continue;
        }
        $r = ensure_item_id($r, 'completed');
        if ((string)$r['id'] !== $id) {
            $kept[] = $r;
        }
    }
    $_SESSION['completed'] = $kept;
}

