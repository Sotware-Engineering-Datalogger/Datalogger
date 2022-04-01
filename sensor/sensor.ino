// Blink example

void setup() {
  // Initialize digital pin 1 as an output.
  pinMode(1, OUTPUT);
}

void loop() {
  digitalWrite(1, HIGH);   // turn the LED on
  delay(1000);             // wait for a second
  digitalWrite(1, LOW);    // turn the LED off
  delay(1000);             // wait for a second
}
