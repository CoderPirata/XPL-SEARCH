#!/bin/env php
<?php
/*

Official repository - https://github.com/CoderPirata/XPL-SEARCH/

.--------------------------------------------------------------------------------------.
[ XPL SEARCH 1.0 ][ Search exploits/vulnerabilities in multiple databases online.      ]

XPL SEARCH is a multiplatform tool(Windows and Linux), 
which was developed in PHP with the aim of helping the hacker community to find exploits or 'vulnerabilities', 
using online databases, below is the list of databases which can be used in this release:
1. Exploit-DB
2. MIlw00rm
3. PacketStormSecurity
4. IntelligentExploit
5. IEDB
6. CVE
7. Siph0n

The tool offers several options, such as:
* Search individual.
* Search with multiple words(list).
* Select which databases will be used for research.
* Filter to remove repeatable results.
* Blocking specific databases.
* Save log with the survey data.
* Save the exploits/vulnerabilities found.
* Use of proxy.
* Set the time that the databases have to answer.
* Conduct research just indicating the author's name.
* Disable display of the banner.

Simple use: php xpl_search.php [command] [term]
        Ex: php xpl_search.php --search WordPress

Video demonstrating a simple search:
https://www.youtube.com/watch?v=Ja_yTWBR1eE

To use all the features as the tool provides, the following is recommended:
PHP Version(cli) 5.5.8 or higher
 php5-cli         Lib
cURL support      Enabled
 php5-curl        Lib
cURL Version      7.40.0 or higher
allow_url_fopen   On
Permission        Writing & Reading

Dependencies necessary:
php5
php5-cli
php5-curl
curl
libcurl3

If you are unsure if the dependencies are installed, run the following command(Only for linux):
php "xpl search.php" --install-dependencie

Or run in terminal: sudo apt-get install php5 php5-cli php5-curl curl libcurl3


To learn more about the tool, your commands and to see some pictures of the tool, acess the wiki on github:
https://github.com/CoderPirata/XPL-SEARCH/wiki

Download:
With git:
git clone https://github.com/CoderPirata/XPL-SEARCH.git xpl_search

With wget:
wget https://raw.githubusercontent.com/CoderPirata/XPL-SEARCH/master/xpl%20search.php -O xpl_search.php

With GitHub(web browser):
https://github.com/CoderPirata/XPL-SEARCH/releases/latest

|--------------------------------------------------------------------------------------|
[ LOG ]--------------------------------------------------------------------------------|

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

0.8 - [05/10/2015]
- Added shebang.
- Commands "save", "save-log" and "save-dir" have been modified.
- Added "no-db" option.
- GETOPT() modified - Thanks Jack2.
- Bug on save-dir solved.
- Others minor bugs solved.

0.9 - [19/11/2015]
- Added Siph0n database - ID 7.
- Update reworked.
- Comment in script updated.
- Adjustment in translation on "help page" - Thanks j3gb0.
- Milw00rm domain changed.

1.0 - [00/00/2016]
- Added Dependencies installation function.
- Added Color Theme function.
- Added command to force update.
- Exploit-DB function reworked.
- Added Filter function
- Minor bugs and adaptations solved.

.-----------------------------------[ Thanks to ]--------------------------------------.
|                             Jack2 - j3gb0 - php.net                                  |
|                                 BlackArch Linux                                      |
| And everyone who contributed in any way to the disclosure or improvement of the tool |
|--------------------------------------------------------------------------------------|
| If you find any bug or want to make any suggestions, please contact me by email.     |
'--------------------------------------------------------------------------------------'
*/

ini_set('error_log', NULL);
ini_set('log_errors', FALSE);
ini_restore("allow_url_fopen");
ini_set('allow_url_fopen', TRUE);
ini_set('display_errors', FALSE);
ini_set('max_execution_time', FALSE);
$long_opt = array('search:', 'search-list:', 'author:',
                  'save-log::', 'save::', 'save-dir::', 
                  'proxy:', 'proxy-login:',
                  'set-db:', 'no-db:', 'cve-id:', 'filter::',
                  'update::', 'force-update::',
				  'about::', 'help::', 'respond-time:', 'banner-no::', 'install-dependencie::', 'theme::');
$oo = getopt('h::s:p:a::', $long_opt);
define("VS", "1.0");
$_SESSION["AC_THEME"]="default";
if(substr(strtolower(PHP_OS), 0, 3) == "win"){ $_SESSION["os"] = "w"; }else{ $_SESSION["os"] = "l"; }

####################################################################################################
## GENERAL FUNCTIONS
function banner(){
echo c("g1")."
\t.   ..--. .        .-. .---.    .    .--.  .--..   .
\t \ / |   )|       (   )|       / \   |   ):    |   |
\t  /  |--' |        `-. |---   /___\  |--' |    |---|
\t / \ |    |       (   )|     /     \ |  \ :    |   |
\t'   ''    '---'    `-' '---''       `'   ` `--''   ' ".c("r").VS."
".c("g2")."------------------------------------------------------------------------------~".c("g1")."
HELP: {$_SERVER["SCRIPT_NAME"]} ".c("b")."--help".c("g1")."
USAGE: {$_SERVER["SCRIPT_NAME"]} ".c("b")."--search ".c("g1")."\"name to search\"\n".c(0);

if(!extension_loaded("curl")){	
echo c("g2")."\n[ ".c("g1")."ERROR".c("g2")." ]::".c("r")." LIB cURL not found!\n".c("g2")."[ ".c("g1")."WARN".c("g2")." ]:: ".c("g1");
if($_SESSION["os"] == "l"){  
echo c("g1")."run the command ".c("b")."\"--install-dependencie\"\n".c(0);
}else{ die("Please, install the cURL and run the script again\n".c(0));
}
}
echo c("g2")."------------------------------------------------------------------------------~\n".c(0);
}

function help(){
$script = $_SERVER["SCRIPT_NAME"];
echo c("g1")."
\t\t.   ..---..    .--.    .-. 
\t\t|   ||    |    |   )  '   )
\t\t|---||--- |    |--'      / 
\t\t|   ||    |    |        '  
\t\t'   ''---''---''        o  

".c("g2").".-----------------------------------------------------------------------------.";
if($_SESSION["os"] == "l"){
echo "\n|
| ".c("g1")."If the XPL SEARCH is not looking for any database,".c("g2")."
| ".c("g1")."you can try to reinstall (or install if you do not already have it) the dependencies.".c("g2")."
| ".c("g1")."Use the command(Only for linux): \"{$script} ".c("b")."--install-dependency\"".c("g2")."
|";
}
echo "\n[ ".c("g1")."MAIN COMMANDS".c("g2")." ]-------------------------------------------------------------'".c("g1")."

COMMAND: ".c("b")."--search".c("g1")." ~ Simple search.
         Example: {$script} ".c("b")."--search ".c("g1")."\"name to search\"
              Or: {$script} ".c("b")."-s ".c("g1")."\"name to search\"

COMMAND: ".c("b")."--help".c("g1")." ~ To view HELP.
         Example: {$script} ".c("b")."--help".c("g1")."
              Or: {$script} ".c("b")."-h".c("g1")."

COMMAND: ".c("b")."--about".c("g1")." ~ To view ABOUT.
         Example: {$script} ".c("b")."--about".c("g1")."
              Or: {$script} ".c("b")."-a".c("g1")."

COMMAND: ".c("b")."--update".c("g1")." ~ Command to update the script.
         Example: {$script} ".c("b")."--update".c("g2")."

.-----------------------------------------------------------------------------.
[ ".c("g1")."OTHERS COMMANDS".c("g2")." ]-----------------------------------------------------------'".c("g1")."

COMMAND: ".c("b")."--set-db".c("g1")." ~ Select which database(s) will be used, using the id's
           ".c("b")."0".c("g1")." - ALL (".c("b")."DEFAULT".c("g1").")
           ".c("b")."1".c("g1")." - Exploit-DB
           ".c("b")."2".c("g1")." - Milw00rm
           ".c("b")."3".c("g1")." - PacketStormSecurity
           ".c("b")."4".c("g1")." - IntelligentExploit
           ".c("b")."5".c("g1")." - IEDB
           ".c("b")."6".c("g1")." - CVE
           ".c("b")."7".c("g1")." - Siph0n
         Example: {$script} ".c("b")."--set-db".c("g1")." 1
                  {$script} ".c("b")."--set-db".c("g1")." 3,6,2
			  
COMMAND: ".c("b")."--no-db".c("g1")." ~ Exclude the indicated databases, indicate the \"id\" of the database to exclude.
         Example: {$script} ".c("b")."--no-db".c("g1")." 4
                  {$script} ".c("b")."--no-db".c("g1")." 2,5
				  
COMMAND: ".c("b")."--cve-id".c("g1")." ~ Displays the description and link to the CVE.
         Example: {$script} ".c("b")."--cve-id".c("g1")." 2015-0349
			  
COMMAND: ".c("b")."--author".c("g1")." ~ Search for exploits based on exploit Author.
         Example: {$script} ".c("b")."--author".c("g1")." CoderPirata
          ".c("b")."* ".c("p")."IntelligentExploit and Siph0n does not support this type of search.".c("g1")."
				
COMMAND: ".c("b")."--filter".c("g1")." ~ Command to filter the results.
         Example: {$script} ".c("b")."--filter".c("g1")."

COMMAND: ".c("b")."--save".c("g1")." ~  Save the exploits found by the tool. Add the location to save to after the command.
         Example: {$script} ".c("b")."--save".c("g1")."
                  {$script} ".c("b")."--save".c("g1")."=/opt/

COMMAND: ".c("b")."--save-log".c("g1")." ~ Saves log of search in the current dir and to define in what folder save, add the dir after the command.
         Example: {$script} ".c("b")."--save-log".c("g1")."
                  {$script} ".c("b")."--save-log".c("g1")."=/opt/

COMMAND: ".c("b")."--save-dir".c("g1")." ~ Sets the directory for saving files.
         Example: {$script} ".c("b")."--save-dir".c("g1")."=/opt/

COMMAND: ".c("b")."--force-update".c("g1")." ~ Command to force the tool update.
         Example: {$script} ".c("b")."--force-update".c("g1")."

COMMAND: ".c("b")."--proxy".c("g1")." ~ Set which proxy to use to perform searches in the dbs.
         Example: {$script} ".c("b")."--proxy".c("g1")." 127.0.0.1:80
                  {$script} ".c("b")."--proxy".c("g1")." 127.0.0.1
              Or: {$script} ".c("b")."--p".c("g1")."

COMMAND: ".c("b")."--proxy-login".c("g1")." ~ Set username and password to login on the proxy, if necessary.
         Example: {$script} ".c("b")."--proxy-login".c("g1")." user:pass

COMMAND: ".c("b")."--respond-time".c("g1")." ~ Command to set the maximum time(in seconds) that the database has before timeout.
         Example: {$script} ".c("b")."--respond-time".c("g1")." 30

COMMAND: ".c("b")."--banner-no".c("g1")." ~ Command to not display the banner.
         Example: {$script} ".c("b")."--banner-no".c("g1")."

COMMAND: ".c("b")."--install-dependencie".c("g1")." ~ Command to install the following dependencies.
           ".c("b")."*".c("g1")." php5
           ".c("b")."*".c("g1")." php5-cli
           ".c("b")."*".c("g1")." php5-curl
           ".c("b")."*".c("g1")." curl
           ".c("b")."*".c("g1")." libcurl3
         Example: {$script} ".c("b")."--install-dependencie".c("g1")."
		 
COMMAND: ".c("b")."--theme".c("g1")." ~ Command to change the color palette of the tool. Currently only 3 options are available:
           ".c("b")."Default".c("g1")." - Colors used since version 0.2
           ".c("b")."greenworld".c("g1")." - \"Green World\" As its name suggests, this theme is focused on green.
           ".c("b")."vsdark".c("g1")." - \"Blue and Yellow\"
           ".c("b")."wab".c("g1")." - \"Blue and Red\"
         Example: {$script} ".c("b")."--theme=".c("g1")."vsdark
                  {$script} ".c("b")."--theme=".c("g1")."wab
                  {$script} ".c("b")."--theme=".c("g1")."default".c("g2")."

'------------------------------------------------------------------------------~\n".c(0);
die();
}

function about(){
die(c("g1")."
\t\t    .    .              .  
\t\t   / \   |             _|_ 
\t\t  /___\  |.-.  .-. .  . |  
\t\t /     \ |   )(   )|  | |  
\t\t'       `'`-'  `-' `--`-`-' 

".c("g2").".-----------------------------------------------------------------------------.
[ ".c("b")."XPL SEARCH ".c("r").VS.c("g2")." ][ ".c("g1")."Search exploits/vulnerabilities in multiple databases online.".c("g2")."  ]

".c("g1")."XPL SEARCH was developed with the aim of helping the hacker community to find exploits or 'vulnerabilities', using online databases, below is the list of databases which can be used in this release:
".c("b")."1. ".c("g1")."Exploit-DB
".c("b")."2. ".c("g1")."MIlw00rm
".c("b")."3. ".c("g1")."PacketStormSecurity
".c("b")."4. ".c("g1")."IntelligentExploit
".c("b")."5. ".c("g1")."IEDB
".c("b")."6. ".c("g1")."CVE
".c("b")."7. ".c("g1")."Siph0n

".c("g1")."Among the options that the script has, we can mention the following:
".c("b")."* ".c("g1")."Search individual or with multiple words.
".c("b")."* ".c("g1")."Select which databases will be used for research.
".c("b")."* ".c("g1")."Blocking specific databases
".c("b")."* ".c("g1")."Save log with the survey data.
".c("b")."* ".c("g1")."Save the exploits/vulnerabilities found.
".c("b")."* ".c("g1")."Use of proxy.
".c("b")."* ".c("g1")."Set the time that the databases have to answer.
".c("b")."* ".c("g1")."Conduct research just indicating the author's name.
".c("b")."* ".c("g1")."Disable display of the banner.

".c("g1")."Simple use: php xpl_search.php ".c("g2")."[".c("b")."command".c("g2")."] [".c("b")."term".c("g2")."]
        ".c("g1")."Ex: ".c("b").$_SERVER["SCRIPT_NAME"]." --search WordPress

".c("g1")."Video demonstrating a simple search:
".c("b")."https://www.youtube.com/watch?v=Ja_yTWBR1eE

".c("g1")."To use all the features as the tool provides, the following is recommended:
PHP Version(cli)  ".c("b")."5.5.8 or higher".c("g1")."
 php5-cli         ".c("b")."Lib".c("g1")."
cURL support      ".c("b")."Enabled".c("g1")."
 php5-curl        ".c("b")."Lib".c("g1")."
cURL Version      ".c("b")."7.40.0 or higher".c("g1")."
allow_url_fopen   ".c("b")."On".c("g1")."
Permission        ".c("b")."Writing".c("g1")." & ".c("b")."Reading".c("g1")."

Dependencies necessary:
".c("b")."* ".c("g1")."php5
".c("b")."* ".c("g1")."php5-cli
".c("b")."* ".c("g1")."php5-curl
".c("b")."* ".c("g1")."curl
".c("b")."* ".c("g1")."libcurl3

To learn more about the tool, your commands and to see some pictures of the tool, acess the wiki on github:
".c("b")."https://github.com/CoderPirata/XPL-SEARCH/wiki

".c("g1")."Installing
With git:
".c("b")."git clone https://github.com/CoderPirata/XPL-SEARCH.git xpl_search

".c("g1")."With GitHub(web browser):
".c("b")."https://github.com/CoderPirata/XPL-SEARCH/releases/latest

".c("g2").".-----------------------------------------------------------------------------.
[ ".c("b")."ABOUT DEVELOPER".c("g2")." ]-----------------------------------------------------------'".c("g1")."
Author_Nick       ".c("b")."CoderPIRATA".c("g1")."
Author_Name       ".c("b")."Eduardo".c("g1")."
Email             ".c("b")."coderpirata@gmail.com".c("g1")."
Blog              ".c("b")."http://coderpirata.blogspot.com.br/".c("g1")."
Twitter           ".c("b")."https://twitter.com/coderpirata".c("g1")."
Google+           ".c("b")."https://plus.google.com/103146866540699363823".c("g1")."
Pastebin          ".c("b")."http://pastebin.com/u/CoderPirata".c("g1")."
Github            ".c("b")."https://github.com/coderpirata/".c("g2")."
------------------------------------------------------------------------------~\n".c(0));
}

function c($nome){
/* ONLY FOR "DEFAULT" COLOR
b  -> Light blue
g  -> Green
g1 -> Light grey
g2 -> Dark grey
p  -> Purple
r  -> Red light
0  -> Reset
*/

if($_SESSION["AC_THEME"] == "greenworld"){
/* Green World */
$c = array("r" => "\033[1;31m", "g" => "\033[0;32m", "b" => "\033[1;32m", "g2" => "\033[1;37m", "g1" => "\033[0;32m", 
		   "p" => "\033[1;33m", 0 => "\033[0m");

}elseif($_SESSION["AC_THEME"] == "vsdark"){
/* "VSDark" */
$c = array("r" => "\033[1;31m", "g" => "\033[0;32m", "b" => "\033[1;33m", "g2" => "\033[0m", "g1" => "\033[1;34m", 
		   "p" => "\033[1;36m", 0 => "\033[0m");

}elseif($_SESSION["AC_THEME"] == "wab"){
/* wab (White and Blue) */
$c = array("r" => "\033[1;35m", "g" => "\033[0;32m", "b" => "\033[1;31m", "g2" => "\033[0m", "g1" => "\033[1;34m", 
		   "p" => "\033[1;36m", 0 => "\033[0m");
		   
}else{
/* Cores padrão */
$c = array("r" => "\033[1;31m", "g" => "\033[0;32m", "b" => "\033[1;34m", "g2" => "\033[1;30m", "g1" => "\033[0;37m", 
           "p" => "\033[0;35m", 0 => "\033[0m");
}

if($_SESSION["os"] == "l"){ return $c[strtolower($nome)]; }

}

function theme($theme){
if(file_put_contents(__FILE__, str_replace('_SESSION["AC_THEME"]="'.$_SESSION["AC_THEME"].'";', '_SESSION["AC_THEME"]="'.$theme.'";', @file_get_contents(__FILE__)))){
 echo banner().c("g2")."\n\n[ ".c("g1")." THEME CHANGE".c("g2")." ]:: ".c("g")."SUCESFULLY\n\n";
 echo c("b")."Start the script again\n"; 
 exit();
}
}

function ccdbs($OPT){
$ids = array(0,1,2,3,4,5,6,7);
foreach($ids as $idz){
 foreach($OPT["db"] as $id){ if(!preg_match("/{$idz}/i", $id)){$o=$o+1;} }
}
if($o==8){$OPT["db"][] = 0;}

return $OPT;
}

function infos($OPT){
if(isset($OPT["save"])){ $info_save = "\n| ".c("p")."* Only text files will be saved!".c("g2")."                                            |"; }
if(!empty($OPT["proxy"])){$proxyR = "\n| ".c("g1")."PROXY - ".c("b").$OPT["proxy"];}
if(!empty($OPT["time"])){$timeL  = c("b").$OPT["time"].c("g1")." sec"; }else{ $timeL = c("b")."INDEFINITE"; }
if(isset($OPT["sfile"])){ $OPT["find"]=c("b").$OPT["sfile"]." is a list!".c("g2");  }
if(isset($OPT["author"])){ $OPT["find"]=c("g1")."AUTHOR ".c("b").$OPT["author"].c("g2"); }
if(isset($OPT["cve-id"])){ $OPT["find"]=c("g1")."CVE-".c("b").$OPT["cve-id"].c("g2"); }

if(isset($OPT["save"]) or isset($OPT["save-log"])){
if(isset($OPT["save"]) and isset($OPT["save-log"])){ $s = c("b")."XPL".c("g2")."|".c("b")."LOG"; }else
if(isset($OPT["save"])){ $s = c("b")."EXPLOIT's"; }else
if(isset($OPT["save-log"])){ $s = c("b")."LOG"; }

$a = "\n| ".c("g1")."SAVE {$s}: ";
$save_xpl = $a.c("b")."YES".c("g2")."\n| ".c("g1")."SAVE IN ".c("b");
 if(empty($OPT["save-dir"]) == FALSE){
 $save_xpl .= c("b")."\"{$OPT["save-dir"]}\"".c("g2");
  if(!is_dir($OPT["save-dir"])){ 
   $save_xpl .= " [".c("r")."ERROR WITH DIR ".c("g2")."-".c("b")." CURRENT DIR WILL BE USED!".c("g2")."]";   
  }else{ $save_xpl .=" - [".c("g")."DIR OK".c("g2")."]"; }
 }else{ $save_xpl .= c("b")."CURRENT DIR".c("g2"); }
}

$a=array(1 => c("g2")."[ ".c("b")."EXPLOIT-DB".c("g2")." ] ", 2 => c("g2")."[ ".c("b")."MILW00RM".c("g2")." ] ", 
		 3 => c("g2")."[ ".c("b")."PACKETSTORMSECURITY".c("g2")." ] ", 
		 4 => c("g2")."[ ".c("b")."INTELLIGENTEXPLOIT".c("g2")." ] ", 5 => c("g2")."[ ".c("b")."IEDB".c("g2")." ] ", 
		 6 => c("g2")."[ ".c("b")."CVE".c("g2")." ] ", 
		 7 => c("g2")."[ ".c("b")."SIPH0N".c("g2")." ] ");
foreach($OPT["db"] as $id){ 
 foreach($a as $N => $W){ if(preg_match("/{$N}/i", $id) or isset($OPT["no-db"])){ $setdb .= $W; } } 
}
if($id == 0 and !isset($OPT["no-db"])){ $setdb = c("g2")."[ ".c("b")."ALL".c("g2")." ] "; }
foreach($a as $H => $NM){ if(preg_match("/{$H}/i", $OPT["no-db"])){ $setdb = str_replace($NM, "", $setdb); } }
if($OPT["db"]=="999"){ $setdb .= c("g2")."[ ".c("b")."CVE-ID".c("g2")." ] "; }

if($_SESSION["filter"]){ $filtro = c("g")."ON".c("g2"); }else{ $filtro = c("r")."OFF".c("g2"); }

if(isset($OPT["no-db"])){
$h = 0;
$no_db = "\n| ".c("g1")."DATABASES BLOCKED: ";
$ha = explode(",", $OPT["no-db"]);
foreach($ha as $id){ foreach($a as $N => $W){ if(preg_match("/{$N}/i", $id)){ $no_db .= $W; $h++; } } }
if($h==0){$no_db="";}
}

$l=c("g1")."|".c("g2");
return c("g2").".-[ ".c("g1")."Info".c("g2")." ]--------------------------------------------------------------------.
| ".c("g1")."SEARCH FOR ".c("b")."{$OPT["find"]}".c("g2")."{$proxyR}
| ".c("g1")."TIME LIMIT FOR DBS RESPOND: {$timeL}".c("g2")."{$save_xpl}
| ".c("g1")."FILTER: {$filtro}
| ".c("g1")."DATABASES TO SEARCH: {$setdb}{$no_db}
".c("g2")."'-----------------------------------------------------------------------------'{$info_save}
'[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]-[:{$l}:]'\n\n";
}

function update($mode){
if($mode=="force"){ $p = "Force uptade activate!";$f = 1;}else{ $p = "Looking for a new version!";$f = 0;}
echo c("g2")."\n[ ".c("g1")."STARTING".c("g2")." ]:: ".c("g1").$p;
echo c("g2")."\n[ ".c("g1")."NEW SOURCE".c("g2")." ]:: ".c("g1");

$OPT["url"] = "https://raw.githubusercontent.com/CoderPirata/XPL-SEARCH/master/xpl%20search.php";
$update = browser($OPT);

if($update["http_code"]>307 or $update["http_code"]==0){ echo c("g2")."Retrying... "; $update = browser($browser); }
if($update["http_code"]>307 or $update["http_code"]==0){ echo die(c("r")."Error with the connection!\n\n".c("g2")); }
echo c("g")." FOUND\n".c(0);

if($f==0){ /* SEARCHING NEW VERSION */
 preg_match_all('#XPL SEARCH (.*?) ]#', $update["file"], $version);
 if($version[1][0] == VS){ die(c("g2")."[ ".c("g1")."DONE".c("g2")." ]:: ".c("g")." THERE ARE NO UPDATES AVAILABLE\n"); }else
 if($version[1][0] > VS){
  echo c("g2")."[ ".c("g1")."STATUS".c("g2")." ]:: ".c("b")."NEW VERSION FOUND: ".c("g").$version[1][0]."\n";
 }
}

echo c("g2")."[ ".c("g1")."STATUS".c("g2")." ]:: ".c("b")."UPDATING THE TOOL";
echo c("g2")."\n[ ".c("p")."WARNING".c("g2")." ]:: ".c("p")."the source code of this tool will be ".c("g1")."overwritten".c("p")." with the new version!\n";
if(file_put_contents(__FILE__,  $update["file"]) == FALSE){ die(c("g2")."[ ".c("r")."ERROR".c("g2")." ]:: ".c("r")."Error in updating tool!\n..Make sure you have sufficient permission for this.\n"); }
die(c("g2")."[ ".c("g1")."STATUS".c("g2")." ]:: ".c("g")."SUCCESSFULY UPDATED!\n".c(0));

}

function dependencies(){
if($_SESSION["os"] == "w"){ echo c("g2")."\n[ ".c("r")."ERROR".c("g2")." ]:: ".c("g1")."ONLY FOR LINUX".c(0); die(); }

echo c("g2")."\n[ ".c("g1")."DEPENDENCIES INSTALLATION".c("g2")." ]:: ".c("g1")."LIST BELOW".c("g2")."
.----------------------------------------------------.
| ".c("g1")."php5".c("g2")."                                               |
| ".c("g1")."php5-cli".c("g2")."                                           |
| ".c("g1")."php5-curl".c("g2")."                                          |
| ".c("g1")."curl".c("g2")."                                               |
| ".c("g1")."libcurl3".c("g2")."                                           |
'----------------------------------------------------'
\n[ ".c("g1")."COMMAND EXECUTED".c("g2")." ]:: ".c("b")."sudo apt-get install php5 php5-cli php5-curl curl libcurl3 libcurl3-dev\n\n".c("p");

passthru("sudo apt-get install curl libcurl3 php5 php5-cli php5-curl");
echo c(0);
}

function filter($OPT){
ob_end_clean();
$limpos = array();
$a = $_SESSION["filtro"];

foreach($a as $i => $d){ $n = 0; 
 foreach ($limpos as $il => $dl){ if($d["title"] == $dl["title"]){ $n = 1; } } 
 if($n != 1){ $limpos[] = $d; }	
}

$limpos = array_filter($limpos);

if(count($limpos)>0){
echo c("g2")."\n\n.-[ ".c("b")."FOUND".c("g2")." ]-------------------------------------------------------------------.\n|\n";

//print_r($limpos).die();

 foreach($limpos as $b => $inf){
  if($inf["db"] == "cve"){
   echo c("g2")."| ".c("g1")."DATABASE:: CVE\n";
   echo c("g2")."| ".c("g1")."AUTHOR:: ".c("r")."Not available\n";
   echo c("g2")."| ".c("g1")."DATE:: ".$inf["date"]."\n";
   echo c("g2")."| ".c("g1")."CVE-ID:: ".c("b").$inf["title"]."\n";
   echo c("g2")."| ".c("g1")."DESCRIPTION:: ".$inf["desc"]."\n";
   echo c("g2")."| ".c("g1")."LINK:: ".c("b").$inf["link"]."\n".c("g2");	
  }else{
   echo c("g2")."| ".c("g1")."DATABASE:: ".$inf["db"]."\n";
   echo c("g2")."| ".c("g1")."AUTHOR:: ".$inf["author"]."\n";
   echo c("g2")."| ".c("g1")."DATE:: ".$inf["date"]."\n";
   echo c("g2")."| ".c("g1")."TITLE:: ".c("b").$inf["title"]."\n";
   echo c("g2")."| ".c("g1")."LINK:: ".c("b").$inf["link"]."\n".c("g2");	
  }
  if($OPT["save"]==1){echo save($inf);}else{ echo "|\n"; }
  if($OPT["save-log"]==1){echo save_log($inf);}
 }

echo c("g2")."'-----------------------------------------------------------------------------'\n";
}

}

function save($save){
$n = PHP_EOL;
$ds = DIRECTORY_SEPARATOR;

if(preg_match("/milw00rm.org/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
if(!preg_match("/# milw00rm.org/i", $save["xpl"])){$ok=$ok+1;}
}

if(preg_match("/iedb.ir/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
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
$save["xpl"] = $save["desc"];
}else{ $ok=$ok+1; }

if(preg_match("/siph0n.net/i", $save["url"])){ 
$resultado = browser($save);
preg_match_all('/pre>(.+)<\/pre/s', htmlspecialchars_decode($resultado["file"]), $xpl);
$save["xpl"] = $xpl[1];
if(preg_match("/# siph0n/i", $save["xpl"])){$ok=$ok+1;} 
}

if($ok!=6 and !empty($save["xpl"])){
$save["title"] = trim(str_replace("/", "-", $save["title"]));
if(isset($save["save-dir"])){
if(!is_dir($save["save-dir"])){ goto pula; }
$bmk = $save["save-dir"].$ds."logs".$ds;
mkdir($bmk); 
mkdir($bmk.$save["find"].$ds); 
mkdir($bmk.$save["find"].$ds.$save["db"].$ds);
$bmk .= $save["find"].$ds.$save["db"].$ds;
}else{ pula:
$bmk = "logs".$ds;
mkdir($bmk); 
mkdir($bmk.$save["find"].$ds); 
mkdir($bmk.$save["find"].$ds.$save["db"].$ds);
$bmk .= $save["find"].$ds.$save["db"].$ds;
}

file_put_contents($bmk.$ds.$save["title"].".txt", $save["xpl"]);
return "| ".c("g1")."SAVED: ".c("g")."YES\n".c("g2")."|\n";
}else{ return "| ".c("g1")."SAVED: ".c("r")."NOT\n".c("g2")."|\n"; }

}

function save_log($OPT){
$ds = DIRECTORY_SEPARATOR;
$n = PHP_EOL;
$svd = "";
if(isset($OPT["save-dir"]) and !empty($OPT["save-dir"]) and is_dir($OPT["save-dir"])){$svd = $OPT["save-dir"].$ds;}
mkdir($svd."logs".$ds);
file_put_contents($svd."logs".$ds."search_log.txt", "DATABASE: ".$OPT["db"].$n."AUTHOR: ".$OPT["author"].$n."DATE: ".$OPT["date"].$n."TITLE: ".$OPT["title"].$n."LINK: ".$OPT["link"].$n.$n, FILE_APPEND);
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
 curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, $browser["time"]);
 curl_setopt($ch, CURLOPT_TIMEOUT, $browser["time"]);
}

curl_setopt($ch, CURLOPT_USERAGENT, $UserAgent);
curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);

if(!empty($browser["post"])){ curl_setopt($ch, CURLOPT_POSTFIELDS, $browser["post"]); }

curl_setopt($ch, CURLOPT_COOKIEFILE, 'cookie.txt');
curl_setopt($ch, CURLOPT_COOKIEJAR, 'cookie.txt');

$resultado["file"] = html_entity_decode(htmlspecialchars_decode(curl_exec($ch))); 
$resultado["http_code"] = curl_getinfo($ch, CURLINFO_HTTP_CODE);
curl_close($ch);

return $resultado; 
}

####################################################################################################
## DATABASES
function milw00rm($OPT){

echo "\n".c("g2")."[ ".c("g1")."MILW00RM.com ".c("g2")."]:: ";	
$resultado=NULL;
$f=0000;
$save=array();
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
if(isset($OPT["author"])){
$browser = array("url" => "https://milw00rm.com/author.php?name=".urlencode($OPT["author"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://milw00rm.com/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

if(!preg_match('/<td class="style1">-::DATE<\/td>/i', $resultado["file"]) or empty($resultado["file"])){ 
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

preg_match_all('#<td class="style1" nowrap="nowrap" width="62">(.*?)</td>#', $resultado["file"], $date);		
preg_match_all('#<td nowrap="nowrap" width="135"><a href="(.*?)">(.*?)</a></td>#', $resultado["file"], $author);
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $resultado["file"], $title_link);

$nn = count($date[0]); $nn--; $i=0;

if($_SESSION["filter"]){ ob_start();  }

while($i <= $nn){	

if($_SESSION["filter"]){
ob_end_clean();
$_SESSION["filtro"][] = array('db'     => 'MILW00RM',
                             'author' => $author[2][$i], 
                             'date' => $date[1][$i], 
                             'title' => $title_link[2][$i], 
                             'link' => "http://milw00rm.org/".$title_link[1][$i]); 
}else{

echo c("g2")."| ".c("g1")."AUTHOR:: ".$author[2][$i]."\n";
echo c("g2")."| ".c("g1")."DATE:: ".$date[1][$i]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").$title_link[2][$i]."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b")."http://milw00rm.org/".$title_link[1][$i]."\n".c("g2");

$save["author"] = $author[2][$i];
$save["title"] = $title_link[2][$i]; 
$save["link"] = "http://milw00rm.org/".$title_link[1][$i];
$save["date"] = $date[1][$i];
$save["db"]="MILW00RM";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}
}

$i++;


}
if(!$_SESSION["filter"]){ echo c("g2")."'-----------------------------------------------------------------------------'\n"; }

}
$LAIA = array();
saida:
}

function packetstormsecurity($OPT){
echo "\n".c("g2")."[ ".c("g1")."PACKETSTORMSECURITY.com ".c("g2")."]:: ";
$resultado=NULL;
$id_pages=2;
if(isset($OPT["author"])){
$browser = array("url" => "https://packetstormsecurity.com/search/authors/?q=".urlencode($OPT["author"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "https://packetstormsecurity.com/search/?q=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

if(preg_match('/<title>No Results Found/i', $resultado["file"]) or empty($resultado["file"])){ 
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

while($id_pages < 100){	 if($_SESSION["filter"]){ ob_start(); }
preg_match_all('/<dl id="(.*?)" class="(.*?)">(.*?)<\/dl>/s', $resultado["file"], $a);
foreach($a[3] as $source){
preg_match_all('#<dd class="datetime">Posted <a href="/files/date/(.*?)/" title=".*">.*</a></dd>#', $source, $date);
preg_match_all('#<a class="ico .*" href="(.*?)" title="(.*?)">(.*?)<\/a>#', $source, $nmlk);
preg_match_all('#<dd class="refer">Authored by <a href="(.*?)" class="(.*?)">(.*?)<\/a>#', $source, $author);
preg_match_all('#/files/(.*?)/#', $nmlk[1][0], $ab);
$link = "https://packetstormsecurity.com/files/{$ab[1][0]}/";

if($_SESSION["filter"]){ 
ob_end_clean();
$_SESSION["filtro"][] = array('db'     => 'PACKETSTORMSECURITY',
                             'author' => $author[3][0], 
                             'date' => $date[1][0], 
                             'title' => $nmlk[3][0], 
                             'link' => $link); 
}else{

echo c("g2")."| ".c("g1")."AUTHOR:: ".$author[3][0]."\n";
echo c("g2")."| ".c("g1")."DATE:: ".$date[1][0]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").$nmlk[3][0]."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b").$link."\n".c("g2");

$save["author"] = $author[3][0];
$save["date"] = $date[1][0];
$save["title"] = $nmlk[3][0];
$save["link"] = $link; 
$save["db"]="PACKETSTORMSECURITY";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}
}

}

if(preg_match('/accesskey="]">Next<\/a>/i', $resultado["file"])){
$browser["url"]="https://packetstormsecurity.com/search/files/page{$id_pages}/?q={$OPT["find"]}";
$resultado = browser($browser);
}else{ goto fim_; }

$id_pages++;
if($_SESSION["filter"]){ ob_end_clean(); }
}

fim_:
if(!$_SESSION["filter"]){ echo c("g2")."'-----------------------------------------------------------------------------'\n"; }
}
saida:
}

function iedb($OPT){
echo "\n".c("g2")."[ ".c("g1")."IEDB.ir ".c("g2")."]:: ";	
$resultado=NULL;
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
if(isset($OPT["author"])){
$browser = array("url" => "http://iedb.ir/author-{$OPT["author"]}.html", "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "http://iedb.ir/search.php", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

if(!preg_match('/<td class="style1">-::DATE<\/td>/i', $resultado["file"]) or empty($resultado["file"])){ 
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

preg_match_all('/<tr class="submit">(.*?)<\/tr>/s', $resultado["file"], $a);

foreach($a[0] as $v){
preg_match_all('#<td class="style1" nowrap="nowrap" width="62">(.*?)</td>#', $v, $date);
preg_match_all('#<td nowrap="nowrap" width="135"><a href="(.*?)">(.*?)</a></td>#', $v, $author);
preg_match_all('#<a href="(.*?)" target="_blank" class="style1">(.*?)</a>#', $v, $nmlnk);

if($_SESSION["filter"]){ 
ob_end_clean();
$_SESSION["filtro"][] = array('db'     => 'IEDB',
                             'author' => $author[2][0], 
                             'date' => $date[1][0], 
                             'title' => $nmlnk[2][0], 
                             'link' => "http://iedb.ir/".$nmlnk[1][0]); 
}else{

echo c("g2")."| ".c("g1")."AUTHOR:: ".$author[2][0]."\n";
echo c("g2")."| ".c("g1")."DATE:: ".$date[1][0]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").$nmlnk[2][0]."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b")."http://iedb.ir/".$nmlnk[1][0]."\n".c("g2");

$save["author"] = $author[2][0];
$save["date"] = $date[1][0];
$save["title"] = $nmlnk[2][0]; 
$save["link"] = "http://iedb.ir/".$nmlnk[1][0]; 
$save["db"]="IEDB";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}

}

}

if(!$_SESSION["filter"]){ 
echo c("g2")."'-----------------------------------------------------------------------------'\n";
}

}
saida:
}

function intelligentexploit($OPT){
echo "\n".c("g2")."[ ".c("g1")."INTELLIGENTEXPLOIT.com ".c("g2")."]:: ";
$resultado=NULL;
if(isset($OPT["author"])){ echo c("r")."This db does not support this type of search.\n"; goto saida; }
$browser = array("url" => "http://www.intelligentexploit.com/api/search-exploit?name=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

if(empty($resultado["file"])){
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

preg_match_all('#{"id":"(.*?)","date":"(.*?)","name":"(.*?)"}#', $resultado["file"], $a);

$i=0;
while($i < count($a[0])){

if($_SESSION["filter"]){ 
ob_end_clean();
$_SESSION["filtro"][] = array('db'     => 'INTELLIGENTEXPLOIT',
                             'author' => "Not available", 
                             'date' => $a[2][$i], 
                             'title' => htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i])), 
                             'link' => "https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}"); 
}else{

echo c("g2")."| ".c("g1")."AUTHOR:: ".c("r")."Not available\n";
echo c("g2")."| ".c("g1")."DATE:: ".c("b").$a[2][$i]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i]))."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b")."https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}\n".c("g2");

$save["author"] = "Not available"; 
$save["date"] = $a[2][$i];
$save["title"] = htmlspecialchars_decode(str_replace("\/", "/", $a[3][$i])); 
$save["link"] = "https://www.intelligentexploit.com/view-details.html?id={$a[1][$i]}"; 
$save["db"]="INTELLIGENTEXPLOIT";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}
}

$i++;
}

if(!$_SESSION["filter"]){ echo c("g2")."'-----------------------------------------------------------------------------'\n"; }
saida:
}

}

function exploitdb($OPT){
echo "\n".c("g2")."[ ".c("g1")."EXPLOIT-DB.com ".c("g2")."]:: ";	

$string = explode(" ", trim($OPT["find"]));
$n = count($string);

/* PHP_EOL DON'T WORK*/
$list = explode("\n", @file_get_contents("files.csv"));

/* UPDATING FILE IF NECCESSARY */
if(date('Y-m-d')>$list[0]){
$li['url'] = "https://github.com/offensive-security/exploit-database/blob/master/files.csv?raw=true";
$lista = browser($li);
if(count($lista['file'])!=0){ file_put_contents("files.csv", date('Y-m-d').PHP_EOL.$lista['file']); }
unset($list);
$list = explode("\n", @file_get_contents("files.csv"));
}

$possible = array();

foreach($list as $l){$n1=0; 
 preg_match_all('#(.*?),.*,"(.*?)",(.*?),(.*?),.*,.*,.*#', $l, $data);
 foreach($string as $s){ if(preg_match("/$s/i", $data[2][0])){$n1++;} }
 if($n1==$n){
  $fnd[$data[3][0]]= array('t'=>$data[2][0],'a'=>str_replace('"', '',$data[4][0]), 'i'=>$data[1][0], 'd'=>$data[3][0]);
  $order[] = $data[3][0];
 }
}
sort($order);


foreach($order as $i => $da){ 
 foreach($fnd as $i2 => $info){ 
  if($da == $i2){ 
   foreach($ok as $a => $b){}
  	if($b["id"]!=$fnd[$da]["i"]){
  	 $ok[] = array('title'=>$fnd[$da]['t'], 'author'=>$fnd[$da]['a'], 'date'=>$fnd[$da]['d'], 'id'=>$fnd[$da]['i']); 
    }
   }
  } 
 }
$found = array_reverse($ok);

if(count($found)==0){
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

foreach($found as $i => $info){

if($_SESSION["filter"]){ 
ob_end_clean(); 
$_SESSION["filtro"][] = array('db'     => 'EXPLOIT-DB',
                             'author' => $info["author"], 
                             'date' => $info["date"], 
                             'title' => $info["title"], 
                             'link' => "https://www.exploit-db.com/exploits/{$info["id"]}/"); 

}else{
echo c("g2")."| ".c("g1")."AUTHOR:: ".$info["author"]."\n";
echo c("g2")."| ".c("g1")."DATE:: ".$info["date"]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").$info["title"]."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b")."https://www.exploit-db.com/exploits/{$info["id"]}/".c("g2")."\n".c("g2");


$save["author"] = $info["author"];
$save["date"] = $info["date"];
$save["title"] = $info["title"];
$save["link"] = "https://www.exploit-db.com/exploits/{$info["id"]}/"; 	
$save["db"] = "EXPLOIT-DB";
if($OPT["save"]==1){ echo save($save); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
}

}
if(!$_SESSION["filter"]){ echo c("g2")."'-----------------------------------------------------------------------------'\n"; }
}

saida:
}

function CVE($OPT){
echo "\n".c("g2")."[ ".c("g1")."CVE.mitre.org ".c("g2")."]:: ";	
$resultado=NULL;

if(isset($OPT["find"])){
$browser = array("url" => "http://cve.mitre.org/cgi-bin/cvekey.cgi?keyword=".urlencode($OPT["find"]), "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}else{
$browser = array("url" => "http://cve.mitre.org/cgi-bin/cvename.cgi?name=".$OPT["cve-id"], "proxy" => $OPT["proxy"], "time" => $OPT["time"]);
}
$resultado = browser($browser);

if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); } 
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

if(preg_match("/There are <b>0<\/b> CVE entries that match your search/i", $resultado["file"]) or 
   preg_match("ERROR: Couldn't find/i", $resultado["file"]) or empty($resultado["file"])){
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2").".-----------------------------------------------------------------------------.\n|\n";

if(isset($OPT["find"])){
preg_match_all('/<table cellpadding="0" cellspacing="0" border="0" width="100%">(.*?)<\/table>/s', $resultado["file"], $source);
$sourc = explode("</tr>", $source[0][0]);
array_pop($sourc);
array_shift($sourc);

foreach($sourc as $source){
preg_match_all('/<td valign="top" nowrap="nowrap"><a href="(.*?)">(.*?)<\/a><\/td>/s', $source, $nmlk);
preg_match_all('/<td valign="top">(.*?)<\/td>/s', $source, $descript);
preg_match_all('/CVE-(.*?)-.*/s', $nmlk[2][0], $year);
$ds = trim($descript[1][0]);

if($_SESSION["filter"]){ 
ob_end_clean(); 
$_SESSION["filtro"][] = array('db'     => 'cve',
                             'author' => "Not available", 
                             'data' => $year[1][0], 
                             'title' => $nmlk[2][0], 
                             'desc' => $ds,
                             'link' => "http://cve.mitre.org".$nmlk[1][0]); 

}else{

echo c("g2")."| ".c("g1")."AUTHOR:: ".c("r")."Not available\n";
echo c("g2")."| ".c("g1")."DATE:: ".c("b").$year[1][0]."\n";
echo c("g2")."| ".c("g1")."CVE-ID:: ".$nmlk[2][0].c("b")."\n";
echo c("g2")."| ".c("g1")."DESCRIPTION:: ".c("b").$ds.c("g2")."\n";
echo c("g2")."| ".c("g1")."LINK:: http://cve.mitre.org".$nmlk[1][0].c("g2")."\n";

$save["author"] = "Not available";
$save["date"] = $year[1][0];
$save["description"] = $ds;
$save["title"] = $nmlk[2][0];
$save["link"] = "http://cve.mitre.org".$nmlk[1][0];
$save["db"] = "CVE";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}
}
}

}else{	
preg_match_all('/<h2>(.*?)<\/h2>/s', $resultado["file"], $nmlk);
preg_match_all('/<td colspan="2">(.*?)<\/td>/s', $resultado["file"], $descript);
preg_match_all('/CVE-(.*?)-.*/s', $nmlk[1][0], $year);
$ds = trim($descript[1][0]);

echo c("g2")."| ".c("g1")."AUTHOR:: ".c("r")."Not available\n";
echo c("g2")."| ".c("g1")."DATE:: ".c("b").$year[1][0]."\n";
echo c("g2")."| ".c("g1")."CVE-ID:: ".$nmlk[1][0].c("b")."\n";
echo c("g2")."| ".c("g1")."DESCRIPTION:: ".c("b").html_entity_decode(htmlspecialchars_decode($ds)).c("g2")."\n";
echo c("g2")."| ".c("g1")."LINK:: http://cve.mitre.org/cgi-bin/cvename.cgi?name=".$nmlk[1][0].c("g2")."\n";

$save["author"] = "Not available"; 
$save["date"] = $year[1][0];
$save["description"] = html_entity_decode(htmlspecialchars_decode($ds)); 
$save["title"] = $nmlk[1][0]; 
$save["url"] = "http://cve.mitre.org/cgi-bin/cvename.cgi?name=".$nmlk[1][0];
$save["dbs"] = "CVE";
$LAIA = array_merge($save, $OPT);
if($OPT["save"]==1){ echo save($LAIA); }else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($LAIA);}
}

fim_:
echo c("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}

function siph0n($OPT){
echo "\n".c("g2")."[ ".c("g1")."SIPH0N.in ".c("g2")."]:: ";	
$resultado=NULL;

if(isset($OPT["author"])){
echo c("r")."Not available\n";
goto saida;
}else{
$info = array('search' => $OPT["find"], 'Submit' => 'Submit');
$browser = array("url" => "http://siph0n.in/", "proxy" => $OPT["proxy"], "post" => $info, "time" => $OPT["time"]);
}

$resultado = browser($browser);
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("g2")."Retrying... "; $resultado = browser($browser); }
if($resultado["http_code"]>307 or $resultado["http_code"]==0){ echo c("r")."Error with the connection...\n\n".c("g2"); goto saida; }

$la=0;
$a = explode("\n", $resultado["file"]);
foreach($a as $line){ if($line == "<br><br><b>[ Search Results ]</b><br>")$la=1; }
if($la!=1){
echo c("r")."NOT FOUND\n".c("g2");
}else{
echo c("g")."FOUND\n".c("g2");
if(!$_SESSION["filter"]){ echo ".-----------------------------------------------------------------------------.\n|\n".c("g1"); }

preg_match_all('/<table width="597" align="center" border="0">(.*?)<\/table>/s', $resultado["file"], $data_brute);
$data_brute_2 = explode("</tr>", $data_brute[0][0]);
unset($data_brute_2[0]);
unset($data_brute_2[count($data_brute_2)]);

foreach($data_brute_2 as $data){
preg_match_all('#<td class="style1" nowrap="nowrap" width="62">(.*?)</td>#', $data, $date);		
preg_match_all('#<td nowrap="nowrap" width="375"><a href="(.*?)" target="_blank" class="style1">(.*?)</a></td>#', $data, $title_link);
preg_match_all('#<a href=".*">(.*?)</a>#', $data, $author);

if($_SESSION["filter"]){ 
ob_end_clean();
$_SESSION["filtro"][] = array('db'     => 'SIPH0N',
                             'author' => $author[1][2], 
                             'date' => $date[1][0], 
                             'title' => trim($title_link[2][0]), 
                             'link' => "http://siph0n.in/{$title_link[1][0]}"); 
}else{
	
echo c("g2")."| ".c("g1")."AUTHOR:: ".$author[1][2]."\n";
echo c("g2")."| ".c("g1")."DATE:: ".$date[1][0]."\n";
echo c("g2")."| ".c("g1")."TITLE:: ".c("b").trim($title_link[2][0])."\n";
echo c("g2")."| ".c("g1")."LINK:: ".c("b")."http://siph0n.in/{$title_link[1][0]}".c("g2")."\n".c("g2");

$save["author"] = $author[1][2];
$save["date"] = $date[1][0];
$save["title"] = trim($title_link[2][0]);
$save["link"] = "http://siph0n.in/".$title_link[1][0]; 	
$save["db"] = "SIPH0N";
if($OPT["save"]==1){echo save($save);}else{ echo "|\n"; }
if($OPT["save-log"]==1){echo save_log($save);}
}

}

echo c("g2")."'-----------------------------------------------------------------------------'\n";
}
saida:
}
####################################################################################################
## CONFIGS
$OPT = array();
$OPT["db"] = array(0);
if(isset($oo["theme"]) and !empty($oo["theme"])){echo c("a"); theme($oo["theme"]);}
if(isset($oo["filter"])){ $_SESSION["filter"] = TRUE; }else{ $_SESSION["filter"] = FALSE; }
if(!isset($oo["banner-no"]))echo banner();
if(isset($oo["install-dependencie"])){ echo dependencies(); }
if(isset($oo["h"]) or isset($oo["help"]))echo help();
if(isset($oo["a"]) or isset($oo["about"]))echo about();
if(isset($oo["s"])){$OPT["find"]=$oo["s"];}else{$O=1;}
if(isset($oo["search"])){$OPT["find"]=$oo["search"];}else{$O=$O+1;}
if(isset($oo["p"])){$OPT["proxy"]=$oo["p"];}
if(isset($oo["proxy"])){$OPT["proxy"]=$oo["proxy"];}
if(isset($oo["respond-time"])){$OPT["time"]=$oo["respond-time"];}else{$OPT["time"]="60";}
if(isset($oo["proxy-login"])){$OPT["proxy-login"]=$oo["proxy-login"];}
if(isset($oo["update"])){echo update($OPT);}
if(isset($oo["force-update"])){echo update("force");}
if(isset($oo["save"])){$OPT["save"] = 1;}
if(isset($oo["save-dir"])){$OPT["save-dir"] = $oo["save-dir"];}
if(isset($oo["save-log"])){$OPT["save-log"] = 1;}
if(isset($oo["set-db"])){ $OPT["db"]=""; $OPT["db"] = explode(",", $oo["set-db"]);}
if(isset($oo["d"])){$OPT["db"] = $oo["d"];}
if(isset($oo["search-list"])){if(!file_exists($oo["search-list"])){ die(c("r")."\nFILE \"{$oo["search-list"]}\" does not exist!\n"); }else{$OPT["sfile"]=$oo["search-list"];}}else{$O=$O+1;}
if(isset($oo["author"])){$OPT["author"]=$oo["author"];}else{$O=$O+1;}
if(isset($oo["cve-id"])){$OPT["cve-id"]=$oo["cve-id"];$OPT["db"]="999";}else{$O=$O+1;}
if(isset($oo["no-db"])){$OPT["no-db"]=$oo["no-db"];}
if(empty($oo["save-dir"]) and !empty($oo["save"])){ $OPT["save-dir"] = $oo["save"]; }
if(empty($oo["save-dir"]) and !empty($oo["save-log"])){ $OPT["save-dir"] = $oo["save-log"]; }
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
if(empty($file)){ die(c("r")."File \"{$OPT["sfile"]}\" are empty!"); } 
$file = explode("\n", $file);
}else{ 
$file = array($OPT["find"]); 
if(isset($OPT["author"])){$file = array($OPT["author"]);}
}

####################################################################################################
## STARTING THE SEARCH - EXPLOIT DATABASES
foreach($file as $f){
$OPT["find"] = trim($f);
if(file_exists($OPT["sfile"])){ $l=c("g1")."|".c("g2"); echo c("g2")."\n[ ".c("g1")."SEARCH:: ".c("b").$OPT["find"].c("g2")." ]::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::{$l}::-"; }
foreach($OPT["db"] as $id){
 if(preg_match("/1/i", $id) or $id == 0 and !preg_match("/1/i", $OPT["no-db"])){ echo exploitdb($OPT);           }
 if(preg_match("/2/i", $id) or $id == 0 and !preg_match("/2/i", $OPT["no-db"])){ echo milw00rm($OPT);            }
 if(preg_match("/3/i", $id) or $id == 0 and !preg_match("/3/i", $OPT["no-db"])){ echo packetstormsecurity($OPT); }
 if(preg_match("/4/i", $id) or $id == 0 and !preg_match("/4/i", $OPT["no-db"])){ echo intelligentexploit($OPT);  }
 if(preg_match("/5/i", $id) or $id == 0 and !preg_match("/5/i", $OPT["no-db"])){ echo iedb($OPT);                }
 if(preg_match("/6/i", $id) or $id == 0 and !preg_match("/6/i", $OPT["no-db"])){ echo CVE($OPT);                 }
 if(preg_match("/7/i", $id) or $id == 0 and !preg_match("/7/i", $OPT["no-db"])){ echo siph0n($OPT);              }
 }
}

if($_SESSION["filter"]){ filter($OPT);  }

#END
