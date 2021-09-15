#!/usr/bin/env python

from midea_inventor_lib import MideaClient

client = MideaClient("angklim@yahoo.com", "", "32f01da61d4c8a4b5ce0821030f2f9eb3026f68e2144fa30ca872c50fabbd2d7")
# MideaClient(<mail>, <password>, "") 


res = client.login()
if res == -1:
	print "Login error: please check log messages."
else:
	sessionId = client.current["sessionId"]

	appliances = {}
	appliances = client.listAppliances()
	for a in appliances:
		print "[id="+a["id"]+" type="+a["type"]+" name="+a["name"]+"]"

		if a["onlineStatus"] == "1":
			print("is online")
		else:
			print("is offline")
		if a["activeStatus"] == "1":
			print("is active")
		else:
			print("is not active")
