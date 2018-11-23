import time
import cv2
import numpy as np
import time
import datetime
import io
import socket
import struct
import pickle
import zlib
import sys
from collections import deque

#collections
queue = deque()
def prepare_send_data(data2):
    client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    #cam= cv2.VideoCapture('rtsp://admin:admin123@192.168.1.2/')
    try:
        client_socket.connect(('127.0.0.1', 8888))
    except:
        print("Connection error")
        str1 = ''.join(str(e) for e in data2)
        #print(str1)
        queue.append(str1)
        return
    cam = cv2.VideoCapture(0)
    if not cam.isOpened():
        print("camera disconected")
        cam.release()
    else:
         #connection = client_socket.makefile('wb') 
        cam.set(3, 800)
        cam.set(4, 600)
        encode_param = [int(cv2.IMWRITE_JPEG_QUALITY), 90]
        ret, frame = cam.read()
        result, frame = cv2.imencode('.jpg', frame, encode_param)
        data = pickle.dumps(frame, 0)
        #index=0
        #send_data(client_socket,index)
        send_frame(data,data2,client_socket)
    cam.release()
    return
def send_data(client_socket,index):
    is_active = True
    if index == 1:
        while is_active:
            if queue:
                rfid=queue.popleft()
                print("id:")
                print(rfid)
                send_rfid(rfid,client_socket)
                if queue:
                    menssage="morerfid"
                    client_socket.sendall(menssage.encode("utf8"))
                else:
                    menssage="quit"
                    client_socket.sendall(menssage.encode("utf8"))
            else:
                print("sending is over")
                is_active= False
    elif index == 0:
        while is_active:
            if queue:
                rfid=queue.popleft()
                print("id:")
                print(rfid)
                send_rfid(rfid,client_socket)
                menssage="morerfid"
                client_socket.sendall(menssage.encode("utf8"))
            else:
                print("sending is over")
                is_active= False  
def send_frame(data,data2,client_socket):
    #indicando que envia un frame y el id
    menssage="frame"
    client_socket.sendall(menssage.encode("utf8"))
    r=client_socket.recv(4096).decode("utf8")
    print(r)
    #se envia el frame
    if r == "sendframe":
        size = len(data)
        client_socket.sendall(struct.pack(">L", size) + data)
    str1 = ''.join(str(e) for e in data2)
    r=client_socket.recv(4096).decode("utf8")
    print(r)
    if r == "4":
            #se envia el rfid
        client_socket.sendall(str1.encode("utf8"))
    r=client_socket.recv(4096).decode("utf8")
    print(r)
    menssage="quit"
    client_socket.sendall(menssage.encode("utf8"))
def send_rfid(str1,client_socket):
    menssage="rfid"
    client_socket.sendall(menssage.encode("utf8"))

    print("wating for server response to sendrfid")
    r=client_socket.recv(4096).decode("utf8")
    print(r)
    if r == "sendrfid":
            #se envia el rfid
        client_socket.sendall(str1.encode("utf8"))
    r=client_socket.recv(4096).decode("utf8")
    print(r)
def test_connection():
    if queue:
        client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        try:
            client_socket.connect(('127.0.0.1', 8888))
        except:
            print("Connection error")
            return
        index=1
        send_data(client_socket,index)
    else:
        print("nothing to send")

message=""
while 1:
    
    if message!="exit":
        message = input(" -> ")
        prepare_send_data(message)
    else:
        test_connection()
    print("in the queue:")
    for elem in queue:                   # iterate over the deque's elements
         
         print(elem.upper())
