import cv2
import datetime
import time
import boto3
import os
import sys
from pprint import pprint
# variables
client = boto3.client('rekognition')
path = 'C:/xampp/htdocs/Proyecto/pages/Pagetheme/imgtaken'
# video capture source camera (Here webcam of laptop)
CID=sys.argv[1]
cap = cv2.VideoCapture(0)
p = 0
i = 1
img_Similarity =[]
openD = 0

def get_image_from_file(filename):
    '''Based on
       https://docs.aws.amazon.com/rekognition/latest/dg/example4.html,
       last access 10/3/2017'''
    with open(filename, 'rb') as imgfile:
        return imgfile.read()


def compare_faces(img, CoID, threshold=80):

	response = client.search_faces_by_image(
    CollectionId=CoID,
    FaceMatchThreshold=threshold,
    Image={
       'Bytes': img
    },
    MaxFaces=1,
)
	return response['FaceMatches']


while p < 3:
    ret, frame = cap.read()  # return a single frame in variable `frame`
    if i % 5 == 0:
        date = datetime.datetime.now().strftime("%Y_%m_%d_%H_%M_%S")
        img_name = "{}/{}.png".format(path, date)
        cv2.imwrite(img_name, frame)
        matches = compare_faces(get_image_from_file(img_name), '{}'.format(CID))
        #a=float(matches['Similarity'])
        #img_Similarity.append(a)
        for match in matches:
            #pprint(match['Similarity'])
            a=float(match['Similarity'])
            img_Similarity.append(a)
        #print("{} written!".format(img_name))
        p += 1
    i = i+1
for imgS in img_Similarity:
    if imgS >85 :
     openD=1           
pprint(openD)
cap.release()
cv2.destroyAllWindows()
