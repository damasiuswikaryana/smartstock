<?php

use Carbon\Carbon;

function getDay(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd');
}
function tanggalIndo(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, LL');
}
function tanggalIndo2(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll');
}
function tanggalIndoWaktu(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('ll HH:mm');
}
function tanggalIndoWaktu2(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll HH:mm');
}
function tanggalIndoWaktu3(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('LL HH:mm');
}
function tanggalIndoWaktuLidgkap(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, LL HH:mm');
}
function tglIndo2(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('L');
}
function tanggalIndo3(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('ll');
}
function tanggalIndo6(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('dddd, ll');
}
function tglIndo4(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('LL');
}
function tglIndo5(string $date)
{
    $datetime = new DateTime($date);
    $newdate = $datetime->format(' d M Y ');
    return $newdate;
}
function TampilJamMidit(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('HH:mm');
}
function TampilTanggal(string $date)
{
    return Carbon::parse($date)->locale('id')->format('Y-m-d');
}
function TanggalBulan(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('D MMM');
}
function BulanTahun(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('MMMM Y');
}
function TanggalOnly(string $date)
{
    $tanggal = Carbon::parse($date)->locale('id')->format('d');
    $tgl = ltrim($tanggal, '0');
    return $tgl;
}
function BulanOnly(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('MMM');
}
function BulanOnlyAngka(string $date)
{
    return Carbon::parse($date)->locale('id')->isoFormat('M');
}
function ago(string $date)
{
    return Carbon::createFromTimeStamp(strtotime($date))->diffForHumans();
}
function hoursandmins(string $time, $format = '%02d:%02d')
{
    if ($time < 1) {
        return;
    }
    $hours = floor($time / 60);
    $minutes = ($time % 60);
    return sprintf($format, $hours, $minutes);
}

function JamOnly(string $date)
{
    if ($date != null) {
        $jam = Carbon::parse($date)->locale('id')->isoFormat('HH');
        $jam2 = str_replace("0", "", $jam);
    } else {
        $jam2 = 0;
    }
    return $jam2;
}
function Bulan(string $date)
{
    $dateObj = DateTime::createFromFormat('!m', $date);
    return Carbon::parse($dateObj)->locale('id')->isoFormat('MMMM');
}
function Bulanidg(string $date)
{
    $dateObj = DateTime::createFromFormat('!m', $date);
    return Carbon::parse($dateObj)->isoFormat('MMMM');
}
function converttanggal(string $date)
{
    $temp = explode("-", $date);
    $tahun = $temp[0];
    $bl = $temp[1];
    $tanggal = $temp[2];
    $waktu = $bl . "/" . $tanggal . "/" . $tahun;
    return $waktu;
}
function inverttanggal(string $date)
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
function ubahKeTanggal(string $datetime)
{
    $tanggal = date("Y-m-d", strtotime($datetime));
    return $tanggal;
}
function cvtdMYtoYmd(string $hari)
{
    $dates = DateTime::createFromFormat('d M Y', $hari);
    $dama = $dates->format('Y-m-d');
    return $dama;
}
