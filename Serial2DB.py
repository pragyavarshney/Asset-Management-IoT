import serial
import pymysql
import pandas
from sklearn import svm

# Code to predict room

# Training model
rssi = pandas.read_csv('TrainingData.csv')
rssi.shape
X = rssi.drop('ActualRoom', axis=1)
y = rssi['ActualRoom']
from sklearn.model_selection import train_test_split

X_train, X_test, y_train, y_test = train_test_split(X, y, test_size=0.15)
from sklearn.svm import SVC

svclassifier = SVC(kernel='linear')
svclassifier.fit(X_train, y_train)

# Code to take input from serial and insert in db
s = serial.Serial('COM4', 115200)

db = pymysql.connect(host="localhost", user="root", password="tiger", db="assetdb")

while (True):
    line = str(s.readline())[2:-5]
    print(line)
    val=[]
    val = line.split(",")

    if val[0] == "INSERT":
        print(val)
        data=[val[2], val[3], val[6], val[7], val[10], val[11], val[4], val[8], val[12], val[13]]
        #data = list(map(int, data))
        print(data)
        #data.reshape(1,-1)
        predRoom = svclassifier.predict([data])
        print(predRoom)
        try:
            # Serial.printf("INSERT,3c:71:bf:8c:3d:26,%d,%d,%d,3c:71:bf:6b:85:e2,%d,%d,%d,3c:71:bf:6b:8c:fe,%d,%d,%d,%d \n", avg_rssi1[2], packet1[3],packet1_percent[4], avg_rssi2[6],packet2[7],packet2_percent[8], avg_rssi3[10],packet3[11], packet3_percent[12],count[13]);

            sql_select_Query = "select nextval('sq_my_sequence') as next_seq"
            cursor = db.cursor()
            cursor.execute(sql_select_Query)
            records = cursor.fetchone()
            group_id = records[0]
            print(group_id)
            room_no = 1

            print(val)

            mySql_insert_query = """INSERT INTO assetdb.asset_device_info (asset_id,asset_name,sensor_mac, rssi_avg_value, packet_value,Percent_packet,predicted_room)
                                       VALUES (%s,%s, %s, %s, %s, %s, %s) """

            records_to_insert = [(group_id, "Laptop1",val[1], val[2], val[3], val[4], str(predRoom[0])),
                                 (group_id, "Laptop1",val[5], val[6], val[7], val[8], str(predRoom[0])),
                                 (group_id, "Laptop1",val[9], val[10], val[11], val[12], str(predRoom[0]))]

            cursor = db.cursor()
            cursor.executemany(mySql_insert_query, records_to_insert)
            db.commit()
            print(cursor.rowcount, "Record inserted successfully into Laptop table")
            cursor.close()

        except pymysql.err.ProgrammingError as except_detail:
            print("pymysql.err.ProgrammingError: «{}»".format(except_detail))
        #
        # for i in val:
        #    print(i)