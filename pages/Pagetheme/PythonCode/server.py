# import the necessary packages
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
import argparse
import face_recognition

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
    
    client_input = receive_input(connection, max_buffer_size)
    connection.sendall(client_input.encode("utf8"))
    print(client_input)
    if connection.recv(max_buffer_size).decode("utf8")=="quit":
        print("Client is requesting to quit")
        connection.close()
        print("Connection " + ip + ":" + port + " closed")
    else:
        print("no llego nada pero la cerrare")
        connection.close()
        print("Connection " + ip + ":" + port + " closed")
        
        
        

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
    send="4"
    connection.sendall(send.encode("utf8"))
    data += connection.recv(max_buffer_size)
    decoded_input = data.decode("utf8").rstrip()
    print(decoded_input)
    ID = get_id(decoded_input)
    if ID == "-1":
        result= ID
    else:
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
    result=reconocimiento(ID,img_name)
    return result

def get_id(carn):
    proc = subprocess.Popen("/xampp/php/php.exe /xampp/htdocs/Proyecto/pages/buscarid.php " + carn, shell=True, stdout=subprocess.PIPE)
    script_response = proc.stdout.read()
    script_response = script_response.decode("utf8").rstrip()
    print(script_response)
    return script_response

def reconocimiento(id,image):
    encodings_name = "encodings/{}.pickle".format(id)
    image_location = image
    # construct the argument parser and parse the arguments
    ap = argparse.ArgumentParser()
    ap.add_argument("-e", "--encodings", required=True,
        help="path to serialized db of facial encodings")
    ap.add_argument("-i", "--image", required=True,
        help="path to input image")
    ap.add_argument("-d", "--detection-method", type=str, default="cnn",
        help="face detection model to use: either `hog` or `cnn`")
    args = vars(ap.parse_args(["--encodings", encodings_name,"--image",image_location,"--detection-method","hog"]))
    # load the known faces and embeddings
    #print("[INFO] loading encodings...")
    data = pickle.loads(open(args["encodings"], "rb").read())

    # load the input image and convert it from BGR to RGB
    image = cv2.imread(args["image"])
    rgb = cv2.cvtColor(image, cv2.COLOR_BGR2RGB)

    # detect the (x, y)-coordinates of the bounding boxes corresponding
    # to each face in the input image, then compute the facial embeddings
    # for each face
    #print("[INFO] recognizing faces...")
    boxes = face_recognition.face_locations(rgb,
        model=args["detection_method"])
    encodings = face_recognition.face_encodings(rgb, boxes)

    # initialize the list of names for each face detected
    names = []

    # loop over the facial embeddings
    for encoding in encodings:
        # attempt to match each face in the input image to our known
        # encodings
        matches = face_recognition.compare_faces(data["encodings"],
            encoding)
        name = "Unknown"

        # check to see if we have found a match
        if True in matches:
            # find the indexes of all matched faces then initialize a
            # dictionary to count the total number of times each face
            # was matched
            matchedIdxs = [i for (i, b) in enumerate(matches) if b]
            counts = {}

            # loop over the matched indexes and maintain a count for
            # each recognized face face
            for i in matchedIdxs:
                name = data["names"][i]
                counts[name] = counts.get(name, 0) + 1

            # determine the recognized face with the largest number of
            # votes (note: in the event of an unlikely tie Python will
            # select first entry in the dictionary)
            name = max(counts, key=counts.get)
        
        # update the list of names
        names.append(name)
    print(names)    
    if not names:
        return "no faces in the image"
    elif names[0] == id:
        return "1"
    else:
        return "0"
    

if __name__ == "__main__":
    main()