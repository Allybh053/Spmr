#!/usr/bin/env python3

import re
import sys
from email.header import decode_header

queue_id_regex = "\s{1}([A-Za-z0-9]{8,}):" ## new


def decode_subject():
    if len(sys.argv) > 1:
        file1 = open(sys.argv[1], 'r')
        lines = file1.readlines()
    else:
        lines = []
        for line in sys.stdin:
            lines.append(line)

    decoded_lines = []
    queue_id_list = []
    for line in lines:
        encoded_combined_list = re.findall("Subject:(.*?)\s+(from unknown\[\d+\.\d+\.\d+\.\d+\]\;|from)", line)
        if encoded_combined_list:
            encoded_combined_subject = encoded_combined_list[0][0]
            encoded_list = re.findall("(=\?.*?\?=)", encoded_combined_subject)
            if len(encoded_list) > 0:
                decoded_subject = ""
                for encodedLine in encoded_list:
                    text, encoding = decode_header(encodedLine)[0]
                    try:
                        decoded_subject += text.decode('utf-8')
                    except:
                        decoded_subject += str(text)
                decoded_line = line.replace(encoded_combined_subject, decoded_subject)
                decoded_lines.append(decoded_line.strip())
            else:
                decoded_lines.append(line.strip())
            queue_id_re = re.findall(queue_id_regex, line)
            if len(queue_id_re) > 0:
                queue_id_list.append(queue_id_re[0])
        else:
            print("No match! Line: " + line)

    text_file = open("__temp-queue-id-list.txt", "w")
    count = 0
    for line in decoded_lines:
        if count > 0:
            text_file.write("\n")
        text_file.write(queue_id_list[count].strip())
        count = count + 1
        print(line)


if __name__ == '__main__':
    decode_subject()
