import cv2
import io
import socket
import struct
import time
import pickle
import zlib

client_socket = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
client_socket.connect(('127.0.0.1', 8888))
connection = client_socket.makefile('wb')

cam = cv2.VideoCapture(0)
#cam= cv2.VideoCapture('rtsp://admin:admin123@192.168.1.2/')

cam.set(3, 800)
cam.set(4, 600)

img_counter = 0

encode_param = [int(cv2.IMWRITE_JPEG_QUALITY), 90]

ret, frame = cam.read()
result, frame = cv2.imencode('.jpg', frame, encode_param)
data = pickle.dumps(frame, 0)
data2="hello"
data2.encode("utf8")
size = len(data)


print("{}: {}".format(img_counter, size))
client_socket.sendall(struct.pack(">L", size) + data)
client_socket.sendall(data2.encode("utf8"))
#img_counter += 1

cam.release()