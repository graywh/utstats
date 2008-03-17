UTStats
Copyright (C) 2004/2005 azazel, AnthraX and toa

This program is free software; you can redistribute and/or modify
it under the terms of the Open Unreal Mod License.
See license.txt for more information.

=============================
UTStats beta 4.2 Installation
=============================

1) If you don't have UTStats installed yet, go to:

   http://downloads.unrealadmin.org/UnrealTournament/Addons/UTStats/

   Download the beta 4.0 package and follow the steps described 
   in install.txt, then proceed to step 2.

2) If you do have UTStats installed then:
	* Upload the UTStatsBeta4_2.u and UTSAccuBeta4_2.u files
          to your UTServer\System folder.
	* Open your server.ini and find the line [Engine.GameEngine]
	* Remove all ServerPackages and ServerActors entries of previous
	  UTStats versions, then add:
              ServerPackages=UTSAccuBeta4_2
	      ServerActors=UTStatsBeta4_2.UTStatsSA
	* Restart your UT Server
3) Upload the files in the html folder to your webserver.



