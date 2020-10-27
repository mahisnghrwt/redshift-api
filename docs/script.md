###### [Home](README.md) • [Endpoints](/endpoints/README.md) • [Authentication and Authorization](/authentication-authorization.md) • [Script](/script.md)
---

# Script

### Conventions a 'Good' script follows
* The first command-line argument is the path to the JSON file containing all the measurements for a calculation, the second argument is the path to directory where to output the file-based results.
* For every single calulation, all the measurements are stored inside the `args` property, when decoded.
* A script can either return numeric or file-based or numeric and file-based result.
* Result must be printed in the same order as calculations in JSON file, and seperated by newline.
* If the results are file-based, then it must print the name of the ouput file along with the extension.
    * Every single calculation, also has its corresponding `unique_id` property, which can be used for naming the output, to avoid overwriting of files.
* If returning both numeric and file-based result, then for every single calculation it must print its numeric result and then output filename, seperated by newline.

#### Sample, numeric result
```
import sys
import json

def doSomething(x):
  result = 0;
  #perform calculation and return the result
  #here we are just adding up all the measurements
  for key, value in x.items():
    result += value

  return result;

def main():
  #the first command-line argument is the path to the JSON file containg all the 'measurements'
  file = sys.argv[1]

  #open the json file, decode it
  with open(file) as f:
    data = json.load(f)

  #'data' here is an array
  #for every single calculation, args contains all the measurements
  #and the results must be printed, seperated by newline
  for x in data:
    print(doSomething(x['args']))

main()
```

#### Sample, file-based result
```
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
```

#### Sample JSON file, containing all measurements
```
[{
    "method_id": 2,
    "script_path": "scripts\/sum.py",
    "args": {
        "optical_u": 10,
        "optical_v": 9786,
        "optical_g": 79786,
        "optical_r": 786,
        "optical_i": 6,
        "optical_z": 98,
        "infrared_three_six": 897,
        "infrared_four_five": 6,
        "infrared_five_eight": 54,
        "infrared_eight_zero": 876,
        "infrared_J": 978,
        "infrared_H": 876,
        "infrared_K": 76,
        "radio_one_four": 9768
    },
    "unique_id": "5f924ebf74cf8",
    "galaxy_id": 27
}, {
    "method_id": 2,
    "script_path": "scripts\/sum.py",
    "args": {
        "optical_u": 10,
        "optical_v": 9786,
        "optical_g": 79786,
        "optical_r": 786,
        "optical_i": 6,
        "optical_z": 98,
        "infrared_three_six": 897,
        "infrared_four_five": 6,
        "infrared_five_eight": 54,
        "infrared_eight_zero": 876,
        "infrared_J": 978,
        "infrared_H": 876,
        "infrared_K": 76,
        "radio_one_four": 9768
    },
    "unique_id": "5f924ebf74d02",
    "galaxy_id": 28
}]
```