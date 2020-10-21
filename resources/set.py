#!/usr/bin/env python3

from msmart.device import device as midea_device
from msmart.device import air_conditioning_device as ac
import logging
import sys
import argparse

logging.basicConfig(level=logging.DEBUG)

def intToBool(val):
	return True if val == 1 else False

def getOptions(args=sys.argv[1:]):
	parser = argparse.ArgumentParser(description='parsing des arguments, merci de passer par le plugin.')
	parser.add_argument("--ip", dest='ip', help="IP de la clim", required=True)
	parser.add_argument("--id", type=int, dest='id', help="ID de la clim", required=True)
	parser.add_argument("--port", type=int, dest='port', help="Port de la clim", required=True)
	parser.add_argument("--power_state", type=int, dest='power_state', help="changement d'état 0/1")
	parser.add_argument("--prompt_tone", type=int, dest='prompt_tone', help="Etat du bip 0/1")
	parser.add_argument("--target_temperature", type=float, dest='target_temperature', help="température désirée (float)")
	parser.add_argument("--operational_mode", dest='operational_mode', help="changement de mode (enum)")
	parser.add_argument("--fan_speed", dest='fan_speed', help="changement de vitesse (enum)")
	parser.add_argument("--swing_mode", dest='swing_mode', help="changement d'inclinaison (enum)")
	parser.add_argument("--mode_eco", type=int, dest='mode_eco', help="mode eco 0/1")
	parser.add_argument("--mode_turbo", type=int, dest='mode_turbo', help="mode turbo 0/1")
	parser.add_argument("--mode_normal", type=int, dest='mode_normal', help="mode normal 1")
	options = parser.parse_args(args)
	return options

options = getOptions(sys.argv[1:])

c = midea_device(options.ip, options.id, options.port)
device = c.setup()
device.refresh()

if options.power_state is not None:
	boolPState = intToBool(options.power_state)
	device.power_state = boolPState

if options.prompt_tone is not None:
	device.prompt_tone = intToBool(options.prompt_tone)

if options.target_temperature is not None:
	device.target_temperature = float(options.target_temperature)

if options.operational_mode is not None:
	if options.operational_mode == "auto":
		device.operational_mode = ac.operational_mode_enum.auto
	if options.operational_mode == "cool":
		device.operational_mode = ac.operational_mode_enum.cool
	if options.operational_mode == "dry":
		device.operational_mode = ac.operational_mode_enum.dry
	if options.operational_mode == "heat":
		device.operational_mode = ac.operational_mode_enum.heat
	if options.operational_mode == "fan_only":
		device.operational_mode = ac.operational_mode_enum.fan_only

if options.fan_speed is not None:
	if options.fan_speed == "Auto":
		device.fan_speed = ac.fan_speed_enum.Auto
	if options.fan_speed == "High":
		device.fan_speed = ac.fan_speed_enum.High
	if options.fan_speed == "Medium":
		device.fan_speed = ac.fan_speed_enum.Medium
	if options.fan_speed == "Low":
		device.fan_speed = ac.fan_speed_enum.Low
	if options.fan_speed == "Silent":
		device.fan_speed = ac.fan_speed_enum.Silent

if options.swing_mode is not None:
	if options.swing_mode == "Off":
		device.swing_mode = ac.swing_mode_enum.Off
	if options.swing_mode == "Vertical":
		device.swing_mode = ac.swing_mode_enum.Vertical
	if options.swing_mode == "Horizontal":
		device.swing_mode = ac.swing_mode_enum.Horizontal
	if options.swing_mode == "Both":
		device.swing_mode = ac.swing_mode_enum.Both

if options.mode_eco is not None:
	device.eco_mode = intToBool(options.mode_eco)

if options.mode_turbo is not None:
	device.turbo_mode = intToBool(options.mode_turbo)

if options.mode_normal is not None:
	device.eco_mode = False
	device.turbo_mode = False

# 	#class operational_mode_enum(Enum):
#     #    auto = 1
#     #    cool = 2
#     #    dry = 3
#     #    heat = 4
#     #    fan_only = 5

# 	#class fan_speed_enum(Enum):
#     #    Auto = 102
#     #    High = 80
#     #    Medium = 60
#     #    Low = 40
#     #    Silent = 20

# 	#class swing_mode_enum(Enum):
#     #    Off = 0x0
#     #    Vertical = 0xC
#     #    Horizontal = 0x3
#     #    Both = 0xF


# commit the changes with apply()
device.apply()
print('========== Application du changement effectue =========')
print("\r\n")
print({
	'id': device.id,
	'name': device.ip,
	'power_state': device.power_state,
	'prompt_tone': device.prompt_tone,
	'target_temperature': device.target_temperature,
	'operational_mode': device.operational_mode,
	'fan_speed': device.fan_speed,
	'swing_mode': device.swing_mode,
	'eco_mode': device.eco_mode,
	'turbo_mode': device.turbo_mode,
	'indoor_temperature': device.indoor_temperature,
	'outdoor_temperature': device.outdoor_temperature
	})