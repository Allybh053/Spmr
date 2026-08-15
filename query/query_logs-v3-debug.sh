#!/bin/bash
#(C) 2021-2024 Emgraft Systems Sdn. Bhd.

logFile=/opt/query/current/log2xlsx-v3.py
subjectDecodeFile=/opt/query/current/decodeSubject-v2.py
postfixParser=/opt/query/current/parsePostfixLog-v2-test.py
metaGenFile=/opt/query/current/metaGen.py
searchMore=0

# Start clean 
sudo rm -f __temp-*

# Check and begin
if [[ -s $logFile && -s $subjectDecodeFile ]];
then
read -e -p "Source log path: " -i /var/log/mail.log sourceLog
echo
echo "Enter search filters (leave blank to skip)" 
read -e -p "Date (e.g. Jan  5): " -i "Jan  4" tarikh
read -e -p "Sender IP (e.g. 10.1.2.3): " -i "10.28.20.79" senderIP
read -e -p "FROM or TO address (e.g. admin@example.com): " -i peperiksaan@spa.gov.my senderFrom
echo ""
echo "Only search first 120 chars for the Subject"
read -p "Email subject: " emailSubject
echo ""

echo "Search more data (MTA Outbound ID/IP, Status, etc) in log file? (Note: This might take a long time depending on your search query)"
read -r -p "[Y/n]: " response
echo ""

read -e -p "Output file name without ext (e.g. 210202): " outFilename
echo "Limited to max 1,048,576 rows of output..."
echo ""

# Check user input for more data
response=${response,,} # tolower
if [[ $response =~ ^(yes|y| ) ]] || [[ -z $response ]]; then
   searchMore=1
fi

# Variables
outFile="/data/reports/${outFilename}.xlsx"
outMeta="/data/reports/${outFilename}.meta"
shortSubject=`echo "$emailSubject" | cut -c1-120`
filter1="Subject:"
searchCount=0
queueIdFile="__temp-queue-id-list.txt"
queueIdResult="__temp-queue-id-result.txt"
tarikhResult="__temp-tarikh-result.txt"
senderIpResult="__temp-sender-ip-result.txt"
senderResult="__temp-sender-result.txt"


getSource () {
	if [ $searchCount -gt 0 ]
		then
			sudo echo "__temp-$searchCount.txt"
	else
		echo $sourceLog
			fi
}

getOutput() {
	inc
		sudo echo "__temp-$searchCount.txt"
}

inc() {
	searchCount=$((searchCount+1))
}

if [ -s "$sourceLog" ]
then

# Filter only with subjects
#echo "Reading log file..."
#sudo egrep "$filter1" "`getSource`" > $(getOutput)
#inc

# Date section	
if ! [ -z "$tarikh" ] 
then
echo "Searching Date..."
#sudo egrep "$tarikh" "`getSource`" > $(getOutput)
sudo fgrep "$tarikh" "`getSource`" > $(getOutput)

inc
else
echo "Skipped searching date"
fi

# IP section      
if ! [ -z "$senderIP" ]
then
echo "Searching sender IP..."
#sudo egrep -w "$senderIP" "`getSource`" > $(getOutput)
sudo fgrep -w "$senderIP" "`getSource`" > $(getOutput)
inc
else
echo "Skipped searching sender IP"
fi

# FROM section      
if ! [ -z "$senderFrom" ]
then
echo "Searching From address..."
sudo egrep -w "$senderFrom" "`getSource`" > $(getOutput)
inc
else
echo "Skipped searching From address"
fi

# Filter only with subjects
echo "Filtering log file..."
sudo egrep "$filter1" "`getSource`" > $(getOutput)
inc

# Decode subject section
if [ -s "`getSource`" ]
then
	echo "Decoding subjects..."
sudo $subjectDecodeFile `getSource` > $(getOutput)
	inc
	else
	echo "Empty result for specified Date/IP/From combination"
	fi

# Subject section
	if ! [ -z "$emailSubject" ]
	then
	if [ -s "`getSource`" ]
	then
	echo "Searching subject..."
	sudo egrep "$shortSubject" "`getSource`" > $(getOutput)
	inc
	else
	echo "Empty result for decoded subject"
	fi
	else
	echo "Skipped searching email subject"
	fi

# Search all queue id
if [ $searchMore = 1 ]
then
        if [ -s "$queueIdFile" ]
        then
                echo "Searching additional data..."
                echo "├ Filtering inbound ID..."
                sudo egrep -f "$queueIdFile" "$sourceLog" > "$queueIdResult"
		sudo egrep "$tarikh" "$queueIdResult" > "$tarikhResult"
		#sudo egrep -f "$queueIdFile" "$tarikhResult" > "$queueIdResult"
		echo "└ Parsing inbound ID..."
                #sudo $postfixParser "$queueIdFile" "$queueIdResult"
		sudo $postfixParser "$queueIdFile" "$tarikhResult"
		echo
        else
                echo "Skipped search additional data"
        fi
else
        echo "Skipped search additional data"
fi


# Output section
	if [ -s "`getSource`" ]
	then
	echo "Generating XLSX file..."
	sudo $logFile "`getSource`" "$outFile"
	else
	echo "Empty results - no output generated"
	fi

	else
	echo "Source log does not exist or empty"
	exit 1
	fi

# Generating Meta
	#echo -e "SourceLog=${sourceLog}\n\rDate=${tarikh}\n\rSenderIP=${senderIP}\n\rFrom=${senderFrom}\n\rSubject=${emailSubject}\n\rOutputFile=${outFile}" > $outMeta
	sudo $metaGenFile "$sourceLog" "$tarikh" "$senderIP" "$senderFrom" "$emailSubject" "$outFile" "$outMeta"

# Cleaning up section
	echo "Cleaning up..."
	sudo rm -f __temp-*

	echo "Done"
	else
	echo "Nothing todo, core files missing."
	echo "Contact Emgraft Systems for support."
	fi

# Upload section
	curl --insecure --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: G86hMGVQDQEfQZMbmQSUuo' --form 'file=@"'$outFile'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.xlsx'"' 2&>/dev/null
	curl --insecure --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: G86hMGVQDQEfQZMbmQSUuo' --form 'file=@"'$outMeta'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.meta'"' 2&>/dev/null

	#curl --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: lOFUE3PJICGYoBHsc1EDzu' --form 'file=@"'$outFile'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.xlsx'"' 2&>/dev/null
        #curl --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: lOFUE3PJICGYoBHsc1EDzu' --form 'file=@"'$outMeta'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.meta'"' 2&>/dev/null

	echo ""
	read -e -p "Your request has ended, press Enter to proceed..." junk
