import sys, json
from pattern.en import singularize

try:
    data = json.loads(sys.argv[1])
except:
    print "ERROR"
    sys.exit(1)

# Send it to stdout (to PHP)
print json.dumps(singularize(data))
