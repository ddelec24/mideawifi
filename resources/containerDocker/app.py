from flask import Flask, request, jsonify
import os, re

app = Flask(__name__)

@app.route('/')
def unused():
	return jsonify( [ {"status": "unused"} ] )

@app.route('/discover', methods = ['POST'])
def discover_applicances():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	ACCOUNT_EMAIL = request.json.get('accountmail', None)
	PASSWORD = request.json.get('accountpass', None)
	WITHAPP = request.json.get('withapp', None)

	if not ACCOUNT_EMAIL:
		return jsonify({"status": "nok", "response":"Missing params: email"}), 400
	if not PASSWORD:
		return jsonify({"status": "nok", "response":"Missing params: password"}), 400

	askwithapp = " --app MSmartHome" if WITHAPP == "y" else ""
	stream = os.popen('midea-beautiful-air-cli discover --account ' + ACCOUNT_EMAIL + ' --password ' + PASSWORD + ' --credentials' + askwithapp)
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )



@app.route('/discover_from_ip', methods = ['POST'])
def discover_applicance_from_ip():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	ACCOUNT_EMAIL = request.json.get('accountmail', None)
	PASSWORD = request.json.get('accountpass', None)
	IP_ADDRESS = request.json.get('ipaddress', None)

	if not ACCOUNT_EMAIL:
		return jsonify({"status": "nok", "response":"Missing params: email"}), 400
	if not PASSWORD:
		return jsonify({"status": "nok", "response":"Missing params: password"}), 400
	if not IP_ADDRESS:
		return jsonify({"status": "nok", "response":"Missing params: ip address"}), 400

	stream = os.popen('midea-beautiful-air-cli discover --account ' + ACCOUNT_EMAIL + ' --password ' + PASSWORD + ' --address ' + IP_ADDRESS + ' --credentials')
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )

@app.route('/appliance_status_with_token_key', methods = ['POST'])
def appliance_status_with_token_key():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	IP_ADDRESS = request.json.get('ipaddress', None)
	TOKEN = request.json.get('token', None)
	KEY = request.json.get('key', None)

	if not IP_ADDRESS:
		return jsonify({"status": "nok", "response":"Missing params: ip address"}), 400
	if not TOKEN:
		return jsonify({"status": "nok", "response":"Missing params: token"}), 400
	if not KEY:
		return jsonify({"status": "nok", "response":"Missing params: key"}), 400

	stream = os.popen('midea-beautiful-air-cli status --ip ' + IP_ADDRESS + ' --token ' + TOKEN + ' --key ' + KEY)
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )

@app.route('/appliance_status_with_account', methods = ['POST'])
def appliance_status_with_account():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	IP_ADDRESS = request.json.get('ipaddress', None)
	ACCOUNT_EMAIL = request.json.get('accountmail', None)
	PASSWORD = request.json.get('accountpass', None)

	if not ACCOUNT_EMAIL:
		return jsonify({"status": "nok", "response":"Missing params: email"}), 400
	if not PASSWORD:
		return jsonify({"status": "nok", "response":"Missing params: password"}), 400
	if not IP_ADDRESS:
		return jsonify({"status": "nok", "response":"Missing params: ip address"}), 400

	stream = os.popen('midea-beautiful-air-cli status --ip ' + IP_ADDRESS + ' --account ' + ACCOUNT_EMAIL + ' --password ' + PASSWORD)
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )

@app.route('/appliance_status_with_id_and_account_cloud', methods = ['POST'])
def appliance_status_with_id_account_cloud():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	APPLIANCE_ID = request.json.get('applianceid', None)
	ACCOUNT_EMAIL = request.json.get('accountmail', None)
	PASSWORD = request.json.get('accountpass', None)

	if not APPLIANCE_ID:
		return jsonify({"status": "nok", "response":"Missing params: id appliance"}), 400
	if not ACCOUNT_EMAIL:
		return jsonify({"status": "nok", "response":"Missing params: email"}), 400
	if not PASSWORD:
		return jsonify({"status": "nok", "response":"Missing params: password"}), 400

	stream = os.popen('midea-beautiful-air-cli status --id ' + APPLIANCE_ID + ' --account ' + ACCOUNT_EMAIL + ' --password ' + PASSWORD + ' --cloud')
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )


@app.route('/set_appliance_attribute', methods = ['POST'])
def set_appliance_attribute():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	IP_ADDRESS = request.json.get('ipaddress', None)
	TOKEN = request.json.get('token', None)
	KEY = request.json.get('key', None)
	COMMANDS = request.json.get('commands', None)
    
	if not IP_ADDRESS:
		return jsonify({"status": "nok", "response":"Missing params: ip address"}), 400
	if not TOKEN:
		return jsonify({"status": "nok", "response":"Missing params: token"}), 400
	if not KEY:
		return jsonify({"status": "nok", "response":"Missing params: key"}), 400
	if not COMMANDS:
		return jsonify({"status": "nok", "response":"Missing params: command"}), 400

	stream = os.popen('midea-beautiful-air-cli set --ip ' + IP_ADDRESS + ' --token ' + TOKEN + ' --key ' + KEY + ' ' + COMMANDS)
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )

@app.route('/set_appliance_attribute_with_account', methods = ['POST'])
def set_appliance_attribute_with_account():
	if not request.is_json:
		return jsonify({"status": "nok", "response": "invalid json post"}), 400

	APPLIANCE_ID = request.json.get('applianceid', None)
	ACCOUNT_EMAIL = request.json.get('accountmail', None)
	PASSWORD = request.json.get('accountpass', None)
	COMMANDS = request.json.get('commands', None)

	if not APPLIANCE_ID:
		return jsonify({"status": "nok", "response":"Missing params: id appliance"}), 400
	if not ACCOUNT_EMAIL:
		return jsonify({"status": "nok", "response":"Missing params: email"}), 400
	if not PASSWORD:
		return jsonify({"status": "nok", "response":"Missing params: password"}), 400
	if not COMMANDS:
		return jsonify({"status": "nok", "response":"Missing params: command"}), 400

	stream = os.popen('midea-beautiful-air-cli set --id ' + APPLIANCE_ID + ' --account ' + ACCOUNT_EMAIL + ' --password ' + PASSWORD + ' ' + COMMANDS + ' --cloud')
	output = stream.read()

	if not output:
		return jsonify( {"status": "nok", "response": "no response from cmd"} ), 400

	return jsonify( {"status": "ok", "response": output} )