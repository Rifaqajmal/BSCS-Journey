class User {
  final String name;
  final String email;
  final String city;
  final String company;

  User({
    required this.name,
    required this.email,
    required this.city,
    required this.company,
  });

  factory User.fromJson(Map<String, dynamic> json) {
    return User(
      name: json['name'],
      email: json['email'],
      city: json['address']['city'],
      company: json['company']['name'],
    );
  }
}