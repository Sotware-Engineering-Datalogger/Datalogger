#include <DFRobot_DHT20.h>
#include <LOLIN_HP303B.h>
#include <OneWire.h>
#include <WiFi.h>
#include <HTTPClient.h>


DFRobot_DHT20 dht20;

LOLIN_HP303B HP303BPressureSensor;

const char* ssid = "datalogger";
const char* paswoord = "datalogger";
const char* servernaam = "http://192.168.2.1/sensor/temperature";

unsigned long laatstekeer = 0;

unsigned long timerDelay = 5000;


#define LICHT_SENSOR_PIN 36

typedef struct dht20sensor{
  float temp;
  float vocht;
}dht20sensor;



float readDHTTemperature() {
  
  float t = dht.readTemperature();
  
  if (isnan(t)) {    
    Serial.println("Gevaalt om DHT20 sensor te lezen");
    return 0;
  }
  
  else {
    Serial.println(t);
    return t;
  }
  
}

float readDHTHumidity() {
  
  float h = dht.readHumidity();
  
  if (isnan(h)) {
    Serial.println("Gevaalt om DHT20 sensor te lezen");
    return 0;
  }
  
  else {
    Serial.println(h);
    return h;
  }
  
}


void setup() {
  
  Serial.begin(115200);

   while(dht20.begin()){
    Serial.println("Sensor niet kunnen initializeren");
    delay(1000);
   }
   
   while (!Serial){
    HP303BPressureSensor.begin();
    Serial.println("Init compleet!");
   }
   
  WiFi.begin(ssid, password);
  Serial.println("Conecteren");
  
  while(WiFi.status() != WL_CONNECTED) {
    delay(500);
    Serial.print(".");
  }
  
  Serial.println("");
  Serial.print("Conectie tot WiFi netwerk met IP Address: ");
  Serial.println(WiFi.localIP());
 
  Serial.println("Timer gezet tot 5 secondes (timerDelay variable), het gaat 5 secondes duren voor dat het terug geprint is.");
}



void loop() {
  int licht = analogRead(LICHT_SENSOR_PIN);

  int32_t temp2;
  int32_t druk;
  int16_t oversampling = 7;
  int16_t ret;
  Serial.println();
  
  dht20sensor.temp = readDHTTemperature();
  dht20sensor.vocht = readDHTHumidity();

  Serial.print("licht = ");
  Serial.print(licht);
  
  Serial.print("temperatuur = ");
  Serial.print(dht20sensor.temp);
  
  Serial.print("vochtigheid = ");
  Serial.print(dht20sensor.vocht);

  ret = HP303BPressureSensor.measureTempOnce(temperature, oversampling);

  if (ret != 0){
    Serial.print("FAIL! ret = ");
    Serial.println(ret);
  }

  else{
    Serial.print("temperatuur2: ");
    Serial.print(temp2);
    Serial.println(" C");
  }

 
  ret = HP303BPressureSensor.measurePressureOnce(pressure, oversampling);
  
  if (ret != 0){
    Serial.print("FAIL! ret = ");
    Serial.println(ret);
  }
  
  else{
    Serial.print("druk: ");
    Serial.print(druk);
    Serial.println(" Pascal");
  }

    //Zend een HTTP POST request elke 10 minuten
    
  if ((millis() - laatstekeer) > timerDelay) {
    //Controleer WiFi connectie
    
    if(WiFi.status()== WL_CONNECTED){
      WiFiClient client;
      HTTPClient http;
    
      // domain naam plus ip adres
      http.begin(client, servernaam);

      // Specivieren content-type voor header
      http.addHeader("Content-Type", "application/x-www-form-urlencoded");
      // Data zenden met HTTP POST
      String httpRequestData = "api_key=tPmAT5Ab3j7F9&sensor=dht20&temp="+dht20sensor.temp+"&vocht="+dht20sensor.vocht+"&sensor=LDR-VT90N2&licht="+licht+"&sensor=HP303B&temp2="+temp2+"&druk="+druk+";           
      // Zend HTTP POST request
      int httpResponseCode = http.POST(httpRequestData);
      
      Serial.print("HTTP Response code: ");
      Serial.println(httpResponseCode);

      http.end();
    }
    
    else {
      Serial.println("WiFi Disconnected");
    }
    
    lastTime = millis();
    
  }
  
}
