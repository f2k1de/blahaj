import json
import urllib.request

data = urllib.request.urlopen("https://www.ikea.com/de/de/products/javascripts/range-stockcheck.99d519931706b73269c0.js").read().decode("utf-8")

# Trust me, I'm an engineer
data = data.split("e.allStores=[{")[1].split("LOCAL_STORAGE_SELECTED_STORE_ID")[0].split("value")

result = []

for entry in data:
    if len(entry) > 10:
       parts = entry.split(",")
       id = parts[0].split("\"")[1]
       name = parts[1].split("\"")[1]
       print(name, id)
       result.append({"id" : id, "name" : name})

with open("stores.json", "w") as file:
    file.write(json.dumps(result))