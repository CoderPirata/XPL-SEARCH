<?php
/*

Official repository - https://github.com/CoderPirata/XPL-SEARCH/

-------------------------------------------------------------------------------
[ XPL SEARCH 0.5 ]-------------------------------------------------------------
-This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in five database:
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
Permission        Writing/Reading

-------------------------------------------------------------------------------
[ ABOUT DEVELOPER ]------------------------------------------------------------
NAME              CoderPirata
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
- Added Exploit-DB.
- Added Colors, only for linux!
- Added Update Function.
- "Generator" of User-Agent reworked.
- Small errors and adaptations.

0.3 - [22/07/2015]
- Bugs solved
- Added "save" Function
- Added "set-db" function

0.4 - [05/08/2015]
- Save function modified
- Added search with list

0.5 - [30/08/2015]
- Added search by Author


If you find any bug or want to make any suggestions, please contact me by email.
*/

ini_set('error_log',NULL);
ini_set('log_errors',FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen',TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);
$oo = getopt('h::s:p:a::d:', ['author:', 'set-db:', 'save::', 'update::', 'help::', 'search:', 
                            'proxy:', 'proxy-login', 'about::', 'respond-time:', 
							'banner-no::', 'save-dir:', 'search-list:', 'save-log']);

####################################################################################################
## GENERAL FUNCTIONS
function banner(){
if(!extension_loaded("curl")){$cr = cores("r")."LIB cURL disabled, some functions may not work correctly!\n".cores("g1");}	
return cores("g1")."
\t.   ..--. .        .-. .---.    .    .--.  .--..   .
\t \ / |   )|       (   )|       / \   |   ):    |   |
\t  /  |--' |        `-. |---   /___\  |--' |    |---|
\t / \ |    |       (   )|     /     \ |  \ :    |   |
\t'   ''    '---'    `-' '---''       `'   ` `--''   '".cores("r")." 0.5
".cores("g2")."------------------------------------------------------------------------------~".cores("g1")."
{$cr}
HELP: {$_SERVER["SCRIPT_NAME"]} ".cores("b")."--help".cores("g1")."
USAGE: {$_SERVER["SCRIPT_NAME"]} ".cores("b")."--search ".cores("g1")."\"name to search\"
".cores("g2")."------------------------------------------------------------------------------~\n";
}

function help(){
$script = $_SERVER["SCRIPT_NAME"];
die(cores("g1")."
\t\t.   ..---..    .--.    .-. 
\t\t|   ||    |    |   )  '   )
\t\t|---||--- |    |--'      / 
\t\t|   ||    |    |        '  
\t\t'   ''---''---''        o  

".cores("g2").".-----------------------------------------------------------------------------.
[ ".cores("g1")."MAIN COMMANDS".cores("g2")." ]-------------------------------------------------------------'".cores("g1")."

COMMAND: ".cores("b")."--search".cores("g1")." ~ Simple search
         Example: {$script} ".cores("b")."--search ".cores("g1")."\"name to search\"
              Or: {$script} ".cores("b")."-s ".cores("g1")."\"name to serch\"

COMMAND: ".cores("b")."--help".cores("g1")." ~ For view HELP
         Example: {$script} ".cores("b")."--help".cores("g1")."
              Or: {$script} ".cores("b")."-h".cores("g1")."

COMMAND: ".cores("b")."--about".cores("g1")." ~ For view ABOUT
         Example: {$script} ".cores("b")."--about".cores("g1")."
              Or: {$script} ".cores("b")."-a".cores("g1")."

COMMAND: ".cores("b")."--update".cores("g1")." ~ Command for update the script.
         Example: {$script} ".cores("b")."--update".cores("g2")."

.-----------------------------------------------------------------------------.
[ ".cores("g1")."OTHERS COMMANDS".cores("g2")." ]-----------------------------------------------------------'".cores("g1")."

COMMAND: ".cores("b")."--set-db".cores("g1")." ~ Select which databases will be used, using the \"system\" of ID's
           ".cores("b")."0".cores("g1")." - ALL (".cores("b")."DEFAULT".cores("g1").")
           ".cores("b")."1".cores("g1")." - Exploit-DB
           ".cores("b")."2".cores("g1")." - Milw00rm
           ".cores("b")."3".cores("g1")." - PacketStormSecurity
           ".cores("b")."4".cores("g1")." - IntelligentExploit
           ".cores("b")."5".cores("g1")." - IEDB
         Example: {$script} ".cores("b")."--set-db".cores("g1")." 1
                  {$script} ".cores("b")."--set-db".cores("g1")." 3,4,2
              Or: {$script} ".cores("b")."-d".cores("g1")." 4,1
			  
COMMAND: ".cores("b")."--author".cores("g1")." ~ Search for exploits writed by the \"Author\" seted.
         Example: {$script} ".cores("b")."--author".cores("g1")." CoderPirata
          ".cores("p")."* IntelligentExploit does not support this type of search.".cores("g1")."
				
COMMAND: ".cores("b")."--save".cores("g1")." ~ Save the exploits found by the tool.
         Example: {$script} ".cores("b")."--save".cores("g1")."
		 
COMMAND: ".cores("b")."--save-log".cores("g1")." ~ Saves log of search in the current dir.
         Example: {$script} ".cores("b")."--save-log".cores("g1")."
		 
COMMAND: ".cores("b")."--save-dir".cores("g1")." ~ Sets the directory for saving files.
         Example: {$script} ".cores("b")."--save-dir".cores("g1")." /media/flash_disc/folder/

COMMAND: ".cores("b")."--proxy".cores("g1")." ~ Set which proxy to use to perform searches in the dbs.
         Example: {$script} ".cores("b")."--proxy".cores("g1")." 127.0.0.1:80
                  {$script} ".cores("b")."--proxy".cores("g1")." 127.0.0.1
              Or: {$script} ".cores("b")."--p".cores("g1")."

COMMAND: ".cores("b")."--proxy-login".cores("g1")." ~ Set username and password to login on the proxy, if necessary.
         Example: {$script} ".cores("b")."--proxy-login".cores("g1")." user:pass

COMMAND: ".cores("b")."--respond-time".cores("g1")." ~ Command to set the maximum time(in seconds) that the databases have for respond.
         Example: {$script} ".cores("b")."--respond-time".cores("g1")." 30

COMMAND: ".cores("b")."--banner-no".cores("g1")." ~ Command for does not display the banner.
         Example: {$script} ".cores("b")."--banner-no".cores("g2")."

------------------------------------------------------------------------------~\n");
}

function about(){
die(cores("g1")."
\t\t    .    .              .  
\t\t   / \   |             _|_ 
\t\t  /___\  |.-.  .-. .  . |  
\t\t /     \ |   )(   )|  | |  
\t\t'       `'`-'  `-' `--`-`-' 

".cores("g2").".-----------------------------------------------------------------------------.
[ ".cores("g1")."XPL SEARCH 0.5".cores("g2")." ]------------------------------------------------------------'".cores("g1")."
".cores("b")."--".cores("g1")." This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 5 database:
".cores("b")."*".cores("g1")." Exploit-DB
".cores("b")."*".cores("g1")." MIlw0rm
".cores("b")."*".cores("g1")." PacketStormSecurity
".cores("b")."*".cores("g1")." IEDB
".cores("b")."*".cores("g1")." IntelligentExploit

".cores("g2").".-----------------------------------------------------------------------------.
[ ".cores("g1")."TO RUN THE SCRIPT".cores("g2")." ]---------------------------------------------------------'".cores("g1")."
PHP Version       ".cores("b")."5.6.8".cores("g1")."
 php5-cli         ".cores("b")."Lib".cores("g1")."
cURL support      ".cores("b")."Enabled".cores("g1")."
 php5-curl        ".cores("b")."Lib".cores("g1")."
cURL Version      ".cores("b")."7.40.0".cores("g1")."
allow_url_fopen   ".cores("b")."On".cores("g1")."
Permission        ".cores("b")."Writing".cores("g2")."/".cores("b")."Reading".cores("g2")."

.-----------------------------------------------------------------------------.
[ ".cores("g1")."ABOUT DEVELOPER".cores("g2")." ]-----------------------------------------------------------'".cores("g1")."
NAME              ".cores("b")."CoderPirata".cores("g1")."
Email             ".cores("b")."coderpirata@gmail.com".cores("g1")."
Blog              ".cores("b")."http://coderpirata.blogspot.com.br/".cores("g1")."
Twitter           ".cores("b")."https://twitter.com/coderpirata".cores("g1")."
Google+           ".cores("b")."https://plus.google.com/103146866540699363823".cores("g1")."
Pastebin          ".cores("b")."http://pastebin.com/u/CoderPirata".cores("g1")."
Github            ".cores("b")."https://github.com/coderpirata/".cores("g2")."
------------------------------------------------------------------------------~\n");
}

function cores($nome){
$cores = array("r"     => "\033[1;31m", "g"   => "\033[0;32m", "b"    => "\033[1;34m",
               "g2"   => "\033[1;30m", "g1"    => "\033[0;37m", "p" => "\033[0;35m");
if(substr(strtolower(PHP_OS), 0, 3) != "win"){ return $cores[strtolower($nome)]; }
}

function ccdbs($OPT){
$ids = array(0,1,2,3,4,5);
foreach($ids as $idz){
 foreach($OPT["db"] as $id){ if(!preg_match("/{$idz}/i", $id)){$o=$o+1;} }
}
if($o==6){$OPT["db"] = 0;}
return $OPT;
}

function infos($OPT){
if(!empty($OPT["proxy"])){$proxyR = "\n| ".cores("g1")."PROXY - ".cores("b").$OPT["proxy"];}
if(!empty($OPT["time"])){$timeL  = cores("b").$OPT["time"].cores("g1")." sec"; }else{ $timeL = cores("b")."INDEFINITE"; }
if(isset($OPT["sfile"])){ $OPT["find"]=cores("b").$OPT["sfile"]." is a list!".cores("g2");  }
if(isset($OPT["author"])){ $OPT["find"]="AUTHOR ".cores("b").$OPT["author"].cores("g2"); }

if($OPT["save"]==1){
$save_xpl = cores("b")."YES".cores("g2")."\n| ".cores("g1")."SAVE IN ".cores("b");
 if(isset($OPT["save-dir"]) and !empty($OPT["save-dir"])){
 $save_xpl .= cores("b")."\"{$OPT["save-dir"]}\"".cores("g2");
  if(!is_dir($OPT["save-dir"])){ 
   $save_xpl .= " [".cores("r")."ERROR WITH DIR ".cores("g2")."-".cores("b")." CURRENT DIR WILL BE USED!".cores("g2")."]";   
  }else{ $save_xpl .=" [".cores("g")."DIR OK".cores("g2")."]"; }
 }else{ $save_xpl .= cores("b")."CURRENT DIR".cores("g2"); }
}else{ $save_xpl = cores("b")."NOT".cores("g2"); }

# DBS TO USE
foreach($OPT["db"] as $id){
 if($id == 0){ $setdb = cores("g2")."[ ".cores("b")."ALL".cores("g2")." ] "; }
 if(preg_match("/1/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."EXPLOIT-DB".cores("g2")." ] "; }
 if(preg_match("/2/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."MILW0RM".cores("g2")." ] "; }
 if(preg_match("/3/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."PACKETSTORMSECURITY".cores("g2")." ] "; }
 if(preg_match("/4/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."INTELLIGENTEXPLOIT".cores("g2")." ] "; }
 if(preg_match("/5/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."IEDB".cores("g2")." ] "; }
}

$l=cores("g1")."|".cores("g2");
return cores("g2").".-[ ".cores("g1")."Infos".cores("g2")." ]-------------------------------------------------------------------.
| ".cores("g1")."SEARCH FOR ".cores("b")."{$OPT["find"]}".cores("g2")."{$proxyR}
| ".cores("g1")."TIME LIMIT FOR DBS RESPOND: {$timeL}".cores("g2")."
| ".cores("g1")."SAVE EXPLOIT's: {$save_xpl}
| ".cores("g1")."DATABASES TO SEARCH: {$setdb}
'-----------------------------------------------------------------------------'
| ".cores("p")."* Only text files are listed!".cores("g2")."                                               |
'[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]'\n\n";
}

function update($OPT){
echo cores("g1")."\nUpdating, wait...\n";

$OPT["url"] = "https://raw.githubusercontent.com/CoderPirata/XPL-SEARCH/master/xpl%20search.php";
$update = browser($OPT);

if(!preg_match("/#END/i", $update["file"])){ die(cores("r")."\nIt seems that the code has not been fully updated.\n Canceled update, try again...\n"); }

file_put_contents(__FILE__,  $update["file"]);
die(cores("g")."\nUpdate DONE!");
}

function save($save){
$n = PHP_EOL;
$ds = DIRECTORY_SEPARATOR;
$svd = "| ".cores("g1")."SAVED: ";

if(preg_match("/milw00rm.org/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
$save["dbs"] = "milw00rm";
if(!preg_match("/# milw00rm.org/i", $save["xpl"])){$ok=$ok+1;}
}

if(preg_match("/iedb.ir/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
$save["dbs"] = "iedb";
if(!preg_match("/# Iranian Exploit DataBase =/i", $save["xpl"])){$ok=$ok+1;} 
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

if(preg_match("/intelligentexploit.com/i", $save["url"])) {
$resultado = browser($save);
preg_match_all('/<\/HEAD><BODY>(.+)<\/BODY>/s', htmlspecialchars_decode($resultado["file"]), $xpl);
preg_match_all('/<script type="text\/javascript">(.+)<\/script>/s', $xpl[1][0], $xpl_l);
$save["xpl"] = trim(str_replace($xpl_l[0][0], "", $xpl[1][0]));
$save["xpl"] = trim(str_replace("&#039;", "'", $save["xpl"]));
$save["dbs"] = "intelligentexploit";
if(preg_match("/<\/HEAD><BODY>/i", htmlspecialchars_decode($resultado["file"]))){$ok=$ok+1;} 
}

if(preg_match("/exploit-db.com/i", $save["url"])){ 
preg_match_all('#/exploits/(.*?)/#', $save["url"], $xpl_link);
$save["url"] = "https://www.exploit-db.com/download/".$xpl_link[1][0];
$resultado = browser($save);
$save["xpl"] = $resultado["file"];
$save["dbs"] = "exploit-db";
if(preg_match("/<div class=\"w-copyright\">© Copyright 2015 Exploit Database<\/div>/i", $save["xpl"])){$ok=$ok+1;} 
}

if($ok!=5 and !empty($save["xpl"])){
$save["title"] = trim(str_replace("/", "-", $save["title"]));
if(isset($save["save-dir"])){
$svdr = $save["save-dir"]; 
$bmk = $save["save-dir"].$ds."logs".$ds;
if(!is_dir($svdr)){ goto pula; }
mkdir($bmk); mkdir($bmk.$save["find"].$ds); mkdir($bmk.$save["find"].$ds.$save["dbs"].$ds);
$bmk .= $save["find"].$ds.$save["dbs"].$ds;
}else{ pula:
$bmk = "logs".$ds;
mkdir($bmk); mkdir($bmk.$save["find"].$ds); mkdir($bmk.$save["find"].$ds.$save["dbs"].$ds);
$bmk .= $save["find"].$ds.$save["dbs"].$ds;
}

file_put_contents($bmk.$ds.$save["title"].".txt", $save["xpl"]);
return "{$svd}".cores("g")."YES\n".cores("g2")."|\n";
}else{ return "{$svd}".cores("r")."NOT\n".cores("g2")."|\n"; }

}

function save_log($OPT){
$ds = DIRECTORY_SEPARATOR;
$n = PHP_EOL;
mkdir("logs".$ds);
file_put_contents("logs".$ds."search_log.txt", "TITLE: ".$OPT["title"].$n."LINK: ".$OPT["url"].$n.$n, FILE_APPEND);
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
$opts['http']['method']  = "GET";
$opts['https']['content'] = $browser["post"];
$opts['https']['method'] = "GET";
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
echo "\n".cores("g2")."[ ".cores("g1")."MILW00RM.org ".cores("g2")."]:: ";	
$resultado=NULL;
$save=array();
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
if(isset($OPT["author"])){
$browser = array("url" => "http://milw00rm.org/author.php?name=".$OPT["author"], "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "http://milw00rm.org/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(!preg_match('/<td class="style1">-::DATE<\/td>/i', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND".cores("g2")."\n.-----------------------------------------------------------------------------.\n|\n".cores("g1")."";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("g1")."http://milw00rm.org/", $v);
$var = str_replace('" target="_blank" class="style1">', "\n", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("g2")."| ".cores("g1")."NAME:: ".cores("b").htmlspecialchars_decode($fim[1])."\n".cores("g2")."| ".cores("g1")."LINK:: ".cores("b").$fim[0]."\n".cores("g2");
$save["title"] = htmlspecialchars_decode($fim[1]); $save["url"] = $fim[0]; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
}
echo cores("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function packetstormsecurity($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."PACKETSTORMSECURITY.com ".cores("g2")."]:: ";
$resultado=NULL;
$id_pages=2;
$id_info=0;
if(isset($OPT["author"])){
$browser = array("url" => "https://packetstormsecurity.com/search/authors/?q={$OPT["author"]}", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://packetstormsecurity.com/search/?q={$OPT["find"]}", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(preg_match('/<title>No Results Found/i', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2").".-----------------------------------------------------------------------------.\n|\n";
while($id_pages < 100){	
preg_match_all('#<a class="ico text-plain" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $resultado["file"], $a);

while($id_info < count($a[0])){ 
echo cores("g2")."| ".cores("g1")."NAME:: ".cores("b")."".htmlspecialchars_decode($a[3][$id_info])."\n";
preg_match_all('#/files/(.*?)/#', $a[1][$id_info], $ab);
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."https://packetstormsecurity.com/files/{$ab[1][0]}/\n".cores("g2");
$save["title"] = htmlspecialchars_decode($a[3][$id_info]); $save["url"] = "https://packetstormsecurity.com/files/{$ab[1][0]}/"; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
$id_info++;
}

if(preg_match('/accesskey="]">Next<\/a>/i', $resultado["file"])){
$browser["url"]="https://packetstormsecurity.com/search/files/page{$id_pages}/?q={$OPT["find"]}";
$resultado = browser($browser);
}else{ goto fim_; }

$id_pages++;
}

fim_:
echo cores("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function iedb($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."IEDB.ir ".cores("g2")."]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
if(isset($OPT["author"])){
$browser = array("url" => "http://iedb.ir/author-{$OPT["author"]}.html", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "http://iedb.ir/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(!preg_match('/<td class="style1">-::DATE<\/td>/i', $resultado["file"]) or empty($resultado["file"])){ 
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2").".-----------------------------------------------------------------------------.\n|\n";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("g1")."http://iedb.ir/", $v);
$var = str_replace('" target="_blank" class="style1">', "\n", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("g2")."| ".cores("g1")."NAME:: ".cores("b").htmlspecialchars_decode($fim[1])."\n".cores("g2")."| ".cores("g1")."LINK:: ".cores("b").$fim[0]."\n".cores("g2");
$save["title"] = htmlspecialchars_decode($fim[1]); $save["url"] = $fim[0]; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
}
echo cores("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function intelligentexploit($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."INTELLIGENTEXPLOIT.com ".cores("g2")."]:: ";
$resultado=NULL;
if(isset($OPT["author"])){ echo cores("r")."This db does not support this type of search.\n"; goto saida; }
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".$OPT["find"], "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(empty($resultado["file"])){
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2").".-----------------------------------------------------------------------------.\n|\n";
preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado["file"], $a);

$i=0;
while($i < count($a[0])){
echo cores("g2")."| ".cores("g1")."NAME:: ".cores("b")."".htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i]))."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}\n".cores("g2");

$save["title"] = htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i])); 
$save["url"] = "https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}"; 
$save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}

$i++;
}
echo cores("g1")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function exploitdb($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."EXPLOIT-DB.com ".cores("g2")."]:: ";	
$resultado=NULL;
$id_pages=2;

if(isset($OPT["author"])){
$browser = array("url" => "https://www.exploit-db.com/search/?action=search&e_author=+".$OPT["author"], "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}

$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(preg_match('/No results/i', $resultado["file"]) or empty($resultado["file"])){
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2")."+-----------------------------------------------------------------------------.\n|\n";

while($id_pages < 100){ $id_info=0;
preg_match_all('#<a href="https://www.exploit-db.com/exploits/(.*?)/">(.*?)</a>#', $resultado["file"], $a, PREG_SET_ORDER);

while($id_info < count($a)){ 
echo cores("g2")."| ".cores("g1")."NAME:: ".cores("b")."".htmlspecialchars_decode($a[$id_info][2])."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."https://www.exploit-db.com/exploits/{$a[$id_info][1]}/".cores("g2")."\n".cores("g2");
$save["title"] = htmlspecialchars_decode($a[$id_info][2]); $save["url"] = "https://www.exploit-db.com/exploits/{$a[$id_info][1]}/"; $save = array_merge($OPT, $save);
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
$id_info++;
}

if(preg_match('/>next<\/a>/i', $resultado["file"])){
$browser["url"]="https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&pg={$id_pages}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=";
$resultado = browser($browser);
}else{ goto fim_; }
$id_pages++;
}

fim_:
echo cores("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

####################################################################################################
## CONFIGS
$OPT = array();
$OPT["db"] = array(0);
if(!isset($oo["banner-no"]))echo banner();
if(isset($oo["h"]) or isset($oo["help"]))echo help();
if(isset($oo["a"]) or isset($oo["about"]))echo about();
if(isset($oo["s"])){$OPT["find"]=$oo["s"];}else{$O=1;}
if(isset($oo["search"])){$OPT["find"]=$oo["search"];}else{$O=$O+1;}
if(isset($oo["p"])){$OPT["proxy"]=$oo["p"];}
if(isset($oo["proxy"])){$OPT["proxy"]=$oo["proxy"];}
if(isset($oo["respond-time"])){$OPT["time"]=$oo["respond-time"];}
if(isset($oo["proxy-login"])){$OPT["proxy-login"]=$oo["proxy-login"];}
if(isset($oo["update"])){echo update($OPT);}
if(isset($oo["save"])){$OPT["save"] = 1;}
if(isset($oo["save-dir"])){$OPT["save-dir"] = $oo["save-dir"];}
if(isset($oo["save-log"])){$OPT["save-log"] = 1;}
if(isset($oo["set-db"])){ $OPT["db"]=""; $OPT["db"] = explode(",", $oo["set-db"]);}
if(isset($oo["d"])){$OPT["db"] = $oo["d"];}
if(isset($oo["search-list"])){if(!file_exists($oo["search-list"])){ die(cores("r")."\nFILE \"{$oo["search-list"]}\" does not exist!\n"); }else{$OPT["sfile"]=$oo["search-list"];}}else{$O=$O+1;}
if(isset($oo["author"])){$OPT["author"]=$oo["author"];}else{$O=$O+1;}
if($O==4)die();

####################################################################################################
## VERIFY SET-DB
if(isset($OPT["db"])){ $OPT = ccdbs($OPT); }

####################################################################################################
## INFOS
echo infos($OPT);

####################################################################################################
## FILE SEARCH
if(file_exists($OPT["sfile"])){
$file = file_get_contents($OPT["sfile"]);
if(empty($file)){ die(cores("r")."File \"{$OPT["sfile"]}\" are empty!"); } 
$file = explode("\n", $file);
}else{ 
$file = array($OPT["find"]); 
if(isset($OPT["author"])){$file = array($OPT["author"]);}
}

####################################################################################################
## STARTING THE SEARCH
foreach($file as $f){
$OPT["find"] = trim($f);
if(file_exists($OPT["sfile"])){ echo cores("g2")."\n[ ".cores("g1")."FIND:: ".cores("b").$OPT["find"].cores("g2")." ]::|::|::|::|::|::|::|::|::|::|::-"; }
foreach($OPT["db"] as $id){
 if(preg_match("/1/i", $id) or $id == 0){ echo exploitdb($OPT);           }
 if(preg_match("/2/i", $id) or $id == 0){ echo milw00rm($OPT);            }
 if(preg_match("/3/i", $id) or $id == 0){ echo packetstormsecurity($OPT); }
 if(preg_match("/4/i", $id) or $id == 0){ echo intelligentexploit($OPT);  }
 if(preg_match("/5/i", $id) or $id == 0){ echo iedb($OPT);                }
}
}

#END
