# syntax=docker/dockerfile:1

FROM python:3.9.15-slim-bullseye

WORKDIR /app

COPY requirements.txt requirements.txt 

RUN pip3 install -r requirements.txt

COPY  . .

# fix str to int issue
RUN mv command.py /usr/local/lib/python3.9/site-packages/midea_beautiful/command.py
RUN mv appliance.py /usr/local/lib/python3.9/site-packages/midea_beautiful/appliance.py

CMD [ "python3", "-m", "flask", "run", "--host=0.0.0.0" ]
# CMD [ "python3", "-m", "flask", "--debug", "run", "--host=0.0.0.0" ]