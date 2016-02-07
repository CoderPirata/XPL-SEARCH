XPL SEARCH
===============
```
Search exploits/vulnerabilities in multiple databases online!

XPL SEARCH is a multiplatform tool(Windows and Linux), which was developed in PHP with the aim of helping the hacker community to find exploits or 'vulnerabilities', using online databases, below is the list of databases which can be used in this release:
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

Video demonstrating a simple search: https://www.youtube.com/watch?v=Ja_yTWBR1eE
```

TO RUN THE SCRIPT
----
```
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
```

"CHANGELOG"
----
```
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

1.0 - [07/02/2016]
- Added Dependencies installation function.
- Added Color Theme function.
- Added command to force update.
- Exploit-DB function reworked.
- Added Filter function
- Minor bugs and adaptations solved.
```

ABOUT DEVELOPER
----
```
Author_Nick       CoderPIRATA
Author_Name       Eduardo
Email             coderpirata@gmail.com
Blog              http://coderpirata.blogspot.com.br/
Twitter           https://twitter.com/coderpirata
Google+           https://plus.google.com/103146866540699363823
Pastebin          http://pastebin.com/u/CoderPirata
Github            https://github.com/coderpirata/
```

Screenshot
----
![images](http://2.bp.blogspot.com/-_zxNoFeLuHk/VcLdwG4g8dI/AAAAAAAAAJM/VXmDTolozeU/s640/banner_xpl-search.png)
![images](https://2.bp.blogspot.com/-d43yUtGTcos/VrfSIcRXqdI/AAAAAAAAANg/WlJUhtV2zIs/s1600/filter.png)
![images](http://1.bp.blogspot.com/-P9K9fJ6k53o/VeJcozOiH2I/AAAAAAAAAJ4/iN5EwcdwIUM/s1600/exec.png)
![images](http://3.bp.blogspot.com/-SoUb9FnrRvo/Vk4itKD8M8I/AAAAAAAAANA/WWOITDYllCw/s1600/search-siph0n.png)


If you find any bug or want to make any suggestions, please contact me by email
