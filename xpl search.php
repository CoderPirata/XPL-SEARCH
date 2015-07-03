<?php
/*

> https://github.com/CoderPirata/XPL-SEARCH/

-------------------------------------------------------------------------------
[ XPL SEARCH 0.1 ]-------------------------------------------------------------
-This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 4 database:
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

-------------------------------------------------------------------------------
[ ABOUT DEVELOPER ]------------------------------------------------------------
NAME              CoderPirata
EMAIL             coderpirata@gmail.com
Blog              http://coderpirata.blogspot.com.br/
Twitter           https://twitter.com/coderpirata
Google+           https://plus.google.com/103146866540699363823
Pastebin          http://pastebin.com/u/CoderPirata
Github            https://github.com/coderpirata/

-------------------------------------------------------------------------------
[ VERSION/LOG ]----------------------------------------------------------------

0.1 - Started

*/

ini_set('error_log',NULL);
ini_set('log_errors',FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen',TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);

$oo = getopt('h::s:p:a::', ['help::', 'search:', 'proxy:', 'proxy-login', 'about::', 'respond-time:', 'banner-no']);

function banner(){
if(!extension_loaded("curl")){$cr = "LIB cURL disabled, some functions may not work correctly!\n";}	
return "
Yb  dP 88\"\"Yb 88         .dP\"Y8 888888    db    88\"\"Yb  dP\"\"b8 88  88
 YbdP  88__dP 88         `Ybo.\" 88__     dPYb   88__dP dP   `\" 88  88
 dPYb  88\"\"\"  88  .o     o.`Y8b 88\"\"    dP__Yb  88\"Yb  Yb      888888
dP  Yb 88     88ood8     8bodP' 888888 dP\"\"\"\"Yb 88  Yb  YboodP 88  88 0.1
-------------------------------------------------------------------------------
{$cr}
HELP: {$_SERVER["SCRIPT_NAME"]} --help
USAGE: {$_SERVER["SCRIPT_NAME"]} --search \"name to search\"
-------------------------------------------------------------------------------\n";
}

function help(){
$n = $_SERVER["SCRIPT_NAME"];
die("
\t 88  88 888888 88     88\"\"Yb oP\"Yb. 
\t 88  88 88__   88     88__dP \"'.dP' 
\t 888888 88\"\"   88  .o 88\"\"\"    8P   
\t 88  88 888888 88ood8 88      (8)   

-------------------------------------------------------------------------------
[ MAIN COMMANDS ]--------------------------------------------------------------

\nCOMMAND: --search ~ Simple search
         Example: {$n} --search \"name to search\"
           Other: {$n} -s \"name to serch\"

\nCOMMAND: --help ~ For view HELP
         Example: {$n} --help
           Other: {$n} -h

\nCOMMAND: --about ~ For view ABOUT
         Example: {$n} --about
           Other: {$n} -a
				  
-------------------------------------------------------------------------------
[ OTHERS COMMANDS ]------------------------------------------------------------

\nCOMMAND: --proxy ~ Set which proxy to use to perform searches in the dbs.
         Example: {$n} --proxy 127.0.0.1:80
                  {$n} --proxy 127.0.0.1
           Other: {$n} --p 
				   
				  
\nCOMMAND: --proxy-login ~ Set username and password to login on the proxy, if necessary.
         Example: {$n} --proxy-login user:pass
		
\nCOMMAND: --respond-time ~ Command to set the maximum time(in seconds) that the databases have for respond.
         Example: {$n} --respond-time 30

		
\nCOMMAND: --banner-no ~ Command for does not display the banner.
         Example: {$n} --banner-no


-------------------------------------------------------------------------------\n");
}

function about(){
die("
\t    db    88\"\"Yb  dP\"Yb  88   88 888888 
\t   dPYb   88__dP dP   Yb 88   88   88   
\t  dP__Yb  88\"\"Yb Yb   dP Y8   8P   88   
\t dP\"\"\"\"Yb 88oodP  YbodP  `YbodP'   88   

-------------------------------------------------------------------------------
[ XPL SEARCH 0.1 ]-------------------------------------------------------------
-This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits in 4 database:
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

-------------------------------------------------------------------------------
[ ABOUT DEVELOPER ]------------------------------------------------------------
NAME              CoderPirata
Blog              http://coderpirata.blogspot.com.br/
Twitter           https://twitter.com/coderpirata
Google+           https://plus.google.com/103146866540699363823
Pastebin          http://pastebin.com/u/CoderPirata
Github            https://github.com/coderpirata/
-------------------------------------------------------------------------------\n");
}

function browser($browser){
$resultado=array();

$agb = array("Firefox", "Mobile", "Opera", "Tor Browser", "GoogleBot", "Internet Explorer");
$ags = array("Redhat Linux", "Ubuntu", "FreeBSD Linux", "CentOS Linux", "Android", "Debian Linux");
$adl = array("en-US", "pt-BR", "cs_CZ", "pt_PT", "ru_RU", "en_IN");
$UserAgent = "XPL SEARCH - ".$agb[rand(0,5)]."/".rand(0,5).".".rand(0,5)." (".$ags[rand(0,5)]."; ".$adl[rand(0,5)].";)";

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
echo "\n[ MILW00RM.org ]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://milw00rm.org/search.php",
                 "proxy" => $OPT["proxy"],
                 "post" => $info, 
				 "time" => $OPT["time"]);
$resultado = browser($browser);
if(empty($resultado)){ echo "Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo "Error with the connection...\n\n"; goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado)){ 
echo "NOT FOUND\n";
}else{
echo "FOUND\n.------------------------------------------------------------------------------\n|\n";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado, $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', "LINK:: http://milw00rm.org/", $v);
$var = str_replace('" target="_blank" class="style1">', "\nNAME:: ", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo "| ".$fim[1]."\n| ".$fim[0]."\n|\n";
}
echo "'------------------------------------------------------------------------------\n";
}
saida:
}

function packetstormsecurity($OPT){
echo "\n[ PACKETSTORMSECURITY.com ]:: ";	
$resultado=NULL;
$id_pages=2;
$id_info=0;

$browser = array("url" => "https://packetstormsecurity.com/search/?q={$OPT["find"]}", "proxy" => $OPT["proxy"], "time" => $OPT["time"], "post" => "");
$resultado = browser($browser);
if(empty($resultado)){ echo "Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo "Error with the connection...\n\n"; goto saida; }

if(eregi('<title>No Results Found', $resultado)){ 
echo "NOT FOUND\n";
}else{
echo "FOUND\n.------------------------------------------------------------------------------\n|\n";
while($id_pages < 100){	
preg_match_all('#<a class="ico text-plain" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $resultado, $a);

while($id_info < count($a[0])){ 
echo "| NAME:: ".$a[3][$id_info]."\n";
preg_match_all('#/files/(.*?)/#', $a[1][$id_info], $ab);
echo "| LINK:: https://packetstormsecurity.com/files/{$ab[1][0]}/\n";
echo "|\n";
$id_info++;
}

if(eregi('accesskey="]">Next</a>', $resultado)){
$browser["url"]="https://packetstormsecurity.com/search/files/page{$id_pages}/?q={$OPT["find"]}";
$resultado = browser($browser);
}else{ goto fim_; }

$id_pages++;
}

fim_:
echo "'------------------------------------------------------------------------------\n";
}
saida:
}

function iedb($OPT){
echo "\n[ IEDB.ir ]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://iedb.ir/search.php",
                 "proxy" => $OPT["proxy"],
                 "post" => $info, 
				 "time" => $OPT["time"]);
$resultado = browser($browser);
if(empty($resultado)){ echo "Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo "Error with the connection...\n\n"; goto saida; }

if(!eregi('<td class="style1">-::DATE</td>', $resultado)){ 
echo "NOT FOUND\n";
}else{
echo "FOUND\n.------------------------------------------------------------------------------\n|\n";
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado, $a);
foreach($a[0] as $v){ 
$var = str_replace('<a href="', "LINK:: http://iedb.ir/", $v);
$var = str_replace('" target="_blank" class="style1">', "\nNAME:: ", $var);
$var = str_replace("</a>", "", $var);
$fim = explode("\n", $var);
echo "| ".$fim[1]."\n| ".$fim[0]."\n|\n";
}
echo "'------------------------------------------------------------------------------\n";
}
saida:
}

function intelligentexploit($OPT){
echo "\n[ INTELLIGENTEXPLOIT.com ]:: ";	
$resultado=NULL;
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".$OPT["find"],
                 "proxy" => $OPT["proxy"],
                 "post" => "", 
				 "time" => $OPT["time"]);
$resultado = browser($browser);
preg_match_all('#(H77PR365UL7::!!::!!:(.*?):)#', browser($browser), $http_code, PREG_SET_ORDER);
if($http_code[0][2] < 207 and $http_code[0][2]!=0){ goto pula_catraca; }
if($http_code[0][2] == 0){
if(empty($resultado)){ echo "Retrying... "; $resultado = browser($browser); }
if(empty($resultado)){ echo "Error with the connection...\n\n"; goto saida; }
}
pula_catraca:

if(strlen($resultado)==27 or strlen($resultado)==26 or strlen($resultado)==25){
echo "NOT FOUND\n";
}else{
echo "FOUND\n.------------------------------------------------------------------------------\n|\n";
preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado, $a);

$i=0;
while($i < count($a[0])){ 
$a[3][$i] = str_replace("\/", "/", $a[3][$i]);
$a[3][$i] = str_replace("&amp;", "&", $a[3][$i]);
echo "| NAME:: ".$a[3][$i]."\n";
echo "| LINK:: https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}\n|\n";
$i++;
}
echo "'------------------------------------------------------------------------------\n";
}
saida:
}

####################################################################################################
## CONFIGS
if(!isset($oo["banner-no"]))echo banner();
if(isset($oo["h"]) or isset($oo["help"]))echo help();
if(isset($oo["a"]) or isset($oo["about"]))echo about();
if(isset($oo["s"])){$name=$oo["s"];}else{$O=1;}
if(isset($oo["search"])){$name=$oo["search"];}else{$O=$O+1;}
if($O==2)die();
if(isset($oo["p"])){$proxy=$oo["p"];}
if(isset($oo["proxy"])){$proxy=$oo["proxy"];}
if(isset($oo["respond-time"])){$time=$oo["respond-time"];}
$OPT = array();
$OPT["find"] = $name;
$OPT["proxy"] = $proxy;
$OPT["time"] = $time;
$OPT["proxy-login"] = $browser["proxy-login"];

####################################################################################################
## INFOS
echo ".-[ Infos ]--------------------------------------~
| SEARCH FOR  {$OPT["find"]}";
if(!empty($proxy))echo "\n| PROXY - ".$proxy;
echo "\n| TIME LIMIT FOR DBS RESPOND ";
if(!empty($OPT["time"])){ echo $OPT["time"]." sec"; }else{ echo "INDEFINITE"; }
echo "\n'------------------------------------------------~\n\n";

####################################################################################################
## STARTING THE SEARCH 
echo milw00rm($OPT);
echo iedb($OPT);
echo packetstormsecurity($OPT);
echo intelligentexploit($OPT);
