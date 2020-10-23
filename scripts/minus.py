import sys
import json

def sum_(x):
  result = 0;
  for key, value in x.items():
    result -= value

  return result;

def main():
  #take the file path as argument
  file = sys.argv[1]

  #open the json file, decode as an array
  with open(file) as f:
    data = json.load(f)

  #'data' here is an array
  #The results 
  for x in data:
    print(sum_(x['args']))

main()