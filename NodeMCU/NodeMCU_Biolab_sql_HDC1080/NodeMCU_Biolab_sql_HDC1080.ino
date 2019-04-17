/*   
NODE MCU Web server with two Sensors over I2C and aREST API
ClosedCube_HDC1080 humidity 
https://www.sparkfun.com/products/11824

Hardware connections:
- (GND) to GND
+ (VDD) to 3.3V
(WARNING: do not connect + to 5V or the sensor will be damaged!)

You will also need to connect the I2C pins (SCL and SDA) to your
Arduino. The pins are different on different Arduinos:

Any Arduino pins labeled:  SDA  SCL
Uno, Redboard, Pro:        A4   A5
Mega2560, Due:             20   21
Leonardo:                   2    3
NodeMCU V1(ESP12E ):       D2   D1

*/

// Your sketch must #include this library, and the Wire library.
// Install them! 
#include <Wire.h>
#include "ClosedCube_HDC1080.h"

//Wifi
#include <ESP8266WiFi.h>
const char* ssid     = "NoName";
const char* password = "raspian3b";
const char* host = "192.168.50.1";

ClosedCube_HDC1080 hdc1080;

//Variables de control y tiempo
int ledState = LOW;
unsigned long previousBlink = 0;
long blinkInterval = 500;

unsigned long previousMillis = 0;
long interval = 900000;   //  15*60*1000 cada 15 min (en milisegundos )  900000

//variables Hdc1080
double temp;
double rh;

//variables SQL
  double temperature;
 double  humidity ;

// ID del ESP 
String sensorID= String(ESP.getChipId()); 


void setup()
{
  //LED
  pinMode(LED_BUILTIN, OUTPUT);

  //Serial
  Serial.begin(115200);
  Serial.println("REBOOT");

// Start hdc1080 sensor
  hdc1080.begin(0x40);
//Read info 
  Serial.print("Manufacturer ID=0x");
  Serial.println(hdc1080.readManufacturerId(), HEX); // 0x5449 ID of Texas Instruments
  Serial.print("Device ID=0x");
  Serial.println(hdc1080.readDeviceId(), HEX); // 0x1050 ID of the device

//WIfi connect
  Serial.println();
  Serial.print("Connecting to ");
  Serial.println(ssid);

  WiFi.begin(ssid, password);

  while (WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }

  Serial.println("");
  Serial.println("WiFi connected");
  Serial.println("IP address: ");
  Serial.println(WiFi.localIP());

  Serial.print("EspID: "); 
  Serial.println(sensorID);  
}

void loop()
{
  unsigned long currentMillis = millis();
  if(currentMillis - previousBlink >= blinkInterval) {
    previousBlink = currentMillis;
    if (ledState == LOW)
    ledState = HIGH;  // Note that this switches the LED *off*
    else
    ledState = LOW;   // Note that this switches the LED *on*
    digitalWrite(LED_BUILTIN, ledState);
  } 
  
  // Get temp and humidity from 1080
  temp = hdc1080.readTemperature();
  rh = hdc1080.readHumidity();
  
  // Pass readings
  temperature =  temp;
  humidity =  rh;
  
  //Subir los datos (temp hum)a la base 
  if(previousMillis==0 ||(currentMillis - previousMillis >= interval)) {
    previousMillis = currentMillis;   
    upload();
  }
}

void upload(){
    Serial.print("Temperatura: ");
    Serial.print(temperature);
    Serial.print(" Humedad: ");
    Serial.print(humidity);
    Serial.println();
    Serial.print("connecting to ");
    Serial.println(host);
  
    // Use WiFiClient class to create TCP connections
    WiFiClient client;
    const int httpPort = 80;
    if (!client.connect(host, httpPort)) {
      Serial.println("connection failed");
      blinkInterval = 5000;
      return;
    }
    blinkInterval = 500;
    // We now create a URL for the request
    String url = "/dht11.php";
    String key = "?pass=1234";
    String sid = "&sid=";
    String dato1 = "&temp=";
    String dato2 = "&humedad=";
  
    Serial.print("Requesting URL: ");
    Serial.println(url);
  
    // This will send the request to the server
  //  client.print(String("GET ") + url + key + dato1 + temp + dato2 + hum + " HTTP/1.1\r\n" +
  //               "Host: " + host + "\r\n" +
  //               "Connection: close\r\n\r\n");
    client.print(String("GET ") + url + key + sid + sensorID + dato1 + temperature + dato2 + humidity + " HTTP/1.1\r\n" +
                 "Host: " + host + "\r\n" +
                 "Connection: close\r\n\r\n");
    unsigned long timeout = millis();                 
    while (client.available() == 0) {
      if (millis() - timeout > 5000) {
        Serial.println(">>> Client Timeout !");
        client.stop();
        return;
      }
    }
  
    // Read all the lines of the reply from server and print them to Serial
    while (client.available()) {
      String line = client.readStringUntil('\r');
      Serial.print(line);
    }
  
    Serial.println();
    Serial.println("closing connection");
}

