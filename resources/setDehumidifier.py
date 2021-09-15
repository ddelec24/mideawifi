#!/usr/bin/env python

from midea_inventor_lib import MideaClient

import logging
import sys
import argparse

logging.basicConfig(level=logging.DEBUG)

def intToBool(val):
	return True if val == 1 else False

def getOptions(args=sys.argv[1:]):
	parser = argparse.ArgumentParser(description='parsing des arguments, merci de passer par le plugin.')
	parser.add_argument("--id", type=int, dest='deviceId', help="ID de la clim", required=True)
	parser.add_argument("--login", dest='login', help="mail cloud", required=True)
	parser.add_argument("--password", dest='password', help="mot de passe cloud", required=True)
	parser.add_argument("--power_mode", type=int, dest='power_mode', help="changement d'etat 0/1")
	parser.add_argument("--mode", type=int, dest='mode', help="changement de mode")
	parser.add_argument("--target_humidity", type=int, dest='target_humidity', help="humidite desiree")
	parser.add_argument("--wind_speed", type=int, dest='wind_speed', help="changement de vitesse ")
	options = parser.parse_args(args)
	return options

options = getOptions(sys.argv[1:])

client = MideaClient(options.login,"",options.password,"","")
res = client.login()

deviceId = options.deviceId

if res == -1:
	print "Login error"
else:
	sessionId = client.current["sessionId"]

res = client.get_device_status(deviceId)
if res == 1:
	device = client.deviceStatus
else:
	sys.exit("Device non trouve")
print "setdeshumidificateur en route"
print options.power_mode
if options.power_mode is not None:
	if options.power_mode == 1:
		client.send_poweron_command(deviceId)
	else:
		client.send_poweroff_command(deviceId)

	
# modeStr = Mode [1..4] (1: TARGET_MODE, 2: CONTINUOUS_MODE, 3: SMART_MODE, 4: DRYER_MODE)
if options.mode is not None:
	if device.powerMode == 0: # pas d'action si appareil coupe
		print "Equipement eteint"
	elif options.mode > 0 and options.mode < 5:
		client.send_mode_command(deviceId, options.mode)
	else:
		print "le mode doit etre numerique, 1: TARGET_MODE, 2: CONTINUOUS_MODE, 3: SMART_MODE, 4: DRYER_MODE"
		
# Speed [0..100] (silent:40, medium:60, high:80)
if options.wind_speed is not None:
	if device.powerMode == 0: # pas d'action si appareil coupe
		print "Equipement eteint"
	elif options.wind_speed > 0 and options.wind_speed < 100:
		client.send_fan_speed_command(deviceId, options.wind_speed)
	else:
		print "la vitesse doit etre numerique, Speed [0..100] (silent:40, medium:60, high:80)"

# Target Humidity [30..70]
if options.target_humidity is not None:
	if device.powerMode == 0: # pas d'action si appareil coupe
		print "Equipement eteint"
	elif options.target_humidity >= 30 and options.target_humidity <= 70 :
		client.send_target_humidity_command(deviceId, options.target_humidity)
	else:
		print "l'humidite doit etre numerique,Target Humidity [30..70]"



print '========== Application du changement effectue ========='
print "\r\n"

print client.deviceStatus.toString()