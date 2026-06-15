class AIService {
  static Future<String> generateWish(String name) async {
    await Future.delayed(const Duration(seconds: 1));

    return "Happy Birthday $name 🎉\n"
        "Wishing you a year full of happiness, success, and health!";
  }
}