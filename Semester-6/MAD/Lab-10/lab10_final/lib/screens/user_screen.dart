import 'package:flutter/material.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

import '../models/user_model.dart';
import '../widgets/reusable_row.dart';

class UserScreen extends StatefulWidget {
  @override
  _UserScreenState createState() => _UserScreenState();
}

class _UserScreenState extends State<UserScreen> {

  List<User> users = [];

  Future<void> fetchUsers() async {
    final response = await http.get(
      Uri.parse("https://jsonplaceholder.typicode.com/users"),
    );

    if (response.statusCode == 200) {
      List data = jsonDecode(response.body);

      setState(() {
        users = data.map((e) => User.fromJson(e)).toList();
      });
    }
  }

  @override
  void initState() {
    super.initState();
    fetchUsers();
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text("Students")),

      body: users.isEmpty
          ? Center(child: CircularProgressIndicator())
          : ListView.builder(
              itemCount: users.length,
              itemBuilder: (context, index) {

                final user = users[index];

                return Card(
                  margin: EdgeInsets.all(10),
                  child: Padding(
                    padding: EdgeInsets.all(10),
                    child: Column(
                      children: [
                        ReusableRow(title: "Name", value: user.name),
                        ReusableRow(title: "Email", value: user.email),
                        ReusableRow(title: "City", value: user.city),
                        ReusableRow(title: "Company", value: user.company),
                      ],
                    ),
                  ),
                );
              },
            ),
    );
  }
}