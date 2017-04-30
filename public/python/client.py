__author__ = 'tanel'

import argparse
from ws4py.client.threadedclient import WebSocketClient
import time
import threading
import sys
import urllib
import queue
import json
import time
import os

def rate_limited(maxPerSecond):
    minInterval = 1.0 / float(maxPerSecond)
    def decorate(func):
        lastTimeCalled = [0.0]
        def rate_limited_function(*args,**kargs):
            elapsed = time.clock() - lastTimeCalled[0]
            leftToWait = minInterval - elapsed
            if leftToWait>0:
                time.sleep(leftToWait)
            ret = func(*args,**kargs)
            lastTimeCalled[0] = time.clock()
            return ret
        return rate_limited_function
    return decorate


class MyClient(WebSocketClient):

    def __init__(self, filename, url, protocols=None, extensions=None, heartbeat_freq=None, byterate=32000,
                 save_adaptation_state_filename=None, send_adaptation_state_filename=None):
        super(MyClient, self).__init__(url, protocols, extensions, heartbeat_freq)
        self.final_hyps = []
        self.fn = filename
        self.byterate = byterate
        self.final_hyp_queue = queue.Queue()
        self.save_adaptation_state_filename = save_adaptation_state_filename
        self.send_adaptation_state_filename = send_adaptation_state_filename

    @rate_limited(4)
    def send_data(self, data):
        self.send(data, binary=True)

    def opened(self):
        #print "Socket opened!"
        def send_data_to_ws():
            f = open(self.fn, "rb")
            if self.send_adaptation_state_filename is not None:
                print >> sys.stderr, "Sending adaptation state from %s" % self.send_adaptation_state_filename
                try:
                    adaptation_state_props = json.load(open(self.send_adaptation_state_filename, "r"))
                    self.send(json.dumps(dict(adaptation_state=adaptation_state_props)))
                except:
                    e = sys.exc_info()[0]
                    print >> sys.stderr, "Failed to send adaptation state: ",  e
            for block in iter(lambda: f.read(self.byterate//4), ""):
                self.send_data(block)
            print >> sys.stderr, "Audio sent, now sending EOS"
            self.send("EOS")

        t = threading.Thread(target=send_data_to_ws)
        t.start()


    def received_message(self, m):
        response = json.loads(str(m))
        #print >> sys.stderr, "RESPONSE:", response
        #print >> sys.stderr, "JSON was:", m
        if response['status'] == 0:
            if 'result' in response:
                trans = response['result']['hypotheses'][0]['transcript']
                if response['result']['final']:
                    #print >> sys.stderr, trans,
                    self.final_hyps.append(trans)
                    # print >> sys.stderr, '\r%s' % trans.replace("\n", "\\n")
                else:
                    print_trans = trans.replace("\n", "\\n")
                    if len(print_trans) > 80:
                        print_trans = "... %s" % print_trans[-76:]
                    # print >> sys.stderr, '\r%s' % print_trans,
            if 'adaptation_state' in response:
                if self.save_adaptation_state_filename:
                    # print >> sys.stderr, "Saving adaptation state to %s" % self.save_adaptation_state_filename
                    with open(self.save_adaptation_state_filename, "w") as f:
                        f.write(json.dumps(response['adaptation_state']))
        #else:
            # print >> sys.stderr, "Received error from server (status %d)" % response['status']
            #if 'message' in response:
            #    print >> sys.stderr, "Error message:",  response['message']


    def get_full_hyp(self, timeout=60):
        return self.final_hyp_queue.get(timeout)

    def closed(self, code, reason=None):
        #print "Websocket closed() called"
        #print >> sys.stderr
        self.final_hyp_queue.put(" ".join(self.final_hyps))


def main():

    parser = argparse.ArgumentParser(description='Command line client for kaldigstserver')
    parser.add_argument('-u', '--uri', default="ws://localhost:8888/client/ws/speech", dest="uri", help="Server websocket URI")
    parser.add_argument('-r', '--rate', default=32000, dest="rate", type=int, help="Rate in bytes/sec at which audio should be sent to the server. NB! For raw 16-bit audio it must be 2*samplerate!")
    parser.add_argument('--save-adaptation-state', help="Save adaptation state to file")
    parser.add_argument('--send-adaptation-state', help="Send adaptation state from file")
    parser.add_argument('--content-type', default='', help="Use the specified content type (empty by default, for raw files the default is  audio/x-raw, layout=(string)interleaved, rate=(int)<rate>, format=(string)S16LE, channels=(int)1")
    parser.add_argument('audiofile', help="Audio file to be sent to the server")
    args = parser.parse_args()

    content_type = args.content_type
    if content_type == '' and args.audiofile.endswith(".raw"):
        content_type = "audio/x-raw, layout=(string)interleaved, rate=(int)%d, format=(string)S16LE, channels=(int)1" %(args.rate/2)

    ws = MyClient(args.audiofile, args.uri + '?%s' % (urllib.parse.urlencode([("content-type", content_type)])), byterate=args.rate,
                  save_adaptation_state_filename=args.save_adaptation_state, send_adaptation_state_filename=args.send_adaptation_state)
    ws.connect()
    result = ws.get_full_hyp()
    print(json.dumps(get_task([result]+result.split())))
    # print(result)

def get_task(s):

    if 'พฤหัสบดี' in s:
        s.remove('พฤหัสบดี')
        s.append('พฤหัส')

    day = ['จันทร์', 'อังคาร', 'พุธ', 'พฤหัส', 'ศุกร์']
    time = ['เช้า', 'บ่าย']
    num = ['หนึ่ง', 'สอง', 'สาม', 'สี่', 'ห้า', 'หก', 'เจ็ด', 'แปด', 'เก้า', 'สิบ']
    course = ['ดิจิตอลโฟโต้', 'ฟูดไซอาร์ท', 'พารากราฟไรติ้ง', 'เวทคอนโทรล', 'เพอร์ซันแนลไฟแนนซ์', 'อินโทรแพค']

    day_eng = ['mon','tue','wed','thu','fri']
    time_eng = ['am','pm']
    course_eng = ['digital_photo','food_sci_art','paragraph_writing','weight_control','personal_finance','intro_pack']

    task = ['0' for i in range(9)]
    
    task[0] = '1' if 'ยืนยัน' in s else '0'
    task[1] = '1' if 'ยกเลิก' in s else '0'
    task[2] = str(len(set(s)&set(day)))
    task[3] = str(len(set(s)&set(time)))
    cc = [c for c in course if c in ''.join(s)]
    task[4] = str(len(cc))
    task[5] = str(len(set(s)&set(num)))
    task[6] = '1' if 'ลง' in s or 'ลงทะเบียน' in s else '0'
    task[7] = '1' if 'ถอน' in s else '0'
    task[8] = '1' if 'ดู' in s or 'แสดง' in s else '0'

    ss = ''.join(task)

    if ss == '100000000': return [1,1,1]
    if ss == '010000000': return [2,2,2]
    if ss == '001100000' or ss == '001100100': return [3,day_eng[day.index(list(set(s)&set(day))[0])],time_eng[time.index(list(set(s)&set(time))[0])]]
    if ss == '000010000' or ss == '000010100': return [4,course_eng[course.index(cc[0])],course_eng[course.index(cc[0])]]
    if ss == '000011100': return [5,course_eng[course.index(cc[0])],num.index(list(set(s)&num)[0])+1]
    if ss == '000000100' or ss == '000000001' or ss == '000000101': return [6,6,6]
    if ss == '000010010': return [7,course_eng[course.index(cc[0])],course_eng[course.index(cc[0])]]

    return [9,9,9]

if __name__ == "__main__":
    main()
