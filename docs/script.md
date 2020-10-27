# Script


#### Sample
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

### Navigation Links
* [Home](/)
* [Endpoints](/endpoints/README.md)
* [Authentication and Authorization](/authentication-authorization.md)
* [Script](/script.md)