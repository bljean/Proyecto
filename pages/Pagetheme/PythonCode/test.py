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
    #get_id("123456")
    reconocimiento("20131036","imgtaken/my-image.png")
def get_id(carn):
    proc = subprocess.Popen("/xampp/php/php.exe /xampp/htdocs/Proyecto/pages/buscarid.php " + carn, shell=True, stdout=subprocess.PIPE)
    script_response = proc.stdout.read()
    script_response = script_response.decode("utf8").rstrip()
    print(script_response)
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
    if name == id:
        print(1)
    else:
        print(0)
if __name__ == "__main__":
    main()