<?php
header("Cache-Control: no-cache, no-store, must-revalidate");
header("Pragma: no-cache");
header("Expires: 0");
?>
<!DOCTYPE html PUBLIC "-//W3C//DTD HTML 4.01 Transitional//EN" "http://www.w3.org/TR/html4/loose.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=UTF-8" />
<script type="text/javascript" src="ajax.js"></script>
<script type="text/javascript" src="/includes/jquery-3.1.1.min.js"></script>
<meta http-equiv=refresh content=1800>
<link type="text/css" href="stylesheet.css" rel="stylesheet"/>
<?php
session_start();
?>
<title>MIT Overview</title>
</head>
<body>
<div id='pagetitle'>Device Status</div>   
<div id="container1">
<script type="text/javascript">
refresh_container1_div();
</script>
</div>
<div id="container3">
<script type="text/javascript">
refresh_container3_div();
</script>
</div>
<div id="container2">
<script type="text/javascript">
refresh_container2_div();
</script>
</div>

</div>

</body>

</html>

