#!/usr/bin/env python3

import sys
import re
import xlsxwriter
from datetime import datetime
from os import path
import pickle

timestamp_regex = "(\d{4}-\d{2}-\d{2}T\d{2}:\d{2}:\d{2}\.\d{6}\+\d{2}:\d{2})"
queue_id_regex = "\s{1}([A-Za-z0-9]{8,}):"
email_from_regex = "(from=)<([0-9a-zA-Z_+-@\.]+)>"
email_to_regex = "(to=|[^=])<([0-9a-zA-Z_+-@\.]+)>"
email_subject_regex = "Subject:(.*?)\s+(from unknown\[\d+\.\d+\.\d+\.\d+\]\;|from)"
sender_ip_regex = "from.{1}.*?\[(.*?)\]\;|from.{1}.*?(.*?)\;"
temp_object_filename = "__temp-objects.bin"
extra_metadata = {}


class edata:
    def __init__(self, queueId, timestamp_list, rcpt_list, status_list, message_list, relay_list, attachment_list):
        self.queueId = queueId
        self.timestamp_list = timestamp_list
        self.rcpt_list = rcpt_list
        self.status_list = status_list
        self.message_list = message_list
        self.relay_ip_list = relay_list
        self.attachment_list = attachment_list


class XlsxRow:
    def __init__(self, index, hostname, timestamp, queue_id, email_from, email_to,
                 subject, error_msg, status, sender_ip,
                 outbound_id, receiver_ip, attachments):
        self.index = index
        self.hostname = hostname
        self.timestamp = timestamp
        self.queue_id = queue_id
        self.email_from = email_from
        self.email_to = email_to
        self.subject = subject
        self.error_msg = error_msg
        self.status = status
        self.sender_ip = sender_ip
        self.outbound_id = outbound_id
        self.receiver_ip = receiver_ip
        self.attachments = attachments

    def to_array(self):
        return [self.hostname, self.index, self.timestamp, self.sender_ip,
                self.queue_id, self.status, self.receiver_ip,
                self.outbound_id, self.email_from, self.email_to,
                self.subject, self.attachments, self.error_msg]


def generate_xlsx(outfile, rows):
    this_workbook = xlsxwriter.Workbook(outfile)
    worksheet = this_workbook.add_worksheet()

    wrap_text = this_workbook.add_format({'text_wrap': True, 'valign': 'vcenter'})
    center_middle = this_workbook.add_format({'valign': 'vcenter'})
    center_all = this_workbook.add_format({'align': 'center', 'valign': 'vcenter'})

    worksheet.add_table('A1:M' + str(1 + len(rows)), {'data': rows,
                                                      'columns': [
                                                          {'header': 'Hostname'},
                                                          {'header': 'No.'},
                                                          {'header': 'Timestamp'},
                                                          {'header': 'MTA Sender IP'},
                                                          {'header': 'Inbound ID'},
                                                          {'header': 'Status'},
                                                          {'header': 'MTA Receiver IP'},
                                                          {'header': 'Outbound ID'},
                                                          {'header': 'FROM Address'},
                                                          {'header': 'TO Address'},
                                                          {'header': 'Subject'},
                                                          {'header': 'Attachments'},
                                                          {'header': 'Remarks'},
                                                      ]})
    worksheet.set_column('A:A', 15, center_all)
    worksheet.set_column('B:B', 5, center_all)
    worksheet.set_column('C:C', 13, wrap_text)
    worksheet.set_column('D:D', 15, center_middle)
    worksheet.set_column('E:E', 12, center_middle)
    worksheet.set_column('F:F', 10, wrap_text)
    worksheet.set_column('G:G', 15, wrap_text)
    worksheet.set_column('H:H', 22, wrap_text)
    worksheet.set_column('I:I', 30, center_middle)
    worksheet.set_column('J:J', 30, wrap_text)
    worksheet.set_column('K:K', 60, center_middle)
    worksheet.set_column('L:L', 60, wrap_text)
    worksheet.set_column('M:M', 60, center_middle)

    this_workbook.close()


def safe_open(filepath):
    try:
        return open(filepath, 'r', encoding='utf-8')
    except UnicodeDecodeError:
        print("[WARNING] UTF-8 decode failed. Retrying with latin1...")
        return open(filepath, 'r', encoding='latin1')


def populate_data(excelRows, hostname, error_message):
    global extra_metadata
    input_path = sys.argv[1]

    def try_open_file(path):
        try:
            return open(path, 'r', encoding='utf-8'), 'utf-8'
        except UnicodeDecodeError:
            print("[WARNING] UTF-8 decode failed. Retrying with latin1...")
            try:
                return open(path, 'r', encoding='latin1'), 'latin1'
            except Exception as e:
                print(f"[ERROR] Failed reading file (latin1): {e}")
                return None, None
        except Exception as e:
            print(f"[ERROR] Failed reading file (utf-8): {e}")
            return None, None

    file1, encoding_used = try_open_file(input_path)
    if not file1:
        return

    try:
        total_lines = sum(1 for _ in file1)
    except Exception as e:
        print(f"[ERROR] Could not count lines: {e}")
        file1.close()
        return
    file1.close()

    if path.exists(temp_object_filename):
        with open(temp_object_filename, 'rb') as tObject:
            extra_metadata = pickle.load(tObject)

    print("Total lines:", total_lines)

    file1, _ = try_open_file(input_path)
    if not file1:
        return

    count = 1

    for raw_line in file1:
        try:
            line = raw_line.encode('utf-8', errors='replace').decode('utf-8')
        except Exception:
            print("[WARNING] Skipping unreadable line")
            continue

        timestamp_obj = datetime(1970, 1, 1)
        queue_id = "-"
        email_from = "-"
        email_to = "-"
        email_subject = "-"
        sender_ip = "-"

        try:
            timestamp_re = re.findall(timestamp_regex, line)
            timestamp = timestamp_re[0]
            timestamp_obj = datetime.strptime(timestamp, '%Y-%m-%dT%H:%M:%S.%f%z')
        except:
            pass

        try:
            queue_id_re = re.findall(queue_id_regex, line)
            queue_id = queue_id_re[0]
        except:
            pass

        try:
            email_from_re = re.findall(email_from_regex, line)
            email_from = email_from_re[0][1]
        except:
            pass

        try:
            email_to_re = re.findall(email_to_regex, line)
            email_to_list = [t[1] for t in email_to_re]
            email_to = "\n".join(email_to_list)
        except:
            pass

        try:
            email_subject_re = re.findall(email_subject_regex, line)
            email_subject = email_subject_re[0][0]
        except:
            pass

        try:
            sender_ip_re = re.findall(sender_ip_regex, line)
            if sender_ip_re[0][0]:
                sender_ip = sender_ip_re[0][0]
            elif sender_ip_re[0][1]:
                sender_ip = sender_ip_re[0][1]
        except:
            pass

        if queue_id in extra_metadata:
            additional_data = extra_metadata[queue_id]
            total = len(additional_data.timestamp_list)
            for i in range(total):
                timestamp_final = additional_data.timestamp_list[i].strftime("%b %d %H:%M:%S")
                status = additional_data.status_list[i] if i < len(additional_data.status_list) else "-"
                outbound_id = additional_data.message_list[i] if i < len(additional_data.message_list) else "-"
                receiver_ip = additional_data.relay_ip_list[i] if i < len(additional_data.relay_ip_list) else "-"
                email_to = additional_data.rcpt_list[i] if i < len(additional_data.rcpt_list) else "-"
                attachments = additional_data.attachment_list[i] if i < len(additional_data.attachment_list) else "-"

                excelRows.append(XlsxRow(count, hostname, timestamp_final, queue_id.strip(),
                                         email_from.strip(), email_to.strip(), email_subject.strip(),
                                         error_message.strip(), status.strip(), sender_ip.strip(),
                                         outbound_id.strip(), receiver_ip.strip(), attachments.strip()).to_array())
                count += 1
        else:
            timestamp_final = timestamp_obj.strftime("%b %d %H:%M:%S")
            excelRows.append(XlsxRow(count, hostname, timestamp_final, queue_id.strip(),
                                     email_from.strip(), email_to.strip(), email_subject.strip(),
                                     error_message.strip(), "-", sender_ip.strip(),
                                     "-", "-", "-").to_array())
            count += 1

        if count % 50 == 0:
            calc_first = (count / total_lines) * 100
            print("\r{:.2f}%".format(min(calc_first, 100.0)), end=" ")

    file1.close()



if __name__ == '__main__':
    if len(sys.argv) < 4:
        print("Usage: log2xlsx-v4.py <input_log_file> <output_xlsx> <hostname> [resultEmailTo]")
        exit(1)

    input_file = sys.argv[1]
    outfile = sys.argv[2]
    hostname = sys.argv[3]
    result_email_to = sys.argv[4] if len(sys.argv) > 4 else ""

    excelRows = []
    populate_data(excelRows, hostname, "")

    if not excelRows:
        print("[WARNING] No matching data found, creating placeholder row...")
        excelRows.append(["-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "-", "No matching data found"])

    generate_xlsx(outfile, excelRows)

    print(f"\nCompleted - Check output file \"{outfile}\"")
    if result_email_to:
        print(f"(Optional) Result to be emailed to: {result_email_to}")
    exit(0)
