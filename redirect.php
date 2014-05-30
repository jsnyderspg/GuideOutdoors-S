<?php
	define( 'BLOCK_LOAD', true );
	//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-config.php' );
	//require_once( $_SERVER['DOCUMENT_ROOT'] . '/wordpress/wp-includes/wp-db.php' );
	require_once( 'wp-config.php' );
	require_once( 'wp-includes/wp-db.php' );
	function endsWith($haystack, $needle) {
		return $needle === "" || substr($haystack, -strlen($needle)) === $needle;
	}
	$wpdb = new wpdb( DB_USER, DB_PASSWORD, DB_NAME, DB_HOST);
	$old_url = mysql_real_escape_string($_GET['url']);

	$appending_params = false;
	foreach ($_GET as $key => $value) {
		if($key != 'url') {
			if($appending_params) {
				$old_url.= "&" . $key . "=" . $value;
			} else {
				$old_url.= "?" . $key . "=" . $value;
				$appending_params = true;
			}
		}
	}
	if (strpos($old_url,'/resource/') !== false) { 
		$ballistics = substr($old_url, strrpos($old_url, '/' )+1 );
		$new_url = $wpdb->get_var( sprintf("SELECT new_url FROM url_ballistic_redirects WHERE old_url='%s'", $ballistics) );
		if (!empty($new_url)) {
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: " . $new_url); 
			exit;
		}
	}
	$aid_found = false;
	if (isset($_GET['aid'])) {
		$aid_found = true;
		$aid = mysql_real_escape_string($_GET['aid']);
	} else {
		parse_str($old_url, $url_params);
		if (isset($url_params['aid'])) {
			$aid = $url_params['aid'];
			$aid_found = true;
		}
	}
	if ($aid_found) {
		$new_url = $wpdb->get_var( sprintf("SELECT new_url FROM url_aid_redirects WHERE old_url='%s'", $aid) );
		if (!empty($new_url)) {
			header("HTTP/1.1 301 Moved Permanently"); 
			header("Location: " . $new_url); 
			exit;
		}
	}
	$old_url = str_replace("outdoors", "Outdoors", $old_url);
	$new_url = $wpdb->get_var( sprintf("SELECT new_url FROM url_redirects WHERE old_url='%s'", $old_url) );
	if (!empty($new_url)) {
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: " . $new_url); 
		exit;
	}
	if (endsWith($old_url, "/")) {
		$old_url = rtrim($old_url, "/");
	} else {
		$old_url .= "/";
	}
	$new_url = $wpdb->get_var( sprintf("SELECT new_url FROM url_redirects WHERE old_url='%s'", $old_url) );
	if (!empty($new_url)) {
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: " . $new_url); 
		exit;
	}
	if (strpos($old_url,'sid=111') !== false) { $new_url = "/category/hunting/whitetail-2/"; }
	else if (strpos($old_url,'sid=73') !== false) { $new_url = "/category/hunting/shooting-sports/"; }
	else if (strpos($old_url,'sid=72') !== false) { $new_url = "/category/hunting/predator/"; }
	else if (strpos($old_url,'sid=71') !== false) { $new_url = "/category/hunting/small-gamevarmints/"; }
	else if (strpos($old_url,'sid=28') !== false) { $new_url = "/category/explore/canoeingkayaking/"; }
	else if (strpos($old_url,'sid=27') !== false) { $new_url = "/category/explore/biking/"; }
	else if (strpos($old_url,'sid=26') !== false) { $new_url = "/category/explore/hiking/"; }
	else if (strpos($old_url,'sid=25') !== false) { $new_url = "/category/explore/camping/"; }
	else if (strpos($old_url,'sid=22') !== false) { $new_url = "/category/fishing/freshwater/"; }
	else if (strpos($old_url,'sid=21') !== false) { $new_url = "/category/fishing/saltwater/"; }
	else if (strpos($old_url,'sid=20') !== false) { $new_url = "/category/fishing/panfish-3/"; }
	else if (strpos($old_url,'sid=19') !== false) { $new_url = "/category/fishing/pikemuskie/"; }
	else if (strpos($old_url,'sid=18') !== false) { $new_url = "/category/fishing/walleye-3/"; }
	else if (strpos($old_url,'sid=16') !== false) { $new_url = "/category/fishing/smallmouth-bass-fishing/"; }
	else if (strpos($old_url,'sid=14') !== false) { $new_url = "/category/fishing/largemouth-bass-2/"; }
	else if (strpos($old_url,'sid=13') !== false) { $new_url = "/category/hunting/black-powder/"; }
	else if (strpos($old_url,'sid=11') !== false) { $new_url = "/category/hunting/bowhunting/"; }
	else if (strpos($old_url,'sid=6') !== false) { $new_url = "/category/hunting/waterfowl-2/"; }
	else if (strpos($old_url,'sid=9') !== false) { $new_url = "/category/upland-2/"; }
	else if (strpos($old_url,'sid=7') !== false) { $new_url = "/category/hunting/turkey-3/"; }
	else if (strpos($old_url,'sid=3') !== false) { $new_url = "/category/hunting/bear-2/"; }
	else if (strpos($old_url,'sid=2') !== false) { $new_url = "/category/hunting/elk-3/"; }
	else if (strpos($old_url,'sid=1') !== false) { $new_url = "/category/hunting/whitetail-2/"; }
	else if (strpos($old_url,'ballistics.aspx') !== false) { $new_url = "/ballisticscharts/"; } /* redirect all duplicate ballistics charts URL's */
	else if (strpos($old_url,'OutdoorMain.aspx') !== false) { $new_url = "/"; } /* redirect all e-mails directed to homepage */
	else if (strpos($old_url,'/resource/remington_charts/pr_ballistics.pdf') !== false) { $new_url = "/ballistic-chart/remington_charts/pr_ballistics.pdf/"; }
	if (!empty($new_url)) {
		$protocol = $_SERVER['HTTPS'] == '' ? 'http://' : 'https://';
		$folder = $protocol . $_SERVER['HTTP_HOST'];
		header("HTTP/1.1 301 Moved Permanently"); 
		header("Location: " . $folder . $new_url); 
	} else {
		header("HTTP/1.0 404 Not Found");
	}
?>
<!doctype html>
<html>
<head>
	<title>Not Found</title>
</head>
<body>
<div style="display:none;"><?php echo $old_url . ' not found'; ?></div>
<h1>Oops! We weren't able to find the page you are looking for.</h1>
<h2>Please Try the Links Below:</h2>
<ul>
<li><a href="http://guide.sportsmansguide.com">Guide Outdoors Homepage - Hunting and Fishing Tips, News, and Gear Reviews</a></li>
<li><a href="http://guide.sportsmansguide.com/ballisticscharts/">Ballistics Charts and Tables</a></li>
<li><a href="http://guide.sportsmansguide.com/arrow-charts/">Arrow Selection Guide and Charts</a></li>
<li><a href="http://guide.sportsmansguide.com/trophies/">Hunting and Fishing Trophy Gallery</a></li>
<li><a href="http://www.sportsmansguide.com">Shop Sportsman's Guide Hunting, Fishing, Shooting, and Outdoor Gear</a>
</ul>

<script>
  (function(i,s,o,g,r,a,m){i['GoogleAnalyticsObject']=r;i[r]=i[r]||function(){
  (i[r].q=i[r].q||[]).push(arguments)},i[r].l=1*new Date();a=s.createElement(o),
  m=s.getElementsByTagName(o)[0];a.async=1;a.src=g;m.parentNode.insertBefore(a,m)
  })(window,document,'script','//www.google-analytics.com/analytics.js','ga');

  ga('create', 'UA-50105640-1', 'sportsmansguide.com');
  ga('send', 'pageview');

</script>
</body>
</html>