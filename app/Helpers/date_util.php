<?php

use Carbon\Carbon;

function getDay($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd');
}
function tanggalIndo($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, LL');
}
function tanggalIndo2($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll');
}
function tanggalIndoWaktu($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('ll HH:mm');
}
function tanggalIndoWaktu2($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll HH:mm');
}
function tanggalIndoWaktu3($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('LL HH:mm');
}
function tanggalIndoWaktuLidgkap($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, LL HH:mm');
}
function tglIndo2($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('L');
}
function tanggalIndo3($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('ll');
}
function tanggalIndo6($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll');
}
function tglIndo4($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('LL');
}
function tglIndo5($date)
{
    $datetime = new DateTime($date);
    $newdate = $datetime->format(' d M Y ');
    return $newdate;
}
function TampilJamMidit($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('HH:mm');
}
function TampilTanggal($date)
{
    return Carbon::parse($date)->locale('id')->format('Y-m-d');
}
function TanggalBulan($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('D MMM');
}
function BulanTahun($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('MMMM Y');
}
function TanggalOnly($date)
{
    $tanggal = Carbon::parse($date)->locale('id')->format('d');
    $tgl = ltrim($tanggal, '0');
    return $tgl;
}
function BulanOnly($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('MMM');
}
function BulanOnlyAngka($date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('M');
}
function ago($date)
{
    return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
}
function hoursandmins($time, $format = '%02d:%02d')
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function JamOnly($date)
{
    if ($date != null) {
        $jam = Carbon::parse($date)->locale('id')->isoFormat('HH');
        $jam2 = str_replace("0", "", $jam);
    } else {
        $jam2 = 0;
    }
    return $jam2;
}
function Bulan($date)
{
    $dateObj = DateTime::createFromFormat('!m', $date);
    return Carbon::parse($dateObj)->locale('id')->isoFormat('MMMM');
}
function Bulanidg($date)
{
    $dateObj = DateTime::createFromFormat('!m', $date);
    return Carbon::parse($dateObj)->isoFormat('MMMM');
}
function converttanggal($date)
{
    $temp = explode("-", $date);
    $tahun = $temp[0];
    $bl = $temp[1];
    $tanggal = $temp[2];
    $waktu = $bl . "/" . $tanggal . "/" . $tahun;
    return $waktu;
}
function inverttanggal($date)
{
    if ($date == "") {
        $tgl_ukur_bider = "0000-00-00";
    } else {
        $temp = explode("/", $date);
        $bl = $temp[0];
        $tanggal = $temp[1];
        $tahun = $temp[2];
        $tgl_ukur_bider = $tahun . "-" . $bl . "-" . $tanggal;
    }
    return str_replace(' ', '', $tgl_ukur_bider);
}
function ubahKeTanggal($datetime)
{
    $tanggal = date("Y-m-d", strtotime($datetime));
    return $tanggal;
}
function cvtdMYtoYmd($hari)
{
    $dates = DateTime::createFromFormat('d M Y', $hari);
    $dama = $dates->format('Y-m-d');
    return $dama;
}
