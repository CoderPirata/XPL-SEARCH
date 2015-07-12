<?php
/*

Official repository - https://github.com/CoderPirata/XPL-SEARCH/

-------------------------------------------------------------------------------
[ XPL SEARCH 0.2 ]-------------------------------------------------------------
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

*/

ini_set('error_log',NULL);
ini_set('log_errors',FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen',TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);

$oo = getopt('h::s:p:a::', ['update::', 'help::', 'search:', 'proxy:', 'proxy-login', 'about::', 'respond-time:', 'banner-no::']);

function banner(){
if(!extension_loaded("curl")){$cr = cores("red")."LIB cURL disabled, some functions may not work correctly!\n".cores("end");}	
return cores("grey")."
Yb  dP 88\"\"Yb 88         .dP\"Y8 888888    db    88\"\"Yb  dP\"\"b8 88  88
 YbdP  88__dP 88         `Ybo.\" 88__     dPYb   88__dP dP   `\" 88  88
 dPYb  88\"\"\"  88  .o     o.`Y8b 88\"\"    dP__Yb  88\"Yb  Yb      888888
dP  Yb 88     88ood8     8bodP' 888888 dP\"\"\"\"Yb 88  Yb  YboodP 88  88".cores("red")." 0.2
".cores("grey2")."-------------------------------------------------------------------------------".cores("grey")."
{$cr}
HELP: {$_SERVER["SCRIPT_NAME"]} ".cores("blue")."--help".cores("grey")."
USAGE: {$_SERVER["SCRIPT_NAME"]} ".cores("blue")."--search ".cores("grey")."\"name to search\"
".cores("grey2")."-------------------------------------------------------------------------------\n";
}

function help(){
$n = $_SERVER["SCRIPT_NAME"];
die(cores("grey")."
\t 88  88 888888 88     88\"\"Yb oP\"Yb. 
\t 88  88 88__   88     88__dP \"'.dP' 
\t 888888 88\"\"   88  .o 88\"\"\"    8P   
\t 88  88 888888 88ood8 88      (8)   

".cores("grey2")."-------------------------------------------------------------------------------
[ ".cores("grey")."MAIN COMMANDS".cores("grey2")." ]--------------------------------------------------------------".cores("grey")."

COMMAND: ".cores("blue")."--search".cores("grey")." ~ Simple search
         Example: {$n} ".cores("blue")."--search ".cores("grey")."\"name to search\"
           Other: {$n} ".cores("blue")."-s ".cores("grey")."\"name to serch\"

COMMAND: ".cores("blue")."--help".cores("grey")." ~ For view HELP
         Example: {$n} ".cores("blue")."--help".cores("grey")."
           Other: {$n} ".cores("blue")."-h".cores("grey")."

COMMAND: ".cores("blue")."--about".cores("grey")." ~ For view ABOUT
         Example: {$n} ".cores("blue")."--about".cores("grey")."
           Other: {$n} ".cores("blue")."-a".cores("grey")."

COMMAND: ".cores("blue")."--update".cores("grey")." ~ Command for update the script.
         Example: {$n} ".cores("blue")."--update".cores("grey2")."

-------------------------------------------------------------------------------
[ ".cores("grey")."OTHERS COMMANDS".cores("grey2")." ]------------------------------------------------------------".cores("grey")."

COMMAND: ".cores("blue")."--proxy".cores("grey")." ~ Set which proxy to use to perform searches in the dbs.
         Example: {$n} ".cores("blue")."--proxy".cores("grey")." 127.0.0.1:80
                  {$n} ".cores("blue")."--proxy".cores("grey")." 127.0.0.1
           Other: {$n} ".cores("blue")."--p".cores("grey")."

COMMAND: ".cores("blue")."--proxy-login".cores("grey")." ~ Set username and password to login on the proxy, if necessary.
         Example: {$n} ".cores("blue")."--proxy-login".cores("grey")." user:pass

COMMAND: ".cores("blue")."--respond-time".cores("grey")." ~ Command to set the maximum time(in seconds) that the databases have for respond.
         Example: {$n} ".cores("blue")."--respond-time".cores("grey")." 30

COMMAND: ".cores("blue")."--banner-no".cores("grey")." ~ Command for does not display the banner.
         Example: {$n} ".cores("blue")."--banner-no".cores("grey2")."

-------------------------------------------------------------------------------\n");
}

function about(){
die(cores("grey")."
\t    db    88\"\"Yb  dP\"Yb  88   88 888888 
\t   dPYb   88__dP dP   Yb 88   88   88   
\t  dP__Yb  88\"\"Yb Yb   dP Y8   8P   88   
\t dP\"\"\"\"Yb 88oodP  YbodP  `YbodP'   88   

".cores("grey2")."-------------------------------------------------------------------------------
[ ".cores("grey")."XPL SEARCH 0.2".cores("grey2")." ]-------------------------------------------------------------".cores("grey")."
".cores("blue")."--".cores("grey")." This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 5 database:
".cores("blue")."*".cores("grey")." Exploit-DB
".cores("blue")."*".cores("grey")." MIlw0rm
".cores("blue")."*".cores("grey")." PacketStormSecurity
".cores("blue")."*".cores("grey")." IEDB
".cores("blue")."*".cores("grey")." IntelligentExploit

".cores("grey2")."-------------------------------------------------------------------------------
[ ".cores("grey")."TO RUN THE SCRIPT".cores("grey2")." ]----------------------------------------------------------".cores("grey")."
PHP Version       ".cores("blue")."5.6.8".cores("grey")."
 php5-cli         ".cores("blue")."Lib".cores("grey")."
cURL support      ".cores("blue")."Enabled".cores("grey")."
 php5-curl        ".cores("blue")."Lib".cores("grey")."
cURL Version      ".cores("blue")."7.40.0".cores("grey")."
allow_url_fopen   ".cores("blue")."On".cores("grey")."
Permission        ".cores("blue")."Writing".cores("grey2")."

-------------------------------------------------------------------------------
[ ".cores("grey")."ABOUT DEVELOPER".cores("grey2")." ]------------------------------------------------------------".cores("grey")."
NAME              ".cores("blue")."CoderPirata".cores("grey")."
Email             ".cores("blue")."coderpirata@gmail.com".cores("grey")."
Blog              ".cores("blue")."http://coderpirata.blogspot.com.br/".cores("grey")."
Twitter           ".cores("blue")."https://twitter.com/coderpirata".cores("grey")."
Google+           ".cores("blue")."https://plus.google.com/103146866540699363823".cores("grey")."
Pastebin          ".cores("blue")."http://pastebin.com/u/CoderPirata".cores("grey")."
Github            ".cores("blue")."https://github.com/coderpirata/".cores("grey2")."
-------------------------------------------------------------------------------\n");
}

function cores($nome){
$cores = array("red"     => "\033[1;31m", "green"   => "\033[0;32m", "blue"    => "\033[1;34m",
               "end"     => "\033[0m",    "grey2"   => "\033[1;30m", "grey"    => "\033[0;37m");
if(substr(strtolower(PHP_OS), 0, 3) != "win"){ return $cores[strtolower($nome)]; }
}

function infos($OPT){
if(!empty($OPT["proxy"]))$proxyR = "\n| ".cores("grey")."PROXY - ".cores("blue").$OPT["proxy"];
if(!empty($OPT["time"])){$timeL  = cores("blue").$OPT["time"].cores("grey")." sec"; }else{ $timeL = cores("blue")."INDEFINITE"; }	
return cores("grey2").".-[ ".cores("grey")."Infos".cores("grey2")." ]-------------------------------------------------------------------~
| ".cores("grey")."SEARCH FOR  ".cores("blue")."{$OPT["find"]}".cores("grey2")."{$proxyR}
| ".cores("grey")."TIME LIMIT FOR DBS RESPOND {$timeL}".cores("grey2")."
'-----------------------------------------------------------------------------~\n\n";
}

function update($OPT){
echo cores("grey")."\nUpdating, wait...\n";

$OPT["url"] = "https://raw.githubusercontent.com/CoderPirata/XPL-SEARCH/master/xpl%20search.php";
$update = browser($OPT);

preg_match_all('#(H77PR365UL7::!!::!!:(.*?):)#', $update, $rm);
$update = str_replace("(H77PR365UL7::!!::!!:".$rm[2][0].":) ", "", $update);
if(!eregi("#END", $update)){ die(cores("red")."\nIt seems that the code has not been fully updated.\n Canceled update, try again...\n".cores("end")); }

file_put_contents(__FILE__,  $update);
die(cores("green")."\nUpdate DONE!");
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

$resultado = curl_exec($ch); 
$status = curl_getinfo($ch);
curl_close($ch);
$resultado = "(H77PR365UL7::!!::!!:{$status["http_code"]}:) ".$resultado;
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
$resultado = "(H77PR365UL7::!!::!!:333:)".file_get_contents($browser["url"],false,$scc);

}

return $resultado; 
}

####################################################################################################
## DATABASES

function milw00rm($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."MILW00RM.org ".cores("grey2")."]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://milw00rm.org/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
$resultado = browser($browser);
if(empty($resultado)){ echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo cores("red")."Error with the connection...\n\n".cores("end"); goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado)){ 
echo cores("red")."NOT FOUND\n".cores("end");
}else{
echo cores("green")."FOUND".cores("grey2")."\n.------------------------------------------------------------------------------\n|\n".cores("grey")."";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado, $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("grey")."LINK:: ".cores("blue")."http://milw00rm.org/", $v);
$var = str_replace('" target="_blank" class="style1">', "\n".cores("grey")."NAME:: ".cores("blue")."", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("grey2")."| ".htmlspecialchars_decode($fim[1])."\n".cores("grey2")."| ".$fim[0]."\n".cores("grey2")."|\n";
}
echo cores("grey2")."'------------------------------------------------------------------------------\n";
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
if(empty($resultado)){ echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo cores("red")."Error with the connection...\n\n".cores("end"); goto saida; }

if(eregi('<title>No Results Found', $resultado)){ 
echo cores("red")."NOT FOUND\n".cores("end");
}else{
echo cores("green")."FOUND\n".cores("grey2").".------------------------------------------------------------------------------\n|\n";
while($id_pages < 100){	
preg_match_all('#<a class="ico text-plain" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $resultado, $a);

while($id_info < count($a[0])){ 
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode($a[3][$id_info])."\n";
preg_match_all('#/files/(.*?)/#', $a[1][$id_info], $ab);
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://packetstormsecurity.com/files/{$ab[1][0]}/\n";
echo cores("grey2")."|\n";
$id_info++;
}

if(eregi('accesskey="]">Next</a>', $resultado)){
$browser["url"]="https://packetstormsecurity.com/search/files/page{$id_pages}/?q={$OPT["find"]}";
$resultado = browser($browser);
}else{ goto fim_; }

$id_pages++;
}

fim_:
echo cores("grey2")."'------------------------------------------------------------------------------\n";
}
saida:
}

function iedb($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."IEDB.ir ".cores("grey2")."]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://iedb.ir/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
$resultado = browser($browser);
if(empty($resultado)){ echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo cores("red")."Error with the connection...\n\n".cores("end"); goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado)){ 
echo cores("red")."NOT FOUND\n".cores("end");
}else{
echo cores("green")."FOUND\n".cores("grey2").".------------------------------------------------------------------------------\n|\n";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado, $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', cores("grey")."LINK:: ".cores("blue")."http://iedb.ir/", $v);
$var = str_replace('" target="_blank" class="style1">', "\nNAME:: ".cores("blue")."", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo cores("grey2")."| ".cores("grey").htmlspecialchars_decode($fim[1])."\n".cores("grey2")."| ".$fim[0]."\n".cores("grey2")."|\n";
}
echo cores("grey2")."'------------------------------------------------------------------------------\n";
}
saida:
}

function intelligentexploit($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."INTELLIGENTEXPLOIT.com ".cores("grey2")."]:: ";
$resultado=NULL;
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".$OPT["find"], "proxy" => $OPT["proxy"], "post" => "", "time" => $OPT["time"]);
$resultado = browser($browser);
preg_match_all('#(H77PR365UL7::!!::!!:(.*?):)#', browser($browser), $http_code, PREG_SET_ORDER);
if($http_code[0][2] < 207 and $http_code[0][2]!=0){ goto pula_catraca; }
if($http_code[0][2] == 0){
if(empty($resultado)){ echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo cores("red")."Error with the connection...\n\n".cores("end"); goto saida; }
}
pula_catraca:

if(strlen($resultado)==27 or strlen($resultado)==26 or strlen($resultado)==25){
echo cores("red")."NOT FOUND\n".cores("end");
}else{
echo cores("green")."FOUND\n".cores("grey2").".------------------------------------------------------------------------------\n|\n";
preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado, $a);

$i=0;
while($i < count($a[0])){
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i]))."\n";
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}\n".cores("grey2")."|\n";
$i++;
}
echo cores("grey")."'------------------------------------------------------------------------------\n";
}
saida:
}

function exploitdb($OPT){
echo "\n".cores("grey2")."[ ".cores("grey")."EXPLOIT-DB.com ".cores("grey2")."]:: ";	
$resultado=NULL;
$id_pages=2;

$browser = array("url" => "https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
$resultado = browser($browser);
if(empty($resultado)){ echo cores("grey2")."Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo cores("red")."Error with the connection...\n\n".cores("end"); goto saida; }

if(eregi('No results', $resultado)){
echo cores("red")."NOT FOUND\n".cores("end");
}else{
echo cores("green")."FOUND\n".cores("grey2")."+------------------------------------------------------------------------------\n|\n";

while($id_pages < 100){ $id_info=0;
preg_match_all('#<a href="https://www.exploit-db.com/exploits/(.*?)/">(.*?)</a>#', $resultado, $a, PREG_SET_ORDER);

while($id_info < count($a)){ 
echo cores("grey2")."| ".cores("grey")."NAME:: ".cores("blue")."".htmlspecialchars_decode($a[$id_info][2])."\n";
echo cores("grey2")."| ".cores("grey")."LINK:: ".cores("blue")."https://www.exploit-db.com/exploits/{$a[$id_info][1]}/".cores("grey2")."\n".cores("grey2")."|\n";
$id_info++;
}

if(eregi('>next</a>', $resultado)){
$browser["url"]="https://www.exploit-db.com/search/?action=search&description={$OPT["find"]}&pg={$id_pages}&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=";
$resultado = browser($browser);
}else{ goto fim_; }
$id_pages++;
}

fim_:
echo cores("grey2")."'------------------------------------------------------------------------------\n";
}
saida:
}

####################################################################################################
## CONFIGS
$OPT = array();
if(!isset($oo["banner-no"]))echo banner();
if(isset($oo["h"]) or isset($oo["help"]))echo help();
if(isset($oo["a"]) or isset($oo["about"]))echo about();
if(isset($oo["s"])){$OPT["find"]=$oo["s"];}else{$O=1;}
if(isset($oo["search"])){$OPT["find"]=$oo["search"];}else{$O=$O+1;}
if(isset($oo["p"])){$OPT["proxy"]=$oo["p"];}
if(isset($oo["proxy"])){$OPT["proxy"]=$oo["proxy"];}
if(isset($oo["respond-time"])){$OPT["time"]=$oo["respond-time"];}
if(isset($OPT["proxy-login"])){$OPT["proxy-login"]=$oo["proxy-login"];}
if(isset($oo["update"])){ echo update($OPT); }
if($O==2)die();

####################################################################################################
## INFOS
echo infos($OPT);

####################################################################################################
## STARTING THE SEARCH 
echo exploitdb($OPT);
echo milw00rm($OPT);
echo packetstormsecurity($OPT);
echo intelligentexploit($OPT);
echo iedb($OPT);

#END
