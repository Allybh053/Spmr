#!/bin/bash
# (C) 2021-2024 Emgraft Systems Sdn. Bhd.

# Assign variables
hostname="$1"
sourceLog="$2"
tarikh="$3"
senderIP="$4"
senderFrom="$5"
emailSubject="$6"
response="$7"
outFilename="$8"
resultemailto="$9"

# Paths to scripts
# logFile=/opt/query/query-v7.1/log2xlsx-v4.py
# subjectDecodeFile=/opt/query/query-v7.1/decodeSubject-v3.py
# postfixParser=/opt/query/query-v7.1/parsePostfixLog-v2.py
# metaGenFile=/opt/query/query-v7.1/metaGen.py
logFile=./query/log2xlsx-v4.py
subjectDecodeFile=./query/decodeSubject-v3.py
postfixParser=./query/parsePostfixLog-v2.py
metaGenFile=./query/metaGen.py
searchMore=0

# Start clean
rm -f __temp-*

# Check required files
if [[ -s "$logFile" && -s "$subjectDecodeFile" ]]; then

echo
echo "Only search first 120 chars for the Subject"
echo ""

# Convert response to lowercase
response=${response,,}
if [[ "$response" =~ ^(yes|y| ) ]] || [[ -z "$response" ]]; then
   searchMore=1
fi

# Output file paths
timestamp=$(date +%Y%m%d_%H%M%S)
# outFile="/data/reporting/query_logs/${hostname}_${outFilename}_${timestamp}.xlsx"
# outMeta="/data/reporting/query_logs/${hostname}_${outFilename}_${timestamp}.meta"
outFile="./query/output/${hostname}_${outFilename}_${timestamp}.xlsx"
outMeta="./query/output/${hostname}_${outFilename}_${timestamp}.meta"


# Start filtering
if [ -s "$sourceLog" ]; then

    currentSource="$sourceLog"

    if [ -n "$tarikh" ]; then
        echo "Searching Date..."
        grep "$tarikh" "$currentSource" > "__temp-1.txt"
        if [ ! -s "__temp-1.txt" ]; then echo "No results for date: $tarikh"; exit 1; fi
        currentSource="__temp-1.txt"
    fi

    if [ -n "$senderIP" ]; then
        echo "Searching sender IP..."
        grep -w "$senderIP" "$currentSource" > "__temp-2.txt"
        if [ ! -s "__temp-2.txt" ]; then echo "No results for IP: $senderIP"; exit 1; fi
        currentSource="__temp-2.txt"
    fi

    if [ -n "$senderFrom" ]; then
        echo "Searching From address..."
        grep -w "$senderFrom" "$currentSource" > "__temp-3.txt"
        if [ ! -s "__temp-3.txt" ]; then echo "No results for sender: $senderFrom"; exit 1; fi
        currentSource="__temp-3.txt"
    fi

    echo "Filtering for Subject lines..."
    grep "Subject:" "$currentSource" > "__temp-4.txt"
    if [ ! -s "__temp-4.txt" ]; then echo "No subject lines found"; exit 1; fi
    currentSource="__temp-4.txt"

    echo "Decoding subjects..."
    # "$subjectDecodeFile" "$currentSource" > "__temp-5.txt"
    /c/laragon/bin/python/python-3.10/python "$subjectDecodeFile" "$currentSource" > "__temp-5.txt"
    if [ ! -s "__temp-5.txt" ]; then echo "Decoded subject is empty"; exit 1; fi
    currentSource="__temp-5.txt"

    if [ -n "$emailSubject" ]; then
        shortSubject=$(echo "$emailSubject" | cut -c1-120)
        echo "Searching subject..."
        grep "$shortSubject" "$currentSource" > "__temp-6.txt"
        if [ ! -s "__temp-6.txt" ]; then echo "Subject match not found"; exit 1; fi
        currentSource="__temp-6.txt"
    fi

    # Search queue IDs if enabled
    if [ "$searchMore" = 1 ]; then
        queueIdFile="__temp-queue-id-list.txt"
        queueIdResult="__temp-queue-id-result.txt"

        if [ -s "$queueIdFile" ]; then
            echo "Searching additional data..."
            echo "├ Filtering inbound ID..."
            grep -f "$queueIdFile" "$sourceLog" > "$queueIdResult"
            echo "└ Parsing inbound ID..."
            # "$postfixParser" "$queueIdFile" "$queueIdResult"
            /c/laragon/bin/python/python-3.10/python "$postfixParser" "$queueIdFile" "$queueIdResult"
            echo
        else
            echo "Skipped search additional data"
        fi
    else
        echo "Skipped search additional data"
    fi

    # Generate output files
    echo "Generating XLSX file..."
    # "$logFile" "$hostname" "$currentSource" "$outFile"
    /c/laragon/bin/python/python-3.10/python "$logFile" "$hostname" "$currentSource" "$outFile"

    echo "Generating metadata..."
    # "$metaGenFile" "$sourceLog" "$tarikh" "$senderIP" "$senderFrom" "$emailSubject" "$outFile" "$outMeta"
    /c/laragon/bin/python/python-3.10/python "$metaGenFile" "$sourceLog" "$tarikh" "$senderIP" "$senderFrom" "$emailSubject" "$outFile" "$outMeta"

else
    echo "Source log does not exist or empty"
    exit 1
fi

# Cleanup
echo "Cleaning up..."
rm -f __temp-*

echo "Done"

# Email results if email address provided
if [[ "$resultemailto" == *"@"* ]]; then
    echo "Sending result to $resultemailto..."

    (
        echo "Subject: Query Report [$hostname - $outFilename]"
        echo "To: $resultemailto"
        echo "MIME-Version: 1.0"
        echo "Content-Type: multipart/mixed; boundary=\"BOUNDARY\""
        echo
        echo "--BOUNDARY"
        echo "Content-Type: text/plain"
        echo
        echo "Attached are the requested files for:"
        echo "- Hostname: $hostname"
        echo "- Filename: $outFilename"
        echo "- Date: $tarikh"
        echo
        echo "--BOUNDARY"
        echo "Content-Type: application/vnd.openxmlformats-officedocument.spreadsheetml.sheet; name=\"$(basename "$outFile")\""
        echo "Content-Transfer-Encoding: base64"
        echo "Content-Disposition: attachment; filename=\"$(basename "$outFile")\""
        base64 "$outFile"
        echo "--BOUNDARY"
        echo "Content-Type: text/plain; name=\"$(basename "$outMeta")\""
        echo "Content-Transfer-Encoding: base64"
        echo "Content-Disposition: attachment; filename=\"$(basename "$outMeta")\""
        base64 "$outMeta"
        echo "--BOUNDARY--"
    ) | sendmail -t

    echo "Email sent to $resultemailto"
else
    echo "No valid email detected. Skipped sending."
fi

echo ""
read -e -p "Your request has ended, press Enter to proceed..." junk

else
    echo "Nothing todo, core files missing."
    echo "Contact Emgraft Systems for support."
fi

# Upload section (commented out)
# curl --insecure --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: G86hMGVQDQEfQZMbmQSUuo' --form 'file=@"'$outFile'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.xlsx'"' 2&>/dev/null
# curl --insecure --location --request POST 'https://bmt.emgraft.com/api/datashare/file' --header 'apiKey: G86hMGVQDQEfQZMbmQSUuo' --form 'file=@"'$outMeta'"' --form 'path="/"' --form 'chunks="1"' --form 'name="'${outFilename}.meta'"' 2&>/dev/null

