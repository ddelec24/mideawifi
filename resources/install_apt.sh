#! /bin/bash

PROGRESS_FILE=/tmp/mideawifi;
if [ ! -z $1 ]; then
    PROGRESS_FILE=$1
fi

DISCOVER_TIME=2;
if [ ! -z $2 ]; then
    DISCOVER_TIME=$2
fi

echo 0 > ${PROGRESS_FILE}

echo "********************************************************"
echo "* Install dependancies                                 *"
echo "********************************************************"
echo "> Progress file: " ${PROGRESS_FILE}
echo "*"
echo "* Update repository"
echo "*"
apt-get update
echo 25 > ${PROGRESS_FILE}

echo "*"
echo "* Install python3.7 and pip3"
echo "*"
apt-get -y install  python3.7 python3-pip

echo 50 > ${PROGRESS_FILE}

echo "*"
echo "* Install pip3 required modules"
echo "*"
pip3 install -I msmart==0.1.23
# click va etre mis dans les require a la prochaine version msmart, a surveiller
pip3 install click

echo 75 > ${PROGRESS_FILE}
echo "*"
echo "* Updating discover duration"
echo "*"

MSMART=`pip3 show msmart | grep 'Location:'`
LOCATION=${MSMART#*: }
sed -i "56s/range\(([0-9]*)\)/range\($DISCOVER_TIME\)/" "$LOCATION/msmart/cli.py"

rm ${PROGRESS_FILE}

echo "************************************************"
echo "*             End dependancy installation              *"
echo "************************************************"
