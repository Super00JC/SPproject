<?php
declare(strict_types=1);

/**
 * Expected variables:
 * - $active: string one of: receive, agenda, ldr, ao, action, completed
 */

$active = isset($active) ? (string)$active : '';

$items = [
    ['key' => 'receive', 'label' => 'To Receive', 'href' => 'dashboard.php'],
    ['key' => 'agenda', 'label' => 'For Agenda', 'href' => 'forAgenda.php'],
    ['key' => 'ldr', 'label' => 'Legislative Document Request', 'href' => 'Legaslative.php'],
    ['key' => 'ao', 'label' => 'Administrative Order', 'href' => 'Administrative.php'],
    ['key' => 'action', 'label' => 'For Action', 'href' => 'Action.php'],
    ['key' => 'completed', 'label' => 'Completed', 'href' => 'completed.php'],
];
?>

<aside class="sidebar" aria-label="Sidebar">
  <div class="navgroup">
    <?php foreach ($items as $it): ?>
      <?php
        $isActive = ($active === $it['key']);
        $cls = 'navbtn' . ($isActive ? ' navbtn--active' : '');
        $href = (string)$it['href'];
        $isPlaceholder = ($href === '#');
      ?>
      <a
        class="<?= $cls ?>"
        href="<?= htmlspecialchars($href, ENT_QUOTES, 'UTF-8') ?>"
        <?= $isPlaceholder ? 'onclick="return false;"' : '' ?>
      >
        <?= htmlspecialchars($it['label'], ENT_QUOTES, 'UTF-8') ?>
      </a>
    <?php endforeach; ?>
  </div>
</aside>

