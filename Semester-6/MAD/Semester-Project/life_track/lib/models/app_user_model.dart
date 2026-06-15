class AppUserModel {
  final String uid;
  final String email;

  AppUserModel({
    required this.uid,
    required this.email,
  });

  Map<String, dynamic> toMap() {
    return {
      'uid': uid,
      'email': email,
    };
  }

  factory AppUserModel.fromMap(Map<dynamic, dynamic> map) {
    return AppUserModel(
      uid: map['uid'] ?? '',
      email: map['email'] ?? '',
    );
  }
}