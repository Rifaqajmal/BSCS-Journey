import 'package:flutter/material.dart';
import 'package:flutter_stripe/flutter_stripe.dart';
import 'package:http/http.dart' as http;
import 'dart:convert';

void main() async {
  WidgetsFlutterBinding.ensureInitialized();

  // Initialize Stripe with your Publishable Key
  Stripe.publishableKey = 'pk_test_51TaJwhBFWzura37gPk4oWWwomb07Iz84DEpCPT6PONhkKPZR8LsZZgJNnKGIOtsGnfWwRbBnuzD9C6odlbDTgh7R00Nx3FTjvp';
  await Stripe.instance.applySettings();

  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Payment App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.indigo),
        useMaterial3: true,
      ),
      home: const PaymentScreen(),
    );
  }
}

class PaymentScreen extends StatefulWidget {
  const PaymentScreen({super.key});

  @override
  State<PaymentScreen> createState() => _PaymentScreenState();
}

class _PaymentScreenState extends State<PaymentScreen> {
  bool isLoading = false;
  String statusMessage = '';

  // Step 1: Create Payment Intent using Stripe Secret Key
  Future<String?> createPaymentIntent() async {
    try {
      final response = await http.post(
        Uri.parse('https://api.stripe.com/v1/payment_intents'),
        headers: {
          'Authorization': 'Bearer sk_test_51TaJwhBFWzura37gz7SHz5sS1Ybt162xvHUSth5N99ijfU5JDYQV771S3Kp3W9abga9JPuikmLrpZmrTU0mDe4Cr00IHI9k1tG',
          'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: {
          'amount': '2000', // 2000 cents = $20.00
          'currency': 'usd',
          'payment_method_types[]': 'card',
        },
      );

      final data = jsonDecode(response.body);
      return data['client_secret'];
    } catch (e) {
      setState(() {
        statusMessage = 'Error creating payment: $e';
      });
      return null;
    }
  }

  // Step 2: Show Stripe Payment Sheet
  Future<void> makePayment() async {
    setState(() {
      isLoading = true;
      statusMessage = '';
    });

    // Get client secret
    final clientSecret = await createPaymentIntent();
    if (clientSecret == null) {
      setState(() => isLoading = false);
      return;
    }

    // Initialize Payment Sheet
    await Stripe.instance.initPaymentSheet(
      paymentSheetParameters: SetupPaymentSheetParameters(
        paymentIntentClientSecret: clientSecret,
        merchantDisplayName: 'Rifaq Payment App',
        style: ThemeMode.light,
      ),
    );

    // Show Payment Sheet to user
    try {
      await Stripe.instance.presentPaymentSheet();
      setState(() {
        statusMessage = '✅ Payment Successful!';
      });
    } on StripeException catch (e) {
      setState(() {
        statusMessage = '❌ Payment Failed: ${e.error.localizedMessage}';
      });
    } finally {
      setState(() => isLoading = false);
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Mobile Payment App'),
        backgroundColor: Colors.indigo,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            const Icon(Icons.payment, size: 80, color: Colors.indigo),
            const SizedBox(height: 20),
            const Text(
              'Total Amount: \$20.00',
              style: TextStyle(fontSize: 24, fontWeight: FontWeight.bold),
            ),
            const SizedBox(height: 10),
            const Text(
              'Secure Payment via Stripe',
              style: TextStyle(fontSize: 16, color: Colors.grey),
            ),
            const SizedBox(height: 40),
            SizedBox(
              width: double.infinity,
              height: 55,
              child: ElevatedButton(
                onPressed: isLoading ? null : makePayment,
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.indigo,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
                child: isLoading
                    ? const CircularProgressIndicator(color: Colors.white)
                    : const Text(
                        'Pay Now',
                        style: TextStyle(fontSize: 18),
                      ),
              ),
            ),
            const SizedBox(height: 20),
            if (statusMessage.isNotEmpty)
              Text(
                statusMessage,
                style: TextStyle(
                  fontSize: 16,
                  color: statusMessage.contains('✅')
                      ? Colors.green
                      : Colors.red,
                  fontWeight: FontWeight.bold,
                ),
                textAlign: TextAlign.center,
              ),
          ],
        ),
      ),
    );
  }
}