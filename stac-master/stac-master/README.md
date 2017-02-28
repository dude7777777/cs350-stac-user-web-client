# UNCO-CS350-User-Client-Web
The web-facing user client for UNCO CS350 class project. Fall 2016.

# Getting Started
## VCRedist
Here's a good list of Microsoft Visual C++ Redistributables we'll need for WampServer to run:
http://standaloneofflineinstallers.blogspot.com/2015/12/Microsoft-Visual-C-Redistributable-2015-2013-2012-2010-2008-2005-32-bit-x86-64-bit-x64-Standalone-Offline-Installer-for-Windows.html

Install versions of 2015, 2013, and 2012 that are compatible with your system (x86/x64)

## WampServer
Download WampServer 3.0.6 here: http://www.wampserver.com/en/
Install it (optionally choose the executables for your default browser and code editors, just click next if you don't know/care about this)

Once you run WampServer, there'll be a green icon in your system tray if everything went well


## GitHub Desktop
Download GitHub Desktop: https://desktop.github.com/

Once installed you can optionally go through the tutorial, but the major thing is to clone the repo. To clone the repo, click the plus (+) icon at the top left of GitHub desktop, click Clone, and then select this repo (UNCO-CS350-User-Client-Web). This just downloads all of the code for the repo to your local machine

When it asks where to clone your repository to, clone it to the www folder under where you installed WampServer (default: C:\wamp64\www\)

# Start the Server
Once WampServer is running and the system tray icon is green, you can access the index page of the site at:
http://localhost/cs350

# Additional Programs
Mehrgan has recommended this program to test TCP connections on your local machine:
https://packetsender.com/

# TCP Reference
## Login
- **Command**: LOGU "<Username>" "<Password>"
- **Format**: Accepts letters, numbers, underscores, and spaces for <Username> and <Password>
- **Response**: <LOGR S|LOGR F>

## Register
- **Command**: REGU "<Username>" "<Password>" "<FirstName>" "<LastName>"
- **Format**: Accepts letters, numbers, underscores, and spaces for <Username> and <Password>. Letters and spaces for <FirstName> and <LastName>
- **Response**: <REGR S|REGR F>

# Authors
Marcus Longwell
