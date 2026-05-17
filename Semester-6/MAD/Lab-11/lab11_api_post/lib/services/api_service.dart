import 'dart:convert';
import 'package:http/http.dart' as http;

class ApiService {

  /// GET API
  Future<List<dynamic>> fetchUsers() async {

    final response = await http.get(
      Uri.parse("https://jsonplaceholder.typicode.com/users"),
    );

    return jsonDecode(response.body);
  }

  /// POST API
  Future<void> signupUser(String name, String email) async {

    final response = await http.post(
      Uri.parse("https://jsonplaceholder.typicode.com/posts"),

      body: {
        "name": name,
        "email": email,
      },
    );

    print(response.body);
  }
}