import numpy as np
import cv2
from PIL import Image

face_cascade = cv2.CascadeClassifier('cascades/data/haarcascade_frontalface_default.xml')

image = cv2.imread('jeantest.JPG')
gray  = cv2.cvtColor(image, cv2.COLOR_BGR2GRAY)
faces = face_cascade.detectMultiScale(gray, 1.1, 5)
 
for (x, y, w, h) in faces:
	 print(x,y,w,h)
	 roi_gray =  gray[y:y+h, x:x+w]
	 roi_color = image[y:y+h, x:x+w]
	 img_item = "nuevaimg.png"
	 cv2.imwrite(img_item, roi_color)
	 cv2.waitKey(20) & 0xFF == ord('q')

cv2.destroyAllWindows()