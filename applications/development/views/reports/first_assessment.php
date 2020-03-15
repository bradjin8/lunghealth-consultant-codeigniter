<?php

echo "<h1>Initial Assessment Report</h1>";

$this->load->view('reports/sections/intro',$objReview); 
$this->load->view('reports/sections/diagnosis',$objReview); 
$this->load->view('reports/sections/spirometry',$objReview); 
$this->load->view('reports/sections/personal-features',$objReview); 
$this->load->view('reports/sections/control',$objReview);
$this->load->view('reports/sections/clinical-exam',$objReview);
$this->load->view('reports/sections/therapy-review',array('objReview'=>$objReview,'arrModels'=>$arrModels));
$this->load->view('reports/sections/inhaler-technique',$objReview);
$this->load->view('reports/sections/concordance',$objReview);
$this->load->view('reports/sections/aggravating_factors',$objReview);
$this->load->view('reports/sections/vaccination-status',$objReview);
$this->load->view('reports/sections/education-materials-and-management-plan',$objReview);
$this->load->view('reports/sections/alerts-and-warnings',$objReview);
$this->load->view('reports/sections/referrals',$objReview);
$this->load->view('reports/sections/qof',$objReview);
$this->load->view('reports/sections/consent',$objReview);

echo "<p><small>Review ID:".$objReview->intReviewID."</small></p>";