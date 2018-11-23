#!/usr/bin/env python
# -*- coding: utf8 -*-
#
#    Copyright 2014,2018 Mario Gomez <mario.gomez@teubi.co>
#
#    This file is part of MFRC522-Python
#    MFRC522-Python is a simple Python implementation for
#    the MFRC522 NFC Card Reader for the Raspberry Pi.
#
#    MFRC522-Python is free software: you can redistribute it and/or modify
#    it under the terms of the GNU Lesser General Public License as published by
#    the Free Software Foundation, either version 3 of the License, or
#    (at your option) any later version.
#
#    MFRC522-Python is distributed in the hope that it will be useful,
#    but WITHOUT ANY WARRANTY; without even the implied warranty of
#    MERCHANTABILITY or FITNESS FOR A PARTICULAR PURPOSE.  See the
#    GNU Lesser General Public License for more details.
#
#    You should have received a copy of the GNU Lesser General Public License
#    along with MFRC522-Python.  If not, see <http://www.gnu.org/licenses/>.
#

import RPi.GPIO as GPIO
import MFRC522
import signal
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
continue_reading = True
#collections
queue = deque()
host="10.0.0.3"
port=8888
# Capture SIGINT for cleanup when the script is aborted
def end_read(signal,frame):
    global continue_reading
    print("Ctrl+C captured, ending read.")
    continue_reading = False
    GPIO.cleanup()
# Capture the image and sending to the sever, using sockets, to recognize    
def prepare_send_data(data2):
    client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    client_socket.settimeout(1)
    cam= cv2.VideoCapture('rtsp://admin:admin123@192.168.1.2/')
    #cam = cv2.VideoCapture(0)
    if cam.isOpened() == False:
        print("camera disconected")
        
        try:
            print("testing the connection")
            client_socket.connect((host, port))
        except:
            print("Connection error")
            str1 = ''.join(str(e) for e in data2)
            print(str1)
            queue.append(str1)
            return
        print("Connection successfull")
        str1 = ''.join(str(e) for e in data2)
        send_rfid(str1,client_socket)
        menssage="quit"
        client_socket.sendall(menssage.encode("utf8"))
    else:
        try:
            print("testing the connection")
            client_socket.connect((host, port))
        except:
            print("Connection error")
            str1 = ''.join(str(e) for e in data2)
            print(str1)
            queue.append(str1)
            return
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
        client_socket.settimeout(1)
        try:
            print("testing the connection")
            client_socket.connect((host, port))
        except:
            print("Connection error")
            return
        index=1
        send_data(client_socket,index)

# Hook the SIGINT
signal.signal(signal.SIGINT, end_read)

# Create an object of the class MFRC522
MIFAREReader = MFRC522.MFRC522()

# Welcome message
print("Welcome to the MFRC522 data read example")
print("Press Ctrl-C to stop.")

# This loop keeps checking for chips. If one is near it will get the UID and authenticate
while continue_reading:
    
    # Scan for cards    
    (status,TagType) = MIFAREReader.MFRC522_Request(MIFAREReader.PICC_REQIDL)
    # If a card is found
    if status == MIFAREReader.MI_OK:
        print("Card detected")
    
    # Get the UID of the card
    (status,uid) = MIFAREReader.MFRC522_Anticoll()

    # If we have the UID, continue
    if status == MIFAREReader.MI_OK:
        
        # Print UID
        print("Card read UID: %s,%s,%s,%s" % (uid[0], uid[1], uid[2], uid[3]))
        # This is the default key for authentication
        key = [0xFF,0xFF,0xFF,0xFF,0xFF,0xFF]
        
        # Select the scanned tag
        MIFAREReader.MFRC522_SelectTag(uid)

        # Authenticate
        status = MIFAREReader.MFRC522_Auth(MIFAREReader.PICC_AUTHENT1A, 8, key, uid)
        
        # Check if authenticated
        if status == MIFAREReader.MI_OK:
            MIFAREReader.MFRC522_Read(8)
            MIFAREReader.MFRC522_StopCrypto1()
            
        else:
            print("Authentication error")
        prepare_send_data(uid)
        print(queue)
    test_connection()
    time.sleep(2)
        
    

