<?php

echo "<h1>Summary Report (Early Exit)</h1>";
 
$this->load->view('reports/sections/intro',$objReview);
$this->load->view('reports/sections/exit-summary',$objReview);
$this->load->view('reports/sections/alerts-and-warnings',$objReview);
$this->load->view('reports/sections/referrals',$objReview);
$this->load->view('reports/sections/qof',$objReview);
$this->load->view('reports/sections/consent',$objReview);

echo "<p><small>Review ID:".$objReview->intReviewID."</small></p>";