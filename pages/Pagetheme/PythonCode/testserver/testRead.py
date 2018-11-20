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



def send_data(data2):
    cam = cv2.VideoCapture(0)
    #cam= cv2.VideoCapture('rtsp://admin:admin123@192.168.1.2/')
    if not cam.isOpened():
        print("camera disconected")
    else:
        client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
        try:
            client_socket.connect(('127.0.0.1', 8888))
        except:
            print("Connection error")
            return
        #connection = client_socket.makefile('wb') 
        cam.set(3, 800)
        cam.set(4, 600)
        encode_param = [int(cv2.IMWRITE_JPEG_QUALITY), 90]
        ret, frame = cam.read()
        result, frame = cv2.imencode('.jpg', frame, encode_param)
        data = pickle.dumps(frame, 0)
        size = len(data)
        client_socket.sendall(struct.pack(">L", size) + data)
        str1 = ''.join(str(e) for e in data2)
        r=client_socket.recv(4096).decode("utf8")
        print(r)
        if r == "4":
            client_socket.sendall(str1.encode("utf8"))
        
        r=client_socket.recv(4096).decode("utf8")
        print(r)
        menssage="quit"
        client_socket.sendall(menssage.encode("utf8"))
    cam.release()

send_data("1021981126177")