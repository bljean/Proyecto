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
    get_id("123456")

def get_id(carn):
    proc = subprocess.Popen("/xampp/php/php.exe /xampp/htdocs/Proyecto/pages/buscarid.php " + carn, shell=True, stdout=subprocess.PIPE)
    script_response = proc.stdout.read()
    script_response = script_response.decode("utf8").rstrip()
    print(script_response)
   
if __name__ == "__main__":
    main()