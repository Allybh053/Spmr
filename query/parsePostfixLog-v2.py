#!/usr/bin/env python3

import sys
import re
import datetime
import pickle


class edata:
    def __init__(self, queueId, timestamp_list, rcpt_list, status_list, message_list, relay_list, attachment_list):
        self.queueId = queueId
        self.timestamp_list = timestamp_list
        self.rcpt_list = rcpt_list
        self.status_list = status_list
        self.message_list = message_list
        self.relay_ip_list = relay_list
        self.attachment_list = attachment_list


combined_regex = "(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}\+\d{2}:\d{2}).*?smtp.*?:\s{1}(.*?):\s{1}to=<(.*?)>.*?relay=.*?\[(.*?)\].*status=(.*?)\s+\((.*)\)" ## new
iw_message_id_regex = "message-id=<([^>]+)>" ## new
attachment_regex = "(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}\+\d{2}:\d{2}).*?cleanup.*?:\s{1}(.*?):.*?Content-Disposition:.*?filename=\"(.*?)\"" ## new

# Compile regex
combined_cre = re.compile(combined_regex)
attachment_cre = re.compile(attachment_regex)
iw_cre = re.compile(iw_message_id_regex)

all_item = {}


def parse():
    file1 = open(sys.argv[1], 'r')
    queueIdList = file1.readlines()

    file2 = open(sys.argv[2], 'r')
    lines = file2.readlines()

    total_id = len(queueIdList)
    print("Total ID: {0}".format(total_id))

    current_id = 0
    skip_lines = {}
    for queueId in queueIdList:
        current_id = current_id + 1
        queueId = queueId.strip()
        rcpt_list = []
        status_list = []
        status_message_list = []
        timestamp_list = []
        relay_ip_list = []
        attachment_list = []
        total_progress = (current_id / total_id) * 100
        print("\rProgress: {:.2f}% ({} / {})".format(total_progress, current_id, total_id), end=" ")

        current_line = 0
        for line in lines:

            # Skipped processed lines
            if current_line in skip_lines:
                current_line = current_line + 1
                continue

           #if "{0}: removed".format(queueId) in line:
           #     skip_lines[current_line] = None
           #     break

            if queueId in line:
                # Find combined_re
                # combined_re = re.findall(combined_regex, line)
                combined_re = combined_cre.findall(line)
                if len(combined_re) > 0:
                    timestamp_list.append(datetime.datetime.strptime(combined_re[0][0], '%Y-%m-%dT%H:%M:%S.%f%z')) ## new
                    current_queue_id = combined_re[0][1].strip() ## new
                    if current_queue_id == queueId:
                        rcpt_list.append(combined_re[0][2].strip()) ## new
                        relay_ip_list.append(combined_re[0][3].strip()) ## new
                        status_list.append(combined_re[0][4].strip()) ## new
                        status_message_list.append(if_messageId_exists(combined_re[0][5].strip())) ## new

                ## Find attachment
                attachment_re = attachment_cre.findall(line)
                if len(attachment_re) > 0:
                    current_queue_id = attachment_re[0][1].strip() ## new
                    if current_queue_id == queueId:
                        attachment_list.append(attachment_re[0][2].strip()) ## new

                skip_lines[current_line] = None

            current_line = current_line + 1
        if len(status_list) > 0:
            all_item[queueId] = edata(queueId, timestamp_list, rcpt_list, status_list, status_message_list, relay_ip_list, attachment_list)


def if_messageId_exists(status_message):
    # iw_re = re.findall(iw_message_id_regex, status_message)
    iw_re = iw_cre.findall(status_message)
    if iw_re:
        return iw_re[0]
    else:
        return status_message


if __name__ == '__main__':
    parse()
    f = open('__temp-objects.bin', 'wb')
    pickle.dump(all_item, f)
    f.close()
    print()

