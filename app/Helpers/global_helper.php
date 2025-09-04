<?php

if (!function_exists('hilangkanKoma')) {
    function hilangkanKoma($nilai)
    {
        return (int) str_replace(',', '', $nilai);
    }
}
if (!function_exists('formatRupiah')) {
    function formatRupiah($nilai)
    {
        return 'Rp ' . number_format($nilai, 0, ',', '.');
    }
}
