import 'package:flutter/material.dart';
import 'package:local_auth/local_auth.dart';

void main() {
  runApp(const MyApp());
}

class MyApp extends StatelessWidget {
  const MyApp({super.key});

  @override
  Widget build(BuildContext context) {
    return MaterialApp(
      title: 'Fingerprint App',
      debugShowCheckedModeBanner: false,
      theme: ThemeData(
        colorScheme: ColorScheme.fromSeed(seedColor: Colors.indigo),
        useMaterial3: true,
      ),
      home: const FingerprintScreen(),
    );
  }
}

class FingerprintScreen extends StatefulWidget {
  const FingerprintScreen({super.key});

  @override
  State<FingerprintScreen> createState() => _FingerprintScreenState();
}

class _FingerprintScreenState extends State<FingerprintScreen> {
  final LocalAuthentication auth = LocalAuthentication();
  String statusMessage = 'Press button to authenticate';
  bool isAuthenticated = false;

  Future<void> authenticate() async {
    try {
      bool canCheck = await auth.canCheckBiometrics;
      if (!canCheck) {
        setState(() {
          statusMessage = '❌ Fingerprint not available on this device';
        });
        return;
      }

      bool result = await auth.authenticate(
        localizedReason: 'Please scan your fingerprint to continue',
      );

      setState(() {
        isAuthenticated = result;
        statusMessage = result
            ? '✅ Authentication Successful! Welcome Rifaq!'
            : '❌ Authentication Failed! Try Again.';
      });
    } catch (e) {
      setState(() {
        statusMessage = '❌ Error: $e';
      });
    }
  }

  @override
  Widget build(BuildContext context) {
    return Scaffold(
      appBar: AppBar(
        title: const Text('Fingerprint Authentication'),
        backgroundColor: Colors.indigo,
        foregroundColor: Colors.white,
      ),
      body: Padding(
        padding: const EdgeInsets.all(24.0),
        child: Column(
          mainAxisAlignment: MainAxisAlignment.center,
          children: [
            Icon(
              isAuthenticated ? Icons.lock_open : Icons.fingerprint,
              size: 120,
              color: isAuthenticated ? Colors.green : Colors.indigo,
            ),
            const SizedBox(height: 30),
            Text(
              statusMessage,
              textAlign: TextAlign.center,
              style: TextStyle(
                fontSize: 18,
                fontWeight: FontWeight.bold,
                color: statusMessage.contains('✅')
                    ? Colors.green
                    : statusMessage.contains('❌')
                        ? Colors.red
                        : Colors.black,
              ),
            ),
            const SizedBox(height: 40),
            SizedBox(
              width: double.infinity,
              height: 55,
              child: ElevatedButton.icon(
                onPressed: authenticate,
                icon: const Icon(Icons.fingerprint),
                label: const Text(
                  'Authenticate with Fingerprint',
                  style: TextStyle(fontSize: 16),
                ),
                style: ElevatedButton.styleFrom(
                  backgroundColor: Colors.indigo,
                  foregroundColor: Colors.white,
                  shape: RoundedRectangleBorder(
                    borderRadius: BorderRadius.circular(12),
                  ),
                ),
              ),
            ),
            const SizedBox(height: 20),
            if (isAuthenticated)
              Container(
                padding: const EdgeInsets.all(16),
                decoration: BoxDecoration(
                  color: Colors.green.shade50,
                  borderRadius: BorderRadius.circular(12),
                  border: Border.all(color: Colors.green),
                ),
                child: const Row(
                  children: [
                    Icon(Icons.check_circle, color: Colors.green),
                    SizedBox(width: 10),
                    Text(
                      'Access Granted!',
                      style: TextStyle(
                        color: Colors.green,
                        fontWeight: FontWeight.bold,
                        fontSize: 16,
                      ),
                    ),
                  ],
                ),
              ),
          ],
        ),
      ),
    );
  }
}