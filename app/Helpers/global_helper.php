<?php

use Carbon\Carbon;
use Illuminate\Support\Facades\Http;

function umur($date)
{
    return Carbon::createFromFormat("Y-m-d", $date)->age . " th";
}

function ticketNumber($int)
{
    return date('dmy') . str_pad($int, 4, '0', STR_PAD_LEFT);
}

function Ymd($date)
{
    return Carbon::parse($date)->format('Y/m/d');
}

function nomorUrut($int)
{
    $no = 1;
    if ($int) {
        $latest = sprintf("%03s", abs($int + 1));
    } else {
        $latest = sprintf("%03s", $no);
    }
    return $latest;
}

function bulanRomawi()
{
    $a = Carbon::now();
    $romawi = array("", "I", "II", "III", "IV", "V", "VI", "VII", "VIII", "IX", "X", "XI", "XII");
    $b = $romawi[$a->month];
    return $b;
}

function hapusTitikAngka($int)
{
    $a = str_replace(".", "", $int);
    return $a;
}

function rupiah($nilai, $pecahan = 0)
{
    return "Rp. " . number_format($nilai, $pecahan, ',', '.');
}

function pecahTanpaRp($nilai, $pecahan = 0)
{
    return number_format($nilai, $pecahan, ',', '.');
}
function pecah2Desimal($nilai, $pecahan = 2)
{
    return number_format($nilai, $pecahan, ',', '.');
}

function ubahAngka($str)
{
    $a = (int) $str;
    return $a;
}

function penomoranSurat($kode, $urut, $init, $bulan, $tahun)
{
    $nomor = $kode . '' . $urut . '/' . $init . '/' . $bulan . '/' . $tahun;
    return $nomor;
}

function penyebut($nilai)
{
    $nilai = abs($nilai);
    $huruf = ['', 'satu', 'dua', 'tiga', 'empat', 'lima', 'enam', 'tujuh', 'delapan', 'sembilan', 'sepuluh', 'sebelas'];
    $temp = '';
    if ($nilai < 12) {
        $temp = ' ' . $huruf[$nilai];
    } elseif ($nilai < 20) {
        $temp = penyebut($nilai - 10) . ' belas';
    } elseif ($nilai < 100) {
        $temp = penyebut($nilai / 10) . ' puluh' . penyebut($nilai % 10);
    } elseif ($nilai < 200) {
        $temp = ' seratus' . penyebut($nilai - 100);
    } elseif ($nilai < 1000) {
        $temp = penyebut($nilai / 100) . ' ratus' . penyebut($nilai % 100);
    } elseif ($nilai < 2000) {
        $temp = ' seribu' . penyebut($nilai - 1000);
    } elseif ($nilai < 1000000) {
        $temp = penyebut($nilai / 1000) . ' ribu' . penyebut($nilai % 1000);
    } elseif ($nilai < 1000000000) {
        $temp = penyebut($nilai / 1000000) . ' juta' . penyebut($nilai % 1000000);
    } elseif ($nilai < 1000000000000) {
        $temp = penyebut($nilai / 1000000000) . ' milyar' . penyebut(fmod($nilai, 1000000000));
    } elseif ($nilai < 1000000000000000) {
        $temp = penyebut($nilai / 1000000000000) . ' trilyun' . penyebut(fmod($nilai, 1000000000000));
    }
    return $temp;
}

function terbilang($nilai)
{
    if ($nilai < 0) {
        $hasil = 'minus ' . trim(penyebut($nilai));
    } else {
        $hasil = trim(penyebut($nilai));
    }
    return $hasil;
}
function removeNol($angka)
{
    $angkaTanpaNol = str_replace("0", "", $angka);
    return $angkaTanpaNol;
}
function tambahNol($angka)
{
    if (strlen($angka) == 1) {
        $angkaDenganNol = "0" . $angka;
    } else {
        $angkaDenganNol = $angka;
    }
    return $angkaDenganNol;
}



function mkInitial1($string)
{
    $str2 = substr($string, 4);
    $words = explode(" ", $str2);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= mb_substr($w, 0, 1);
    }
    return $acronym;
}

function mkInitial2($string)
{
    $words = explode(" ", $string);
    $acronym = "";

    foreach ($words as $w) {
        $acronym .= mb_substr($w, 0, 1);
    }
    return $acronym;
}

function intToTime($int)
{
    $date = date("Y-m-d H:i:s", $int);
    return Carbon::parse($date);
}
function intToMonth($int)
{
    $date = date("m", $int);
    return $date;
}
function intToYear($int)
{
    $date = date("Y", $int);
    return $date;
}

function diffHuman($date)
{
    return Carbon::parse($date)->locale('id')->diffForHumans();
}

function ambilAngka($int)
{
    return preg_replace("/[^0-9]/", "", $int);
}


function add_months($months, DateTime $dateObject)
{
    $next = new DateTime($dateObject->format('Y-m-d'));
    $next->modify('last day of +' . $months . ' month');

    if ($dateObject->format('d') > $next->format('d')) {
        return $dateObject->diff($next);
    } else {
        return new DateInterval('P' . $months . 'M');
    }
}


function wa_api($no_wa, $msg)
{
    $response = Http::withHeaders([
        'Content-Type' => 'application/json',
    ])->post('http://103.127.96.85:3000/client/sendMessage/waapik', [
        'chatId' => $no_wa . "@c.us",
        'contentType' => 'string',
        'content' => $msg,
    ]);

    if ($response->successful()) {
        return dd($response->body());
    } else {
        return 'Unexpected HTTP status: ' . $response->status() . ' ' . $response->body();
    }
}

function convertPhoneNumber($phone)
{
    // Hapus semua karakter kecuali angka
    $phone = preg_replace('/[^0-9]/', '', $phone);

    // Ubah awalan '0' menjadi '62'
    if (substr($phone, 0, 1) === '0') {
        $phone = '62' . substr($phone, 1);
    }

    // Tambahkan akhiran '@c.us'
    $phone = $phone . '@c.us';

    return $phone;
}


function generateRandomString($length = 10)
{
    $characters = '0123456789abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ';
    $charactersLength = strlen($characters);
    $randomString = '';
    for ($i = 0; $i < $length; $i++) {
        $randomString .= $characters[random_int(0, $charactersLength - 1)];
    }
    return $randomString;
}
