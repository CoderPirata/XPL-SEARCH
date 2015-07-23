<?php
/*

Official repository - https://github.com/CoderPirata/XPL-SEARCH/

-------------------------------------------------------------------------------
[ XPL SEARCH 0.3 ]-------------------------------------------------------------
-This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 5 database:
* Exploit-DB
* MIlw0rm
* PacketStormSecurity
* IEDB
* IntelligentExploit

-------------------------------------------------------------------------------
[ TO RUN THE SCRIPT ]----------------------------------------------------------
PHP Version       5.6.8
 php5-cli         Lib
cURL support      Enabled
 php5-curl        Lib
cURL Version      7.40.0
allow_url_fopen   On
Permission        Writing

-------------------------------------------------------------------------------
[ ABOUT DEVELOPER ]------------------------------------------------------------
NAME              CoderPirata
Email             coderpirata@gmail.com
Blog              http://coderpirata.blogspot.com.br/
Twitter           https://twitter.com/coderpirata
Google+           https://plus.google.com/103146866540699363823
Pastebin          http://pastebin.com/u/CoderPirata
Github            https://github.com/coderpirata/

-------------------------------------------------------------------------------
[ LOG ]------------------------------------------------------------------------

0.1 - [02/07/2015]
- Started.

0.2 - [12/07/2015]
- Add Exploit-DB.
- Add Colors, only for linux!
- Add Update Function.
- "Generator" of User-Agent reworked.
- Small errors and adaptations.

0.3 - [22/07/2015]
- Bugs solved
- Add "save" Function
- Add "set-db" function

*/

ini_set('error_log',NULL);
ini_set('log_errors',FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen',TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);
$oo = getopt('h::s:p:a::d:', ['set-db:', 'save::', 'update::', 'help::', 'search:', 
                            'proxy:', 'proxy-login', 'about::', 'respond-time:', 
							'banner-no::', 'save-dir:']);

####################################################################################################
## GENERAL FUNCTIONS
function banner(){
if(!extension_loaded("curl")){$cr = cores("red")."LIB cURL disabled, some functions may not work correctly!\n".cores("grey");}	
return cores("grey")."
\t.   ..--. .        .-. .---.    .    .--.  .--..   .
\t \ / |   )|       (   )|       / \   |   ):    |   |
\t  /  |--' |        `-. |---   /___\  |--' |    |---|
\t / \ |    |       (   )|     /     \ |  \ :    |   |
\t'   ''    '---'    `-' '---''       `'   ` `--''   '".cores("red")." 0.3
".cores("grey2")."------------------------------------------------------------------------------~".cores("grey")."
{$cr}
HELP: {$_SERVER["SCRIPT_NAME"]} ".cores("blue")."--help".cores("grey")."
USAGE: {$_SERVER["SCRIPT_NAME"]} ".cores("blue")."--search ".cores("grey")."\"name to search\"
".cores("grey2")."------------------------------------------------------------------------------~\n";
}

function help(){
$script = $_SERVER["SCRIPT_NAME"];
die(cores("grey")."
\t\t.   ..---..    .--.    .-. 
\t\t|   ||    |    |   )  '   )
\t\t|---||--- |    |--'      / 
\t\t|   ||    |    |        '  
\t\t'   ''---''---''        o  

".cores("grey2").".-----------------------------------------------------------------------------.
[ ".cores("grey")."MAIN COMMANDS".cores("grey2")." ]-------------------------------------------------------------'".cores("grey")."

COMMAND: ".cores("blue")."--search".cores("grey")." ~ Simple search
         Example: {$script} ".cores("blue")."--search ".cores("grey")."\"name to search\"
              Or: {$script} ".cores("blue")."-s ".cores("grey")."\"name to serch\"

COMMAND: ".cores("blue")."--help".cores("grey")." ~ For view HELP
         Example: {$script} ".cores("blue")."--help".cores("grey")."
              Or: {$script} ".cores("blue")."-h".cores("grey")."

COMMAND: ".cores("blue")."--about".cores("grey")." ~ For view ABOUT
         Example: {$script} ".cores("blue")."--about".cores("grey")."
              Or: {$script} ".cores("blue")."-a".cores("grey")."

COMMAND: ".cores("blue")."--update".cores("grey")." ~ Command for update the script.
         Example: {$script} ".cores("blue")."--update".cores("grey2")."

.-----------------------------------------------------------------------------.
[ ".cores("grey")."OTHERS COMMANDS".cores("grey2")." ]-----------------------------------------------------------'".cores("grey")."

COMMAND: ".cores("blue")."--set-db".cores("grey")." ~ Select which databases will be used, using the \"system\" of ID's
           ".cores("blue")."0".cores("grey")." - ALL (".cores("blue")."DEFAULT".cores("grey").")
           ".cores("blue")."1".cores("grey")." - Exploit-DB
           ".cores("blue")."2".cores("grey")." - Milw00rm
           ".cores("blue")."3".cores("grey")." - PacketStormSecurity
           ".cores("blue")."4".cores("grey")." - IntelligentExploit
           ".cores("blue")."5".cores("grey")." - IEDB
         Example: {$script} ".cores("blue")."--set-db".cores("grey")." 1
                  {$script} ".cores("blue")."--set-db".cores("grey")." 3,4
              Or: {$script} ".cores("blue")."-d".cores("grey")." 4,1
				
COMMAND: ".cores("blue")."--save".cores("grey")." ~ Save the exploits found by the tool.
         Example: {$script} ".cores("blue")."--save".cores("grey")."
		 
COMMAND: ".cores("blue")."--save-dir".cores("grey")." ~ Sets the directory for saving files.
         Example: {$script} ".cores("blue")."--save-dir".cores("grey")." /media/flash_disc/folder/

COMMAND: ".cores("blue")."--proxy".cores("grey")." ~ Set which proxy to use to perform searches in the dbs.
         Example: {$script} ".cores("blue")."--proxy".cores("grey")." 127.0.0.1:80
                  {$script} ".cores("blue")."--proxy".cores("grey")." 127.0.0.1
              Or: {$script} ".cores("blue")."--p".cores("grey")."

COMMAND: ".cores("blue")."--proxy-login".cores("grey")." ~ Set username and password to login on the proxy, if necessary.
         Example: {$script} ".cores("blue")."--proxy-login".cores("grey")." user:pass

COMMAND: ".cores("blue")."--respond-time".cores("grey")." ~ Command to set the maximum time(in seconds) that the databases have for respond.
         Example: {$script} ".cores("blue")."--respond-time".cores("grey")." 30

COMMAND: ".cores("blue")."--banner-no".cores("grey")." ~ Command for does not display the banner.
         Example: {$script} ".cores("blue")."--banner-no".cores("grey2")."

------------------------------------------------------------------------------~\n");
}

function about(){
die(cores("grey")."
\t\t    .    .              .  
\t\t   / \   |             _|_ 
\t\t  /___\  |.-.  .-. .  . |  
\t\t /     \ |   )(   )|  | |  
\t\t'       `'`-'  `-' `--`-`-' 

".cores("grey2").".-----------------------------------------------------------------------------.
[ ".cores("grey")."XPL SEARCH 0.3".cores("grey2")." ]------------------------------------------------------------'".cores("grey")."
".cores("blue")."--".cores("grey")." This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 5 database:
".cores("blue")."*".cores("grey")." Exploit-DB
".cores("blue")."*".cores("grey")." MIlw0rm
".cores("blue")."*".cores("grey")." PacketStormSecurity
".cores("blue")."*".cores("grey")." IEDB
".cores("blue")."*".cores("grey")." IntelligentExploit

".cores("grey2").".-----------------------------------------------------------------------------.
[ ".cores("grey")."TO RUN THE SCRIPT".cores("grey2")." ]---------------------------------------------------------'".cores("grey")."
PHP Version       ".cores("blue")."5.6.8".cores("grey")."
 php5-cli         ".cores("blue")."Lib".cores("grey")."
cURL support      ".cores("blue")."Enabled".cores("grey")."
 php5-curl        ".cores("blue")."Lib".cores("grey")."
cURL Version      ".cores("blue")."7.40.0".cores("grey")."
allow_url_fopen   ".cores("blue")."On".cores("grey")."
Permission        ".cores("blue")."Writing".cores("grey2")."

.-----------------------------------------------------------------------------.
[ ".cores("grey")."ABOUT DEVELOPER".cores("grey2")." ]-----------------------------------------------------------'".cores("grey")."
NAME              ".cores("blue")."CoderPirata".cores("grey")."
Email             ".cores("blue")."coderpirata@gmail.com".cores("grey")."
Blog              ".cores("blue")."http://coderpirata.blogspot.com.br/".cores("grey")."
Twitter           ".cores("blue")."https://twitter.com/coderpirata".cores("grey")."
Google+           ".cores("blue")."https://plus.google.com/103146866540699363823".cores("grey")."
Pastebin          ".cores("blue")."http://pastebin.com/u/CoderPirata".cores("grey")."
Github            ".cores("blue")."https://github.com/coderpirata/".cores("grey2")."
------------------------------------------------------------------------------~\n");
}

function cores($nome){
$cores = array("red"     => "\033[1;31m", "green"   => "\033[0;32m", "blue"    => "\033[1;34m",
               "grey2"   => "\033[1;30m", "grey"    => "\033[0;37m");
if(substr(strtolower(PHP_OS), 0, 3) != "win"){ return $cores[strtolower($nome)]; }
}

function ccdbs($OPT){
$ids = array(0,1,2,3,4,5);
foreach($ids as $id){ if(!eregi($id, $OPT["db"])){$o=$o+1;} }
if($o==6){$OPT["db"]=0;}
return $OPT;
}

function infos($OPT){
if(!empty($OPT["proxy"]))$proxyR = "\n| ".cores("grey")."PROXY - ".cores("blue").$OPT["proxy"];
if(!empty($OPT["time"])){$timeL  = cores("blue").$OPT["time"].cores("grey")." sec"; }else{ $timeL = cores("blue")."INDEFINITE"; }	

if($OPT["save"]==1){
$save_xpl = cores("blue")."YES".cores("grey2")."\n| ".cores("grey")."SAVE IN ".cores("blue");
 if(isset($OPT["save-dir"]) and !empty($OPT["save-dir"])){
 $save_xpl = $save_xpl.$OPT["save-dir"].cores("grey2");
   if(!is_dir($OPT["save-dir"])){ 
    $save_xpl=$save_xpl." [".cores("red")."ERROR WITH DIR".cores("grey2")."]";   
   }else{
	$save_xpl=$save_xpl." [".cores("green")."DIR OK".cores("grey2")."]";   
   }
 }else{
  $save_xpl = $save_xpl.realpath(".").DIRECTORY_SEPARATOR.cores("grey2")." [ ".cores("blue")."CURRENT DIR".cores("grey2")." ]";
 }
}else{ $save_xpl = cores("blue")."NOT".cores("grey2"); }

if($OPT["db"] == 0){ $setdb = cores("grey2")."[ ".cores("blue")."ALL".cores("grey2")." ] "; }
if(eregi(1, $OPT["db"])){ $setdb .= cores("grey2")."[ ".cores("blue")."EXPLOIT-DB".cores("grey2")." ] "; }
if(eregi(2, $OPT["db"])){ $setdb .= cores("grey2")."[ ".cores("blue")."MILW0RM".cores("grey2")." ] "; }
if(eregi(3, $OPT["db"])){ $setdb .= cores("grey2")."[ ".cores("blue")."PACKETSTORMSECURITY".cores("grey2")." ] "; }
if(eregi(4, $OPT["db"])){ $setdb .= cores("grey2")."[ ".cores("blue")."INTELLIGENTEXPLOIT".cores("grey2")." ] "; }
if(eregi(5, $OPT["db"])){ $setdb .= cores("grey2")."[ ".cores("blue")."IEDB".cores("grey2")." ] "; }

return cores("grey2").".-[ ".cores("grey")."Infos".cores("grey2")." ]-------------------------------------------------------------------.
| ".cores("grey")."SEARCH FOR  ".cores("blue")."{$OPT["find"]}".cores("grey2")."{$proxyR}
| ".cores("grey")."TIME LIMIT FOR DBS RESPOND: {$timeL}".cores("grey2")."
| ".cores("grey")."SAVE EXPLOIT's: {$save_xpl}
| ".cores("grey")."DATABASES TO SEARCH: {$setdb}
'-----------------------------------------------------------------------------'\n\n";
}

function update($OPT){
echo cores("grey")."\nUpdating, wait...\n";

$OPT["url"] = "https://raw.githubusercontent.com/CoderPirata/XPL-SEARCH/master/xpl%20search.php";
$update = browser($OPT);

if(!eregi("#END", $update["file"])){ die(cores("red")."\nIt seems that the code has not been fully updated.\n Canceled update, try again...\n"); }

file_put_contents(__FILE__,  $update["file"]);
die(cores("green")."\nUpdate DONE!");
}

function save($save){
$ds = DIRECTORY_SEPARATOR;
$svd = "| ".cores("grey")."SAVED: ";

if(eregi("milw00rm.org/", $save["link"])){ 
$save["url"] = trim(str_replace("LINK::", "", $save["link"]));
$save["title"] = trim(str_replace("NAME::", "", $save["title"]));
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
$save["dbs"] = "milw00rm";
if(!eregi("# milw00rm.org", $save["xpl"])){$ok=$ok+1;}
}

if(eregi("iedb.ir/", $save["link"])){ 
$save["url"] = trim(str_replace("LINK::", "", $save["link"]));
$save["title"] = trim(str_replace("NAME::", "", $save["title"]));
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
$save["dbs"] = "iedb";
if(!eregi("# Iranian Exploit DataBase =", $save["xpl"])){$ok=$ok+1;} 
}

if(preg_match("/packetstormsecurity.com/i", $save["url"])){
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$xpl = str_replace("<br />", PHP_EOL, $xpl[1][0]);
$xpl = str_replace("<code>", "", $xpl);
$xpl = str_replace("</code>", "", $xpl);
$xpl = str_replace("&#27;", "", $xpl);
$save["xpl"] = html_entity_decode($xpl, ENT_QUOTES);
$save["dbs"] = "packetstormsecurity";
if(empty($save["xpl"])){$ok=$ok+1;}
}

if(eregi("intelligentexploit.com/", $save["url"])) {
$resultado = browser($save);
preg_match_all('/<\/HEAD><BODY>(.+)<\/BODY>/s', htmlspecialchars_decode($resultado["file"]), $xpl);
preg_match_all('/<script type="text\/javascript">(.+)<\/script>/s', $xpl[1][0], $xpl_l);
$save["xpl"] = trim(str_replace($xpl_l[0][0], "", $xpl[1][0]));
$save["xpl"] = trim(str_replace("&#039;", "'", $save["xpl"]));
$save["dbs"] = "intelligentexploit";
if(eregi("</HEAD><BODY>", htmlspecialchars_decode($resultado["file"]))){$ok=$ok+1;} 
}

if(eregi("exploit-db.com/", $save["url"])){ 
preg_match_all('#/exploits/(.*?)/#', $save["url"], $xpl_link);
$save["url"] = "https://www.exploit-db.com/download/".$xpl_link[1][0];
$resultado = browser($save);
$save["xpl"] = $resultado["file"];
$save["dbs"] = "exploit-db";
if(eregi("<div class=\"w-copyright\">© Copyright 2015 Exploit Database</div>", $save["xpl"])){$ok=$ok+1;} 
}

if($ok!=5){
$save["title"] = trim(str_replace("/", "-", $save["title"]));
if(isset($save["save-dir"])){
$svdr = $save["save-dir"]; 
$bmk = $save["save-dir"].$ds.$save["find"].$ds;
if(!is_dir($svdr)){ goto pula; }
mkdir($bmk);
mkdir($bmk.$save["dbs"].$ds);
}else{
pula:
$bmk = $save["find"].$ds;
mkdir($bmk);
mkdir($bmk.$save["dbs"].$ds);
}
file_put_contents($bmk.$save["dbs"].$ds.$save["title"].".txt", $save["xpl"]);
return "{$svd}".cores("green")."YES\n".cores("grey2")."|\n";
}else{
return "{$svd}".cores("red")."NOT\n".cores("grey2")."|\n";
}
}

function browser($browser){
$resultado=array();

$UA[1] = array("SeaMonkey", "Mobile", "Opera", "Safari", "GoogleBot", "K-Meleon", "SO"  => array("NetSecL Linux", "Dragora Linux", "ArchBSD", "Ubunto Linux", "Android", "Debian Linux"), "LNG" => array("en-US", "pt-BR", "cs-CZ", "pt_PT", "ru-RU", "en-IN") );
$UA[2] = array("Firefox", "Mobile", "Opera", "Safari", "GoogleBot", "Galaxy", "SO"  => array("5.1.2600", "6.0", "6.1.7601", "6.2", "6.3", "6.4"), "LNG" => array("en-US", "pt-BR", "cs-CZ", "pt_PT", "ru-RU", "en-IN") );
if(rand(1,2)==1){	
$UserAgent = "XPL SEARCH - ".$UA[1][rand(0,5)]."./".rand(0,5).".".rand(0,5)." (".$UA[1]["SO"][rand(0,5)]."; ".$UA[1]["LNG"][rand(0,5)].";)";	
}else{
$UserAgent = "XPL SEARCH - Mozilla/5.0 (Windows NT ".$UA[2]["SO"][rand(0,5)]."; ".$UA[2]["LNG"][rand(0,5)].") (KHTML, like Gecko) ".$UA[2][rand(0,5)]."/".rand(5,15).".".rand(10,25);
}

if(extension_loaded("curl")){
$ch = curl_init(); 
curl_setopt($ch, CURLOPT_URL, $browser["url"]);
curl_setopt($ch, CURLOPT_FOLLOWLOCATION, TRUE);

if(!empty($browser["proxy"])){
 curl_setopt($ch, CURLOPT_PROXY, $browser["proxy"]);
 
if(!empty($browser["proxy-login"])){
 curl_setopt($ch, CURLOPT_PROXYUSERPWD, $browser["proxy-login"]);
}
}

curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, 0);
curl_setopt($ch, CURLOPT_SSL_VERIFYHOST, 0);

if(!empty($browser["time"])){
 curl_setopt( $ch, CURLOPT_CONNECTTIMEOUT, $browser["time"]);
 curl_setopt( $ch, CURLOPT_TIMEOUT, $browser["time"]);
}

curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

if(!empty($browser["post"])){ curl_setopt($ch, CURLOPT_POSTFIELDS, $browser["post"]); }

curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

$resultado["file"] = curl_exec($ch); 
$resultado["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);
}else{
$opts = array(
    'http' => array ('request_fulluri' => true, 'user_agent' => $UserAgent),
    'https' => array ('request_fulluri' => true, 'user_agent' => $UserAgent) ); 

if(empty($browser["post"])){ 
$opts['http']['method']  = "GET";
$opts['https']['method'] = "GET";
}else{ 
$opts['http']['content']  = $browser["post"];
$opts['http']['method']  = "POST";
$opts['https']['content'] = $browser["post"];
$opts['https']['method'] = "POST";
}

if($browser["time"]!=""){
$opts['http']['method']  = $browser["time"];
$opts['https']['content'] = $browser["time"];
}

if($browser["proxy"]!=""){
$opts['http']['proxy']  = $browser["proxy"];
$opts['https']['proxy'] = $browser["proxy"];

if(!empty($browser["proxy-login"])){
$opts['http']['header']  = "Proxy-Authorization: Basic ".base64_encode($browser["proxy-login"]);
$opts['https']['header'] = "Proxy-Authorization: Basic ".base64_encode($browser["proxy-login"]);
}
}

$scc = stream_context_create($opts); 
$resultado["file"] = file_get_contents($browser["url"],false,$scc);
foreach( $http_response_header as $k=>$v ){
 $t = explode(':', $v, 2);
 if(!isset($t[1])){
   if(preg_match( "#HTTP/[0-9\.]+\s+([0-9]+)#",$v, $out )){
    $resultado['http_code'] = intval($out[1]);
   }
 }
}

}

return $resultado; 
}

####################################################################################################
## DATABASES
function milw00rm($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."MILW00RM.org ".cores("grey2")."]:: ";	
$resultado=NULL;
$save=array();
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://milw00rm.org/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
$resultado = browser($browser);

file_put_contents("file.txt", $resultado["file"]);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("red")."Error with the connection...\n\n".cores("grey2"); goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("red")."NOT FOUND\n".cores("grey2");
}else{
echo cores("green")."FOUND".cores("grey2")."\n.-----------------------------------------------------------------------------.\n|\n".cores("grey")."";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("grey")."LINK:: ".cores("blue")."http://milw00rm.org/", $v);
$var = str_replace('" target="_blank" class="style1">', "\n".cores("grey")."NAME:: ".cores("blue")."", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("grey2")."| ".htmlspecialchars_decode($fim[1])."\n".cores("grey2")."| ".$fim[0]."\n".cores("grey2");
$save["title"] = htmlspecialchars_decode($fim[1]); $save["link"] = $fim[0]; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
}
echo cores("grey2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function packetstormsecurity($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."PACKETSTORMSECURITY.com ".cores("grey2")."]:: ";
$resultado=NULL;
$id_pages=2;
$id_info=0;

$browser = array("url" => "https://packetstormsecurity.com/search/?q={$OPT["find"]}", "proxy" => $OPT["proxy"], "time" => $OPT["time"], "post" => "");
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("red")."Error with the connection...\n\n".cores("grey2"); goto saida; }

if(eregi('<title>No Results Found', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("red")."NOT FOUND\n".cores("grey2");
}else{
echo cores("green")."FOUND\n".cores("grey2").".-----------------------------------------------------------------------------.\n|\n";
while($id_pages < 100){	
preg_match_all('#<a class="ico text-plain" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $resultado["file"], $a);

while($id_info < count($a[0])){ 
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode($a[3][$id_info])."\n";
preg_match_all('#/files/(.*?)/#', $a[1][$id_info], $ab);
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://packetstormsecurity.com/files/{$ab[1][0]}/\n".cores("grey2");
$save["title"] = htmlspecialchars_decode($a[3][$id_info]); $save["url"] = "https://packetstormsecurity.com/files/{$ab[1][0]}/"; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
$id_info++;
}

if(eregi('accesskey="]">Next</a>', $resultado["file"])){
$browser["url"]="https://packetstormsecurity.com/search/files/page{$id_pages}/?q={$OPT["find"]}";
$resultado = browser($browser);
}else{ goto fim_; }

$id_pages++;
}

fim_:
echo cores("grey2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function iedb($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."IEDB.ir ".cores("grey2")."]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://iedb.ir/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("red")."Error with the connection...\n\n".cores("grey2"); goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("red")."NOT FOUND\n".cores("grey2");
}else{
echo cores("green")."FOUND\n".cores("grey2").".-----------------------------------------------------------------------------.\n|\n";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("grey")."LINK:: ".cores("blue")."http://iedb.ir/", $v);
$var = str_replace('" target="_blank" class="style1">', "\nNAME:: ".cores("blue")."", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("grey2")."| ".cores("grey").htmlspecialchars_decode($fim[1])."\n".cores("grey2")."| ".$fim[0]."\n".cores("grey2");
$save["title"] = htmlspecialchars_decode($fim[1]); $save["link"] = $fim[0]; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
}
echo cores("grey2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function intelligentexploit($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."INTELLIGENTEXPLOIT.com ".cores("grey2")."]:: ";
$resultado=NULL;
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".$OPT["find"], "proxy" => $OPT["proxy"], "post" => "", "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("red")."Error with the connection...\n\n".cores("grey2"); goto saida; }

if(empty($resultado["file"])){
echo cores("red")."NOT FOUND\n".cores("grey2");
}else{
echo cores("green")."FOUND\n".cores("grey2").".-----------------------------------------------------------------------------.\n|\n";
preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado["file"], $a);

$i=0;
while($i < count($a[0])){
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i]))."\n";
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}\n".cores("grey2");

$save["title"] = htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i])); 
$save["url"] = "https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}"; 
$save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }

$i++;
}
echo cores("grey")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function exploitdb($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."EXPLOIT-DB.com ".cores("grey2")."]:: ";	
$resultado=NULL;
$id_pages=2;

$browser = array("url" => "https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("red")."Error with the connection...\n\n".cores("grey2"); goto saida; }

if(eregi('No results', $resultado["file"]) or empty($resultado["file"])){
echo cores("red")."NOT FOUND\n".cores("grey2");
}else{
echo cores("green")."FOUND\n".cores("grey2")."+-----------------------------------------------------------------------------.\n|\n";

while($id_pages < 100){ $id_info=0;
preg_match_all('#<a href="https://www.exploit-db.com/exploits/(.*?)/">(.*?)</a>#', $resultado["file"], $a, PREG_SET_ORDER);

while($id_info < count($a)){ 
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode($a[$id_info][2])."\n";
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://www.exploit-db.com/exploits/{$a[$id_info][1]}/".cores("grey2")."\n".cores("grey2");
$save["title"] = htmlspecialchars_decode($a[$id_info][2]); $save["url"] = "https://www.exploit-db.com/exploits/{$a[$id_info][1]}/"; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
$id_info++;
}

if(eregi('>next</a>', $resultado["file"])){
$browser["url"]="https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&pg={$id_pages}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=";
$resultado = browser($browser);
}else{ goto fim_; }
$id_pages++;
}

fim_:
echo cores("grey2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

####################################################################################################
## CONFIGS
$OPT = array();
$OPT["db"] = 0;
if(!isset($oo["banner-no"]))echo banner();
if(isset($oo["h"]) or isset($oo["help"]))echo help();
if(isset($oo["a"]) or isset($oo["about"]))echo about();
if(isset($oo["s"])){$OPT["find"]=$oo["s"];}else{$O=1;}
if(isset($oo["search"])){$OPT["find"]=$oo["search"];}else{$O=$O+1;}
if(isset($oo["p"])){$OPT["proxy"]=$oo["p"];}
if(isset($oo["proxy"])){$OPT["proxy"]=$oo["proxy"];}
if(isset($oo["respond-time"])){$OPT["time"]=$oo["respond-time"];}
if(isset($OPT["proxy-login"])){$OPT["proxy-login"]=$oo["proxy-login"];}
if(isset($oo["update"])){echo update($OPT);}
if(isset($oo["save"])){$OPT["save"] = 1;}
if(isset($oo["save-dir"])){$OPT["save-dir"] = $oo["save-dir"];}
if(isset($oo["set-db"])){$OPT["db"] = $oo["set-db"];}
if(isset($oo["d"])){$OPT["db"] = $oo["d"];}
if($O==2)die();

####################################################################################################
## VERIFY SET-DB
$OPT = ccdbs($OPT);

####################################################################################################
## INFOS
echo infos($OPT);

####################################################################################################
## STARTING THE SEARCH 
if(eregi(1, $OPT["db"]) or $OPT["db"] == 0){ echo exploitdb($OPT);           }
if(eregi(2, $OPT["db"]) or $OPT["db"] == 0){ echo milw00rm($OPT);            }
if(eregi(3, $OPT["db"]) or $OPT["db"] == 0){ echo packetstormsecurity($OPT); }
if(eregi(4, $OPT["db"]) or $OPT["db"] == 0){ echo intelligentexploit($OPT);  }
if(eregi(5, $OPT["db"]) or $OPT["db"] == 0){ echo iedb($OPT);                }

#END
