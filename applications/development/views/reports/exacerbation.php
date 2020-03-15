<?php

echo "<h1>Exacerbation Report</h1>";
 
$this->load->view('reports/sections/intro',$objReview);
$this->load->view('reports/sections/exacerbation-review',$objReview);
$this->load->view('reports/sections/clinical-exam',$objReview);
$this->load->view('reports/sections/referrals',$objReview);
$this->load->view('reports/sections/qof',$objReview);
$this->load->view('reports/sections/consent',$objReview);

echo "<p><small>Review ID:".$objReview->intReviewID."</small></p>";