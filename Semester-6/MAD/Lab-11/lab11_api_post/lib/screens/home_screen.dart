import 'package:flutter/material.dart';
import '../services/api_service.dart';

class HomeScreen extends StatefulWidget {
  @override
  _HomeScreenState createState() => _HomeScreenState();
}

class _HomeScreenState extends State<HomeScreen> {

  ApiService apiService = ApiService();

  List users = [];

  @override
  void initState() {
    super.initState();
    loadUsers();
  }

  void loadUsers() async {
    users = await apiService.fetchUsers();
    setState(() {});
  }

  @override
  Widget build(BuildContext context) {

    return Scaffold(
      appBar: AppBar(title: Text("Lab 11")),

      body: ListView.builder(
        itemCount: users.length,

        itemBuilder: (context, index) {

          return ListTile(
            title: Text(users[index]['name']),
            subtitle: Text(users[index]['email']),
          );
        },
      ),

      floatingActionButton: FloatingActionButton(
        child: Icon(Icons.person_add),

        onPressed: () {
          Navigator.pushNamed(context, "/signup");
        },
      ),
    );
  }
}