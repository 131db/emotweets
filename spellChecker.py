import sys, json

from pattern.en import suggest

try:
    data = json.loads(sys.argv[1])
except:
    print "ERROR"
    sys.exit(1)

print suggest(data)

print json.dumps(suggest(data))
