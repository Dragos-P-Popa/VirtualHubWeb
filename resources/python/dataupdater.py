import os
import glob
import pathlib
import json
import string
from math import sin, cos, sqrt, atan2, radians

directory = "./Airports-development/"

all_gates_world = []
all_gates_in_airport = {}

all_runways_world = []
all_runways_in_airport = {}

all_atc_world = []
all_atc_in_airport = {}

all_runways_world_mapbox = []
all_runways_in_airport_mapbox = {}


def runway_length(ident1_loc, ident2_loc):
    # approximate radius of earth in km
    R = 6373.0

    lat1 = radians(ident1_loc[0])
    lon1 = radians(ident1_loc[1])
    lat2 = radians(ident2_loc[0])
    lon2 = radians(ident2_loc[1])

    dlon = lon2 - lon1
    dlat = lat2 - lat1

    a = sin(dlat / 2) ** 2 + cos(lat1) * cos(lat2) * sin(dlon / 2) ** 2
    c = 2 * atan2(sqrt(a), sqrt(1 - a))

    distancekm = R * c

    return {"km": round(distancekm, 1), "ft": round((distancekm * 3280.839895))}


for txt_file in pathlib.Path(directory).glob('**/*.dat'):

    airport = os.path.basename(os.path.dirname(txt_file))
    qbfile = open(txt_file, "r")

    l1 = 0
    l2 = 0

    for aline in qbfile:
        values = aline.split()

        if len(values) != 0:
            line_id = values[0]

            if line_id == '1300':
                all_gates_in_airport["type"] = "Feature"

                all_gates_in_airport["geometry"] = {}
                all_gates_in_airport["geometry"]["type"] = "Point"
                all_gates_in_airport["geometry"]["coordinates"] = [float(values[2]), float(values[1])]

                all_gates_in_airport["properties"] = {}
                all_gates_in_airport["properties"]["heading"] = float(values[3])
                all_gates_in_airport["properties"]["type"] = string.capwords(values[4].replace("_", " "))
                all_gates_in_airport["properties"]["name"] = string.capwords(values[-1])
                all_gates_in_airport["properties"]["airport"] = airport
                all_gates_in_airport["properties"]["aircrafts"] = string.capwords(values[5].replace("|", ", "))
                all_gates_in_airport["properties"]["id"] = values[-1] + "-" + airport + str(abs(int(float(values[1]) * float(values[2]) * float(values[3]))))

                l1 = 1
            if line_id == '1301':
                all_gates_in_airport["properties"]["size"] = values[1]
                l2 = 1

                if l1 != 0 or l2 != 0:
                    all_gates_world.append(all_gates_in_airport)
                    all_gates_in_airport = {}
                    l1 = 0
                    l2 = 0

            if line_id == '50' or line_id == '51' or line_id == '52' or line_id == '53' or line_id == '54' or line_id == '55' or line_id == '56':
                all_atc_in_airport["frequency"] = values[1][:2] + '.' + values[1][2:]
                all_atc_in_airport["airport"] = airport

                del values[0:2]
                all_atc_in_airport["name"] = string.capwords(' '.join(word.lower() for word in values).replace("twr", "tower").replace("gnd", "ground"))

                all_atc_world.append(all_atc_in_airport)
                all_atc_in_airport = {}

            if line_id == '100':
                all_runways_in_airport["ident1"] = values[8].upper()
                all_runways_in_airport["ident2"] = values[17].upper()
                all_runways_in_airport["coordinates_ident1"] = [float(values[10]), float(values[9])]
                all_runways_in_airport["coordinates_ident2"] = [float(values[19]), float(values[18])]

                length = runway_length([float(values[9]), float(values[10])], [float(values[18]), float(values[19])])

                all_runways_in_airport["length_km"] = length["km"]
                all_runways_in_airport["length_ft"] = length["ft"]
                all_runways_in_airport["airport"] = airport
                all_runways_in_airport["id"] = values[8].upper() + "-" + values[
                    17].upper() + airport

                all_runways_world.append(all_runways_in_airport)
                all_runways_in_airport = {}

                #mapbox version
                all_runways_in_airport_mapbox["type"] = "Feature"

                all_runways_in_airport_mapbox["geometry"] = {}
                all_runways_in_airport_mapbox["geometry"]["type"] = "Point"
                all_runways_in_airport_mapbox["geometry"]["coordinates"] = [float(values[10]), float(values[9])]

                all_runways_in_airport_mapbox["properties"] = {}
                all_runways_in_airport_mapbox["properties"]["ident"] = values[8].upper()
                all_runways_in_airport_mapbox["properties"]["airport"] = airport
                all_runways_in_airport_mapbox["properties"]["id"] = values[8].upper() + airport
                all_runways_in_airport_mapbox["properties"]["length_km"] = length["km"]
                all_runways_in_airport_mapbox["properties"]["length_ft"] = length["ft"]

                all_runways_world_mapbox.append(all_runways_in_airport_mapbox)
                all_runways_in_airport_mapbox = {}

                all_runways_in_airport_mapbox["type"] = "Feature"

                all_runways_in_airport_mapbox["geometry"] = {}
                all_runways_in_airport_mapbox["geometry"]["type"] = "Point"
                all_runways_in_airport_mapbox["geometry"]["coordinates"] = [float(values[19]), float(values[18])]

                all_runways_in_airport_mapbox["properties"] = {}
                all_runways_in_airport_mapbox["properties"]["ident"] = values[17].upper()
                all_runways_in_airport_mapbox["properties"]["airport"] = airport
                all_runways_in_airport_mapbox["properties"]["id"] = values[17].upper() + airport
                all_runways_in_airport_mapbox["properties"]["length_km"] = length["km"]
                all_runways_in_airport_mapbox["properties"]["length_ft"] = length["ft"]

                all_runways_world_mapbox.append(all_runways_in_airport_mapbox)
                all_runways_in_airport_mapbox = {}

    qbfile.close()

geojson_begin = '{ "type": "FeatureCollection", "features": '
geojson_gates = json.dumps(all_gates_world)
geojson_runways = json.dumps(all_runways_world_mapbox)
geojson_end = '}'

with open('gates-mapbox.geojson', 'w', encoding='utf-8') as outfile:
    outfile.write(geojson_begin)
    outfile.write(geojson_gates)
    outfile.write(geojson_end)

with open('runways-mapbox.geojson', 'w', encoding='utf-8') as outfile:
    outfile.write(geojson_begin)
    outfile.write(geojson_runways)
    outfile.write(geojson_end)

deleteTableGates = 'DELETE FROM flightplan_gates;'
sqlQueriesGates = "".join("INSERT INTO flightplan_gates VALUES (null, " + str(gate["geometry"]["coordinates"][0]) + ", " + str(gate["geometry"]["coordinates"][1]) + ", " + str(gate["properties"]["heading"]) + ", '" + gate["properties"]["type"] + "', '" + gate["properties"]["size"] + "', '" + gate["properties"]["airport"] + "', '" + gate["properties"]["name"].replace('\'', '') + "', '" + gate["properties"]["aircrafts"] + "');" for gate in all_gates_world)

with open('gates.sql', 'w', encoding='utf-8') as outfile:
    outfile.write(deleteTableGates + sqlQueriesGates)

deleteTableFrequency = 'DELETE FROM vh_frequencies;'
sqlQueriesFrequency = "".join("INSERT INTO vh_frequencies VALUES (null, " + "'" + atc["frequency"].replace('\'', '') + "', '" + atc["airport"].replace('\'', '') + "', '" + atc["name"].replace('\'', '') + "');" for atc in all_atc_world)

with open('frequencies.sql', 'w', encoding='utf-8') as outfile:
    outfile.write(deleteTableFrequency + sqlQueriesFrequency)

deleteTableRunways = 'DELETE FROM vh_runways;'
sqlQueriesRunways = "".join("INSERT INTO vh_runways VALUES (null, " + "'" + runway["ident1"].replace('\'', '') + "', '" + runway["ident2"].replace('\'', '') + "', '" + str(runway["coordinates_ident1"]) + "', '" + str(runway["coordinates_ident2"]) + "', " + str(runway["length_km"]) + ", " + str(runway["length_ft"]) + ", '" + runway["airport"].replace('\'', '') + "');" for runway in all_runways_world)

with open('runways.sql', 'w', encoding='utf-8') as outfile:
    outfile.write(deleteTableRunways + sqlQueriesRunways)
