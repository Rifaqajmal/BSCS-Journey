import 'package:share_plus/share_plus.dart';

class ShareService {
  static void shareBirthday(String name) {
    Share.share("🎉 Happy Birthday $name! 🎂");
  }
}