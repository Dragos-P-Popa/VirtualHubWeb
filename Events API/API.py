import json
import requests
import re

global ids
global titles
global repliesl
global time
time = []
repliesl = []
titles = []
ids = []


#NOT READY - Each topic will have different formatting or wording therefore it is hard to get the needed info automatically
def getTaskInfo():
    url = 'https://community.infiniteflight.com/t/338747.json'

    #Parsing JSON and saving the HTML formatted result
    resp = requests.get(url=url)
    data = resp.text
    parsed = json.loads(data)
    post_stream = parsed["post_stream"]
    string = post_stream["posts"]
    string2 = string[0]
    cooked = string2["cooked"]
    #HTML
    clean_html = cooked

    server = re.search('<strong>Server</strong>:(.*)<br>', clean_html)
    date = re.search('<strong>Date</strong>:(.*)<br>', clean_html)
    time = re.search('<strong>Time</strong>:(.*)<br>', clean_html)
    route = re.search('<strong>Route</strong>:(.*)<br>', clean_html)
    flTime = re.search('<strong>Flight Time</strong>:(.*)<br>', clean_html)
    aircraft = re.search('<strong>Aircraft</strong>:(.*)<br>', clean_html)

    print(server)
    print(date)
    print(time)
    print(route)
    print(flTime)
    print(aircraft)


def getTaskID():
    tagUrl = 'https://community.infiniteflight.com/c/live/events.json'
    resp = requests.get(url=tagUrl)
    data = resp.text
    parsed = json.loads(data)
    post_stream = parsed["topic_list"]
    string = post_stream["topics"]
    for i in range(0, len(string)):
        string2 = string[i]
        cooked = string2["id"]
        ids.append(cooked)
    getTaskDetails()


def getTaskDetails():
    for i in range(0, len(ids)):
        tagUrl = 'https://community.infiniteflight.com/t/' + str(
            ids[i]) + '.json'
        resp = requests.get(url=tagUrl)
        data = resp.text
        parsed = json.loads(data)
        post_stream = parsed["title"]
        replies = parsed['reply_count']
        timestamp = parsed['created_at']
        time.append(timestamp)
        repliesl.append(replies)
        titles.append(post_stream)
    writeJSON()

#Go to "'https://community.infiniteflight.com/t/' + id" to open topic in IFC
def writeJSON():
    data = {}
    data['events'] = []
    for i in range(0, len(titles)):
        data['events'].append({
          'id': '' + str(ids[i-1]),
          'title': '' + titles[i - 1],
          'replies': '' + str(repliesl[i - 1]),
          'created_at': '' + time[i-1]
                               })

    with open('events.txt', 'w') as outfile:
        json.dump(data, outfile)


getTaskID()
