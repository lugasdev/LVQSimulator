<?php

namespace Lugas\LvqSimulator;

/**
 * Description of LVQku
 *
 * @author lugas
 */
class LVQku {

    public $w_ = array(); // data bobot
    public $x_ = array(); // data learning
    public $t_ = array(); // id dari bobot / kelas
    public $tx_ = array(); // kelas dari data learning
    public $s_ = array(); // data yang akan di cari
    public $maxiterasi = 100;
    public $minerror = 1;
    public $alfa = 0.05;
    public $decalfa = 0.1;
    public $isDebug = false;
    private $size_x = array();
    private $size_y = array();

    public function __construct()
    {
        if (!empty($this->x_[0]) AND ! empty($this->w_[0])) {
            $this->size_x = [count($this->x_), count($this->x_[0])];
            $this->size_w = [count($this->w_), count($this->w_[0])];
        }
    }

    function setSize()
    {
        if (!empty($this->x_[0])) {
            $this->size_x = [count($this->x_), count($this->x_[0])];
        }
        if (!empty($this->w_[0])) {
            $this->size_w = [count($this->w_), count($this->w_[0])];
        }
    }

    public function cariBobot()
    {
        $this->setSize();

        $max_iterasi = $this->maxiterasi;
        $x_ = $this->x_;
        $w_ = $this->w_;
        $size_x = $this->size_x;
        $size_w = $this->size_w;
        $alfa = $this->alfa;
        $decalfa = $this->decalfa;
        $tx_ = $this->tx_;

        $detail_ = array();
        for ($i = 0; $i < $max_iterasi; $i++) {

            $rekap = array();
            $uker = array();
            $uker["status_true"] = 0;
            $uker["status_false"] = 0;
            for ($ii = 0; $ii < $size_x[0]; $ii++) {

                $detail_["iterasi"][$i]["hitung"][$ii]["data"] = $x_[$ii];
                $detail_["iterasi"][$i]["hitung"][$ii]["w_lama"] = $w_;
                for ($j = 0; $j < $size_w[0]; $j++) {
                    $pangkat = 0;
                    $string_hitungan = array();
                    for ($k = 0; $k < $size_w[1]; $k++) {
                        $pangkat = $pangkat + pow(($x_[$ii][$k] - $w_[$j][$k]), 2);
                        $string_hitungan[] = "({$x_[$ii][$k]} - {$w_[$j][$k]})<span style=\"font-size: 10px;vertical-align:+26%;\">2</span>";
                    }
                    $jarak[$j] = sqrt($pangkat);
                    $detail_["iterasi"][$i]["hitung"][$ii]["jarak_hitungan"][] = "&radic;<span style='text-decoration: overline'> " . implode(" + ", $string_hitungan) . " </span> = <b>{$jarak[$j]}</b> ";
                }
                $detail_["iterasi"][$i]["hitung"][$ii]["jarak"] = $jarak;
                $terendah = min($jarak);
                foreach ($jarak as $key => $v) {
                    if ($terendah == $v) {
                        $detail_["iterasi"][$i]["hitung"][$ii]["jarak_terpendek"] = $key;
                        $detail_["iterasi"][$i]["hitung"][$ii]["jarak_terpendek_index"] = $tx_[$key];
                        $detail_["iterasi"][$i]["hitung"][$ii]["jarak_terpendek_seharusnya"] = $tx_[$ii];
                        $rekap[$ii]["jarak"] = $tx_[$key];
                        $rekap[$ii]["jarak_seharusnya"] = $tx_[$ii];
                        $rekap[$ii]["status"] = false;
                        if ($tx_[$ii] == $tx_[$key]) {
                            $rekap[$ii]["status"] = true;

                            $uker["status_true"] ++;
                        } else {
                            $uker["status_false"] ++;
                        }

                        for ($k = 0; $k < $size_w[1]; $k++) {
                            $w_[$key][$k] = $w_[$key][$k] + ($alfa * ($x_[$ii][$k] - $w_[$key][$k]));
                            $detail_["iterasi"][$i]["hitung"][$ii]["w_baru_hitungan"][] = "w[{$key}, {$k}] = {$w_[$key][$k]} + ({$alfa} x ({$x_[$ii][$k]} - {$w_[$key][$k]})) = " . $w_[$key][$k];
                        }

                        break;
                    }
                }
                $detail_["iterasi"][$i]["hitung"][$ii]["w_baru"] = $w_;
            }

            $uker["persen"] = $uker["status_true"] / ($uker["status_false"] + $uker["status_true"]) * 100;
            $uker["persen"] = round($uker["persen"], 2);

            $alfa = $decalfa * $alfa;
            $detail_["iterasi"][$i]["alfa_baru"] = $alfa;
            $detail_["iterasi"][$i]["rekap"] = $rekap;
            $detail_["iterasi"][$i]["uker"] = $uker;
        }

        if ($this->isDebug == false) {
            return array(
                "rekap" => $rekap,
                "uker" => $uker,
                "bobot_akhir" => $w_
            );
        }

        $detail_["bobot_akhir"] = $w_;
        return $detail_;
    }

    public function cariKelas()
    {
        $this->setSize();

        $w_ = $this->w_;
        $cari_ = $this->s_;
        $t = $this->t_;

        $size_w = $this->size_w;

        $detail_["bobot"] = $w_;
        for ($j = 0; $j < $size_w[0]; $j++) {
            $pangkat = 0;
            $detail_["hitung_jarak"][$j] = "";
            for ($k = 0; $k < $size_w[1]; $k++) {
                $detail_["hitung_jarak"][$j] .= "({$cari_[$k]} - {$w_[$j][$k]})2 ";
                $pangkat = $pangkat + pow(($cari_[$k] - $w_[$j][$k]), 2);
            }
            $jarak[$j] = sqrt($pangkat);
            $detail_["hitung_jarak"][$j] .= " = {$jarak[$j]}";
        }
        $detail_["jarak"] = $jarak;
        $terendah = min($jarak);

        $hasil_akhir = null;
        foreach ($jarak as $key => $v) {
            if ($terendah == $v) {
                $hasil_akhir = $t[$key];
                $detail_["jarak_terdekat"] = $key;
                $detail_["hasil"] = $hasil_akhir;
                break;
            }
        }

        if ($this->isDebug == false) {
            return $hasil_akhir;
        }

        return $detail_;
    }

    public function coba()
    {
        return "lugas";
    }

}
