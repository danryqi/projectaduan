<?php

if (!function_exists('e')) {
  function e($s){ return htmlspecialchars($s ?? '', ENT_QUOTES, 'UTF-8'); }
}