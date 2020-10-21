#!/usr/bin/env python3

from msmart.device import device as midea_device
from msmart.device import air_conditioning_device as ac
import logging
import sys

logging.basicConfig(level=logging.WARN)

if len(sys.argv) != 3:
	sys.exit("2 args required")

ip = sys.argv[1]
id = int(sys.argv[2])


c = midea_device(ip, id, 6444)
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