<?php

if (!function_exists('badgeNilai')) {
    function badgeNilai($nilai)
    {
        switch ($nilai) {
            case 5:
                return '<span class="badge bg-success">5</span>';
            case 4:
                return '<span class="badge bg-primary">4</span>';
            case 3:
                return '<span class="badge bg-warning text-dark">3</span>';
            case 2:
                return '<span class="badge bg-danger">2</span>';
            case 1:
                return '<span class="badge bg-dark">1</span>';
            default:
                return '<span class="badge bg-secondary">' . $nilai . '</span>';
        }
    }
}
