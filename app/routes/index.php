<?php
$app->get('/', function() {
    $data = array();
    return $this->view->render('home.twig', $data);
});
$app->post('/', function($req, $res, $args) {
    $w = $req->getParam("w");
    $x = $req->getParam("x");
    $t = $req->getParam("t");
    $tx = $req->getParam("tx");
    $alfa = $req->getParam("alfa");
    $decalfa = $req->getParam("decalfa");
    $max_i = $req->getParam("max");
//    print_r($w);
//    print_r($x);
//    print_r($t);
//    print_r($tx);
//    print_r($alfa);
//    print_r($decalfa);
//    print_r($max_i);

    $src_ = new \Lugas\LvqSimulator\LVQku();

    $src_->alfa = $alfa;
    $src_->decalfa = $decalfa;
    $src_->isDebug = true;
    $src_->w_ = $w;
    $src_->x_ = $x;
    $src_->t_ = $t;
    $src_->tx_ = $tx;
    $src_->maxiterasi = $max_i;

    $lvq_ = $src_->cariBobot();
//    print_r($lvq_);

    $data["debug"] = 1;
    $data["hitung"] = $lvq_;

    return $this->view->render('learning.twig', $data);
});
