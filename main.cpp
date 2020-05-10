

#include <Arduino.h>
#include <M5Stack.h>
#include <Adafruit_NeoPixel.h>
#include <BLEDevice.h>
#include <BLEUtils.h>
#include <BLEServer.h>
#include <WiFi.h>
#include <Wire.h>
//#include <bits/stdc++.h>
#include <cmath>
//#include "BLEScan.h"
#define LED 25
int packet1 =0,packet2 =0,packet3=0,packet4= 0;
int count = 0;
int rssi1 = 0, rssi2 = 0, rssi3 = 0, rssi4 = 0;
int avg_rssi1 = -1000, avg_rssi2 = -1000, avg_rssi3 = -1000, avg_rssi4 = -1000;
int packet1_percent = 0, packet2_percent = 0, packet3_percent = 0, packet4_percent = 0;
long Time_interval  = 0; 
long End_time=0;
long start_time  = 0;       // start time
int interval = 2000;          // interval between scan 50 sec

// The remote service we wish to connect to.
static BLEUUID serviceUUID("91a3f79c-4687-11e9-b210-d663bd873d93");
// The characteristic of the remote service we are interested in.
static BLEUUID charUUID("9ed3ca3c-4687-11e9-b210-d663bd873d93");

typedef struct
{
    char address[17]; // 67:f1:d2:04:cd:5d
    int rssi;
} BeaconData;

typedef struct
{
    char address[17]; // 67:f1:d2:04:cd:5d
    int avg_rssi[10];
    int no_of_pckts[10];
    int curr_rssi;

} TempDataTable;

TempDataTable table[15]; // Buffer to store found device data

//Scan for BLE servers and find the first one that advertises the service we are looking for.

class MyAdvertisedDeviceCallbacks : public BLEAdvertisedDeviceCallbacks
{ //Called for each advertising BLE server.
    void onResult(BLEAdvertisedDevice advertisedDevice)
    {
        // We have found a device, let us now see if it contains the service we are looking for.
        if (advertisedDevice.haveServiceUUID() && advertisedDevice.isAdvertisingService(serviceUUID))
        {
            BLEDevice::getScan()->stop();
        }
        //Serial.printf("MAC: %s \n", advertisedDevice.getAddress().toString().c_str());
        //Serial.printf("name: %s \n", advertisedDevice.getName().c_str());
        // Print everything via serial port for debugging
        if (advertisedDevice.getAddress().toString() == "84:0d:8e:17:67:ea" ||
            advertisedDevice.getAddress().toString() == "3c:71:bf:8c:3d:fe" ||
            advertisedDevice.getAddress().toString() == "3c:71:bf:6b:85:e2")
        {
            count++;

            if (advertisedDevice.getAddress().toString() == "84:0d:8e:17:67:ea")
            {
                packet1++;
                rssi1 = advertisedDevice.getRSSI();
                avg_rssi1 = avg_rssi1 + ((rssi1)-avg_rssi1) / packet1;
                packet1_percent = packet1 * 100 / count;
                //Serial.printf("packet1,%d,%d,%d,%d \n", packet1,rssi1, avg_rssi1, packet1_percent);
            }

            else if (advertisedDevice.getAddress().toString() == "3c:71:bf:8c:3d:fe")
            {
                packet2++;
                rssi2 = advertisedDevice.getRSSI();
                avg_rssi2 = avg_rssi2 + ((rssi2)-avg_rssi2) / packet2;
                packet2_percent = packet2 * 100 / count;
               // Serial.printf("Purple,3c:71:bf:6b:85:e2,%d,%d,%d \n", rssi2, avg_rssi2, packet2_percent);
            }

            
            else if (advertisedDevice.getAddress().toString() == "3c:71:bf:6b:85:e2")
            {
                packet3++;
                rssi3 = advertisedDevice.getRSSI();
                avg_rssi3 = avg_rssi3 + ((rssi3)-avg_rssi3) / packet3;
                packet3_percent = packet3 * 100 / count;
               // Serial.printf("Blue,3c:71:bf:6b:8c:fe,%d,%d,%d \n", rssi3, avg_rssi3, packet3_percent);
            }
        }

    } // onResult
};    // MyAdvertisedDeviceCallbacks

void setup()
{
    Serial.begin(115200);
    Serial.println("Starting Arduino BLE Client application...");
    Serial.println("Room,MAC ADDRESS,RSSI,AvgRSSI,Packet percent");
    BLEDevice::init("");

    /* Retrieve a Scanner and set the callback we want to use to be informed when we
    have detected a new device.  Specify that we want active scanning and start the
    scan to run for 5 seconds.*/
    BLEScan *pBLEScan = BLEDevice::getScan();
    pBLEScan->setAdvertisedDeviceCallbacks(new MyAdvertisedDeviceCallbacks());
    pBLEScan->setInterval(1349);
    pBLEScan->setWindow(449);
    pBLEScan->setActiveScan(true);
    pBLEScan->start(50, false);

    pinMode(LED, OUTPUT);
    start_time=millis();
    //Serial.println(start_time);
    Serial.print("Calculating packets and Rssi ...please wait!\n");

    
} // End of setup.

// This is the Arduino main loop function.
void loop()
    {   
        

        if (millis() - start_time < interval ) 
            {
                //Serial.print((millis() - start_time)/1000);
                BLEDevice::getScan()->start(0); // this is just example to start scan after disconnect, most likely there is better way to do it in arduino
 
            }
       else
       {  //Serial.print("\n--------------------\n");
          //"84:0d:8e:17:67:ea" "3c:71:bf:8c:3d:fe" "3c:71:bf:6b:85:e2"
          //Serial.printf("RED,3c:71:bf:8c:3d:26,%d,%d,%d \n", rssi1, avg_rssi1, packet1_percent);
          //Serial.printf("Purple,3c:71:bf:6b:85:e2,%d,%d,%d \n", rssi2, avg_rssi2, packet2_percent);
          //Serial.printf("Blue,3c:71:bf:6b:8c:fe,%d,%d,%d \n", rssi3, avg_rssi3, packet3_percent);
          Serial.printf("INSERT,84:0d:8e:17:67:ea,%d,%d,%d,3c:71:bf:8c:3d:fe,%d,%d,%d,3c:71:bf:6b:85:e2,%d,%d,%d,%d,1 \n", avg_rssi1, packet1,(packet1/count)*100, avg_rssi2,packet2,(packet2/count)*100, avg_rssi3,packet3, (packet3/count)*100,count);
          //Serial.print("--------------------\n");
          
          
          
           //Check room RED
             if (abs(avg_rssi1) <= abs(avg_rssi2) && abs(avg_rssi1) <= abs(avg_rssi3))
             {
                 if(packet1_percent > packet2_percent && packet1_percent > packet3_percent)
        
                    {
                     //Serial.println("The asset is in room RED!!");
                    }
    }

           //Check room Purple
            else if (abs(avg_rssi2)<= abs(avg_rssi1) && abs(avg_rssi2) <= abs(avg_rssi3))
            {
                 if (packet2_percent > packet1_percent && packet2_percent > packet3_percent)
                    {
                      //Serial.println("The asset is in room PURPLE!!");
                     }
            }
    

            //Check room Blue
            else if (abs(avg_rssi3) <=abs(avg_rssi2) && abs(avg_rssi1) >= abs(avg_rssi3))
            {
                if (packet3_percent > packet2_percent && packet1_percent < packet3_percent)
                   {
           
                      //Serial.println("The asset is in room BLUE!!");
                    }
            }
    

             //No room
             else
                {
                 //Serial.println("Cannot find the room!!");
                }
    
    delay(1000);
    start_time=millis();
    rssi1 = 0, rssi2 = 0, rssi3 = 0, rssi4 = 0;
    avg_rssi1 = -1000, avg_rssi2 = -1000, avg_rssi3 = -1000, avg_rssi4 = -1000;
    packet1_percent = 0, packet2_percent = 0, packet3_percent = 0, packet4_percent = 0;
    packet1 = 0, packet2 = 0, packet3 = 0, packet4 = 0;
    count=0;
    }

    delay(100); // Delay a second between loops.
                 // End of loop
}

   
    