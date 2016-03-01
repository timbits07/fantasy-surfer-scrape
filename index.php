<?php 

include_once "values.php";

//login form action url
$url="http://fantasy.surfermag.com/login/"; 
$postinfo = "username=".$username."&legacy_password=".$legacy_password."&password=".$password."&persistent=on&submit=Login";

$cookie_file_path = "cookie.txt";

$ch = curl_init();
curl_setopt($ch, CURLOPT_HEADER, false);
curl_setopt($ch, CURLOPT_NOBODY, false);
curl_setopt($ch, CURLOPT_URL, $url);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

curl_setopt($ch, CURLOPT_COOKIEJAR, $cookie_file_path);
//set the cookie the site has for certain features, this is optional
curl_setopt($ch, CURLOPT_COOKIE, "cookiename=0");
curl_setopt($ch, CURLOPT_USERAGENT,
    "Mozilla/5.0 (Windows; U; Windows NT 5.0; en-US; rv:1.7.12) Gecko/20050915 Firefox/1.0.7");
curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);
curl_setopt($ch, CURLOPT_REFERER, $_SERVER['REQUEST_URI']);
curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 0);

curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
curl_setopt($ch, CURLOPT_POST, 1);
curl_setopt($ch, CURLOPT_POSTFIELDS, $postinfo);
curl_exec($ch);
// print_r($result);
//page with the content I want to grab
curl_setopt($ch, CURLOPT_URL, "http://fantasy.surfermag.com/club/members/?id=177");
//do stuff with the info with DomDocument() etc
$html = curl_exec($ch);

curl_close($ch);

preg_match_all(
    '/<div class="memhead-user">(.*?)<\/div>/s',
    $html,
    $teams, // will contain the article data
    PREG_SET_ORDER // formats data into an array of posts
);

foreach ($teams as $team) {
    preg_match_all(
	    '/<span>(.*?)<\/span>/s',
	    $team[0],
	    $name, // will contain the article data
	    PREG_SET_ORDER // formats data into an array of posts
		);

		echo "<p>" . $name[0][1] . "</p>";
}

?>