<?php 
//http://www.the-art-of-web.com/php/html-xpath-query/

include_once "creds/values.php";

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

$dom = new DOMDocument();
@$dom->loadHTML($html);

$xpath = new DOMXpath($dom);
$teams = $xpath->query('//div[@class="memhead-user"]');

$teams_info = array();
foreach($teams as $team) {
  $arr = $team->getElementsByTagName("a");
  foreach($arr as $item) {
    $href =  $item->getAttribute("href");
    $text = trim(preg_replace("/[\r\n]+/", " ", $item->nodeValue));
    array_push($teams_info, array(
      'href' => $href,
      'name' => $text
    ));
  }
}

print_r($teams_info);


// Defining the basic cURL function
function curl($url) {
    $ch = curl_init();  // Initialising cURL
    $ckfile = "cookie.txt";
    curl_setopt($ch, CURLOPT_COOKIEFILE, $ckfile);
    curl_setopt($ch, CURLOPT_URL, $url);    // Setting cURL's URL option with the $url variable passed into the function
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE); // Setting cURL's option to return the webpage data
    $data = curl_exec($ch); // Executing the cURL request and assigning the returned data to the $data variable
    curl_close($ch);    // Closing cURL
    return $data;   // Returning the data from the function
}

$scraped_website = curl("http://fantasy.surfermag.com/team/mens/?user=126731");
$dom2 = new DOMDocument();
@$dom2->loadHTML($scraped_website);
$xpath2 = new DOMXpath($dom2);
$teamMembers = $xpath2->query('//ul[@id="DashboardLineup"]');
print_r($teamMembers);
// foreach ($teamMembers as $surfer) {
//   echo "<p>" . $surfer->getAttribute('alt') . "</p>";
// }

// $teamMembers = $xpath2->query('//div[@class="history-surfer"]');
// //print_r($teamMembers);
// foreach($teamMembers as $surfer) {
//   $arr = $surfer->getElementsByTagName("span")->item(0)->nodeValue;
//   echo "<p>" . $arr . "</p>";
// }

?>