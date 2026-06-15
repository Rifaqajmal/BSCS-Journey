import 'package:firebase_database/firebase_database.dart';
import 'package:flutter/material.dart';

class AddPostScreen extends StatefulWidget {
  @override
  State<AddPostScreen> createState() => _AddPostScreenState();
}

class _AddPostScreenState extends State<AddPostScreen> {

  TextEditingController postController = TextEditingController();

  bool loading = false;

  final databaseRef = FirebaseDatabase.instance.ref("Posts");

  addPost() async {

    if(postController.text.isEmpty){

      ScaffoldMessenger.of(context).showSnackBar(
        SnackBar(content: Text("Enter something")),
      );

      return;
    }

    setState(() {
      loading = true;
    });

    String id = DateTime.now().millisecondsSinceEpoch.toString();

    await databaseRef.child(id).set({

      "id": id,
      "title": postController.text,

    });

    setState(() {
      loading = false;
    });

    postController.clear();

    Navigator.pop(context);
  }

  @override
  Widget build(BuildContext context) {

    return Scaffold(

      appBar: AppBar(
        title: Text("AddPostScreen"),
      ),

      body: Padding(

        padding: EdgeInsets.all(20),

        child: Column(

          children: [

            TextField(

              controller: postController,

              maxLines: 5,

              decoration: InputDecoration(
                hintText: "Write something...",
                border: OutlineInputBorder(),
              ),
            ),

            SizedBox(height: 30),

            loading
                ? CircularProgressIndicator()
                : ElevatedButton(
              onPressed: addPost,
              child: Text("Add"),
            ),

          ],
        ),
      ),
    );
  }
}