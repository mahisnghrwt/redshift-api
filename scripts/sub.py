import sys

result  = 0
i = 1

if len(sys.argv) != 3:
    print("Must provide two operands to execute the function!")
    exit()

result = int(sys.argv[2]) - int(sys.argv[1])
print ("Result of subtraction: " + str(result))