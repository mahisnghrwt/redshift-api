import sys
import json
from shutil import copyfile
import time
import matplotlib.pyplot as plt
import numpy as np

destinationDir = ""

def generateGraph(fPrefix):
  #file name including extension
  fileName = fPrefix + ".png"
  global destinationDir
  #Absolute path location to the output file
  file = destinationDir + fileName
  plt.plot(np.random.rand(100))
  plt.savefig(file)
  return fileName

def sum_(x):
  #output file prefix is <galaxy_id>_<method_id>
  fPrefix = str(x["unique_id"])
  return generateGraph(fPrefix)

def main():
  #File containing the arguments sfor the calculation
  argsFile = sys.argv[1]

  #The location where we will output the file
  global destinationDir
  destinationDir = sys.argv[2]

  #Read the file and decode the JSON
  with open(argsFile) as f:
    data = json.load(f)
  
  #For every single calculation
  for x in data:
    print(sum_(x))

main()