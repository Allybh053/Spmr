#!/usr/bin/env python3
import sys

sourceLog = sys.argv[1]
date = sys.argv[2]
senderIP = sys.argv[3]
from_address = sys.argv[4]
subject = sys.argv[5]
outputFile = sys.argv[6]
outMetaName = sys.argv[7]


metaString = '''SourceLog={}
Date={}
SenderIP={}
From={}
Subject={}
OutputFile={}
'''.format(sourceLog, date, senderIP, from_address, subject, outputFile)


with open(outMetaName, "w+") as f:
    f.write(metaString)
