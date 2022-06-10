#include <Wire.h>
#include <LOLIN_HP303B.h>
#include <WiFi.h>
#include <HTTPClient.h>

#define HP303B_OVERSAMPLING  7
#define UPDATE_INTERVAL 5000    // Update interval in ms
#define DEBUG 1

const char* ssid = "datalogger";
const char* password = "datalogger";
const char* server_name = "http://192.168.2.1/sensor";

LOLIN_HP303B HP303BPressureSensor;

/**
 * Send data the server via the API
 * 
 * @param val     The measurement value
 * @param path    The API path
 * @return true   Request succesfull
 * @return false  Request failed
 */
bool storeData(int32_t val, String path) {
  if (WiFi.status()== WL_CONNECTED) {
      WiFiClient client;
      HTTPClient http;
    
      http.begin(client, server_name + path);
      http.addHeader("Content-Type", "application/json");
      int http_response_code = http.POST("{\"value\":\"" + String(val) + "\"}");
      http.end();

      if (http_response_code == 201)
        return true;
      else
        return false;      
  }
  return false;

}

void setup()
{
  Serial.begin(115200);
  
  WiFi.begin(ssid, password);
  while (WiFi.status() != WL_CONNECTED) {
    #ifdef DEBUG
    delay(500);
    Serial.print(".");
    #endif
  }
  
  Wire.begin(18, 19);
  HP303BPressureSensor.begin(Wire, 0x77);
}

void loop()
{
  int32_t pressure;
  int32_t temp;
  int32_t light;
  double adc_v;

  // Read the pressure
  if (HP303BPressureSensor.measurePressureOnce(pressure, HP303B_OVERSAMPLING) == 0) {
    #ifdef DEBUG
    Serial.print(pressure);
    Serial.println(" Pascal");
  } else {
    Serial.println("error");
    #endif
  }

  // Read the temperature
  if (HP303BPressureSensor.measureTempOnce(temp, HP303B_OVERSAMPLING) == 0) {
    #ifdef DEBUG
    Serial.print(temp);
    Serial.println(" °C");
  } else {
    Serial.println("error");
    #endif
  }

  // Read the light intensity
  adc_v = analogRead(1) * 3.3 / 1024;
  light = 500 / (10 * ((3.3 - adc_v) / adc_v));
  #ifdef DEBUG
  Serial.println(light);
  Serial.println("lux");
  #endif

  // Send the data to the server
  storeData(pressure, "/pressure");
  storeData(temp, "/temperature");
  storeData(light, "/light");

  // Wait for the set interval time
  delay(UPDATE_INTERVAL);
}
