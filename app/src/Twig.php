<?php

namespace Lugas\LvqSimulator;

/**
 * Description of Twig
 *
 * @author lugas
 */
class Twig {

    private $t_;

    public function __construct() {
        $loader = new \Twig_Loader_Filesystem(APP_DIR . "views");
        $this->t_ = new \Twig_Environment($loader, array(
//            'cache' => APP_DIR . "cache"
        ));

        $this->globals();
    }

    function globals() {
        $filter_css = new \Twig_SimpleFilter('css', function ($string) {
            return "/assets/css/" . $string;
        });
        $this->t_->addFilter($filter_css);

        $filter_js = new \Twig_SimpleFilter('js', function ($string) {
            return "/assets/js/" . $string;
        });
        $this->t_->addFilter($filter_js);

        $filter_plugin = new \Twig_SimpleFilter('plugins', function ($string) {
            return "/assets/plugins/" . $string;
        });
        $this->t_->addFilter($filter_plugin);

        if (!empty($_SESSION["flash"]["alert"]["success"])) {
            $this->t_->addGlobal('alert_success', $_SESSION["flash"]["alert"]["success"]);
            $_SESSION["flash"]["alert"]["success"] = "";
        }
        if (!empty($_SESSION["flash"]["alert"]["danger"])) {
            $this->t_->addGlobal('alert_danger', $_SESSION["flash"]["alert"]["danger"]);
            $_SESSION["flash"]["alert"]["danger"] = "";
        }
        if (!empty($_SESSION["flash"]["alert"]["info"])) {
            $this->t_->addGlobal('alert_info', $_SESSION["flash"]["alert"]["info"]);
            $_SESSION["flash"]["alert"]["info"] = "";
        }
        if (!empty($_SESSION["flash"]["alert"]["warning"])) {
            $this->t_->addGlobal('alert_warning', $_SESSION["flash"]["alert"]["warning"]);
            $_SESSION["flash"]["alert"]["warning"] = "";
        }

//        $filter_image = new \Twig_SimpleFilter('img', function ($string) {
//            $file_dir = PUBLIC_DIR . Config::$upload_folder . "/" . $string;
//            if (is_file($file_dir)) {
//                return '/' . Config::$upload_folder . "/" . $string;
//            }
//            return "/upload/" . $string;
//        });
//        $this->t_->addFilter($filter_image);
//        $this->t_->addGlobal("upload_folder", Config::$upload_folder);
    }

    function view() {
        return $this->t_;
    }

}
