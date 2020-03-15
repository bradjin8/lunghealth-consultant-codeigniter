<?php
if (strlen($objReview->ProgressReview_AdherenceToTherapyAdequate) > 0)
{
    echo "<p>";
    switch ($objReview->ProgressReview_AdherenceToTherapyAdequate)
    {
        case "Y":
            echo 'Checks on prescription collection suggest good concordance with therapy.';
            break;
        case "N":
            echo 'Checks on prescription collection suggest poor concordance with therapy.';
            break;
        default:
            break;
    }
    echo "</p>";
}
else
{
    echo "
         <!-- NO CONCORDANCE -->
            ";
}