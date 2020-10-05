import sys

mean =  0
count = len(sys.argv)
i = 1

while i < count:
    mean += int(sys.argv[i])
    i += 1

mean = mean / (count - 1)
print mean