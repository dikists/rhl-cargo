<?php

if (!function_exists('getRomawiBulan')) {
    function getRomawiBulan()
    {
        // Ambil bulan sekarang
        $bulan = date('n'); // Menghasilkan angka bulan 1-12

        // Daftar bulan dalam angka Romawi
        $bulanRomawi = [
            1 => 'I',
            2 => 'II',
            3 => 'III',
            4 => 'IV',
            5 => 'V',
            6 => 'VI',
            7 => 'VII',
            8 => 'VIII',
            9 => 'IX',
            10 => 'X',
            11 => 'XI',
            12 => 'XII'
        ];

        // Kembalikan bulan dalam format Romawi
        return $bulanRomawi[$bulan] ?? '';
    }
}

if (!function_exists('get_time')) {
	function get_time($format = 'Y-m-d H:i:s', $tomorrow = false)
	{
		$timezone = "Asia/Jakarta";
		if (function_exists('date_default_timezone_set')) date_default_timezone_set($timezone);

		if ($tomorrow == false) {
			$date = date($format);
		} else {
			$date = date($format, strtotime("+1 day"));
		}

		return $date;
	}
}