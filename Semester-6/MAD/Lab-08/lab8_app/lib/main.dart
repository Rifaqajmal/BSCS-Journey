import 'package:flutter/material.dart';

void main() {
  runApp(MyApp());
}

class MyApp extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      debugShowCheckedModeBanner: false,
      home: HomeScreen(),
    );
  }
}

//////////////////////////////////////////////////////////
// HOME SCREEN WITH DRAWER
//////////////////////////////////////////////////////////

class HomeScreen extends StatelessWidget {
  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(title: Text('Home')),

      drawer: Drawer(
        child: ListView(
          children: [

            DrawerHeader(
              decoration: BoxDecoration(color: Colors.blue),
              child: Text("My Labs",style: TextStyle(color: Colors.white,fontSize: 24)),
            ),

            ListTile(
              title: Text("Task 8"),
              onTap: (){
                Navigator.push(
                  context,
                  MaterialPageRoute(builder: (context)=>Task8Screen()),
                );
              },
            )
          ],
        ),
      ),

      body: Center(child: Text("Open Drawer and click Task 8")),
    );
  }
}

//////////////////////////////////////////////////////////
// TASK 8 SCREEN
//////////////////////////////////////////////////////////

class Task8Screen extends StatefulWidget {
  @override
  _Task8ScreenState createState() => _Task8ScreenState();
}

class _Task8ScreenState extends State<Task8Screen> {

  int index = 0;

  final screens = [
    ProductsHome(),
    CategoriesScreen(),
  ];

  @override
  Widget build(BuildContext context) {

    return Scaffold(
      appBar: AppBar(title: Text("Task 8")),

      body: screens[index],

      bottomNavigationBar: BottomNavigationBar(
        currentIndex: index,

        onTap: (i){
          setState(() {
            index = i;
          });
        },

        items: [

          BottomNavigationBarItem(
            icon: Icon(Icons.home),
            label: "Home",
          ),

          BottomNavigationBarItem(
            icon: Icon(Icons.category),
            label: "Categories",
          ),
        ],
      ),
    );
  }
}

//////////////////////////////////////////////////////////
// PRODUCTS GRID (HOME TAB)
//////////////////////////////////////////////////////////

class ProductsHome extends StatelessWidget {

  final products = [
    {"name":"Laptop","price":"1000"},
    {"name":"Shoes","price":"120"},
    {"name":"Watch","price":"300"},
    {"name":"Phone","price":"900"},
  ];

  @override
  Widget build(BuildContext context) {

    return GridView.builder(

      gridDelegate: SliverGridDelegateWithFixedCrossAxisCount(
        crossAxisCount: 2,
      ),

      itemCount: products.length,

      itemBuilder: (context,index){

        return Card(

          child: Column(
            mainAxisAlignment: MainAxisAlignment.center,
            children: [

              Icon(Icons.shopping_bag,size:50),

              Text(products[index]["name"]!),

              Text("\$${products[index]["price"]}"),
            ],
          ),
        );
      },
    );
  }
}

//////////////////////////////////////////////////////////
// CATEGORIES SCREEN
//////////////////////////////////////////////////////////

class CategoriesScreen extends StatelessWidget {

  final categories = [
    "Electronics",
    "Shoes",
    "Watches",
    "Clothes",
  ];

  @override
  Widget build(BuildContext context) {

    return DefaultTabController(

      length: 3,

      child: Column(

        children: [

          TabBar(
            tabs: [

              Tab(text:"All Items"),
              Tab(text:"Products"),
              Tab(text:"Favorites"),
            ],
          ),

          Expanded(

            child: TabBarView(
              children: [

                AllItemsTab(),
                ProductsTab(),
                FavoritesTab(),

              ],
            ),
          )
        ],
      ),
    );
  }
}

//////////////////////////////////////////////////////////
// ALL ITEMS TAB
//////////////////////////////////////////////////////////

class AllItemsTab extends StatelessWidget {

  final items = [
    "Laptop",
    "Shoes",
    "Watch",
    "Phone"
  ];

  @override
  Widget build(BuildContext context) {

    return ListView.separated(

      itemCount: items.length,

      separatorBuilder: (context,index)=>Divider(),

      itemBuilder: (context,index){

        return ListTile(
          title: Text(items[index]),
        );
      },
    );
  }
}

//////////////////////////////////////////////////////////
// PRODUCTS TAB
//////////////////////////////////////////////////////////

class ProductsTab extends StatelessWidget {

  final products = [
    {"name":"Laptop","price":"1000"},
    {"name":"Shoes","price":"120"},
    {"name":"Watch","price":"300"},
  ];

  @override
  Widget build(BuildContext context) {

    return ListView.builder(

      itemCount: products.length,

      itemBuilder: (context,index){

        return ListTile(

          title: Text(products[index]["name"]!),

          subtitle: Text("\$${products[index]["price"]}"),

          onTap: (){
            Navigator.push(
              context,
              MaterialPageRoute(
                builder: (context)=>ProductDetail(products[index]),
              ),
            );
          },
        );
      },
    );
  }
}

//////////////////////////////////////////////////////////
// PRODUCT DETAIL
//////////////////////////////////////////////////////////

class ProductDetail extends StatelessWidget {

  final product;

  ProductDetail(this.product);

  @override
  Widget build(BuildContext context) {

    return Scaffold(

      appBar: AppBar(title: Text(product["name"])),

      body: Column(

        mainAxisAlignment: MainAxisAlignment.center,

        children: [

          Icon(Icons.shopping_bag,size:100),

          SizedBox(height:20),

          Text(product["name"],style:TextStyle(fontSize:22)),

          Text("\$${product["price"]}",style:TextStyle(fontSize:20)),

          SizedBox(height:40),

          Center(

            child: InkWell(

              onTap: (){
                Navigator.pop(context);
              },

              child: Container(

                padding: EdgeInsets.all(15),

                color: Colors.blue,

                child: Text(
                  "Back to Products",
                  style: TextStyle(color: Colors.white),
                ),
              ),
            ),
          )
        ],
      ),
    );
  }
}

//////////////////////////////////////////////////////////
// FAVORITES TAB
//////////////////////////////////////////////////////////

class FavoritesTab extends StatelessWidget {

  final favorites = [
    "Favorite Laptop",
    "Favorite Shoes"
  ];

  @override
  Widget build(BuildContext context) {

    return ListView.builder(

      itemCount: favorites.length,

      itemBuilder: (context,index){

        return ListTile(
          title: Text(favorites[index]),
        );
      },
    );
  }
}