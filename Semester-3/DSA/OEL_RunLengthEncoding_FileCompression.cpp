#include <iostream>
#include <string>
using namespace std;

// Function to perform Run-Length Encoding
string runLengthEncoding(const string& input) {
    string compressed = "";
    int count = 1;

    for (size_t i = 1; i < input.length(); i++) {

        if (input[i] == input[i - 1]) {
            count++;
        } 
        else {
            compressed += input[i - 1];
            compressed += to_string(count);
            count = 1;
        }
    }

    // Add last character
    compressed += input.back();
    compressed += to_string(count);

    return compressed;
}

int main() {
    string input;

    cout << "Enter string to compress: ";
    cin >> input;

    string result = runLengthEncoding(input);

    cout << "Original: " << input << endl;
    cout << "Compressed: " << result << endl;

    return 0;
}