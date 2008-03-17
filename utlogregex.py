#!/usr/bin/python

import re,os

r = re.compile(r'''
    [\d.]+\tplayer\tRename\t(?P<name>[\S]+)\t(?P<id>[\d]+)\s+        # name and id
    [\d.]+\tplayer\tTeamchange\t(?P=id)\t(?P<team>0|1)\s+            # team
    [\d.]+\tplayer\tConnect\t(?P=name)\t(?P=id)\tFalse\s+            # spectator status
    [\d.]+\tplayer\tTeamName\t(?P=id)\t(Red|Blue|Green|Gold)\s+      # team color
    [\d.]+\tplayer\tTeam\t(?P=id)\t(?P=team)\s+                      #
    [\d.]+\tplayer\tTeamID\t(?P=id)\t(?P<teamid>[\d]+)\s+            # id on team
    [\d.]+\tplayer\tPing\t(?P=id)\t0\s+                              # ping initially 0
    [\d.]+\tplayer\tIsABot\t(?P=id)\tFalse\s+                        # exclude bots
    [\d.]+\tplayer\tSkill\t(?P=id)\t(?P<skill>[\d.]+)\s+             # skill
    [\d.]+\tplayer\tIP\t(?P=id)\t(?P<ip>160\.129\.[\d]+\.[\d]+)      # ip
    ''', re.X)

for f in os.listdir(os.getcwd()):
    if f.endswith('.log'):
        for m in re.finditer(r, file(f, 'r').read()):
            #for k,v in m.groupdict().items():
            #    print k,"=",v
            print '\t'.join(m.groupdict().values())
