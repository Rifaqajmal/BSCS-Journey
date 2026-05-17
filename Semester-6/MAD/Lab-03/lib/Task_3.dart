import 'dart:async';

// Future function
Future<String> fetchWeather() async {
  await Future.delayed(Duration(seconds: 3)); // simulate delay
  return "Weather Data: Sunny, 25°C";
}

void main() async {
  print("Fetching weather data...");

  String data = await fetchWeather();

  print(data);
}