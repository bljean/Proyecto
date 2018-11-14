import socket
import sys
import traceback
from threading import Thread
import cv2
import pickle
import numpy as np
import struct ## new
import zlib
import datetime
import time
import subprocess

def main():
    start_server()


def start_server():
    host = "10.0.0.2"
    port = 8888         # arbitrary non-privileged port

    soc = socket.socket(socket.AF_INET, socket.SOCK_STREAM)
    soc.setsockopt(socket.SOL_SOCKET, socket.SO_REUSEADDR, 1)   # SO_REUSEADDR flag tells the kernel to reuse a local socket in TIME_WAIT state, without waiting for its natural timeout to expire
    print("Socket created")

    try:
        soc.bind((host, port))
    except:
        print("Bind failed. Error : " + str(sys.exc_info()))
        sys.exit()

    soc.listen(5)       # queue up to 5 requests
    print("Socket now listening")

    # infinite loop- do not reset for every requests
    while True:
        connection, address = soc.accept()
        ip, port = str(address[0]), str(address[1])
        print("Connected with " + ip + ":" + port)

        try:
            Thread(target=client_thread, args=(connection, ip, port)).start()
        except:
            print("Thread did not start.")
            traceback.print_exc()

    soc.close()


def client_thread(connection, ip, port, max_buffer_size = 4096):
    is_active = True

    while is_active:
        client_input = receive_input(connection, max_buffer_size)
        print(client_input)
        print("Client is requesting to quit")
        connection.close()
        print("Connection " + ip + ":" + port + " closed")
        is_active = False
        
        

def receive_input(connection, max_buffer_size):
    
    data = b""
    payload_size = struct.calcsize(">L")
   #receive frame
    while len(data) < payload_size:
        print("Recv: {}".format(len(data)))
        data += connection.recv(max_buffer_size)
    
    print("Done Recv: {}".format(len(data)))
    packed_msg_size = data[:payload_size]
    data = data[payload_size:]
    msg_size = struct.unpack(">L", packed_msg_size)[0]
    print("msg_size: {}".format(msg_size))
    while len(data) < msg_size:
        data += connection.recv(max_buffer_size)
    frame_data = data[:msg_size]
    data = data[msg_size:]
    #receive ID
    data += connection.recv(max_buffer_size)
    decoded_input = data.decode("utf8").rstrip()
    print(decoded_input)
    result = process_input(frame_data,ID)

    return result


def process_input(frame_data,ID):
    path = 'C:/xampp/htdocs/Proyecto/pages/Pagetheme/PythonCode/imgtaken'
    print("Processing the input received from client")
    frame=pickle.loads(frame_data, fix_imports=True, encoding="bytes")
    frame = cv2.imdecode(frame, cv2.IMREAD_COLOR)
    date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
    img_name = "{}/{}.png".format(path, date)
    cv2.imwrite(img_name, frame)
    return "complete"

if __name__ == "__main__":
    main()