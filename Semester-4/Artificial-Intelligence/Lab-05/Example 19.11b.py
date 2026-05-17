#Substitution cipher (encoding example):

# Creating a substitution cipher where each letter gets replaced by another
alphabet = 'abcdefghijklmnopqrstuvwxyz'
shuffled_alphabet = 'zyxwvutsrqponmlkjihgfedcba'  # Example shuffled alphabet
cipher = str.maketrans(alphabet, shuffled_alphabet)

message = 'hello'
encoded_message = message.translate(cipher)
print(encoded_message)
