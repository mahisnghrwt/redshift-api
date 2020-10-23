<?php 

define("HEADER_400", "HTTP/1.0 400 Bad Request");
define("HEADER_401", "HTTP/1.0 401 Unauthorized");
define("REDSHIFT_COLS", array("optical_u", "optical_v", "optical_g", "optical_r", "optical_i", "optical_z", "infrared_three_six", "infrared_four_five", "infrared_five_eight", "infrared_eight_zero", "infrared_J", "infrared_H", "infrared_K", "radio_one_four", "status", "job_id"));


define("REDSHIFT_NUMERIC_RESULT", 0);
define("REDSHIFT_IMG_RESULT", 1);
define("REDSHIFT_BOTH_RESULT", 2);
?>