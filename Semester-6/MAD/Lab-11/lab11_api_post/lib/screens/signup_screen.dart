import 'package:flutter/material.dart';
import '../services/api_service.dart';

class SignupScreen extends StatelessWidget {

  final TextEditingController nameController = TextEditingController();
  final TextEditingController emailController = TextEditingController();

  ApiService apiService = ApiService();

  @override
  Widget build(BuildContext context) {

    return Scaffold(
      appBar: AppBar(title: Text("Signup")),

      body: Padding(
        padding: EdgeInsets.all(20),

        child: Column(
          children: [

            TextField(
              controller: nameController,
              decoration: InputDecoration(
                labelText: "Name",
              ),
            ),

            TextField(
              controller: emailController,
              decoration: InputDecoration(
                labelText: "Email",
              ),
            ),

            SizedBox(height: 20),

            ElevatedButton(
              onPressed: () async {

                await apiService.signupUser(
                  nameController.text,
                  emailController.text,
                );

                ScaffoldMessenger.of(context).showSnackBar(
                  SnackBar(
                    content: Text("Signup Successful"),
                  ),
                );
              },

              child: Text("Signup"),
            )

          ],
        ),
      ),
    );
  }
}