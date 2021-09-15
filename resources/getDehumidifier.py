#!/usr/bin/env python

from midea_inventor_lib import MideaClient
import sys

#logging.basicConfig(level=logging.WARN)

if len(sys.argv) != 4:
	sys.exit("3 args required")

id = sys.argv[1]
login = sys.argv[2]
password = sys.argv[3]

# print({
#   'powerMode': 1,
#   'mode': 2,
#   'Filter': 0,
#   'WaterTank': 0,
#   'CurrentHumidity': 72,
#   'WindSpeed': 80,
#   'isDisplay': 1
# })


client = MideaClient(login,"",password,"","")

res = client.login()
if res == -1:
	print "Login error"
else:
	sessionId = client.current["sessionId"]
	res = client.get_device_status(id)
	if res == 1: 
		device = client.deviceStatus
		#print device.toString()
		print({
			'powerMode': device.powerMode,
			'mode': device.setMode,
			'Filter': device.filterShow,
			'WaterTank': device.tankShow,
			'CurrentHumidity': device.humidity,
			'WindSpeed': device.windSpeed,
			'isDisplay': device.isDisplay
		})
	else:
		sys.exit("Device non trouve")