#!/usr/bin/env python3

from msmart.device import device as midea_device
from msmart.device import air_conditioning_device as ac
import logging
import sys

logging.basicConfig(level=logging.WARN)

if len(sys.argv) != 4:
	sys.exit("3 args required")

ip = sys.argv[1]
id = int(sys.argv[2])
port = int(sys.argv[3])


c = midea_device(ip, id, port)
device = c.setup()
# Refresh the object with the actual state by querying it
device.refresh()
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