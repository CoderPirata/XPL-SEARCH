<?php
/*

Official repository - https://github.com/CoderPirata/XPL-SEARCH/

-------------------------------------------------------------------------------
[ XPL SEARCH 0.7 ]-------------------------------------------------------------
-This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits/vulnerabilities in six database:
* Exploit-DB
* MIlw0rm
* PacketStormSecurity
* IEDB
* IntelligentExploit
* CVE

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
NAME              CoderPIRATA
Email             coderpirata@gmail.com
Blog              http://coderpirata.blogspot.com.br/
Twitter           https://twitter.com/CoderPIRATA
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
- Bugs solved.
- Added "save" Function.
- Added "set-db" function.

0.4 - [05/08/2015]
- Save function modified.
- Added search with list.

0.5 - [29/08/2015]
- Added search by Author.

0.6 - [09/09/2015]
- Changes in search logs.
- Now displays the author of the exploit.
 * Does not work with IntelligentExploit.

0.7 - [11/09/2015]
- Added search in CVE.
 * ID.
 * Simple search - id 6.
- Bug in exploit-db search, "papers" fixed.
- Added standard time of 60 seconds for each request.
- file_get_contents() was removed from "browser()".
- Code of milw00rm search has been modified.
- Changes in search logs.
- Added date.

0.7.1 - [17/09/2015]
- Bug in milw00rm solved.

If you find any bug or want to make any suggestions, please contact me by email.
*/

ini_set('error_log',NULL);
ini_set('log_errors',FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen',TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);
$oo = getopt('h::s:p:a::d:', ['cve-id:', 'author:', 'set-db:', 'save::', 
                              'update::', 'help::', 'search:', 'proxy:', 'proxy-login', 
							  'about::', 'respond-time:', 'banner-no::', 'save-dir:', 
							  'search-list:', 'save-log']);

####################################################################################################
## GENERAL FUNCTIONS
function banner(){
return cores("g1")."
\t.   ..--. .        .-. .---.    .    .--.  .--..   .
\t \ / |   )|       (   )|       / \   |   ):    |   |
\t  /  |--' |        `-. |---   /___\  |--' |    |---|
\t / \ |    |       (   )|     /     \ |  \ :    |   |
\t'   ''    '---'    `-' '---''       `'   ` `--''   '".cores("r")." 0.7
".cores("g2")."------------------------------------------------------------------------------~".cores("g1")."
HELP: {$_SERVER["SCRIPT_NAME"]} ".cores("b")."--help".cores("g1")."
USAGE: {$_SERVER["SCRIPT_NAME"]} ".cores("b")."--search ".cores("g1")."\"name to search\"
".cores("g2")."------------------------------------------------------------------------------~\n";

if(!extension_loaded("curl")){die(cores("r")."LIB cURL not found!\nPlease, install the cURL and run the script again.\n".cores("g1"));}
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
           ".cores("b")."5".cores("g1")." - CVE
         Example: {$script} ".cores("b")."--set-db".cores("g1")." 1
                  {$script} ".cores("b")."--set-db".cores("g1")." 3,6,2
              Or: {$script} ".cores("b")."-d".cores("g1")." 4,1
			  
COMMAND: ".cores("b")."--cve-id".cores("g1")." ~ Displays the description and link of CVE.
         Example: {$script} ".cores("b")."--cve-id".cores("g1")." 2015-0349
			  
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
[ ".cores("g1")."XPL SEARCH 0.7".cores("g2")." ]------------------------------------------------------------'".cores("g1")."
".cores("b")."--".cores("g1")." This tool aims to facilitate the search for exploits by hackers, currently is able to find exploits/vulnerabilities in six database:
".cores("b")."*".cores("g1")." Exploit-DB
".cores("b")."*".cores("g1")." MIlw0rm
".cores("b")."*".cores("g1")." PacketStormSecurity
".cores("b")."*".cores("g1")." IEDB
".cores("b")."*".cores("g1")." IntelligentExploit
".cores("b")."*".cores("g1")." CVE

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
$ids = array(0,1,2,3,4,5, 6);
foreach($ids as $idz){
 foreach($OPT["db"] as $id){ if(!preg_match("/{$idz}/i", $id)){$o=$o+1;} }
}
if($o==7){$OPT["db"] = 0;}
return $OPT;
}

function infos($OPT){

if(!empty($OPT["proxy"])){$proxyR = "\n| ".cores("g1")."PROXY - ".cores("b").$OPT["proxy"];}

if(!empty($OPT["time"])){$timeL  = cores("b").$OPT["time"].cores("g1")." sec"; }else{ $timeL = cores("b")."INDEFINITE"; }

if(isset($OPT["sfile"])){ $OPT["find"]=cores("b").$OPT["sfile"]." is a list!".cores("g2");  }
if(isset($OPT["author"])){ $OPT["find"]=cores("g1")."AUTHOR ".cores("b").$OPT["author"].cores("g2"); }
if(isset($OPT["cve-id"])){ $OPT["find"]=cores("g1")."CVE-".cores("b").$OPT["cve-id"].cores("g2"); }

if($OPT["save"]==1){
$save_xpl = cores("b")."YES".cores("g2")."\n| ".cores("g1")."SAVE IN ".cores("b");
 if(isset($OPT["save-dir"]) and !empty($OPT["save-dir"])){
 $save_xpl .= cores("b")."\"{$OPT["save-dir"]}\"".cores("g2");
  if(!is_dir($OPT["save-dir"])){ 
   $save_xpl .= " [".cores("r")."ERROR WITH DIR ".cores("g2")."-".cores("b")." CURRENT DIR WILL BE USED!".cores("g2")."]";   
  }else{ $save_xpl .=" [".cores("g")."DIR OK".cores("g2")."]"; }
 }else{ $save_xpl .= cores("b")."CURRENT DIR".cores("g2"); }
}else{ $save_xpl = cores("b")."NOT".cores("g2"); }

foreach($OPT["db"] as $id){
 if($id == 0){ $setdb = cores("g2")."[ ".cores("b")."ALL".cores("g2")." ] "; }
 if(preg_match("/1/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."EXPLOIT-DB".cores("g2")." ] "; }
 if(preg_match("/2/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."MILW00RM".cores("g2")." ] "; }
 if(preg_match("/3/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."PACKETSTORMSECURITY".cores("g2")." ] "; }
 if(preg_match("/4/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."INTELLIGENTEXPLOIT".cores("g2")." ] "; }
 if(preg_match("/5/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."IEDB".cores("g2")." ] "; }
 if(preg_match("/6/i", $id)){ $setdb .= cores("g2")."[ ".cores("b")."CVE".cores("g2")." ] "; }
}
if($OPT["db"]=="999"){ $setdb .= cores("g2")."[ ".cores("b")."CVE".cores("g2")." ] "; }

if(isset($OPT["save"])){ $info_save = "\n| ".cores("p")."* Only text files will be saved!".cores("g2")."                                            |"; }

$l=cores("g1")."|".cores("g2");
return cores("g2").".-[ ".cores("g1")."Infos".cores("g2")." ]-------------------------------------------------------------------.
| ".cores("g1")."SEARCH FOR ".cores("b")."{$OPT["find"]}".cores("g2")."{$proxyR}
| ".cores("g1")."TIME LIMIT FOR DBS RESPOND: {$timeL}".cores("g2")."
| ".cores("g1")."SAVE EXPLOIT's: {$save_xpl}
| ".cores("g1")."DATABASES TO SEARCH: {$setdb}
'-----------------------------------------------------------------------------'{$info_save}
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
if(!preg_match("/# milw00rm.org/i", $save["xpl"])){$ok=$ok+1;}
}

if(preg_match("/iedb.ir/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"]=$xpl[1];
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
if(empty($save["xpl"])){$ok=$ok+1;}
}

if(preg_match("/intelligentexploit.com/i", $save["url"])) {
$resultado = browser($save);
preg_match_all('/<\/HEAD><BODY>(.+)<\/BODY>/s', htmlspecialchars_decode($resultado["file"]), $xpl);
preg_match_all('/<script type="text\/javascript">(.+)<\/script>/s', $xpl[1][0], $xpl_l);
$save["xpl"] = trim(str_replace($xpl_l[0][0], "", $xpl[1][0]));
$save["xpl"] = trim(str_replace("&#039;", "'", $save["xpl"]));
if(preg_match("/<\/HEAD><BODY>/i", htmlspecialchars_decode($resultado["file"]))){$ok=$ok+1;} 
}

if(preg_match("/exploit-db.com/i", $save["url"])){ 
preg_match_all('#/exploits/(.*?)/#', $save["url"], $xpl_link);
if(empty($xpl_link[1][0])){
preg_match_all('#/papers/(.*?)/#', $save["url"], $xpl_link);
}
$save["url"] = "https://www.exploit-db.com/download/".$xpl_link[1][0];
$resultado = browser($save);
$save["xpl"] = $resultado["file"];
if(preg_match("/<div class=\"w-copyright\">© Copyright 2015 Exploit Database<\/div>/i", $save["xpl"])){$ok=$ok+1;} 
}

if(preg_match("/cve.mitre.org/i", $save["url"])) {
$save["xpl"] = $save["description"];
}else{ $ok=$ok+1; }

if($ok!=6 and !empty($save["xpl"])){
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
file_put_contents("logs".$ds."search_log.txt", "DATABASE: ".$OPT["dbs"].$n."AUTHOR: ".$OPT["author"].$n."DATE: ".$OPT["date"].$n."TITLE: ".$OPT["title"].$n."LINK: ".$OPT["url"].$n.$n, FILE_APPEND);
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
$browser = array("url" => "http://milw00rm.org/author.php?name=".urlencode($OPT["author"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
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
preg_match_all('#<td class="style1" nowrap="nowrap" width="62">(.*?)</td>#', $resultado["file"], $date);		
preg_match_all('#<td nowrap="nowrap" width="135"><a href="(.*?)">(.*?)</a></td>#', $resultado["file"], $author);
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $title_link);

$nn = count($date[0]); $nn--; $i=0;
while($i <= $nn){	
echo cores("g2")."| ".cores("g1")."AUTHOR:: ".$author[2][$i]."\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".$date[1][$i]."\n";
echo cores("g2")."| ".cores("g1")."TITLE:: ".cores("b").htmlspecialchars_decode($title_link[2][$i])."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."http://milw00rm.org/".$title_link[1][$i]."\n".cores("g2");

$save["author"] = $author[2][$i];
$save["title"] = htmlspecialchars_decode($title_link[2][$i]); 
$save["url"] = "http://milw00rm.org/".$title_link[1][$i];
$save["date"] = $date[1][$i];
$save["dbs"]="MILW00RM";
if($OPT["save"]==1){ echo save($save); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
$i++;
}
echo cores("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function packetstormsecurity($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."PACKETSTORMSECURITY.com ".cores("g2")."]:: ";
$resultado=NULL;
$id_pages=2;
if(isset($OPT["author"])){
$browser = array("url" => "https://packetstormsecurity.com/search/authors/?q=".urlencode($OPT["author"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://packetstormsecurity.com/search/?q=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
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
preg_match_all('/<dl id="(.*?)" class="(.*?)">(.*?)<\/dl>/s', $resultado["file"], $a);
foreach($a[3] as $source){
preg_match_all('#<dd class="datetime">Posted <a href="/files/date/(.*?)/" title=".*">.*</a></dd>#', $source, $date);
preg_match_all('#<a class="ico .*" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $source, $title);
preg_match_all('#<dd class="refer">Authored by <a href="(.*?)" class="(.*?)">(.*?)<\/a>#', $source, $author);

echo cores("g2")."| ".cores("g1")."AUTHOR:: ".$author[3][0]."\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".$date[1][0]."\n";
echo cores("g2")."| ".cores("g1")."TITLE:: ".cores("b").htmlspecialchars_decode($title[3][0])."\n";
preg_match_all('#/files/(.*?)/#', $title[1][0], $ab);
$link = "https://packetstormsecurity.com/files/{$ab[1][0]}/";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b").$link."\n".cores("g2");

$save["author"] = $author[3][0];
$save["date"] = $date[1][0];
$save["title"] = htmlspecialchars_decode($title[3][0]);
$save["url"] = $link; 
$save["dbs"]="PACKETSTORMSECURITY";
if($OPT["save"]==1){ echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
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
preg_match_all('/<tr class="submit">(.*?)<\/tr>/s', $resultado["file"], $data);

foreach($data[0] as $dat){
preg_match_all('#<td class="style1" nowrap="nowrap" width="62">(.*?)</td>#', $dat, $date);
preg_match_all('#<td nowrap="nowrap" width="135"><a href="(.*?)">(.*?)</a></td>#', $dat, $author);
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $dat, $link_title);

echo cores("g2")."| ".cores("g1")."AUTHOR:: ".$author[2][0]."\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".$date[1][0]."\n";
echo cores("g2")."| ".cores("g1")."TITLE:: ".cores("b").htmlspecialchars_decode($link_title[2][0])."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."http://iedb.ir/".$link_title[1][0]."\n".cores("g2");

$save["author"] = $author[2][0];
$save["date"] = $date[1][0];
$save["title"] = htmlspecialchars_decode($link_title[2][0]); 
$save["url"] = "http://iedb.ir/".$link_title[1][0]; 
$save["dbs"]="IEDB";
if($OPT["save"]==1){ echo save($save); }else{ echo "|\n"; }
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
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(empty($resultado["file"])){
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2").".-----------------------------------------------------------------------------.\n|\n";
preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado["file"], $data);

$i=0;
while($i < count($data[0])){
echo cores("g2")."| ".cores("g1")."AUTHOR:: ".cores("r")."Not available\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".cores("b").$data[2][$i]."\n";
echo cores("g2")."| ".cores("g1")."TITLE:: ".cores("b")."".htmlspecialchars_decode(str_replace("\/", "/", $data[3][$i]))."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."https://www.intelligentexploit.com/view-details.html?id={$data[1][$i]}\n".cores("g2");

$save["author"] = "Not available"; 
$save["date"] = $data[2][$i];
$save["title"] = htmlspecialchars_decode(str_replace("\/", "/", $data[3][$i])); 
$save["url"] = "https://www.intelligentexploit.com/view-details.html?id={$data[1][$i]}"; 
$save["dbs"]="INTELLIGENTEXPLOIT";
if($OPT["save"]==1){ echo save($save); }else{ echo "|\n"; }
if($OPT["save-log"]==1){ echo save_log($save);}

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
$browser = array("url" => "https://www.exploit-db.com/search/?action=search&e_author=+".urlencode($OPT["author"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://www.exploit-db.com/search/?action=search&description=".urlencode($OPT["find"])."&text=&cve=&e_author=&platform=0&type=0&lang_id=0&port=&osvdb=", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
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
preg_match_all('/<td class="date">(.*?)<\/tr>/s', $resultado['file'], $source);
preg_match_all('#<td class="date">(.*?)</td>#', $source[0][0], $date);

foreach($source[1] as $source){ 

$lnk = "exploits";
preg_match_all('#<a href="https://www.exploit-db.com/exploits/(.*?)/">(.*?)</a>#', $source, $id_title);
if(empty($id_title[2][0]) and empty($id_title[1][0])){ preg_match_all('#<a href="https://www.exploit-db.com/exploits/(.*?)/".*>(.*?)</a>#', $source, $id_title); }
if(empty($id_title[2][0]) and empty($id_title[1][0])){ preg_match_all('#<a href="https://www.exploit-db.com/papers/(.*?)/">(.*?)</a>#', $source, $id_title); $lnk = "papers"; }
preg_match_all('#<a href="https://www.exploit-db.com/author/(.*?)" title="(.*?)">#', $source, $author);


echo cores("g2")."| ".cores("g1")."AUTHOR:: ".$author[2][0]."\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".$date[1][0]."\n";
echo cores("g2")."| ".cores("g1")."TITLE:: ".cores("b")."".htmlspecialchars_decode($id_title[2][0])."\n";
echo cores("g2")."| ".cores("g1")."LINK:: ".cores("b")."https://www.exploit-db.com/{$lnk}/{$id_title[1][0]}/".cores("g2")."\n".cores("g2");

$save["author"] = $author[2][0];
$save["date"] = $date[1][0];
$save["title"] = htmlspecialchars_decode($id_title[2][0]);
$save["url"] = "https://www.exploit-db.com/exploits/{$id_title[1][0]}/"; 	
$save["dbs"]="EXPLOIT-DB";
if($OPT["save"]==1){
echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
$id_info= $id_info+1;
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

function CVE($OPT){
echo "\n".cores("g2")."[ ".cores("g1")."CVE.mitre.org ".cores("g2")."]:: ";	
$resultado=NULL;

if(isset($OPT["find"])){
$browser = array("url" => "http://cve.mitre.org/cgi-bin/cvekey.cgi?keyword=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "http://cve.mitre.org/cgi-bin/cvename.cgi?name=".$OPT["cve-id"], "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ 
echo cores("g2")."Retrying... "; $resultado = browser($browser); } 
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ 
echo cores("r")."Error with the connection...\n\n".cores("g2"); goto saida; }

if(preg_match('/There are <b>0<\/b> CVE entries that match your search./i', $resultado["file"]) or preg_match("ERROR: Couldn't find/i", $resultado["file"]) or empty($resultado["file"])){
echo cores("r")."NOT FOUND\n".cores("g2");
}else{
echo cores("g")."FOUND\n".cores("g2")."+-----------------------------------------------------------------------------.\n|\n";

if(isset($OPT["find"])){
preg_match_all('/<table cellpadding="0" cellspacing="0" border="0" width="100%">(.*?)<\/table>/s', $resultado["file"], $source);
$sourc = explode("</tr>", $source[0][0]);
array_pop($sourc);
array_shift($sourc);

foreach($sourc as $source){
preg_match_all('/<td valign="top" nowrap="nowrap"><a href="(.*?)">(.*?)<\/a><\/td>/s', $source, $link_title);
preg_match_all('/<td valign="top">(.*?)<\/td>/s', $source, $descript);
preg_match_all('/CVE-(.*?)-.*/s', $link_title[2][0], $year);
$ds = trim($descript[1][0]);

echo cores("g2")."| ".cores("g1")."AUTHOR:: ".cores("r")."Not available\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".cores("b").$year[1][0]."\n";
echo cores("g2")."| ".cores("g1")."CVE-ID:: ".$link_title[2][0].cores("b")."\n";
echo cores("g2")."| ".cores("g1")."DESCRIPTION:: ".cores("b").$ds.cores("g2")."\n";
echo cores("g2")."| ".cores("g1")."LINK:: http://cve.mitre.org".$link_title[1][0].cores("g2")."\n|\n";

$save["author"] = "Not available";
$save["date"] = $year[1][0];
$save["description"] = $ds;
$save["title"] = $link_title[2][0];
$save["url"] = "http://cve.mitre.org".$link_title[1][0];
$save["dbs"] = "CVE";
if($OPT["save"]==1){ echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
$id_info= $id_info+1;
}

}else{	
preg_match_all('/<h2>(.*?)<\/h2>/s', $resultado["file"], $link_title);
preg_match_all('/<td colspan="2">(.*?)<\/td>/s', $resultado["file"], $descript);
preg_match_all('/CVE-(.*?)-.*/s', $link_title[1][0], $year);
$ds = trim($descript[1][0]);

echo cores("g2")."| ".cores("g1")."AUTHOR:: ".cores("r")."Not available\n";
echo cores("g2")."| ".cores("g1")."DATE:: ".cores("b").$year[1][0]."\n";
echo cores("g2")."| ".cores("g1")."CVE-ID:: ".$link_title[1][0].cores("b")."\n";
echo cores("g2")."| ".cores("g1")."DESCRIPTION:: ".cores("b").$ds.cores("g2")."\n";
echo cores("g2")."| ".cores("g1")."LINK:: http://cve.mitre.org/cgi-bin/cvename.cgi?name=".$link_title[1][0].cores("g2")."\n";

$save["author"] = "Not available"; 
$save["date"] = $year[1][0];
$save["description"] = $ds; 
$save["title"] = $link_title[1][0]; 
$save["url"] = "http://cve.mitre.org/cgi-bin/cvename.cgi?name=CVE-".$link_title[1][0];
$save["dbs"] = "CVE";
if($OPT["save"]==1){ echo save($save); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}	
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
if(isset($oo["respond-time"])){$OPT["time"]=$oo["respond-time"];}else{$OPT["time"]="60";}
if(isset($oo["proxy-login"])){$OPT["proxy-login"]=$oo["proxy-login"];}
if(isset($oo["update"])){echo update($OPT);}
if(isset($oo["save"])){$OPT["save"] = 1;}
if(isset($oo["save-dir"])){$OPT["save-dir"] = $oo["save-dir"];}
if(isset($oo["save-log"])){$OPT["save-log"] = 1;}
if(isset($oo["set-db"])){ $OPT["db"]=""; $OPT["db"] = explode(",", $oo["set-db"]);}
if(isset($oo["d"])){$OPT["db"] = $oo["d"];}
if(isset($oo["search-list"])){if(!file_exists($oo["search-list"])){ die(cores("r")."\nFILE \"{$oo["search-list"]}\" does not exist!\n"); }else{$OPT["sfile"]=$oo["search-list"];}}else{$O=$O+1;}
if(isset($oo["author"])){$OPT["author"]=$oo["author"];}else{$O=$O+1;}
if(isset($oo["cve-id"])){$OPT["cve-id"]=$oo["cve-id"];$OPT["db"]="999";}else{$O=$O+1;}
if($O==5)die();
unset($oo);

####################################################################################################
## VERIFY SET-DB
if(isset($OPT["db"])){ $OPT = ccdbs($OPT); }

####################################################################################################
## INFOS
echo infos($OPT);

####################################################################################################
## SEARCH BY CVE-ID
if(isset($OPT["cve-id"])){ echo CVE($OPT); die(); }

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
## STARTING THE SEARCH - EXPLOIT DATABASES
foreach($file as $f){
$OPT["find"] = trim($f);
if(file_exists($OPT["sfile"])){ $l=cores("g1")."|".cores("g2"); echo cores("g2")."\n[ ".cores("g1")."SEARCH:: ".cores("b").$OPT["find"].cores("g2")." ]::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::-"; }
foreach($OPT["db"] as $id){
 if(preg_match("/1/i", $id) or $id == 0){ echo exploitdb($OPT);           }
 if(preg_match("/2/i", $id) or $id == 0){ echo milw00rm($OPT);            }
 if(preg_match("/3/i", $id) or $id == 0){ echo packetstormsecurity($OPT); }
 if(preg_match("/4/i", $id) or $id == 0){ echo intelligentexploit($OPT);  }
 if(preg_match("/5/i", $id) or $id == 0){ echo iedb($OPT);                }
 if(preg_match("/6/i", $id) or $id == 0){ echo CVE($OPT);                 }
}
}

#END
